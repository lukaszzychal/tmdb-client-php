<?php

declare(strict_types=1);

namespace LukaszZychal\TMDB\Tests\Unit\Client;

use GuzzleHttp\Psr7\Response;
use LukaszZychal\TMDB\Client\MoviesClient;
use LukaszZychal\TMDB\Http\HttpClientInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class MoviesClientTest extends TestCase
{
    private MoviesClient $moviesClient;
    private MockObject $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->moviesClient = new MoviesClient($this->httpClient);
    }

    public function testGetDetails(): void
    {
        $movieId = 123;
        $options = ['language' => 'en-US'];
        $expectedResponse = new Response(200, [], '{"id": 123, "title": "Test Movie"}');
        
        $this->httpClient
            ->expects($this->once())
            ->method('get')
            ->with("/movie/{$movieId}", ['query' => $options])
            ->willReturn($expectedResponse);

        $response = $this->moviesClient->getDetails($movieId, $options);
        
        $this->assertSame($expectedResponse, $response);
    }

    public function testGetPopular(): void
    {
        $options = ['page' => 1];
        $expectedResponse = new Response(200, [], '{"results": []}');
        
        $this->httpClient
            ->expects($this->once())
            ->method('get')
            ->with('/movie/popular', ['query' => $options])
            ->willReturn($expectedResponse);

        $response = $this->moviesClient->getPopular($options);
        
        $this->assertSame($expectedResponse, $response);
    }

    public function testGetNowPlaying(): void
    {
        $options = ['page' => 1];
        $expectedResponse = new Response(200, [], '{"results": []}');
        
        $this->httpClient
            ->expects($this->once())
            ->method('get')
            ->with('/movie/now_playing', ['query' => $options])
            ->willReturn($expectedResponse);

        $response = $this->moviesClient->getNowPlaying($options);
        
        $this->assertSame($expectedResponse, $response);
    }

    public function testGetUpcoming(): void
    {
        $options = ['page' => 1];
        $expectedResponse = new Response(200, [], '{"results": []}');
        
        $this->httpClient
            ->expects($this->once())
            ->method('get')
            ->with('/movie/upcoming', ['query' => $options])
            ->willReturn($expectedResponse);

        $response = $this->moviesClient->getUpcoming($options);
        
        $this->assertSame($expectedResponse, $response);
    }

    public function testGetTopRated(): void
    {
        $options = ['page' => 1];
        $expectedResponse = new Response(200, [], '{"results": []}');
        
        $this->httpClient
            ->expects($this->once())
            ->method('get')
            ->with('/movie/top_rated', ['query' => $options])
            ->willReturn($expectedResponse);

        $response = $this->moviesClient->getTopRated($options);
        
        $this->assertSame($expectedResponse, $response);
    }

    public function testGetCredits(): void
    {
        $movieId = 123;
        $options = ['language' => 'en-US'];
        $expectedResponse = new Response(200, [], '{"cast": [], "crew": []}');
        
        $this->httpClient
            ->expects($this->once())
            ->method('get')
            ->with("/movie/{$movieId}/credits", ['query' => $options])
            ->willReturn($expectedResponse);

        $response = $this->moviesClient->getCredits($movieId, $options);
        
        $this->assertSame($expectedResponse, $response);
    }

    public function testGetReviews(): void
    {
        $movieId = 123;
        $options = ['page' => 1];
        $expectedResponse = new Response(200, [], '{"results": []}');
        
        $this->httpClient
            ->expects($this->once())
            ->method('get')
            ->with("/movie/{$movieId}/reviews", ['query' => $options])
            ->willReturn($expectedResponse);

        $response = $this->moviesClient->getReviews($movieId, $options);
        
        $this->assertSame($expectedResponse, $response);
    }

    public function testGetVideos(): void
    {
        $movieId = 123;
        $options = ['language' => 'en-US'];
        $expectedResponse = new Response(200, [], '{"results": []}');
        
        $this->httpClient
            ->expects($this->once())
            ->method('get')
            ->with("/movie/{$movieId}/videos", ['query' => $options])
            ->willReturn($expectedResponse);

        $response = $this->moviesClient->getVideos($movieId, $options);
        
        $this->assertSame($expectedResponse, $response);
    }

    public function testGetImages(): void
    {
        $movieId = 123;
        $options = ['language' => 'en-US'];
        $expectedResponse = new Response(200, [], '{"backdrops": [], "posters": []}');
        
        $this->httpClient
            ->expects($this->once())
            ->method('get')
            ->with("/movie/{$movieId}/images", ['query' => $options])
            ->willReturn($expectedResponse);

        $response = $this->moviesClient->getImages($movieId, $options);
        
        $this->assertSame($expectedResponse, $response);
    }

    public function testGetSimilar(): void
    {
        $movieId = 123;
        $options = ['page' => 1];
        $expectedResponse = new Response(200, [], '{"results": []}');
        
        $this->httpClient
            ->expects($this->once())
            ->method('get')
            ->with("/movie/{$movieId}/similar", ['query' => $options])
            ->willReturn($expectedResponse);

        $response = $this->moviesClient->getSimilar($movieId, $options);
        
        $this->assertSame($expectedResponse, $response);
    }

    public function testGetRecommendations(): void
    {
        $movieId = 123;
        $options = ['page' => 1];
        $expectedResponse = new Response(200, [], '{"results": []}');
        
        $this->httpClient
            ->expects($this->once())
            ->method('get')
            ->with("/movie/{$movieId}/recommendations", ['query' => $options])
            ->willReturn($expectedResponse);

        $response = $this->moviesClient->getRecommendations($movieId, $options);
        
        $this->assertSame($expectedResponse, $response);
    }
}
