<?php

namespace Bytesfield\SimpleKyc;

use GuzzleHttp\Client;
use Bytesfield\SimpleKyc\Exceptions\IsNullException;

class HttpProcessor
{
    /**
     * The Request KYC Handler
     * 
     * @var string
     */
    protected $handler;

    /**
     * Instance of HTTP Client
     * 
     * @var Client
     */
    protected $client;

    /**
     * Handler Api Key
     * @var string
     */
    protected $apiKey;

    /**
     * Authentication API base Url
     * 
     * @var string
     */
    protected $baseUrl;

    /**
     *  Response from requests made to Handler Service
     * 
     * @var mixed
     */
    protected $response;

    public function __construct(string $baseUrl, string $apiKey = null, string $handler)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->handler = $handler;
    }

    /**
     * Set options for making the Client request
     */
    private function setRequestOption()
    {
        $authHeader = [];

        if ($this->handler == 'Credequity') {
            $authHeader = ['Access-Key' => $this->apiKey];
        }

        if ($this->handler == 'Appruve') {
            $authHeader = ['Authorization' => "Bearer {$this->apiKey}"];
        }

        $header = [
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json'
        ];

        $this->client = new Client(
            [
                'base_uri' => $this->baseUrl,
                'headers' => array_merge($authHeader, $header)
            ]
        );

        return $this;
    }

    /**
     * @param string $relativeUrl
     * @param string $method
     * @param array $body
     * 
     * @throws IsNullException
     */
    public function process($method, $relativeUrl,  $body = [])
    {
        $this->setRequestOption();

        if (is_null($method)) {
            throw new IsNullException("Empty method not allowed");
        }

        $this->response = $this->client->request(
            strtoupper($method),
            $this->baseUrl . $relativeUrl,
            ["body" => json_encode($body)]
        );

        return $this;
    }


    /**
     * Get the whole response from the request
     * 
     * @return array
     */
    public function getResponse()
    {
        return json_decode($this->response->getBody()->getContents(), true);
    }
}
