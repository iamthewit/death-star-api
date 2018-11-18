<?php

namespace DeathStar\Request;

use Psr\Http\Message\ResponseInterface;

class PrisonerRequest extends AuthorisedRequest
{
    const PRISONER_ENDPOINT = '/prisoner';
    
    /**
     * /prisoner/leia
     * ● Accepts:
     *  ○ GET
     * ● Headers:
     *  ○ Authorization: Bearer [token]
     *  ○ Content-Type: application/json
     *
     * @param string $id
     *
     * @return ResponseInterface
     */
    public function get(string $id): ResponseInterface
    {
        $headers = ['Content-Type' => 'application/json'];
    
        return $this->handle(
            self::PRISONER_ENDPOINT . $id,
            'get',
            ['headers' => $headers]
        );
        
    }
}