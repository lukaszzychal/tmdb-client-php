<?php

declare(strict_types=1);

namespace LukaszZychal\TMDB\Client;

use LukaszZychal\TMDB\Http\HttpClientInterface;
use LukaszZychal\TMDB\Http\TMDBHttpClient;
use Psr\Log\LoggerInterface;

/**
 * Main TMDB API client.
 *
 * @author Łukasz Zychal <https://github.com/lukaszzychal>
 */
class TMDBClient
{
    private HttpClientInterface $httpClient;

    public function __construct(
        string $apiKey,
        array $config = [],
        ?LoggerInterface $logger = null
    ) {
        $this->httpClient = new TMDBHttpClient($apiKey, $config, $logger);
    }

    /**
     * Get movies endpoints.
     */
    public function movies(): MoviesClient
    {
        return new MoviesClient($this->httpClient);
    }

    /**
     * Get TV shows endpoints.
     */
    public function tv(): TVClient
    {
        return new TVClient($this->httpClient);
    }

    /**
     * Get people endpoints.
     */
    public function people(): PeopleClient
    {
        return new PeopleClient($this->httpClient);
    }

    /**
     * Get search endpoints.
     */
    public function search(): SearchClient
    {
        return new SearchClient($this->httpClient);
    }

    /**
     * Get configuration endpoints.
     */
    public function configuration(): ConfigurationClient
    {
        return new ConfigurationClient($this->httpClient);
    }

    /**
     * Get genres endpoints.
     */
    public function genres(): GenresClient
    {
        return new GenresClient($this->httpClient);
    }

    /**
     * Get the underlying HTTP client.
     */
    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }
}
