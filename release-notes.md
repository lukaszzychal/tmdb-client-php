## TMDB Client PHP v1.0.0

### 🎉 First Stable Release!

This is the first stable release of the TMDB Client PHP library.

### ✨ Features

- 🎬 **Complete TMDB API Coverage** - Movies, TV Shows, People, Search, Configuration, Genres
- 🚀 **Modern PHP** - Built for PHP 8.1+ with strict types and PSR compliance
- 🛡️ **Type Safety** - Full type declarations and static analysis support
- 🧪 **Comprehensive Testing** - Unit, integration, and contract tests
- 📊 **CI/CD Pipeline** - Automated testing, code quality, and security scanning
- 📚 **Well Documented** - Complete API documentation and examples
- 🔄 **Automated Maintenance** - Dependabot and monthly contract tests

### 🚀 Quick Start

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
print_r($movies);
```

### 📦 Installation

```bash
composer require lukaszzychal/tmdb-client-php:^1.0
```

### 🔗 Links

- [Documentation](https://github.com/lukaszzychal/tmdb-client-php#readme)
- [Packagist](https://packagist.org/packages/lukaszzychal/tmdb-client-php)
- [Issues](https://github.com/lukaszzychal/tmdb-client-php/issues)
- [Changelog](https://github.com/lukaszzychal/tmdb-client-php/blob/main/CHANGELOG.md)

### 🏷️ Tag: v1.0.0
