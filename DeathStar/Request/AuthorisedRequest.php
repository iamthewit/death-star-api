<?php

namespace DeathStar\Request;

use DeathStar\Exception\TokenRequestException;
use DeathStar\Factory\FailureResponseFactory;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class AuthorisedRequest extends BaseRequest
{
    /**
     * @param string $endpoint
     * @param string $method
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function handle(string $endpoint, string $method, array $options = []): ResponseInterface
    {
        try {
            $response = (new TokenRequest($this->getClient()))->post();
            $response = json_decode($response->getBody()->getContents());
    
            $token = $response->access_token;
        } catch (TokenRequestException $e) {
            return FailureResponseFactory::build($e);
        }
        
        $headers = isset($options['headers']) ? $options['headers'] : [];
        $headers = array_merge($headers, ['Authorization' => 'Bearer ' . $token]);
        $options['headers'] = $headers;
        
        try {
            $response = parent::handle($endpoint, $method, $options);
        } catch (RequestException $e) {
            // log exception somewhere...
            $response = FailureResponseFactory::build($e);
        }
        
        return $response;
    }
}