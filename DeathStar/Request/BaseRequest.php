<?php

namespace DeathStar\Request;

use DeathStar\Exception\UnsupportedHttpMethodException;
use DeathStar\Factory\FailureResponseFactory;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class BaseRequest {
    /**
     * @var Client $client
     */
    private $client;
    
    /**
     * BaseRequest constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }
    
    /**
     * @param string $endpoint
     * @param string $method
     * @param array  $options
     *
     * @return ResponseInterface
     * @throws RequestException
     */
    public function handle(string $endpoint, string $method, array $options = []): ResponseInterface
    {
        // send the request to the death star api
        try {
            // merge options
            $options = array_merge($this->client->getConfig(), $options);
            
            $url = getenv('DEATH_STAR_BASE_URL') . $endpoint;
    
            $response = $this->makeRequest($url, $method, $options);
        } catch (UnsupportedHttpMethodException $e) {
            // log exception somewhere...
            $response = FailureResponseFactory::build($e);
        }
        
        return $response;
    }
    
    /**
     * @param string $url
     *
     * @param string $method
     * @param array  $options
     *
     * @return ResponseInterface
     * @throws RequestException|UnsupportedHttpMethodException
     */
    private function makeRequest(string $url, string $method, array $options = []): ResponseInterface
    {
        switch (strtolower($method)) {
            case "post":
                $response = $this->client->post($url, $options);
                break;
            
            case "get":
                $response = $this->client->get($url, $options);
                break;
            
            case "delete":
                $response = $this->client->delete($url, $options);
                break;
            
            case "put":
            case "patch":
            default:
                $message = 'Unsupported HTTP Method: "' . $method . '" used. This API accepts: get, post, delete.';
                throw new UnsupportedHttpMethodException($message);
                break;
        }
        
        return $response;
    }
    
}