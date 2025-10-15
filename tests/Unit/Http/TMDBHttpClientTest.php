<?php

declare(strict_types=1);

namespace LukaszZychal\TMDB\Tests\Unit\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use LukaszZychal\TMDB\Exception\AuthenticationException;
use LukaszZychal\TMDB\Exception\NotFoundException;
use LukaszZychal\TMDB\Exception\RateLimitException;
use LukaszZychal\TMDB\Exception\TMDBException;
use LukaszZychal\TMDB\Http\TMDBHttpClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class TMDBHttpClientTest extends TestCase
{
    private TMDBHttpClient $client;

    private MockObject $guzzleClient;

    private MockObject $logger;

    private string $apiKey;

    protected function setUp(): void
    {
        $this->apiKey = 'test-api-key';
        $this->guzzleClient = $this->createMock(GuzzleClient::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        // Create client with mocked Guzzle client
        $this->client = new TMDBHttpClient($this->apiKey, [], $this->logger);

        // Use reflection to inject the mocked Guzzle client
        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($this->client, $this->guzzleClient);
    }

    public function testGetRequest(): void
    {
        $expectedResponse = new Response(200, [], '{"success": true}');
        $this->guzzleClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/test', ['query' => ['api_key' => $this->apiKey]])
            ->willReturn($expectedResponse);

        $response = $this->client->get('/test');

        $this->assertSame($expectedResponse, $response);
    }

    public function testPostRequest(): void
    {
        $expectedResponse = new Response(201, [], '{"created": true}');
        $this->guzzleClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/test', ['query' => ['api_key' => $this->apiKey]])
            ->willReturn($expectedResponse);

        $response = $this->client->post('/test');

        $this->assertSame($expectedResponse, $response);
    }

    public function testPutRequest(): void
    {
        $expectedResponse = new Response(200, [], '{"updated": true}');
        $this->guzzleClient
            ->expects($this->once())
            ->method('request')
            ->with('PUT', '/test', ['query' => ['api_key' => $this->apiKey]])
            ->willReturn($expectedResponse);

        $response = $this->client->put('/test');

        $this->assertSame($expectedResponse, $response);
    }

    public function testDeleteRequest(): void
    {
        $expectedResponse = new Response(204);
        $this->guzzleClient
            ->expects($this->once())
            ->method('request')
            ->with('DELETE', '/test', ['query' => ['api_key' => $this->apiKey]])
            ->willReturn($expectedResponse);

        $response = $this->client->delete('/test');

        $this->assertSame($expectedResponse, $response);
    }

    public function testRequestWithCustomOptions(): void
    {
        $options = ['query' => ['page' => 2], 'headers' => ['Accept' => 'application/json']];
        $expectedResponse = new Response(200, [], '{"page": 2}');

        $expectedOptions = [
            'query' => ['page' => 2, 'api_key' => $this->apiKey],
            'headers' => ['Accept' => 'application/json'],
        ];

        $this->guzzleClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/test', $expectedOptions)
            ->willReturn($expectedResponse);

        $response = $this->client->request('GET', '/test', $options);

        $this->assertSame($expectedResponse, $response);
    }

    public function testAuthenticationException(): void
    {
        $request = new Request('GET', '/test');
        $response = new Response(401, [], '{"status_code": 7, "status_message": "Invalid API key"}');
        $exception = new ClientException('Unauthorized', $request, $response);

        $this->guzzleClient
            ->expects($this->once())
            ->method('request')
            ->willThrowException($exception);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Invalid API key or authentication failed');

        $this->client->get('/test');
    }

    public function testNotFoundException(): void
    {
        $request = new Request('GET', '/test');
        $response = new Response(404, [], '{"status_code": 34, "status_message": "The resource you requested could not be found."}');
        $exception = new ClientException('Not Found', $request, $response);

        $this->guzzleClient
            ->expects($this->once())
            ->method('request')
            ->willThrowException($exception);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Resource not found');

        $this->client->get('/test');
    }

    public function testRateLimitException(): void
    {
        $request = new Request('GET', '/test');
        $response = new Response(429, [], '{"status_code": 25, "status_message": "Your request count (40) is over the allowed limit of 40."}');
        $exception = new ClientException('Too Many Requests', $request, $response);

        $this->guzzleClient
            ->expects($this->once())
            ->method('request')
            ->willThrowException($exception);

        $this->expectException(RateLimitException::class);
        $this->expectExceptionMessage('Rate limit exceeded');

        $this->client->get('/test');
    }

    public function testServerException(): void
    {
        $request = new Request('GET', '/test');
        $response = new Response(500, [], '{"status_code": 500, "status_message": "Internal Server Error"}');
        $exception = new ServerException('Internal Server Error', $request, $response);

        $this->guzzleClient
            ->expects($this->once())
            ->method('request')
            ->willThrowException($exception);

        $this->expectException(TMDBException::class);
        $this->expectExceptionMessage('Server error 500: {"status_code": 500, "status_message": "Internal Server Error"}');

        $this->client->get('/test');
    }

    public function testTooManyRedirectsException(): void
    {
        $request = new Request('GET', '/test');
        $exception = new TooManyRedirectsException('Too many redirects', $request);

        $this->guzzleClient
            ->expects($this->once())
            ->method('request')
            ->willThrowException($exception);

        $this->expectException(TMDBException::class);
        $this->expectExceptionMessage('Too many redirects');

        $this->client->get('/test');
    }

    public function testGenericGuzzleException(): void
    {
        $exception = new \GuzzleHttp\Exception\ConnectException('Connection failed', new Request('GET', '/test'));

        $this->guzzleClient
            ->expects($this->once())
            ->method('request')
            ->willThrowException($exception);

        $this->expectException(TMDBException::class);
        $this->expectExceptionMessage('HTTP request failed: Connection failed');

        $this->client->get('/test');
    }

    public function testGetApiKey(): void
    {
        $this->assertEquals($this->apiKey, $this->client->getApiKey());
    }

    public function testSetApiKey(): void
    {
        $newApiKey = 'new-api-key';
        $this->client->setApiKey($newApiKey);
        $this->assertEquals($newApiKey, $this->client->getApiKey());
    }

    public function testLogging(): void
    {
        $expectedResponse = new Response(200, [], '{"success": true}');

        $this->logger
            ->expects($this->exactly(2))
            ->method('info')
            ->with(
                $this->logicalOr(
                    'Making TMDB API request',
                    'TMDB API request successful'
                ),
                $this->isType('array')
            );

        $this->guzzleClient
            ->expects($this->once())
            ->method('request')
            ->willReturn($expectedResponse);

        $this->client->get('/test');
    }

    public function testErrorLogging(): void
    {
        $request = new Request('GET', '/test');
        $response = new Response(401, [], '{"error": "Unauthorized"}');
        $exception = new ClientException('Unauthorized', $request, $response);

        $this->logger
            ->expects($this->once())
            ->method('info')
            ->with(
                'Making TMDB API request',
                $this->isType('array')
            );

        $this->logger
            ->expects($this->once())
            ->method('error')
            ->with(
                'TMDB API client error',
                [
                    'status_code' => 401,
                    'response_body' => '{"error": "Unauthorized"}',
                ]
            );

        $this->guzzleClient
            ->expects($this->once())
            ->method('request')
            ->willThrowException($exception);

        $this->expectException(AuthenticationException::class);
        $this->client->get('/test');
    }
}
