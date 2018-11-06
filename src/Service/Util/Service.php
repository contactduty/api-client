<?php

namespace ContactDuty\Api\Service\Util;

use ContactDuty\Api\ContactDutyClient;

class Service
{
    /** @var string */
    const API_PATH = '/api/v2';

    /** @var Devices */
    public $timezones;

    public function __construct(ContactDutyClient $client)
    {
        $this->timezones = new Timezones($client);
    }
}
