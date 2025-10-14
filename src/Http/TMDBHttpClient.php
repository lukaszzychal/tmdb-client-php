<?php

declare(strict_types=1);

namespace LukaszZychal\TMDB\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use LukaszZychal\TMDB\Exception\AuthenticationException;
use LukaszZychal\TMDB\Exception\NotFoundException;
use LukaszZychal\TMDB\Exception\RateLimitException;
use LukaszZychal\TMDB\Exception\TMDBException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * HTTP client for TMDB API using Guzzle.
 *
 * @author Łukasz Zychal <https://github.com/lukaszzychal>
 */
class TMDBHttpClient implements HttpClientInterface
{
    private const BASE_URI = 'https://api.themoviedb.org/3/';
    private const DEFAULT_TIMEOUT = 30;
    private const DEFAULT_CONNECT_TIMEOUT = 10;

    private GuzzleClient $client;
    private string $apiKey;
    private LoggerInterface $logger;

    public function __construct(
        string $apiKey,
        array $config = [],
        ?LoggerInterface $logger = null
    ) {
        $this->apiKey = $apiKey;
        $this->logger = $logger ?: new NullLogger();

        $defaultConfig = [
            'base_uri' => self::BASE_URI,
            'timeout' => $config['timeout'] ?? self::DEFAULT_TIMEOUT,
            'connect_timeout' => $config['connect_timeout'] ?? self::DEFAULT_CONNECT_TIMEOUT,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ];

        $this->client = new GuzzleClient(array_merge($defaultConfig, $config));
    }

    public function get(string $uri, array $options = []): ResponseInterface
    {
        return $this->request('GET', $uri, $options);
    }

    public function post(string $uri, array $options = []): ResponseInterface
    {
        return $this->request('POST', $uri, $options);
    }

    public function put(string $uri, array $options = []): ResponseInterface
    {
        return $this->request('PUT', $uri, $options);
    }

    public function delete(string $uri, array $options = []): ResponseInterface
    {
        return $this->request('DELETE', $uri, $options);
    }

    public function request(string $method, string $uri, array $options = []): ResponseInterface
    {
        $options = $this->prepareRequestOptions($options);

        $this->logger->info('Making TMDB API request', [
            'method' => $method,
            'uri' => $uri,
            'base_uri' => self::BASE_URI,
            'full_url' => self::BASE_URI . $uri,
            'options' => $this->sanitizeOptions($options),
        ]);

        try {
            $response = $this->client->request($method, $uri, $options);

            $this->logger->info('TMDB API request successful', [
                'method' => $method,
                'uri' => $uri,
                'status_code' => $response->getStatusCode(),
            ]);

            return $response;
        } catch (ClientException $e) {
            $this->handleClientException($e);
        } catch (ServerException $e) {
            $this->handleServerException($e);
        } catch (TooManyRedirectsException $e) {
            $this->logger->error('Too many redirects', ['uri' => $uri]);

            throw new TMDBException('Too many redirects', 0, $e);
        } catch (GuzzleException $e) {
            $this->logger->error('Guzzle exception', [
                'message' => $e->getMessage(),
                'uri' => $uri,
            ]);

            throw new TMDBException('HTTP request failed: ' . $e->getMessage(), 0, $e);
        }

        // This line should never be reached, but PHPStan needs it
        throw new TMDBException('Unexpected error in HTTP request');
    }

    private function prepareRequestOptions(array $options): array
    {
        // Add API key to query parameters
        /** @var array<string, mixed> $query */
        $query = $options['query'] ?? [];
        $query['api_key'] = $this->apiKey;
        $options['query'] = $query;

        return $options;
    }

    private function handleClientException(ClientException $e): void
    {
        $response = $e->getResponse();
        $statusCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        $this->logger->error('TMDB API client error', [
            'status_code' => $statusCode,
            'response_body' => $responseBody,
        ]);

        switch ($statusCode) {
            case 401:
                throw new AuthenticationException('Invalid API key or authentication failed');
            case 404:
                throw new NotFoundException('Resource not found');
            case 429:
                throw new RateLimitException('Rate limit exceeded');
            default:
                throw new TMDBException(
                    sprintf('Client error %d: %s', $statusCode, $responseBody),
                    $statusCode,
                    $e
                );
        }
    }

    private function handleServerException(ServerException $e): void
    {
        $response = $e->getResponse();
        $statusCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        $this->logger->error('TMDB API server error', [
            'status_code' => $statusCode,
            'response_body' => $responseBody,
        ]);

        throw new TMDBException(
            sprintf('Server error %d: %s', $statusCode, $responseBody),
            $statusCode,
            $e
        );
    }

    private function sanitizeOptions(array $options): array
    {
        $sanitized = $options;

        // Remove sensitive data from logging
        if (isset($sanitized['query']['api_key'])) {
            $sanitized['query']['api_key'] = '***';
        }

        return $sanitized;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }
}
