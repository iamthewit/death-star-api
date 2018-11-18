<?php

use DeathStar\Request\AuthorisedRequest;
use DeathStar\Request\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class AuthorisedRequestTest extends TestCase
{
    public function testItAddsBearerTokenHeader()
    {
        $container = [];
        $history = Middleware::history($container);
        
        $accessToken = 'e31a726c4b90462ccb7619e1b51f3d0068bf8006';
        
        $tokenResponseBody = json_encode([
            'access_token' => $accessToken,
            'expires_in'   => 99999999999,
            'token_type'   => 'Bearer',
            'scope'        => 'TheForce',
        ]);
    
        $tokenResponse = new Response(200, [], $tokenResponseBody);
        
        $mock = new MockHandler([
            $tokenResponse,
            new Response()
        ]);
        $stack = HandlerStack::create($mock);
        // Add the history middleware to the handler stack.
        $stack->push($history);
    
        $request = new AuthorisedRequest(new Client(['handler' => $stack]));
        $request->handle('/endpoint', 'get');
        
        /**
         * $container[0] is the first request we made to get the token (mocked above by Guzzle $tokenResponse)
         * $container[1] is the first request we make with the token set as Bearer
         */
        
        $this->assertArrayHasKey('request', $container[1]);
        
        /** @var RequestInterface $request */
        $request = $container[1]['request'];
        
        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertTrue(in_array('Bearer ' . $accessToken, $request->getHeader('Authorization')));
    }
}
