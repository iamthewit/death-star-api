<?php

use DeathStar\Config;
use DeathStar\Request\BaseRequest;
use DeathStar\Request\Client;
use DeathStar\Response\FailureResponse;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class BaseRequestTest extends TestCase
{
    public function testItReturnsAResponse()
    {
        $mock = new MockHandler([new Response()]);
        $handler = HandlerStack::create($mock);
        
        $request = new BaseRequest(new Client(['handler' => $handler]));
        $response = $request->handle('/endpoint', 'get');
        
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
    
    public function testItReturnsFailureResponseWhenAttemptingToUseUnsupportedHTTPMethod()
    {
        $request = new BaseRequest(new Client());
        $response = $request->handle('/endpoint', 'method');
        
        $expectedResponseBodyContents = json_encode([
            'status'  => 'failed',
            'message' => 'Unsupported HTTP Method: "method" used. This API accepts: get, post, delete.',
        ]);
        
        $this->assertInstanceOf(FailureResponse::class, $response);
        $this->assertEquals($expectedResponseBodyContents, $response->getBody()->getContents());
    }
    
    public function testSSLCertificateDetailsAreIncludedInRequest()
    {
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([new Response()]);
        $stack = HandlerStack::create($mock);
        // Add the history middleware to the handler stack.
        $stack->push($history);
    
        $request = new BaseRequest(new Client(['handler' => $stack]));
        $request->handle('/endpoint', 'get');
        
        $this->assertCount(1, $container);
        $this->assertArrayHasKey('cert', $container[0]['options']);
        $this->assertArrayHasKey('ssl_key', $container[0]['options']);
        $this->assertEquals(Config::get('paths.certificates') .'/client.crt.pem', $container[0]['options']['cert']);
        $this->assertEquals(Config::get('paths.certificates') .'/client.key.pem', $container[0]['options']['ssl_key']);
    }
}
