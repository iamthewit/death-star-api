<?php

namespace DeathStar\Request;

use Psr\Http\Message\ResponseInterface;

class ReactorExhaustRequest extends AuthorisedRequest
{
    const REACTOR_EXHAUST_ENDPOINT = '/reactor/exhaust';
    
    /**
     * /reactor/exhaust/1
     * ● Accepts:
     *  ○ DELETE
     * ● Headers:
     *  ○ Authorization: Bearer [token]
     *  ○ Content-Type: application/json
     *  ○ x-torpedoes: 2
     *
     * @param int $id
     *
     * @return ResponseInterface
     */
    public function delete(int $id): ResponseInterface
    {
        $headers = [
            'Content-Type' => 'application/json',
            'x-torpedoes'  => 2,
        ];
        return $this->handle(
            self::REACTOR_EXHAUST_ENDPOINT . '/' . $id,
            'delete',
            ['headers' => $headers]
        );
    }
}