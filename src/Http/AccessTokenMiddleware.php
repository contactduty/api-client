<?php

namespace ContactDuty\Api\Http;

use Psr\Http\Message\RequestInterface;

class AccessTokenMiddleware
{
    /**
     * @var callable
     */
    private $tokenFunc;

    /**
     * Creates a new AccessTokenMiddleware.
     *
     * @param callable $tokenFunc a token generator function
     */
    public function __construct(
        callable $tokenFunc
    ) {
        $this->tokenFunc = $tokenFunc;
    }

    /**
     * @param callable $handler
     * @return \Closure
     */
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            // Requests using "auth"="scoped" will be authorized.
            if (!isset($options['auth']) || $options['auth'] !== 'scoped') {
                return $handler($request, $options);
            }

            $request = $request->withHeader('authorization', 'Bearer ' . $this->fetchToken());

            return $handler($request, $options);
        };
    }

    /**
     * @return string
     */
    private function fetchToken()
    {
        return call_user_func($this->tokenFunc);
    }
}
