<?php

declare(strict_types=1);

namespace LukaszZychal\TMDB\Client;

use LukaszZychal\TMDB\Http\HttpClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Client for TMDB Search API endpoints
 */
class SearchClient
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Search for movies
     */
    public function movies(string $query, array $options = []): ResponseInterface
    {
        $options['query'] = $query;
        return $this->httpClient->get('search/movie', ['query' => $options]);
    }

    /**
     * Search for TV shows
     */
    public function tv(string $query, array $options = []): ResponseInterface
    {
        $options['query'] = $query;
        return $this->httpClient->get('search/tv', ['query' => $options]);
    }

    /**
     * Search for people
     */
    public function people(string $query, array $options = []): ResponseInterface
    {
        $options['query'] = $query;
        return $this->httpClient->get('search/person', ['query' => $options]);
    }

    /**
     * Search for companies
     */
    public function companies(string $query, array $options = []): ResponseInterface
    {
        $options['query'] = $query;
        return $this->httpClient->get('search/company', ['query' => $options]);
    }

    /**
     * Search for collections
     */
    public function collections(string $query, array $options = []): ResponseInterface
    {
        $options['query'] = $query;
        return $this->httpClient->get('search/collection', ['query' => $options]);
    }

    /**
     * Search for keywords
     */
    public function keywords(string $query, array $options = []): ResponseInterface
    {
        $options['query'] = $query;
        return $this->httpClient->get('search/keyword', ['query' => $options]);
    }

    /**
     * Multi search
     */
    public function multi(string $query, array $options = []): ResponseInterface
    {
        $options['query'] = $query;
        return $this->httpClient->get('search/multi', ['query' => $options]);
    }
}
