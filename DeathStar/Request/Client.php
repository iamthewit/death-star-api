<?php

namespace DeathStar\Request;

use DeathStar\Config;

class Client extends \GuzzleHttp\Client
{
    /**
     * Client constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['cert'] = Config::get('paths.certificates') . '/client.crt.pem';
        $config['ssl_key'] = Config::get('paths.certificates') . '/client.key.pem';
        
        parent::__construct($config);
    }
}