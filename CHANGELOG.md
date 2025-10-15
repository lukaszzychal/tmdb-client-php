# Changelog

All notable changes to this project will be documented in this file.

**Author:** [Łukasz Zychal](https://github.com/lukaszzychal)

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- **TMDB License Compliance Utilities** (`LukaszZychal\TMDB\Utils\LicenseCompliance`)
  - Automatic attribution text generation
  - HTML attribution markup generation
  - CSS styling for TMDB attribution
  - JSON-LD structured data for attribution
  - HTML content validation for compliance
  - Compliance checking utilities
- **Comprehensive License Compliance Guide** (`TMDB_LICENSE_COMPLIANCE.md`)
  - Detailed TMDB API terms compliance requirements
  - Implementation examples in PHP and HTML
  - Best practices for API key management
  - Rate limiting guidelines
  - Error handling recommendations
- **License Compliance Example** (`examples/license-compliance-example.php`)
  - Complete working examples of compliance implementation
  - HTML generation with proper attribution
  - Validation and compliance checking
- **Unit Tests** for license compliance utilities
- **Updated Documentation** with compliance requirements and examples

### Changed
- **README.md** updated with TMDB license compliance information
- **Quick Start** examples now include mandatory attribution
- Added compliance helper examples in documentation

## [1.0.0] - 2025-10-15

### Added
- Release 1.0.0

### Changed
- Updated dependencies

### Fixed
- Various bug fixes and improvements


### Added
- Initial release of TMDB Client PHP
- Complete TMDB API coverage for Movies, TV Shows, People, Search, Configuration, and Genres
- HTTP client implementation using Guzzle
- Comprehensive exception handling for different error scenarios
- Full test suite with Unit, Integration, and Contract tests
- CI/CD pipeline with GitHub Actions
- Code quality tools integration (PHPStan, Psalm, PHP CS Fixer)
- Comprehensive documentation and examples
- PSR-4 autoloading and PSR standards compliance
- Logging support with PSR-3 compatible loggers
- Rate limiting and error handling for API calls

### Technical Details
- PHP 8.1+ requirement
- Guzzle HTTP Client 7.8+ dependency
- PSR-7 HTTP message interfaces
- PSR-3 logging interfaces
- Comprehensive type declarations
- Static analysis support
