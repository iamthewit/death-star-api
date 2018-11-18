<?php


use DeathStar\Exception\UnsupportedHttpMethodException;
use DeathStar\Factory\FailureResponseFactory;
use DeathStar\Response\FailureResponse;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class FailureResponseFactoryTest extends TestCase
{
    
    public function testItReturnsAFailureResponse()
    {
        $message = 'Exception Message';
        $statusCode = 404;
        $expectedResponseBody = json_encode([
            'status'  => 'failed',
            'message' => $message,
        ]);
        
        $failureResponse = FailureResponseFactory::build(
            new Exception($message, $statusCode)
        );
        
        $this->assertInstanceOf(FailureResponse::class, $failureResponse);
        $this->assertEquals($expectedResponseBody, $failureResponse->getBody()->getContents());
    }
    
    public function testItReturns405StatusCodeForUnsupportedHttpMethodException()
    {
        $failureResponse = FailureResponseFactory::build(
            new UnsupportedHttpMethodException('message')
        );
        $this->assertEquals(405, $failureResponse->getStatusCode());
    }
    
    public function testItDealsWithARequestException()
    {
        $message = 'Exception Message';
        $statusCode = 404;
        $expectedResponseBody = json_encode([
            'status'  => 'failed',
            'message' => $message,
        ]);
        
        $failureResponse = FailureResponseFactory::build(
            new RequestException(
                $message,
                new Request('get', '/endpoint'),
                new Response($statusCode)
            )
        );
        
//        $this->assertInstanceOf(FailureResponse::class, $failureResponse);
        $this->assertEquals($statusCode, $failureResponse->getStatusCode());
        $this->assertEquals($expectedResponseBody, $failureResponse->getBody()->getContents());
    }
}
