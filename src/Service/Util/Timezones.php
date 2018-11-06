<?php

namespace ContactDuty\Api\Service\Util;

use ContactDuty\Api\ContactDutyClient;
use GuzzleHttp\Psr7;

class Timezones
{
    /** @var string */
    const API_PATH = '/timezones';
    
    /** @var ContactDutyClient */
    protected $client;

    /**
     * @param ContactDutyClient $client
     */
    public function __construct(ContactDutyClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return array
     */
    public function all()
    {
        $headers = array(
            'Content-Type' => 'application/json',
        );
        return $this->client->execute(
            new Psr7\Request(
                'GET',
                $this->geUrl(''),
                $headers
            )
        );
    }

    /**
     * @param string $path
     * @param array $query
     * @return string
     */
    private function geUrl($path = '', $query = [])
    {  
        $url = Service::API_PATH . self::API_PATH;
        if($path){
            $url .=  '/' . $path; 
        }
        if($queryString = Psr7\build_query($query)){
            $url .=  '?' . $queryString; 
        }
        
        return $url;
    }
}
