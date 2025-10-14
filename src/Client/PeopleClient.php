<?php

declare(strict_types=1);

namespace LukaszZychal\TMDB\Client;

use LukaszZychal\TMDB\Http\HttpClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Client for TMDB People API endpoints.
 */
class PeopleClient
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Get person details.
     */
    public function getDetails(int $personId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("person/{$personId}", ['query' => $options]);
    }

    /**
     * Get popular people.
     */
    public function getPopular(array $options = []): ResponseInterface
    {
        return $this->httpClient->get('person/popular', ['query' => $options]);
    }

    /**
     * Get person movie credits.
     */
    public function getMovieCredits(int $personId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("/person/{$personId}/movie_credits", ['query' => $options]);
    }

    /**
     * Get person TV credits.
     */
    public function getTVCredits(int $personId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("/person/{$personId}/tv_credits", ['query' => $options]);
    }

    /**
     * Get person combined credits.
     */
    public function getCombinedCredits(int $personId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("/person/{$personId}/combined_credits", ['query' => $options]);
    }

    /**
     * Get person images.
     */
    public function getImages(int $personId): ResponseInterface
    {
        return $this->httpClient->get("/person/{$personId}/images");
    }

    /**
     * Get person tagged images.
     */
    public function getTaggedImages(int $personId, array $options = []): ResponseInterface
    {
        return $this->httpClient->get("/person/{$personId}/tagged_images", ['query' => $options]);
    }

    /**
     * Get person external IDs.
     */
    public function getExternalIds(int $personId): ResponseInterface
    {
        return $this->httpClient->get("/person/{$personId}/external_ids");
    }
}
