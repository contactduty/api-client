# ContactDuty APIs Client Library for PHP #

The ContactDuty API Client Library enables you to work with Sms Service

## ContactDuty Platform
Using [ContactDuty APIs](https://www.contactduty.com/) you can send text messages through multiples phones or IoT devices.

## Requirements ##
* [PHP 5.6.0 or higher](http://www.php.net/)

## Developer Documentation ##
https://www.contactduty.com/

## Installation ##

```sh
composer require contactduty/api-client:"^0.0.1"
```

Finally, be sure to include the autoloader:

```php
require_once '/path/to/your-project/vendor/autoload.php';
```

## Examples ##

```php
// include your composer dependencies
require_once __DIR__ . '/path/to/your-project/vendor/autoload.php';

use ContactDuty\Api;

$client = new Api\ContactDutyClient([
                                        'client_id'        => '1',
                                        'client_secret'    => 'client_secret',
                                        'redirect_uri'     => 'http://www.client.com/callback'
                                    ]);
$service = new Api\Service\Sms\Service($client);

$service->messages->create(
        [
            'to' => '+0000000000', //E.164 formatted phone number - https://en.wikipedia.org/wiki/E.164
            'message' => 'api call',
            'schedule' => '2017-11-10 12:12:12', //optional
            'timezone' => 'Europe/London', //optional
            'is_recurring' => 0,  //optional - 0/1
            'recurring_type' => '', //daily/weekly/monthly required if is_recurring == 1
        ]
    );

$results = $service->messages->queued();
foreach ($results as $item) {
  echo $item['to'], "<br /> \n";
}

$results = $service->devices->all();
foreach ($results as $item) {
  echo $item['id'], "<br /> \n";
}
```
