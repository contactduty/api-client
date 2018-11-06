<?php

namespace ContactDuty\Api\Http;

use GuzzleHttp\ClientInterface;
use ContactDuty\Api\Service;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Connector
{
    /**
     * Executes a Psr\Http\Message\RequestInterface.
     *
     * @param ClientInterface $client
     * @param RequestInterface $request
     * @return mixed
     * @throws RequestException
     * @throws Service\Exception
     * @throws \Exception
     */
    public static function doRequest(ClientInterface $client, RequestInterface $request)
    {
        try {
            $response = $client->send($request);
        } catch (RequestException $e) {
            if (!$e->hasResponse()) {
                throw $e;
            }
            $response = $e->getResponse();
        }

        return self::decodeResponse($response, $request);
    }

    /**
     * @param ResponseInterface $response
     * @return mixed
     * @throws Service\Exception
     */
    public static function decodeResponse(
        ResponseInterface $response
    ) {
        $code = $response->getStatusCode();

        if (intVal($code) >= 400) {
            $body = (string)$response->getBody();

            throw new Service\Exception($body, $code, null, self::getResponseErrors($body));
        }

        $body = (string)$response->getBody();

        return json_decode($body, true);
    }

    /**
     * @param string $body
     * @return null
     */
    private static function getResponseErrors($body)
    {
        $json = json_decode($body, true);

        if (isset($json['error']['errors'])) {
            return $json['error']['errors'];
        }

        return null;
    }
}
