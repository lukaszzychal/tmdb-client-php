<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use LukaszZychal\TMDB\Client\TMDBClient;
use LukaszZychal\TMDB\Exception\TMDBException;
use LukaszZychal\TMDB\Utils\EnvLoader;

/**
 * Basic usage example for TMDB Client PHP
 * 
 * Option 1: Use .env file (recommended)
 * Copy .env.example to .env and add your API key:
 * cp .env.example .env
 * 
 * Option 2: Set environment variable
 * export TMDB_API_KEY="your-api-key-here"
 */

// Load environment variables from .env file if it exists
EnvLoader::load(__DIR__ . '/../.env');

// Get API key from environment (from .env file or system environment)
$apiKey = EnvLoader::get('TMDB_API_KEY');

if (!$apiKey || $apiKey === 'your-tmdb-api-key-here') {
    echo "Please configure your TMDB API key:\n";
    echo "1. Copy .env.example to .env: cp .env.example .env\n";
    echo "2. Edit .env and replace 'your-tmdb-api-key-here' with your actual API key\n";
    echo "3. Get your API key from: https://www.themoviedb.org/settings/api\n\n";
    echo "Alternatively, set the environment variable:\n";
    echo "export TMDB_API_KEY=\"your-api-key-here\"\n";
    exit(1);
}

try {
    // Initialize the client
    $client = new TMDBClient($apiKey);
    
    echo "=== TMDB Client PHP Basic Usage Example ===\n\n";
    
    // Get popular movies
    echo "1. Getting popular movies...\n";
    $popularMovies = $client->movies()->getPopular(['page' => 1]);
    $movies = json_decode($popularMovies->getBody()->getContents(), true);
    
    if (!empty($movies['results'])) {
        $firstMovie = $movies['results'][0];
        echo "   First popular movie: {$firstMovie['title']} (ID: {$firstMovie['id']})\n";
        
        // Get details for the first movie
        echo "\n2. Getting movie details...\n";
        $movieDetails = $client->movies()->getDetails($firstMovie['id']);
        $movie = json_decode($movieDetails->getBody()->getContents(), true);
        
        echo "   Title: {$movie['title']}\n";
        echo "   Overview: " . substr($movie['overview'], 0, 100) . "...\n";
        echo "   Release Date: {$movie['release_date']}\n";
        echo "   Vote Average: {$movie['vote_average']}\n";
    }
    
    // Search for a movie
    echo "\n3. Searching for movies...\n";
    $searchResults = $client->search()->movies('Inception');
    $search = json_decode($searchResults->getBody()->getContents(), true);
    
    if (!empty($search['results'])) {
        $firstResult = $search['results'][0];
        echo "   First search result: {$firstResult['title']} (ID: {$firstResult['id']})\n";
    }
    
    // Get movie genres
    echo "\n4. Getting movie genres...\n";
    $genres = $client->genres()->getMovieList();
    $genreData = json_decode($genres->getBody()->getContents(), true);
    
    if (!empty($genreData['genres'])) {
        $genreNames = array_column($genreData['genres'], 'name');
        echo "   Available genres: " . implode(', ', array_slice($genreNames, 0, 5)) . "...\n";
    }
    
    // Get configuration
    echo "\n5. Getting API configuration...\n";
    $config = $client->configuration()->getDetails();
    $configData = json_decode($config->getBody()->getContents(), true);
    
    if (isset($configData['images']['base_url'])) {
        echo "   Base image URL: {$configData['images']['base_url']}\n";
    }
    
    echo "\n=== Example completed successfully! ===\n";
    
} catch (TMDBException $e) {
    echo "TMDB Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Unexpected error: " . $e->getMessage() . "\n";
    exit(1);
}
