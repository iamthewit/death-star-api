<?php

use DeathStar\Service\BinaryToTextTranslatorServiceService;
use PHPUnit\Framework\TestCase;

class BinaryToTextTranslatorServiceTest extends TestCase
{
    /** @var BinaryToTextTranslatorServiceService $translatorService */
    public $translatorService;
    
    public function setUp()
    {
        $this->translatorService = new BinaryToTextTranslatorServiceService();
    }
    
    public function testItTranslatesBinary()
    {
        $binaryString = '01000001 00100000 01110011 01110100 01110010 01101001 01101110 01100111 00100000 01101111 01100110 00100000 01100010 01101001 01101110 01100001 01110010 01111001';
        
        $expected = 'A string of binary';
        
        $this->assertEquals($expected, $this->translatorService->translate($binaryString));
    }
    
    public function testItCanDealWithMultipleSpacesInBinaryString()
    {
        $binaryString = '01000001 01000010  01000011   01000100    01000101';
    
        $expected = 'ABCDE';
    
        $this->assertEquals($expected, $this->translatorService->translate($binaryString));
    }
    
    /**
     * @expectedException \DeathStar\Exception\StringIsNotBinaryException
     * @expectedExceptionMessage The given string: "A string of binary" is not binary. Please provide a binary string for translation.
     */
    public function testItThrowsStringIsNotBinaryException()
    {
        $binaryString = 'A string of binary';
        
        $this->translatorService->translate($binaryString);
    }
    
    public function testItCanDealWithNoSpacesInBinaryString()
    {
        $binaryString = '01000001';
        
        $expected = 'A';
        
        $this->assertEquals($expected, $this->translatorService->translate($binaryString));
    }
}
