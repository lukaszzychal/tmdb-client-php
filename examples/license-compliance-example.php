<?php

require_once __DIR__ . '/../vendor/autoload.php';

use LukaszZychal\TMDB\Client\TMDBClient;
use LukaszZychal\TMDB\Utils\EnvLoader;
use LukaszZychal\TMDB\Utils\LicenseCompliance;

// Load environment variables
EnvLoader::load(__DIR__ . '/../.env');

// Create client
$client = new TMDBClient($_ENV['TMDB_API_KEY']);

echo "=== TMDB License Compliance Example ===\n\n";

// Example 1: Basic compliance check
echo "1. License Compliance Check:\n";
$compliance = LicenseCompliance::checkCompliance([
    'requests_per_day' => 500,
    'has_attribution' => true,
    'has_logo' => true,
]);

if ($compliance['is_compliant']) {
    echo "✅ Your implementation is compliant with TMDB terms.\n";
} else {
    echo "❌ Compliance issues found:\n";
    foreach ($compliance['issues'] as $issue) {
        echo "   - {$issue}\n";
    }
}

echo "\n" . str_repeat('=', 50) . "\n\n";

// Example 2: Generate attribution HTML
echo "2. Generated Attribution HTML:\n";
$htmlAttribution = LicenseCompliance::generateHtmlAttribution([
    'class' => 'tmdb-attribution',
    'logo_height' => '20',
    'include_link' => true,
]);

echo $htmlAttribution . "\n";

echo "\n" . str_repeat('=', 50) . "\n\n";

// Example 3: Generate CSS styles
echo "3. CSS Styles for Attribution:\n";
$css = LicenseCompliance::getAttributionStyles();
echo $css . "\n";

echo "\n" . str_repeat('=', 50) . "\n\n";

// Example 4: JSON-LD structured data
echo "4. JSON-LD Structured Data:\n";
$jsonLd = LicenseCompliance::generateJsonLdAttribution();
echo $jsonLd . "\n";

echo "\n" . str_repeat('=', 50) . "\n\n";

// Example 5: Complete HTML page example
echo "5. Complete HTML Page Example:\n";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMDB Data Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .movie {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .movie h3 {
            margin-top: 0;
            color: #333;
        }
        <?php echo LicenseCompliance::getAttributionStyles(); ?>
    </style>
    <script type="application/ld+json">
        <?php echo LicenseCompliance::generateJsonLdAttribution(); ?>
    </script>
</head>
<body>
    <h1>Popular Movies (TMDB Data)</h1>
    
    <?php
    try {
        // Get popular movies
        $moviesResponse = $client->movies()->getPopular();
        $movies = json_decode($moviesResponse->getBody()->getContents(), true);

        foreach (array_slice($movies['results'], 0, 3) as $movie) {
            echo "<div class='movie'>";
            echo "<h3>{$movie['title']}</h3>";
            echo "<p><strong>Release Date:</strong> {$movie['release_date']}</p>";
            echo "<p><strong>Rating:</strong> {$movie['vote_average']}/10</p>";
            echo '<p><strong>Overview:</strong> ' . substr($movie['overview'], 0, 200) . '...</p>';

            // MANDATORY: Include TMDB attribution for each movie
            echo LicenseCompliance::generateHtmlAttribution();
            echo '</div>';
        }
    } catch (Exception $e) {
        echo '<p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
?>
    
    <!-- MANDATORY: Page-level TMDB attribution -->
    <footer>
        <?php echo LicenseCompliance::generateHtmlAttribution(); ?>
    </footer>
</body>
</html>

<?php
echo "\n" . str_repeat('=', 50) . "\n\n";

// Example 6: Validation example
echo "6. Attribution Validation:\n";
$sampleHtml = '
<div class="movie">
    <h3>Sample Movie</h3>
    <p>This product uses the TMDB API but is not endorsed or certified by TMDB.</p>
    <img src="https://www.themoviedb.org/assets/2/v4/logos/v2/blue_short-8e7b30f73a4020692ccca9c88bafe5dcb6f8a62a4c6bc55cd9ba82bb2cd95f6c.svg" alt="TMDB" height="20">
</div>';

$validation = LicenseCompliance::validateHtmlAttribution($sampleHtml);

if ($validation['is_valid']) {
    echo "✅ HTML content passes TMDB attribution validation.\n";
} else {
    echo "❌ HTML content has compliance issues:\n";
    foreach ($validation['errors'] as $error) {
        echo "   - {$error}\n";
    }
}

if (!empty($validation['warnings'])) {
    echo "⚠️ Warnings:\n";
    foreach ($validation['warnings'] as $warning) {
        echo "   - {$warning}\n";
    }
}

echo "\n" . str_repeat('=', 50) . "\n\n";

// Example 7: Rate limiting information
echo "7. Rate Limiting Information:\n";
echo "📊 TMDB API Rate Limits:\n";
echo "   - Free Tier: 1,000 requests per day\n";
echo "   - Paid Tiers: Higher limits available\n";
echo "   - This client includes built-in rate limiting\n";
echo "   - Monitor your usage to stay compliant\n";

echo "\n" . str_repeat('=', 50) . "\n\n";

echo "🎯 Key Compliance Requirements:\n";
echo '1. ✅ Include attribution text: "' . LicenseCompliance::getAttributionText() . "\"\n";
echo '2. ✅ Display TMDB logo: ' . LicenseCompliance::getLogoUrl() . "\n";
echo "3. ✅ Respect rate limits (built into this client)\n";
echo "4. ✅ Handle errors properly (use provided exceptions)\n";
echo '5. ✅ Read TMDB Terms of Service: ' . LicenseCompliance::TMDB_TERMS_URL . "\n";

echo "\n" . str_repeat('=', 50) . "\n\n";
echo "📚 For more information, see TMDB_LICENSE_COMPLIANCE.md\n";
echo '🔗 TMDB Terms of Service: ' . LicenseCompliance::TMDB_TERMS_URL . "\n";
?>
