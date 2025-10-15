<?php

declare(strict_types=1);

namespace LukaszZychal\TMDB\Tests\Unit\Utils;

use LukaszZychal\TMDB\Utils\LicenseCompliance;
use PHPUnit\Framework\TestCase;

/**
 * Test case for LicenseCompliance utility class.
 */
class LicenseComplianceTest extends TestCase
{
    public function testGetAttributionText(): void
    {
        $attribution = LicenseCompliance::getAttributionText();

        $this->assertIsString($attribution);
        $this->assertStringContainsString('TMDB API', $attribution);
        $this->assertStringContainsString('not endorsed or certified', $attribution);
    }

    public function testGetLogoUrl(): void
    {
        $logoUrl = LicenseCompliance::getLogoUrl();

        $this->assertIsString($logoUrl);
        $this->assertStringStartsWith('https://', $logoUrl);
        $this->assertStringContainsString('themoviedb.org', $logoUrl);
    }

    public function testGetPrimaryLogoUrl(): void
    {
        $logoUrl = LicenseCompliance::getPrimaryLogoUrl();

        $this->assertIsString($logoUrl);
        $this->assertStringStartsWith('https://', $logoUrl);
        $this->assertStringContainsString('themoviedb.org', $logoUrl);
    }

    public function testGenerateHtmlAttribution(): void
    {
        $html = LicenseCompliance::generateHtmlAttribution();

        $this->assertIsString($html);
        $this->assertStringContainsString('<div', $html);
        $this->assertStringContainsString('<p>', $html);
        $this->assertStringContainsString('<img', $html);
        $this->assertStringContainsString('TMDB API', $html);
        $this->assertStringContainsString('themoviedb.org', $html);
    }

    public function testGenerateHtmlAttributionWithCustomOptions(): void
    {
        $html = LicenseCompliance::generateHtmlAttribution([
            'class' => 'custom-class',
            'logo_height' => '30',
            'logo_alt' => 'Custom Alt',
            'include_link' => false,
        ]);

        $this->assertStringContainsString('custom-class', $html);
        $this->assertStringContainsString('height="30"', $html);
        $this->assertStringContainsString('alt="Custom Alt"', $html);
        $this->assertStringNotContainsString('<a href', $html);
    }

    public function testGenerateTextAttribution(): void
    {
        $text = LicenseCompliance::generateTextAttribution();

        $this->assertIsString($text);
        $this->assertStringContainsString('TMDB API', $text);
        $this->assertStringContainsString('themoviedb.org', $text);
    }

    public function testGenerateJsonLdAttribution(): void
    {
        $jsonLd = LicenseCompliance::generateJsonLdAttribution();

        $this->assertIsString($jsonLd);

        $data = json_decode($jsonLd, true);
        $this->assertIsArray($data);
        $this->assertEquals('https://schema.org', $data['@context']);
        $this->assertEquals('DataCatalog', $data['@type']);
        $this->assertEquals('The Movie Database (TMDB)', $data['name']);
        $this->assertStringContainsString('TMDB API', $data['attribution']);
    }

    public function testValidateHtmlAttributionWithValidContent(): void
    {
        $validHtml = '
        <div class="movie">
            <h3>Sample Movie</h3>
            <p>This product uses the TMDB API but is not endorsed or certified by TMDB.</p>
            <img src="https://www.themoviedb.org/assets/2/v4/logos/v2/blue_short-8e7b30f73a4020692ccca9c88bafe5dcb6f8a62a4c6bc55cd9ba82bb2cd95f6c.svg" alt="TMDB" height="20">
        </div>';

        $validation = LicenseCompliance::validateHtmlAttribution($validHtml);

        $this->assertTrue($validation['is_valid']);
        $this->assertEmpty($validation['errors']);
    }

    public function testValidateHtmlAttributionWithMissingAttribution(): void
    {
        $invalidHtml = '
        <div class="movie">
            <h3>Sample Movie</h3>
            <p>Movie description without TMDB attribution.</p>
        </div>';

        $validation = LicenseCompliance::validateHtmlAttribution($invalidHtml);

        $this->assertFalse($validation['is_valid']);
        $this->assertContains('Required TMDB attribution text is missing', $validation['errors']);
    }

    public function testValidateHtmlAttributionWithMissingLogo(): void
    {
        $htmlWithTextButNoLogo = '
        <div class="movie">
            <h3>Sample Movie</h3>
            <p>This product uses the TMDB API but is not endorsed or certified by TMDB.</p>
        </div>';

        $validation = LicenseCompliance::validateHtmlAttribution($htmlWithTextButNoLogo);

        $this->assertTrue($validation['is_valid']); // Text is present, so it's valid
        $this->assertNotEmpty($validation['warnings']); // But warnings about logo
        $this->assertStringContainsString('TMDB logo may be missing', $validation['warnings'][0]);
    }

    public function testGetAttributionStyles(): void
    {
        $css = LicenseCompliance::getAttributionStyles();

        $this->assertIsString($css);
        $this->assertStringContainsString('.tmdb-attribution', $css);
        $this->assertStringContainsString('font-size', $css);
        $this->assertStringContainsString('color', $css);
    }

    public function testCheckComplianceWithCompliantUsage(): void
    {
        $compliance = LicenseCompliance::checkCompliance([
            'requests_per_day' => 500,
            'has_attribution' => true,
            'has_logo' => true,
        ]);

        $this->assertTrue($compliance['is_compliant']);
        $this->assertEmpty($compliance['issues']);
    }

    public function testCheckComplianceWithNonCompliantUsage(): void
    {
        $compliance = LicenseCompliance::checkCompliance([
            'requests_per_day' => 1500,
            'has_attribution' => false,
            'has_logo' => false,
        ]);

        $this->assertFalse($compliance['is_compliant']);
        $this->assertNotEmpty($compliance['issues']);
        $this->assertContains('TMDB attribution not detected', $compliance['issues']);
        $this->assertContains('TMDB logo not detected', $compliance['issues']);
    }

    public function testCheckComplianceWithRateLimitExceeded(): void
    {
        $compliance = LicenseCompliance::checkCompliance([
            'requests_per_day' => 1500,
            'has_attribution' => true,
            'has_logo' => true,
        ]);

        $this->assertTrue($compliance['is_compliant']); // Still compliant if attribution is present
        $this->assertNotEmpty($compliance['issues']);
        $this->assertStringContainsString('Exceeding free tier rate limit', $compliance['issues'][0]);
        $this->assertNotEmpty($compliance['recommendations']);
    }

    public function testConstantsAreDefined(): void
    {
        $this->assertIsString(LicenseCompliance::REQUIRED_ATTRIBUTION_TEXT);
        $this->assertIsString(LicenseCompliance::TMDB_LOGO_URL);
        $this->assertIsString(LicenseCompliance::TMDB_PRIMARY_LOGO_URL);
        $this->assertIsString(LicenseCompliance::TMDB_TERMS_URL);

        $this->assertStringStartsWith('https://', LicenseCompliance::TMDB_LOGO_URL);
        $this->assertStringStartsWith('https://', LicenseCompliance::TMDB_PRIMARY_LOGO_URL);
        $this->assertStringStartsWith('https://', LicenseCompliance::TMDB_TERMS_URL);
    }
}
