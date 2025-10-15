<?php

declare(strict_types=1);

namespace LukaszZychal\TMDB\Tests\Unit\Client;

use LukaszZychal\TMDB\Client\ConfigurationClient;
use LukaszZychal\TMDB\Client\GenresClient;
use LukaszZychal\TMDB\Client\MoviesClient;
use LukaszZychal\TMDB\Client\PeopleClient;
use LukaszZychal\TMDB\Client\SearchClient;
use LukaszZychal\TMDB\Client\TMDBClient;
use LukaszZychal\TMDB\Client\TVClient;
use LukaszZychal\TMDB\Http\HttpClientInterface;
use PHPUnit\Framework\TestCase;

class TMDBClientTest extends TestCase
{
    private TMDBClient $client;

    private HttpClientInterface $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->client = new TMDBClient('test-api-key');

        // Use reflection to inject the mocked HTTP client
        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty('httpClient');
        $property->setAccessible(true);
        $property->setValue($this->client, $this->httpClient);
    }

    public function testMoviesClient(): void
    {
        $moviesClient = $this->client->movies();
        $this->assertInstanceOf(MoviesClient::class, $moviesClient);
    }

    public function testTVClient(): void
    {
        $tvClient = $this->client->tv();
        $this->assertInstanceOf(TVClient::class, $tvClient);
    }

    public function testPeopleClient(): void
    {
        $peopleClient = $this->client->people();
        $this->assertInstanceOf(PeopleClient::class, $peopleClient);
    }

    public function testSearchClient(): void
    {
        $searchClient = $this->client->search();
        $this->assertInstanceOf(SearchClient::class, $searchClient);
    }

    public function testConfigurationClient(): void
    {
        $configClient = $this->client->configuration();
        $this->assertInstanceOf(ConfigurationClient::class, $configClient);
    }

    public function testGenresClient(): void
    {
        $genresClient = $this->client->genres();
        $this->assertInstanceOf(GenresClient::class, $genresClient);
    }

    public function testGetHttpClient(): void
    {
        $httpClient = $this->client->getHttpClient();
        $this->assertInstanceOf(HttpClientInterface::class, $httpClient);
    }
}
