<?php

namespace DeathStar\Service;

use DeathStar\Exception\TranslatorException;

interface TranslatorServiceInterface
{
    /**
     * @param string $string
     *
     * @return string
     * @throws TranslatorException
     */
    public function translate(string $string) : string;
}