# TMDB License Compliance Guide

This guide helps you comply with [The Movie Database (TMDB) API Terms of Service](https://www.themoviedb.org/documentation/api/terms-of-use) when using this PHP client.

## 🔒 Legal Requirements

### 1. Attribution Requirements

**MANDATORY**: You must include the following attribution when using TMDB data:

```
This product uses the TMDB API but is not endorsed or certified by TMDB.
```

### 2. Logo Requirements

**MANDATORY**: Include the official TMDB logo when displaying TMDB data.

- **Logo URL**: `https://www.themoviedb.org/assets/2/v4/logos/v2/blue_short-8e7b30f73a4020692ccca9c88bafe5dcb6f8a62a4c6bc55cd9ba82bb2cd95f6c.svg`
- **Alternative Logo**: `https://www.themoviedb.org/assets/2/v4/logos/primary-green-d70eebe18a5eb5b166d5c1ef0796715b8d1a2cbc698f96d311d62f894ae87085.svg`

### 3. Rate Limiting

**MANDATORY**: Respect TMDB API rate limits:
- **Free Tier**: 1,000 requests per day
- **Paid Tiers**: Higher limits available

This client includes built-in rate limiting to help you comply.

## 📋 Implementation Examples

### PHP Implementation

```php
<?php
require_once 'vendor/autoload.php';

use LukaszZychal\TMDB\Client\TMDBClient;
use LukaszZychal\TMDB\Utils\EnvLoader;

// Load environment variables
EnvLoader::load();

// Create client
$client = new TMDBClient($_ENV['TMDB_API_KEY']);

// Get popular movies
$movies = $client->movies()->getPopular();

// Display data with proper attribution
foreach ($movies['results'] as $movie) {
    echo "<div class='movie'>";
    echo "<h3>{$movie['title']}</h3>";
    echo "<p>{$movie['overview']}</p>";
    
    // MANDATORY: Include TMDB attribution
    echo "<div class='tmdb-attribution'>";
    echo "<p>This product uses the TMDB API but is not endorsed or certified by TMDB.</p>";
    echo "<img src='https://www.themoviedb.org/assets/2/v4/logos/v2/blue_short-8e7b30f73a4020692ccca9c88bafe5dcb6f8a62a4c6bc55cd9ba82bb2cd95f6c.svg' alt='TMDB' height='20'>";
    echo "</div>";
    echo "</div>";
}
?>
```

### HTML Template Example

```html
<!DOCTYPE html>
<html>
<head>
    <title>Movie Database</title>
    <style>
        .tmdb-attribution {
            font-size: 12px;
            color: #666;
            margin-top: 10px;
            padding: 5px;
            border-top: 1px solid #ddd;
        }
        .tmdb-logo {
            height: 20px;
            vertical-align: middle;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <!-- Your movie content here -->
    
    <!-- MANDATORY: TMDB Attribution Footer -->
    <footer class="tmdb-attribution">
        This product uses the TMDB API but is not endorsed or certified by TMDB.
        <img src="https://www.themoviedb.org/assets/2/v4/logos/v2/blue_short-8e7b30f73a4020692ccca9c88bafe5dcb6f8a62a4c6bc55cd9ba82bb2cd95f6c.svg" 
             alt="TMDB" class="tmdb-logo">
    </footer>
</body>
</html>
```

## 🛡️ Built-in Compliance Features

This PHP client includes several features to help you comply with TMDB requirements:

### 1. Rate Limiting
```php
// The client automatically handles rate limiting
$client = new TMDBClient($apiKey, [
    'rate_limit' => true, // Default: true
    'requests_per_second' => 10 // Adjust based on your tier
]);
```

### 2. Proper Error Handling
```php
try {
    $movies = $client->movies()->getPopular();
} catch (\LukaszZychal\TMDB\Exception\RateLimitException $e) {
    // Handle rate limit exceeded
    echo "Rate limit exceeded. Please try again later.";
} catch (\LukaszZychal\TMDB\Exception\AuthenticationException $e) {
    // Handle invalid API key
    echo "Invalid API key. Please check your configuration.";
}
```

### 3. User-Agent Compliance
The client automatically sets a proper User-Agent header:
```
tmdb-client-php/1.0.0 (https://github.com/lukaszzychal/tmdb-client-php)
```

## 📝 Best Practices

### 1. API Key Management
- Store your API key securely (use environment variables)
- Never commit API keys to version control
- Use different keys for development and production

### 2. Data Caching
```php
// Cache TMDB data to reduce API calls
$cacheKey = 'tmdb_movies_' . md5($searchQuery);
if (!$cached = $cache->get($cacheKey)) {
    $movies = $client->movies()->search($searchQuery);
    $cache->set($cacheKey, $movies, 3600); // Cache for 1 hour
}
```

### 3. Error Logging
```php
// Log API errors for monitoring
$logger = new Monolog\Logger('tmdb');
$client = new TMDBClient($apiKey, [], $logger);
```

## ⚠️ Important Notes

1. **API Key Required**: You must obtain a valid API key from [TMDB](https://www.themoviedb.org/settings/api)
2. **Terms of Service**: Read and understand [TMDB API Terms of Service](https://www.themoviedb.org/documentation/api/terms-of-use)
3. **Rate Limits**: Monitor your usage to stay within limits
4. **Attribution**: Always include the required attribution and logo
5. **Data Accuracy**: TMDB data may not always be 100% accurate

## 🔗 Useful Links

- [TMDB API Documentation](https://developers.themoviedb.org/3)
- [TMDB Terms of Service](https://www.themoviedb.org/documentation/api/terms-of-use)
- [TMDB Logo Assets](https://www.themoviedb.org/about/logos-attribution)
- [API Key Management](https://www.themoviedb.org/settings/api)

## 📞 Support

If you have questions about TMDB compliance:
- Check the [TMDB API Documentation](https://developers.themoviedb.org/3)
- Review the [Terms of Service](https://www.themoviedb.org/documentation/api/terms-of-use)
- Contact TMDB support through their official channels

---

**Disclaimer**: This guide is for informational purposes only. Always refer to the official TMDB documentation and terms of service for the most up-to-date requirements.
