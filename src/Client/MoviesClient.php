<?php

declare(strict_types=1);

namespace LukaszZychal\TMDB\Client;

use LukaszZychal\TMDB\Http\HttpClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Client for TMDB Movies API endpoints.
 */
class MoviesClient
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Get movie details.
     */
    public function getDetails(int $movieId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("movie/{$movieId}", ['query' => $options]);
    }

    /**
     * Get popular movies.
     */
    public function getPopular(array $options = []): ResponseInterface
    {
        return $this->httpClient->get('movie/popular', ['query' => $options]);
    }

    /**
     * Get now playing movies.
     */
    public function getNowPlaying(array $options = []): ResponseInterface
    {
        return $this->httpClient->get('movie/now_playing', ['query' => $options]);
    }

    /**
     * Get upcoming movies.
     */
    public function getUpcoming(array $options = []): ResponseInterface
    {
        return $this->httpClient->get('movie/upcoming', ['query' => $options]);
    }

    /**
     * Get top rated movies.
     */
    public function getTopRated(array $options = []): ResponseInterface
    {
        return $this->httpClient->get('movie/top_rated', ['query' => $options]);
    }

    /**
     * Get movie credits.
     */
    public function getCredits(int $movieId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("movie/{$movieId}/credits", ['query' => $options]);
    }

    /**
     * Get movie reviews.
     */
    public function getReviews(int $movieId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("movie/{$movieId}/reviews", ['query' => $options]);
    }

    /**
     * Get movie videos.
     */
    public function getVideos(int $movieId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("movie/{$movieId}/videos", ['query' => $options]);
    }

    /**
     * Get movie images.
     */
    public function getImages(int $movieId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("movie/{$movieId}/images", ['query' => $options]);
    }

    /**
     * Get similar movies.
     */
    public function getSimilar(int $movieId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("movie/{$movieId}/similar", ['query' => $options]);
    }

    /**
     * Get recommended movies.
     */
    public function getRecommendations(int $movieId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("movie/{$movieId}/recommendations", ['query' => $options]);
    }
}
