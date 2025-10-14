# TMDB Client PHP

[![CI/CD Pipeline](https://github.com/lukaszzychal/tmdb-client-php/workflows/CI/CD%20Pipeline/badge.svg)](https://github.com/lukaszzychal/tmdb-client-php/actions)
[![Latest Stable Version](https://poser.pugx.org/lukaszzychal/tmdb-client-php/v/stable)](https://packagist.org/packages/lukaszzychal/tmdb-client-php)
[![Total Downloads](https://poser.pugx.org/lukaszzychal/tmdb-client-php/downloads)](https://packagist.org/packages/lukaszzychal/tmdb-client-php)
[![License](https://poser.pugx.org/lukaszzychal/tmdb-client-php/license)](https://packagist.org/packages/lukaszzychal/tmdb-client-php)
[![Code Coverage](https://codecov.io/gh/lukaszzychal/tmdb-client-php/branch/main/graph/badge.svg)](https://codecov.io/gh/lukaszzychal/tmdb-client-php)

A comprehensive PHP client for The Movie Database (TMDB) API built with modern PHP practices and full test coverage.

## Features

- 🎬 **Complete TMDB API Coverage** - Movies, TV Shows, People, Search, Configuration, Genres
- 🚀 **Modern PHP** - Built for PHP 8.1+ with strict types and PSR compliance
- 🛡️ **Type Safety** - Full type declarations and static analysis support
- 🧪 **Comprehensive Testing** - Unit, integration, and contract tests
- 📊 **CI/CD Pipeline** - Automated testing, code quality, and security scanning
- 📚 **Well Documented** - Complete API documentation and examples
- 🔧 **PSR Standards** - PSR-4 autoloading, PSR-3 logging, PSR-7 HTTP messages
- ⚡ **Guzzle Integration** - Built on top of Guzzle HTTP client
- 🎯 **Exception Handling** - Specific exceptions for different error types

## Installation

Install via Composer:

```bash
composer require lukaszzychal/tmdb-client-php
```

## Configuration

### 1. Get TMDB API Key

1. Visit [themoviedb.org](https://www.themoviedb.org/settings/api)
2. Create an account or log in
3. Request an API key
4. Copy your API key

### 2. Configure API Key

**Option A: Using .env file (Recommended)**

```bash
# Copy the example environment file
cp .env.example .env

# Edit .env and replace 'your-tmdb-api-key-here' with your actual API key
nano .env
```

**Option B: Environment Variable**

```bash
export TMDB_API_KEY="your-actual-api-key"
```

## Quick Start

```php
<?php

use LukaszZychal\TMDB\Client\TMDBClient;

// Load API key from .env file or environment variable
$apiKey = $_ENV['TMDB_API_KEY'] ?? getenv('TMDB_API_KEY');

// Initialize the client
$client = new TMDBClient($apiKey);

// Get popular movies
$popularMovies = $client->movies()->getPopular();
$movies = json_decode($popularMovies->getBody()->getContents(), true);

// Get movie details
$movieDetails = $client->movies()->getDetails(550); // Fight Club
$movie = json_decode($movieDetails->getBody()->getContents(), true);

// Search for movies
$searchResults = $client->search()->movies('Inception');
$results = json_decode($searchResults->getBody()->getContents(), true);
```

## API Documentation

### Movies

```php
$movies = $client->movies();

// Get movie details
$movie = $movies->getDetails($movieId, ['language' => 'en-US']);

// Get popular movies
$popular = $movies->getPopular(['page' => 1]);

// Get now playing movies
$nowPlaying = $movies->getNowPlaying(['page' => 1, 'region' => 'US']);

// Get upcoming movies
$upcoming = $movies->getUpcoming(['page' => 1]);

// Get top rated movies
$topRated = $movies->getTopRated(['page' => 1]);

// Get movie credits
$credits = $movies->getCredits($movieId);

// Get movie reviews
$reviews = $movies->getReviews($movieId, ['page' => 1]);

// Get movie videos
$videos = $movies->getVideos($movieId, ['language' => 'en-US']);

// Get movie images
$images = $movies->getImages($movieId, ['language' => 'en-US']);

// Get similar movies
$similar = $movies->getSimilar($movieId, ['page' => 1]);

// Get recommendations
$recommendations = $movies->getRecommendations($movieId, ['page' => 1]);
```

### TV Shows

```php
$tv = $client->tv();

// Get TV show details
$show = $tv->getDetails($tvId, ['language' => 'en-US']);

// Get popular TV shows
$popular = $tv->getPopular(['page' => 1]);

// Get TV shows airing today
$airingToday = $tv->getAiringToday(['page' => 1]);

// Get TV shows on the air
$onTheAir = $tv->getOnTheAir(['page' => 1]);

// Get top rated TV shows
$topRated = $tv->getTopRated(['page' => 1]);

// Get season details
$season = $tv->getSeasonDetails($tvId, $seasonNumber);

// Get episode details
$episode = $tv->getEpisodeDetails($tvId, $seasonNumber, $episodeNumber);
```

### People

```php
$people = $client->people();

// Get person details
$person = $people->getDetails($personId, ['language' => 'en-US']);

// Get popular people
$popular = $people->getPopular(['page' => 1]);

// Get person movie credits
$movieCredits = $people->getMovieCredits($personId);

// Get person TV credits
$tvCredits = $people->getTVCredits($personId);

// Get person combined credits
$combinedCredits = $people->getCombinedCredits($personId);
```

### Search

```php
$search = $client->search();

// Search movies
$movies = $search->movies('Inception', ['year' => 2010]);

// Search TV shows
$tvShows = $search->tv('Breaking Bad', ['first_air_date_year' => 2008]);

// Search people
$people = $search->people('Leonardo DiCaprio');

// Multi search (movies, TV shows, people)
$multi = $search->multi('Marvel');
```

### Configuration & Genres

```php
// Get API configuration
$config = $client->configuration()->getDetails();

// Get movie genres
$movieGenres = $client->genres()->getMovieList(['language' => 'en-US']);

// Get TV genres
$tvGenres = $client->genres()->getTVList(['language' => 'en-US']);
```

## Configuration

You can customize the HTTP client behavior:

```php
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Create a logger
$logger = new Logger('tmdb');
$logger->pushHandler(new StreamHandler('tmdb.log', Logger::INFO));

// Initialize client with custom configuration
$client = new TMDBClient(
    'your-api-key',
    [
        'timeout' => 60,
        'connect_timeout' => 15,
        'headers' => [
            'User-Agent' => 'MyApp/1.0'
        ]
    ],
    $logger
);
```

## Error Handling

The client provides specific exceptions for different error scenarios:

```php
use LukaszZychal\TMDB\Exception\AuthenticationException;
use LukaszZychal\TMDB\Exception\NotFoundException;
use LukaszZychal\TMDB\Exception\RateLimitException;
use LukaszZychal\TMDB\Exception\TMDBException;

try {
    $movie = $client->movies()->getDetails(999999999);
} catch (AuthenticationException $e) {
    // Invalid API key
    echo "Authentication failed: " . $e->getMessage();
} catch (NotFoundException $e) {
    // Resource not found
    echo "Movie not found: " . $e->getMessage();
} catch (RateLimitException $e) {
    // Rate limit exceeded
    echo "Rate limit exceeded: " . $e->getMessage();
} catch (TMDBException $e) {
    // Other TMDB API errors
    echo "API Error: " . $e->getMessage();
}
```

## Testing

### Running Tests

```bash
# Run all tests (includes contract tests - needs real API key)
composer test

# Run core tests only (Unit + Integration - no API key needed)
composer test-core

# Run specific test suites
vendor/bin/phpunit --testsuite=Unit
vendor/bin/phpunit --testsuite=Integration
vendor/bin/phpunit --testsuite=Contract

# Run with coverage
composer test-coverage
```

### Contract Tests

Contract tests verify the real TMDB API behavior. To run them:

1. Get a TMDB API key from [themoviedb.org](https://www.themoviedb.org/settings/api)
2. Configure your API key using one of these methods:
   
   **Option A: Using .env file (Recommended)**
   ```bash
   cp .env.example .env
   # Edit .env and add your API key
   ```
   
   **Option B: Environment variable**
   ```bash
   export TMDB_API_KEY=your-api-key-here
   ```
3. Run contract tests:
   ```bash
   vendor/bin/phpunit --testsuite=Contract
   ```

**Note**: Contract tests are rate-limited and should be run sparingly. They're automatically scheduled to run daily in the CI/CD pipeline.

## Code Quality

This package maintains high code quality standards using multiple static analysis tools:

```bash
# Core quality check (recommended for daily use)
composer quality-core    # Style + Type Safety + Advanced Analysis

# Quick style check only
composer quality-check

# Full quality check (includes contract tests - needs real API key)
composer quality

# Individual tools
composer php-cs-fixer    # Code style check
composer phpstan         # Static type analysis
composer psalm          # Advanced type analysis

# Auto-fix style issues
composer php-cs-fixer-fix

# Run core tests (Unit + Integration - no API key needed)
composer test-core
```

### Quality Tools Used:
- **PHP CS Fixer**: Code style and formatting
- **PHPStan**: Static type analysis (Level 8)
- **Psalm**: Advanced type analysis and security detection
- **PHPUnit**: Unit, Integration, and Contract testing

For detailed information about all quality commands, see [Quality Commands Reference](docs/quality-commands.md).

## Requirements

- PHP 8.1 or higher
- Composer
- Guzzle HTTP Client 7.8+

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Submit a pull request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Links

- [TMDB API Documentation](https://developers.themoviedb.org/3)
- [Packagist Package](https://packagist.org/packages/lukaszzychal/tmdb-client-php)
- [GitHub Repository](https://github.com/lukaszzychal/tmdb-client-php)

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a list of changes and version history.
