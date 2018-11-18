<?php

namespace DeathStar\Service;

use DeathStar\Exception\StringIsNotBinaryException;

class BinaryToTextTranslatorServiceService implements TranslatorServiceInterface
{
    /**
     * @param string $binaryString
     *
     * @return string
     * @throws StringIsNotBinaryException
     */
    public function translate(string $binaryString): string
    {
        if (!$this->isStringBinary($binaryString)) {
            $m = 'The given string: "' . $binaryString . '" is not binary. Please provide a binary string for translation.';
            throw new StringIsNotBinaryException($m);
        }
        
        $textString = '';
        // split string by space
        $binaryStrings = explode(' ', $binaryString);
        
        foreach ($binaryStrings as $binaryString) {
            // remove any empty strings
            if ($binaryString === '') continue;
            $textString .= chr(bindec($binaryString));
        }
        
        return $textString;
    }
    
    /**
     * @param $binary
     *
     * @return bool
     */
    private function isStringBinary($binary): bool
    {
        return preg_match("/^[0-1 ]+$/", $binary) === 1;
    }
}