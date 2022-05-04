<?php

namespace Shaggyrec\PhpHttpClient;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

Class Client
{
    /**
     * @var string
     */
    private $rest;

    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @param string $host
     * @param array $options
     * @return Client
     */
    public static function getInstance(string $host, array $options = []): Client
    {
        return new self(HttpClient::create($options), $host);
    }

    /**
     * Client constructor.
     * @param HttpClientInterface $client
     * @param string $host
     */
    public function __construct(HttpClientInterface $client, string $host)
    {
        $this->client = $client;
        $this->rest = $host;
    }

    /**
     * @param string $resource
     * @param array $query
     * @param array $headers
     * @return ResponseInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function get(string $resource, array $query = [], array $headers = []): ResponseInterface
    {
        return $this->request('GET', $resource, $query, $headers);
    }

    /**
     * @param string $resource
     * @param string $data
     * @param array $query
     * @param array $headers
     * @return ResponseInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function post(string $resource, string $data = '', array $query = [], array $headers = []): ResponseInterface
    {
        return $this->request('POST', $resource, $query, $headers, $data);
    }

    /**
     * @param string $resource
     * @param string $data
     * @param array $query
     * @param array $headers
     * @return ResponseInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function put(string $resource, string $data = '', array $query = [], array $headers = []): ResponseInterface
    {
        return $this->request('PUT', $resource, $query, $headers, $data);
    }

    /**
     * @param string $resource
     * @param array $query
     * @param array $headers
     * @param string $data
     * @return ResponseInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function delete(string $resource, array $query = [], array $headers = [], string $data = ''): ResponseInterface
    {
        return $this->request('DELETE', $resource, $query, $headers, $data);
    }

    /**
     * @param string $method
     * @param string $resource
     * @param array $query
     * @param array $headers
     * @param string $data
     * @param int $currentDelayInSeconds
     * @return ResponseInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function request(
        string $method,
        string $resource,
        array $query = [],
        array $headers = [],
        string $data = '',
        int $currentDelayInSeconds = 1
    ): ResponseInterface
    {
        $options = [];
        $query !== [] && $options['query'] = $query;
        $headers !== [] && $options['headers'] = $headers;
        $data !== '' && $options['body'] = $data;

        $response = $this->client->request($method, $this->rest . '/' . $resource, $options);

        if ($currentDelayInSeconds < 256 && $response->getStatusCode() >= 500) {
            if (defined('UNIT_TESTS_IN_PROGRESS') === false && defined('INTEGRATION_TESTS_IN_PROGRESS') === false) {
                sleep($currentDelayInSeconds);
            }

            return $this->request($method, $resource, $query, $headers, $data, $currentDelayInSeconds * 2);
        }

        return $response;
    }
}
