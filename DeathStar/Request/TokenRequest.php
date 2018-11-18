<?php

namespace DeathStar\Request;

use DeathStar\Exception\TokenRequestException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class TokenRequest extends BaseRequest
{
    const TOKEN_ENDPOINT = '/token';
    
    /**
     * /token
     * ● Credentials:
     *  ○ Client Secret - Alderan
     *  ○ Client ID - R2D2
     * ● Accepts:
     *  ○ POST
     * ● Headers:
     *  ○ Content-Type: application/x-www-form-urlencoded
     * ● Body:
     *  ○ grant_type = client_credentials
     * ● Returns:
     * {
     *      "access_token": "e31a726c4b90462ccb7619e1b51f3d0068bf8006",
     *      "expires_in": 99999999999,
     *      "token_type": "Bearer",
     *      "scope": “TheForce”
     * }
     *
     * @return ResponseInterface
     * @throws TokenRequestException
     */
    public function post(): ResponseInterface
    {
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        
        $formParams = [
            'grant_type'    => 'client_credentials',
            'client_secret' => getenv('CLIENT_SECRET'),
            'client_id'     => getenv('CLIENT_ID'),
        ];
        
        $options = [
            'headers'     => $headers,
            'form_params' => $formParams,
        ];
        
        try {
            $response = $this->handle(self::TOKEN_ENDPOINT, 'post', $options);
        } catch (RequestException $e) {
            $m = 'There was an error retrieving a token from the Death Star.';
            throw new TokenRequestException($m, 0, $e);
        }
        
        return $response;
    }
}