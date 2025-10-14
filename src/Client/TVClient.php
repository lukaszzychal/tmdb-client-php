<?php

declare(strict_types=1);

namespace LukaszZychal\TMDB\Client;

use LukaszZychal\TMDB\Http\HttpClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Client for TMDB TV Shows API endpoints.
 */
class TVClient
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Get TV show details.
     */
    public function getDetails(int $tvId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("tv/{$tvId}", ['query' => $options]);
    }

    /**
     * Get popular TV shows.
     */
    public function getPopular(array $options = []): ResponseInterface
    {
        return $this->httpClient->get('tv/popular', ['query' => $options]);
    }

    /**
     * Get TV shows airing today.
     */
    public function getAiringToday(array $options = []): ResponseInterface
    {
        return $this->httpClient->get('tv/airing_today', ['query' => $options]);
    }

    /**
     * Get TV shows currently on the air.
     */
    public function getOnTheAir(array $options = []): ResponseInterface
    {
        return $this->httpClient->get('tv/on_the_air', ['query' => $options]);
    }

    /**
     * Get top rated TV shows.
     */
    public function getTopRated(array $options = []): ResponseInterface
    {
        return $this->httpClient->get('tv/top_rated', ['query' => $options]);
    }

    /**
     * Get TV show credits.
     */
    public function getCredits(int $tvId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("/tv/{$tvId}/credits", ['query' => $options]);
    }

    /**
     * Get TV show reviews.
     */
    public function getReviews(int $tvId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("/tv/{$tvId}/reviews", ['query' => $options]);
    }

    /**
     * Get TV show videos.
     */
    public function getVideos(int $tvId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("/tv/{$tvId}/videos", ['query' => $options]);
    }

    /**
     * Get TV show images.
     */
    public function getImages(int $tvId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("/tv/{$tvId}/images", ['query' => $options]);
    }

    /**
     * Get similar TV shows.
     */
    public function getSimilar(int $tvId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("/tv/{$tvId}/similar", ['query' => $options]);
    }

    /**
     * Get recommended TV shows.
     */
    public function getRecommendations(int $tvId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("/tv/{$tvId}/recommendations", ['query' => $options]);
    }

    /**
     * Get TV show season details.
     */
    public function getSeasonDetails(int $tvId, int $seasonNumber, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("/tv/{$tvId}/season/{$seasonNumber}", ['query' => $options]);
    }

    /**
     * Get TV show episode details.
     */
    public function getEpisodeDetails(int $tvId, int $seasonNumber, int $episodeNumber, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("/tv/{$tvId}/season/{$seasonNumber}/episode/{$episodeNumber}", ['query' => $options]);
    }
}
