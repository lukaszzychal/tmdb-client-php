<?php

declare(strict_types=1);

namespace LukaszZychal\TMDB\Client;

use LukaszZychal\TMDB\Http\HttpClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Client for TMDB Configuration API endpoints
 */
class ConfigurationClient
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Get API configuration
     */
    public function getDetails(): ResponseInterface
    {
        return $this->httpClient->get('configuration');
    }

    /**
     * Get countries
     */
    public function getCountries(): ResponseInterface
    {
        return $this->httpClient->get('configuration/countries');
    }

    /**
     * Get jobs
     */
    public function getJobs(): ResponseInterface
    {
        return $this->httpClient->get('configuration/jobs');
    }

    /**
     * Get languages
     */
    public function getLanguages(): ResponseInterface
    {
        return $this->httpClient->get('configuration/languages');
    }

    /**
     * Get primary translations
     */
    public function getPrimaryTranslations(): ResponseInterface
    {
        return $this->httpClient->get('configuration/primary_translations');
    }

    /**
     * Get timezones
     */
    public function getTimezones(): ResponseInterface
    {
        return $this->httpClient->get('configuration/timezones');
    }
}
