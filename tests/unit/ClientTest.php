<?php

namespace Shaggyrec\PhpHttpClient\Test;

use Shaggyrec\PhpHttpClient\Client;
use PHPUnit\Framework\TestCase;
use Mockery as m;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ClientTest extends TestCase{

    use m\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testCanRetryRequestOnServerError(): void
    {
        $httpClient = m::mock(HttpClientInterface::class);
        $httpClient->shouldReceive('request')->times(9)->andReturn(self::response(500));

        $client = new Client($httpClient, self::host());
        $client->get('foo');
    }

    public function testSupportPostRequests(): void
    {
        $httpClient = m::mock(HttpClientInterface::class);
        $httpClient->shouldReceive('request')->andReturn(self::response(201));

        $client = new Client($httpClient, self::host());
        $client->post('bar',  json_encode(['name' => 'baz']), ['id' => 1], ['Content-Type' => 'application/json']);
    }

    public function testSupportPutRequests(): void
    {
        $httpClient = m::mock(HttpClientInterface::class);
        $httpClient->shouldReceive('request')->andReturn(self::response(200));

        $client = new Client($httpClient, self::host());
        $client->put('bar', json_encode(['type' => 'something']), ['id' => 2], ['Content-Type' => 'application/json']);
    }

    public function testSupportDeleteRequests(): void
    {
        $httpClient = m::mock(HttpClientInterface::class);
        $httpClient->shouldReceive('request')->andReturn(self::response(204));

        $client = new Client($httpClient, self::host());
        $client->delete('bar', ['id' => 3]);
    }

    /**
     * @param int $code
     * @return ResponseInterface
     */
    private static function response(int $code = 200): ResponseInterface
    {
        $response = m::mock(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->andReturn($code);
        return $response;
    }

    /**
     * @return string
     */
    private static function host(): string
    {
        return 'https://designmodo.com/';
    }
}
