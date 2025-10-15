<?php

declare(strict_types=1);

namespace LukaszZychal\TMDB\Utils;

/**
 * TMDB License Compliance Utilities.
 *
 * This class provides utilities to help comply with TMDB API Terms of Service.
 *
 * @author Łukasz Zychal <https://github.com/lukaszzychal>
 */
class LicenseCompliance
{
    public const REQUIRED_ATTRIBUTION_TEXT = 'This product uses the TMDB API but is not endorsed or certified by TMDB.';

    public const TMDB_LOGO_URL = 'https://www.themoviedb.org/assets/2/v4/logos/v2/blue_short-8e7b30f73a4020692ccca9c88bafe5dcb6f8a62a4c6bc55cd9ba82bb2cd95f6c.svg';

    public const TMDB_PRIMARY_LOGO_URL = 'https://www.themoviedb.org/assets/2/v4/logos/primary-green-d70eebe18a5eb5b166d5c1ef0796715b8d1a2cbc698f96d311d62f894ae87085.svg';

    public const TMDB_TERMS_URL = 'https://www.themoviedb.org/documentation/api/terms-of-use';

    /**
     * Get the required TMDB attribution text.
     */
    public static function getAttributionText(): string
    {
        return self::REQUIRED_ATTRIBUTION_TEXT;
    }

    /**
     * Get the TMDB logo URL (short version).
     */
    public static function getLogoUrl(): string
    {
        return self::TMDB_LOGO_URL;
    }

    /**
     * Get the TMDB primary logo URL (full version).
     */
    public static function getPrimaryLogoUrl(): string
    {
        return self::TMDB_PRIMARY_LOGO_URL;
    }

    /**
     * Generate HTML attribution markup.
     *
     * @param array<string, mixed> $options
     */
    public static function generateHtmlAttribution(array $options = []): string
    {
        /** @var string $class */
        $class = $options['class'] ?? 'tmdb-attribution';
        /** @var string $logoHeight */
        $logoHeight = $options['logo_height'] ?? '20';
        /** @var string $logoAlt */
        $logoAlt = $options['logo_alt'] ?? 'TMDB';
        /** @var string $logoUrl */
        $logoUrl = $options['logo_url'] ?? self::TMDB_LOGO_URL;
        /** @var bool $includeLink */
        $includeLink = $options['include_link'] ?? true;

        $html = "<div class=\"{$class}\">";
        $html .= '<p>' . self::REQUIRED_ATTRIBUTION_TEXT . '</p>';

        if ($includeLink === true) {
            $html .= '<a href="' . self::TMDB_TERMS_URL . '" target="_blank" rel="noopener noreferrer">';
        }

        $html .= "<img src=\"{$logoUrl}\" alt=\"{$logoAlt}\" height=\"{$logoHeight}\" style=\"vertical-align: middle;\">";

        if ($includeLink === true) {
            $html .= '</a>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Generate plain text attribution.
     */
    public static function generateTextAttribution(): string
    {
        return self::REQUIRED_ATTRIBUTION_TEXT . "\n" . self::TMDB_TERMS_URL;
    }

    /**
     * Generate JSON-LD structured data for TMDB attribution.
     */
    public static function generateJsonLdAttribution(): string
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'DataCatalog',
            'name' => 'The Movie Database (TMDB)',
            'description' => 'Movie and TV database',
            'url' => 'https://www.themoviedb.org/',
            'provider' => [
                '@type' => 'Organization',
                'name' => 'TMDB',
                'url' => 'https://www.themoviedb.org/',
                'logo' => self::TMDB_PRIMARY_LOGO_URL,
            ],
            'license' => self::TMDB_TERMS_URL,
            'attribution' => self::REQUIRED_ATTRIBUTION_TEXT,
        ];

        $json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        if ($json === false) {
            throw new \RuntimeException('Failed to encode JSON-LD data');
        }

        return $json;
    }

    /**
     * Validate that required attribution is present in HTML content.
     */
    public static function validateHtmlAttribution(string $htmlContent): array
    {
        $errors = [];
        $warnings = [];

        // Check for required attribution text
        if (stripos($htmlContent, self::REQUIRED_ATTRIBUTION_TEXT) === false) {
            $errors[] = 'Required TMDB attribution text is missing';
        }

        // Check for TMDB logo
        if (stripos($htmlContent, 'themoviedb.org') === false) {
            $warnings[] = 'TMDB logo may be missing (no TMDB URL found)';
        }

        // Check for logo image tag
        if (preg_match('/<img[^>]*src[^>]*themoviedb[^>]*>/i', $htmlContent) === 0) {
            $warnings[] = 'TMDB logo image tag may be missing';
        }

        return [
            'is_valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    /**
     * Get CSS styles for TMDB attribution.
     */
    public static function getAttributionStyles(): string
    {
        return '
        .tmdb-attribution {
            font-size: 12px;
            color: #666;
            margin-top: 10px;
            padding: 5px;
            border-top: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        
        .tmdb-attribution p {
            margin: 0 0 5px 0;
            display: inline;
        }
        
        .tmdb-attribution img {
            height: 20px;
            vertical-align: middle;
            margin-left: 5px;
        }
        
        .tmdb-attribution a {
            text-decoration: none;
            color: inherit;
        }
        
        .tmdb-attribution a:hover {
            opacity: 0.8;
        }
        ';
    }

    /**
     * Check if the current usage complies with TMDB terms.
     */
    public static function checkCompliance(array $usage = []): array
    {
        $compliance = [
            'is_compliant' => true,
            'issues' => [],
            'recommendations' => [],
        ];

        // Check rate limiting
        if (isset($usage['requests_per_day']) && $usage['requests_per_day'] > 1000) {
            $compliance['issues'][] = 'Exceeding free tier rate limit (1000 requests/day)';
            $compliance['recommendations'][] = 'Consider upgrading to a paid TMDB plan';
        }

        // Check attribution
        if (empty($usage['has_attribution'])) {
            $compliance['issues'][] = 'TMDB attribution not detected';
            $compliance['is_compliant'] = false;
        }

        // Check logo
        if (empty($usage['has_logo'])) {
            $compliance['issues'][] = 'TMDB logo not detected';
            $compliance['is_compliant'] = false;
        }

        return $compliance;
    }
}
