<?php

namespace DeathStar\Response;

use DeathStar\Exception\TranslatorException;
use DeathStar\Service\TranslatorServiceInterface;
use Psr\Http\Message\ResponseInterface;

class ResponseHandlerService
{
    /** @var ResponseInterface $response */
    private $response;
    
    /** @var TranslatorServiceInterface $translatorService */
    private $translatorService;
    
    /**
     * ResponseHandlerService constructor.
     *
     * @param ResponseInterface          $response
     * @param TranslatorServiceInterface $translatorService
     */
    public function __construct(ResponseInterface $response, TranslatorServiceInterface $translatorService)
    {
        $this->translatorService = $translatorService;
        $this->response = $response;
    }
    
    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
    
    /**
     * @return string
     */
    public function getJsonResponse(): string
    {
        // check if response is a failure response, no point in translating if it is
        if ($this->response instanceof FailureResponse) {
            return $this->response->getBody()->getContents();
        }
        
        $responseBody = json_decode($this->response->getBody()->getContents(), true);
        
        foreach ($responseBody as $k => $v) {
            try {
                // attempt to translate the response
                // the keys seem to be in english, so concentrate on the values
                $responseBody[$k] = $this->translatorService->translate($v);
            } catch (TranslatorException $e) {
                // if the string isn't binary just carry on
                continue;
            }
        }
    
        return json_encode($responseBody);
    }
}