<?php

declare(strict_types=1);

namespace LukaszZychal\TMDB\Client;

use LukaszZychal\TMDB\Http\HttpClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Client for TMDB Genres API endpoints
 */
class GenresClient
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Get movie genres
     */
    public function getMovieList(array $options = []): ResponseInterface
    {
        return $this->httpClient->get('genre/movie/list', ['query' => $options]);
    }

    /**
     * Get TV show genres
     */
    public function getTVList(array $options = []): ResponseInterface
    {
        return $this->httpClient->get('genre/tv/list', ['query' => $options]);
    }
}
