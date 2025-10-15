<?php

declare(strict_types=1);

namespace LukaszZychal\TMDB\Tests\Contract;

use LukaszZychal\TMDB\Client\TMDBClient;
use LukaszZychal\TMDB\Exception\AuthenticationException;
use LukaszZychal\TMDB\Exception\RateLimitException;
use PHPUnit\Framework\TestCase;

/**
 * Contract tests to verify real TMDB API behavior.
 *
 * These tests require a valid TMDB API key and will make real API calls.
 * Set TMDB_API_KEY environment variable to run these tests.
 *
 * WARNING: These tests are rate-limited and should be run sparingly.
 */
class TMDBContractTest extends TestCase
{
    private ?TMDBClient $client = null;

    private ?string $apiKey = null;

    protected function setUp(): void
    {
        $this->apiKey = getenv('TMDB_API_KEY');

        if (!$this->apiKey) {
            $this->markTestSkipped('TMDB_API_KEY environment variable not set');
        }

        $this->client = new TMDBClient($this->apiKey);

        // Add a small delay to respect rate limits
        usleep(250000); // 250ms delay
    }

    public function testConfigurationEndpoint(): void
    {
        $response = $this->client->configuration()->getDetails();
        $data = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsArray($data);
        $this->assertArrayHasKey('images', $data);
    }

    public function testMovieDetailsEndpoint(): void
    {
        // Test with a well-known movie ID (The Shawshank Redemption)
        $movieId = 278;
        $response = $this->client->movies()->getDetails($movieId);
        $data = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsArray($data);
        $this->assertArrayHasKey('id', $data);
    }

    public function testMoviePopularEndpoint(): void
    {
        $response = $this->client->movies()->getPopular();
        $data = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('results', $data);
        $this->assertArrayHasKey('page', $data);
        $this->assertArrayHasKey('total_pages', $data);
        $this->assertArrayHasKey('total_results', $data);
        $this->assertIsArray($data['results']);

        if (!empty($data['results'])) {
            $movie = $data['results'][0];
            $this->assertArrayHasKey('id', $movie);
            $this->assertArrayHasKey('title', $movie);
            $this->assertArrayHasKey('popularity', $movie);
        }
    }

    public function testTVDetailsEndpoint(): void
    {
        // First get a popular TV show to get a valid ID
        $popularResponse = $this->client->tv()->getPopular();
        $popularData = json_decode($popularResponse->getBody()->getContents(), true);

        $this->assertEquals(200, $popularResponse->getStatusCode());
        $this->assertArrayHasKey('results', $popularData);
        $this->assertNotEmpty($popularData['results']);

        // Use the first TV show from popular results
        $tvId = $popularData['results'][0]['id'];
        $response = $this->client->tv()->getDetails($tvId);
        $data = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($tvId, $data['id']);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('overview', $data);
    }

    public function testTVPopularEndpoint(): void
    {
        $response = $this->client->tv()->getPopular();
        $data = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('results', $data);
        $this->assertArrayHasKey('page', $data);
        $this->assertArrayHasKey('total_pages', $data);
        $this->assertArrayHasKey('total_results', $data);
        $this->assertIsArray($data['results']);

        if (!empty($data['results'])) {
            $tvShow = $data['results'][0];
            $this->assertArrayHasKey('id', $tvShow);
            $this->assertArrayHasKey('name', $tvShow);
            $this->assertArrayHasKey('popularity', $tvShow);
        }
    }

    public function testPersonDetailsEndpoint(): void
    {
        // First get popular people to get a valid ID
        $popularResponse = $this->client->people()->getPopular();
        $popularData = json_decode($popularResponse->getBody()->getContents(), true);

        $this->assertEquals(200, $popularResponse->getStatusCode());
        $this->assertArrayHasKey('results', $popularData);
        $this->assertNotEmpty($popularData['results']);

        // Use the first person from popular results
        $personId = $popularData['results'][0]['id'];
        $response = $this->client->people()->getDetails($personId);
        $data = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($personId, $data['id']);
        $this->assertArrayHasKey('name', $data);
    }

    public function testSearchMoviesEndpoint(): void
    {
        $response = $this->client->search()->movies('Inception');
        $data = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('results', $data);
        $this->assertArrayHasKey('page', $data);
        $this->assertArrayHasKey('total_pages', $data);
        $this->assertArrayHasKey('total_results', $data);
        $this->assertIsArray($data['results']);
        $this->assertGreaterThan(0, $data['total_results']);

        if (!empty($data['results'])) {
            $movie = $data['results'][0];
            $this->assertArrayHasKey('id', $movie);
            $this->assertArrayHasKey('title', $movie);
        }
    }

    public function testGenresEndpoint(): void
    {
        $response = $this->client->genres()->getMovieList();
        $data = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('genres', $data);
        $this->assertIsArray($data['genres']);

        if (!empty($data['genres'])) {
            $genre = $data['genres'][0];
            $this->assertArrayHasKey('id', $genre);
            $this->assertArrayHasKey('name', $genre);
        }
    }

    public function testInvalidApiKey(): void
    {
        $invalidClient = new TMDBClient('invalid-api-key');

        $this->expectException(AuthenticationException::class);
        $invalidClient->configuration()->getDetails();
    }

    public function testRateLimitHandling(): void
    {
        // This test might fail if rate limits are hit
        // It's designed to test the rate limit exception handling
        $responses = [];

        try {
            // Make multiple rapid requests to potentially trigger rate limiting
            for ($i = 0; $i < 5; $i++) {
                $response = $this->client->configuration()->getDetails();
                $responses[] = $response->getStatusCode();
                usleep(100000); // 100ms delay between requests
            }

            $this->assertCount(5, $responses);
            $this->assertContainsOnly('integer', $responses);
        } catch (RateLimitException $e) {
            // Rate limit exception is expected behavior
            $this->assertStringContains('Rate limit', $e->getMessage());
        }
    }

    public function testNotFoundResource(): void
    {
        // Test with a very high movie ID that shouldn't exist
        // Our HTTP client should throw a NotFoundException for 404 responses
        $this->expectException(\LukaszZychal\TMDB\Exception\NotFoundException::class);
        $this->client->movies()->getDetails(999999999);
    }

    /**
     * Test API response structure consistency.
     */
    public function testApiResponseStructure(): void
    {
        $endpoints = [
            'movies_popular' => fn () => $this->client->movies()->getPopular(),
            'tv_popular' => fn () => $this->client->tv()->getPopular(),
            'people_popular' => fn () => $this->client->people()->getPopular(),
        ];

        foreach ($endpoints as $name => $endpointCall) {
            $response = $endpointCall();
            $data = json_decode($response->getBody()->getContents(), true);

            $this->assertEquals(200, $response->getStatusCode(), "Failed for endpoint: {$name}");
            $this->assertArrayHasKey('results', $data, "Missing 'results' key in {$name}");
            $this->assertArrayHasKey('page', $data, "Missing 'page' key in {$name}");
            $this->assertArrayHasKey('total_pages', $data, "Missing 'total_pages' key in {$name}");
            $this->assertArrayHasKey('total_results', $data, "Missing 'total_results' key in {$name}");
        }
    }
}
