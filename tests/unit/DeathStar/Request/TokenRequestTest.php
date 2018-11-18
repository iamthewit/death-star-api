<?php

use DeathStar\Request\Client;
use DeathStar\Request\TokenRequest;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

class TokenRequestTest extends TestCase
{
    /**
     * @expectedException \DeathStar\Exception\TokenRequestException
     */
    public function testItThrowsTokenRequestException()
    {
        $endpointRequest = new Request('post', '/endpoint');
        
        $mock = new MockHandler([
            new RequestException('Request exception message.', $endpointRequest)
        ]);
        $handler = HandlerStack::create($mock);
    
        $request = new TokenRequest(new Client(['handler' => $handler]));
        $request->post();
    }
}
