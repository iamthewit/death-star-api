<?php

use DeathStar\Factory\FailureResponseFactory;
use DeathStar\Response\ResponseHandlerService;
use PHPUnit\Framework\TestCase;

class ResponseHandlerServiceTest extends TestCase
{
    public function testFailureResponse()
    {
        $translatorService = new \DeathStar\Service\BinaryToTextTranslatorServiceService();
        
        $exception = new Exception('exception message');
        $failureResponse = FailureResponseFactory::build($exception);
        
        $expectedResponse = json_encode([
            'status' => 'failed',
            'message' => $exception->getMessage(),
        ]);
        
        $responseHandler = new ResponseHandlerService($failureResponse, $translatorService);
        $handledResponse = $responseHandler->getJsonResponse();
        
        $this->assertEquals($expectedResponse, $handledResponse);
    }
    
    public function testJsonResponseIsTranslatedFromBinaryToText()
    {
        $translatorService = new \DeathStar\Service\BinaryToTextTranslatorServiceService();
        
        $binaryResponseFromDeathStar = [
            'cell'  => '01000011 01100101 01101100 01101100 00100000 00110010 00110001 00111000 00110111',
            'block' => '01000100 01100101 01110100 01100101 01101110 01110100 01101001 01101111 01101110 00100000 01000010 01101100 01101111 01100011 01101011 00100000 01000001 01000001 00101101 00110010 00110011 00101100',
        ];
        $jsonResponse = json_encode($binaryResponseFromDeathStar);
        $response = new \GuzzleHttp\Psr7\Response(200, [], $jsonResponse);
        
        $expectedResponse = json_encode([
            'cell'  => 'Cell 2187',
            'block' => 'Detention Block AA-23,',
        ]);
        
        $responseHandler = new ResponseHandlerService($response, $translatorService);
        
        $this->assertEquals($expectedResponse, $responseHandler->getJsonResponse());
    }
    
    public function testJsonResponseIsNotTranslatedFromBinaryToText()
    {
        $translatorService = new \DeathStar\Service\BinaryToTextTranslatorServiceService();
        
        $responseFromDeathStar = [
            'some_random_key' => 'some_random_non_binary_value',
            'another_key'     => 'another value',
        ];
        $jsonResponse = json_encode($responseFromDeathStar);
        $response = new \GuzzleHttp\Psr7\Response(200, [], $jsonResponse);
        $responseHandler = new ResponseHandlerService($response, $translatorService);
        
        $expectedResponse = json_encode($responseFromDeathStar);
        
        $this->assertEquals($expectedResponse, $responseHandler->getJsonResponse());
    }
    
    public function testJsonResponseIsPartiallyTranslatedFromBinaryToText()
    {
        $translatorService = new \DeathStar\Service\BinaryToTextTranslatorServiceService();
        
        $binaryResponseFromDeathStar = [
            'binary' => '01001001 00100000 01110011 01101000 01101111 01110101 01101100 01100100 00100000 01100010 01100101 00100000 01110100 01110010 01100001 01101110 01110011 01101100 01100001 01110100 01100101 01100100',
            'text'   => 'I should not be translated',
        ];
        $jsonResponse = json_encode($binaryResponseFromDeathStar);
        $response = new \GuzzleHttp\Psr7\Response(200, [], $jsonResponse);
        
        $expectedResponse = json_encode([
            'binary' => 'I should be translated',
            'text'   => 'I should not be translated',
        ]);
        
        $responseHandler = new ResponseHandlerService($response, $translatorService);
        
        $this->assertEquals($expectedResponse, $responseHandler->getJsonResponse());
    }
}
