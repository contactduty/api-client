<?php

namespace ContactDuty\Api\Service\Sms;

use ContactDuty\Api\ContactDutyClient;
use GuzzleHttp\Psr7;

class Messages
{
    /** @var string */
    const API_PATH = '/messages';

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
     * @param array $options
     * @return array
     */
    public function queued($options = [])
    {
        $headers = array(
            'Content-Type' => 'application/json',
        );

        return $this->client->execute(
            new Psr7\Request(
                'GET',
                Service::geUrl(self::API_PATH, '', $options),
                $headers
            )
        );
    }

    /**
     * @param array $message
     * @return array
     */
    public function create($message)
    {

        $headers = array(
            'Content-Type' => 'application/x-www-form-urlencoded',
        );

        return $this->client->execute(
            new Psr7\Request(
                'POST',
                Service::geUrl(self::API_PATH, ''),
                $headers,
                http_build_query($message, null, '&')
            )
        );
    }

    /**
     * @param string $id
     * @return array
     */
    public function get($id)
    {
        $headers = array(
            'Content-Type' => 'application/json',
        );

        return $this->client->execute(
            new Psr7\Request(
                'GET',
                Service::geUrl(self::API_PATH, $id),
                $headers
            )
        );
    }

    /**
     * @param string $id
     * @param array $formParams
     * @return array
     */
    public function update($id, $formParams)
    {
        $headers = array(
            'Content-Type' => 'application/x-www-form-urlencoded',
        );

        return $this->client->execute(
            new Psr7\Request(
                'PUT',
                Service::geUrl(self::API_PATH, $id),
                $headers,
                http_build_query($formParams, null, '&')
            )
        );
    }

    /**
     * @param string $id
     * @return array
     */
    public function delete($id)
    {
        $headers = array(
            'Content-Type' => 'application/json',
        );

        return $this->client->execute(
            new Psr7\Request(
                'DELETE',
                Service::geUrl(self::API_PATH, $id),
                $headers
            )
        );
    }
}
