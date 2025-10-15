<?php

declare(strict_types=1);

namespace LukaszZychal\TMDB\Tests\Integration;

use LukaszZychal\TMDB\Client\TMDBClient;
use LukaszZychal\TMDB\Http\TMDBHttpClient;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for TMDB Client.
 *
 * These tests verify the integration between different components
 * without making real API calls.
 */
class TMDBIntegrationTest extends TestCase
{
    public function testClientInitialization(): void
    {
        $client = new TMDBClient('test-api-key');

        $this->assertInstanceOf(TMDBClient::class, $client);
        $this->assertInstanceOf(TMDBHttpClient::class, $client->getHttpClient());
    }

    public function testClientWithCustomConfiguration(): void
    {
        $config = [
            'timeout' => 60,
            'connect_timeout' => 15,
            'headers' => [
                'User-Agent' => 'TestApp/1.0',
            ],
        ];

        $client = new TMDBClient('test-api-key', $config);
        $httpClient = $client->getHttpClient();

        $this->assertInstanceOf(TMDBClient::class, $client);
        $this->assertEquals('test-api-key', $httpClient->getApiKey());
    }

    public function testClientWithLogger(): void
    {
        $logger = $this->createMock(\Psr\Log\LoggerInterface::class);

        $client = new TMDBClient('test-api-key', [], $logger);

        $this->assertInstanceOf(TMDBClient::class, $client);
    }

    public function testAllClientMethodsReturnCorrectTypes(): void
    {
        $client = new TMDBClient('test-api-key');

        $this->assertInstanceOf(\LukaszZychal\TMDB\Client\MoviesClient::class, $client->movies());
        $this->assertInstanceOf(\LukaszZychal\TMDB\Client\TVClient::class, $client->tv());
        $this->assertInstanceOf(\LukaszZychal\TMDB\Client\PeopleClient::class, $client->people());
        $this->assertInstanceOf(\LukaszZychal\TMDB\Client\SearchClient::class, $client->search());
        $this->assertInstanceOf(\LukaszZychal\TMDB\Client\ConfigurationClient::class, $client->configuration());
        $this->assertInstanceOf(\LukaszZychal\TMDB\Client\GenresClient::class, $client->genres());
    }

    public function testHttpClientApiKeyManagement(): void
    {
        $httpClient = new TMDBHttpClient('original-key');

        $this->assertEquals('original-key', $httpClient->getApiKey());

        $httpClient->setApiKey('new-key');
        $this->assertEquals('new-key', $httpClient->getApiKey());
    }
}
