<?php

declare(strict_types=1);

namespace LukaszZychal\TMDB\Http;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface for HTTP client operations.
 */
interface HttpClientInterface
{
    /**
     * Make a GET request.
     *
     * @param string $uri
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function get(string $uri, array $options = []): ResponseInterface;

    /**
     * Make a POST request.
     *
     * @param string $uri
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function post(string $uri, array $options = []): ResponseInterface;

    /**
     * Make a PUT request.
     *
     * @param string $uri
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function put(string $uri, array $options = []): ResponseInterface;

    /**
     * Make a DELETE request.
     *
     * @param string $uri
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function delete(string $uri, array $options = []): ResponseInterface;

    /**
     * Make a request with custom method.
     *
     * @param string $method
     * @param string $uri
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function request(string $method, string $uri, array $options = []): ResponseInterface;
}
