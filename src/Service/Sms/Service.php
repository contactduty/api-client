<?php

namespace ContactDuty\Api\Service\Sms;

use ContactDuty\Api\ContactDutyClient;
use GuzzleHttp\Psr7;

class Service
{
    /** @var string */
    const API_PATH = '/api/v2';

    /** @var Devices */
    public $devices;

    /** @var Messages */
    public $messages;

    /**
     * @param ContactDutyClient $client
     */
    public function __construct(ContactDutyClient $client)
    {
        $this->devices = new Devices($client);
        $this->messages = new Messages($client);
    }

    /**
     * @param string $servicePath
     * @param string $path
     * @param array $query
     * @return string
     */
    static function geUrl($servicePath, $path = '', $query = [])
    {
        $url = self::API_PATH . $servicePath;
        if ($path) {
            $url .= '/' . $path;
        }
        if ($queryString = Psr7\build_query($query)) {
            $url .= '?' . $queryString;
        }

        return $url;
    }
}
