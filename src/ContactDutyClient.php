<?php

namespace ContactDuty\Api;

use ContactDuty\OAuth2;
use ContactDuty\Api\Http;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client as HttpClient;
use League\OAuth2\Client\Token\Token;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\RequestInterface;

class ContactDutyClient
{
    /** @var string */
    const API_BASE_PATH = 'https://www.contactduty.com';

    /** @var array $config */
    private $config;

    /** @var \League\OAuth2\Client\Provider\AbstractProvider $auth */
    private $auth;

    /** @var \League\OAuth2\Client\Token\AccessToken access token */
    private $token;

    /** @var ClientInterface */
    protected $httpClient;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge(
            [
                'application_name' => '',
                'base_path'        => self::API_BASE_PATH,
                // https://www.contactduty.com/home/api-clients
                'client_id'        => '',
                'client_secret'    => '',
                'redirect_uri'     => null,
                'state'            => null,
            ],
            $config
        );
    }

    public function getClientId()
    {
        return $this->config['client_id'];
    }

    public function getClientSecret()
    {
        return $this->config['client_secret'];
    }

    public function getRedirectUri()
    {
        return $this->config['redirect_uri'];
    }

    /**
     * @return \League\OAuth2\Client\Provider\AbstractProvider implementation
     */
    public function getOAuth2Service()
    {
        if (!isset($this->auth)) {

            $this->auth = $this->createOAuth2Service();
        }

        return $this->auth;
    }

    /**
     * Creates a default auth object.
     *
     * @return OAuth2\Client
     */
    protected function createOAuth2Service()
    {

        $auth = new OAuth2\Client(
            [
                'clientId'     => $this->getClientId(),
                'clientSecret' => $this->getClientSecret(),
                'redirectUri'  => $this->getRedirectUri(),
            ]
        );

        return $auth;
    }

    /**
     * @return AccessToken
     */
    public function fetchAccessToken()
    {

        $this->setToken($this->getOAuth2Service()->getAccessToken('client_credentials'));

        return $this->getToken();
    }

    /**
     * @return bool
     */
    public function isAccessTokenExpired()
    {
        if (!$this->token) {
            return true;
        }

        return $this->getToken()->hasExpired();
    }

    /**
     * @param AccessToken $token
     */
    public function setToken(AccessToken $token)
    {
        $this->token = $token;
    }

    /**
     * @return AccessToken
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->token->getToken();
    }

    /**
     * @return ClientInterface implementation
     */
    public function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->httpClient = $this->createDefaultHttpClient();
        }

        return $this->httpClient;
    }

    /**
     * @return HttpClient
     */
    protected function createDefaultHttpClient()
    {
        $options = ['exceptions' => false];
        $options['base_uri'] = $this->config['base_path'];

        return new HttpClient($options);
    }

    /**
     * Helper method to execute HTTP requests.
     *
     * @param RequestInterface $request
     * @return array
     * @throws Http\RequestException
     * @throws \Exception
     */
    public function execute(RequestInterface $request)
    {
        $request = $request->withHeader(
            'User-Agent',
            $this->config['application_name']
        );

        $http = $this->authorize();

        return Http\Connector::doRequest(
            $http,
            $request
        );
    }

    /**
     * Adds auth headers to the HTTP client
     *
     * @param \GuzzleHttp\ClientInterface $http the http client object.
     * @return \GuzzleHttp\ClientInterface the http client object
     */
    public function authorize(ClientInterface $http = null)
    {
        if (null === $http) {
            $http = $this->getHttpClient();
        }

        if ($this->isAccessTokenExpired()) {
            $token = $this->fetchAccessToken();
        }

        $http = $this->attachToken($http, $token);

        return $http;
    }

    /**
     * @param ClientInterface $http
     * @param AccessToken $token
     * @return HttpClient|ClientInterface
     */
    public function attachToken(ClientInterface $http, AccessToken $token)
    {
        $tokenFunc = function () use ($token) {
            return $token->getToken();
        };
        $middleware = new Http\AccessTokenMiddleware($tokenFunc);

        $config = $http->getConfig();
        $config['auth'] = 'scoped';
        $config['handler']->push($middleware, 'oauth2');
        $http = new HttpClient($config);

        return $http;
    }
}
