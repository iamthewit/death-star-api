<?php

namespace DeathStar\Factory;

use DeathStar\Exception\UnsupportedHttpMethodException;
use DeathStar\Response\FailureResponse;
use GuzzleHttp\Exception\RequestException;

class FailureResponseFactory
{
    /**
     * @param \Exception $exception
     * @param int        $status
     *
     * @return FailureResponse
     */
    public static function build(\Exception $exception, int $status = 200): FailureResponse
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];
        
        $body = json_encode([
            'status'  => 'failed',
            'message' => $exception->getMessage(),
        ]);
        
        if ($exception instanceof UnsupportedHttpMethodException) {
            $status = 405;
        } elseif ($exception instanceof RequestException) {
            /** @var RequestException $status */
            $status = $exception->getCode();
        }
        
        return new FailureResponse($status, $headers, $body);
    }
}