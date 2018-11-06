<?php

namespace ContactDuty\Api\Service\Sms;

use ContactDuty\Api\ContactDutyClient;
use GuzzleHttp\Psr7;

class Devices
{
    /** @var string */
    const API_PATH = '/devices';

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
                Service::geUrl(self::API_PATH, ''),
                $headers
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
     * @param array $options
     * @return array
     */
    public function messages($id, $options)
    {

        $headers = array(
            'Content-Type' => 'application/json',
        );

        return $this->client->execute(
            new Psr7\Request(
                'GET',
                Service::geUrl(self::API_PATH, $id . '/messages', $options),
                $headers
            )
        );
    }
}
