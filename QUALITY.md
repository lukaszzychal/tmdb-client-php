# Quality Tools Configuration - TMDB Client PHP

This project uses a comprehensive set of quality tools to ensure code quality, consistency, and reliability for the TMDB API client.

## 🛠️ Tools Used

### 1. **PHP CS Fixer** - Code Style
- **Version**: ^3.0
- **Config**: `.php-cs-fixer.php`
- **Purpose**: Enforces consistent coding style and formatting
- **Rules**: PSR-12, Symfony, comprehensive formatting rules

### 2. **PHPStan** - Static Analysis
- **Version**: ^1.10
- **Config**: `phpstan.neon`
- **Level**: 8 (maximum)
- **Purpose**: Finds bugs and type errors with advanced analysis

### 3. **Psalm** - Static Analysis
- **Version**: ^6.0 (PHP 8.4 compatible)
- **Config**: `psalm.xml`
- **Level**: 2 (strict)
- **Purpose**: Advanced static analysis with type checking and baseline support

### 4. **PHPUnit** - Testing
- **Version**: ^10.0
- **Config**: `phpunit.xml`
- **Purpose**: Unit, Integration, and Contract testing
- **Coverage**: HTML and text coverage reports

## 🚀 Available Commands

### Individual Tools
```bash
# Code style checking
composer cs-check

# Code style fixing
composer cs-fix

# PHPStan static analysis
composer phpstan

# Psalm static analysis
composer psalm

# Create Psalm baseline
composer psalm-baseline

# Run all tests
composer test

# Run core tests (Unit + Integration)
composer test-core

# Run contract tests
composer test-contract

# Run tests with coverage
composer test-coverage
```

### Quality Checks
```bash
# Fast quality check (CS + Core Tests)
composer quality-fast

# Standard quality check (CS + PHPStan + Psalm + Core Tests)
composer quality-core

# Full quality check (CS + PHPStan + Psalm + All Tests)
composer quality-full

# Quality with Psalm (CS + PHPStan + Psalm + Core Tests)
composer quality-with-psalm
```

## 📋 Quality Levels

### **Level 1: Fast** (`quality-fast`)
- ✅ Code style check
- ✅ Unit and Integration tests
- ⏱️ ~30 seconds
- 🎯 **Use for**: Quick commits, development

### **Level 2: Core** (`quality-core`)
- ✅ Code style check
- ✅ PHPStan analysis (Level 8)
- ✅ Psalm analysis (Level 2)
- ⏱️ ~1.5 minutes
- 🎯 **Use for**: CI/CD, pull requests

### **Level 3: With Psalm** (`quality-with-psalm`)
- ✅ Code style check
- ✅ PHPStan analysis
- ✅ Psalm analysis
- ✅ Unit and Integration tests
- ⏱️ ~2 minutes
- 🎯 **Use for**: Thorough testing

### **Level 4: Full** (`quality-full`)
- ✅ Code style check
- ✅ PHPStan analysis
- ✅ Psalm analysis
- ✅ All tests (Unit, Integration, Contract)
- ⏱️ ~3 minutes
- 🎯 **Use for**: Pre-release, final validation

## 🔧 Configuration Details

### PHP CS Fixer
- **Standards**: PSR-12, Symfony
- **Migration**: PHP 8.2+
- **Features**: Array syntax, braces, spacing, imports, modern PHP features
- **Cache**: Enabled for performance
- **Risky**: Allowed for comprehensive fixes

### PHPStan
- **Level**: 8 (maximum strictness)
- **Target**: `src/` and `examples/` directories
- **Features**: Parallel processing, cache optimization
- **Ignores**: Missing type hints for flexibility
- **Performance**: 4 processes, memory optimization

### Psalm
- **Level**: 2 (strict analysis)
- **Features**: Type checking, dead code detection, baseline support
- **Baseline**: `psalm-baseline.xml` (74 existing issues ignored)
- **Ignores**: Mixed types for API responses, examples
- **Performance**: JIT acceleration, file caching

### PHPUnit
- **Version**: 10.x
- **Test Suites**: Unit, Integration, Contract, All
- **Coverage**: HTML and text reports
- **Environment**: Testing configuration with TMDB settings
- **Performance**: Cache directory, optimized execution

## 🎯 Best Practices

### 1. **Before Committing**
```bash
composer quality-fast
```

### 2. **Before Pull Request**
```bash
composer quality-core
```

### 3. **Before Release**
```bash
composer quality-full
```

### 4. **Development Workflow**
```bash
# Fix code style issues
composer cs-fix

# Check static analysis
composer phpstan
composer psalm

# Run specific tests
composer test-core
```

## 🚨 Troubleshooting

### Common Issues

#### PHP CS Fixer Errors
```bash
# Fix automatically
composer cs-fix

# Check what would be fixed
composer cs-check
```

#### PHPStan Errors
```bash
# Check specific file
vendor/bin/phpstan analyse src/Client/TMDBClient.php

# Check with specific level
vendor/bin/phpstan analyse --level=7 src/
```

#### Psalm Errors
```bash
# Create baseline for existing issues
composer psalm-baseline

# Check specific file
vendor/bin/psalm src/Client/TMDBClient.php

# Update baseline (remove fixed issues)
vendor/bin/psalm --update-baseline
```

#### Test Failures
```bash
# Run specific test
vendor/bin/phpunit tests/Unit/Client/TMDBClientTest.php

# Run with verbose output
vendor/bin/phpunit --verbose

# Run specific test suite
vendor/bin/phpunit --testsuite=Unit
```

## 📊 Coverage Goals

- **Minimum**: 80% code coverage
- **Target**: 90% code coverage
- **Critical**: 100% for core TMDB functionality

## 🔄 Workflow Integration

### GitHub Actions
The CI pipeline runs comprehensive quality checks:
- **Test Matrix**: PHP 8.1, 8.2, 8.3, 8.4
- **Quality Gate**: CS Fixer, PHPStan, Psalm
- **Security**: CodeQL, Trivy scanning
- **Contract Tests**: Real TMDB API validation

### Pre-commit Hooks
Consider adding pre-commit hooks to run `composer quality-fast` automatically.

## 🏗️ Project Structure

```
tmdb-client-php/
├── src/                    # Source code
│   ├── Client/            # API clients
│   ├── Http/              # HTTP layer
│   ├── Exception/         # Custom exceptions
│   └── Utils/             # Utilities
├── tests/                 # Test suites
│   ├── Unit/             # Unit tests
│   ├── Integration/      # Integration tests
│   └── Contract/         # Contract tests
├── examples/             # Usage examples
├── docs/                # Documentation
└── .github/workflows/   # CI/CD pipelines
```

## 🎨 Code Style Standards

- **PSR-12**: PHP coding standards
- **Symfony**: Additional formatting rules
- **Modern PHP**: 8.2+ features and syntax
- **Type Safety**: Strict typing where possible
- **Documentation**: Comprehensive PHPDoc

## 🔍 Static Analysis Features

### PHPStan (Level 8)
- Type inference and checking
- Dead code detection
- Security vulnerability detection
- Performance analysis
- Parallel processing

### Psalm (Level 2)
- Advanced type checking
- Taint analysis capabilities
- Baseline management
- Plugin support
- JIT acceleration

## 📚 Resources

- [PHP CS Fixer Documentation](https://cs.symfony.com/)
- [PHPStan Documentation](https://phpstan.org/)
- [Psalm Documentation](https://psalm.dev/)
- [PHPUnit Documentation](https://phpunit.de/)
- [TMDB API Documentation](https://developers.themoviedb.org/)

## 🚀 Performance Tips

1. **Use Cache**: All tools use caching for faster subsequent runs
2. **Parallel Processing**: PHPStan uses 4 parallel processes
3. **Baseline**: Psalm baseline ignores existing issues
4. **Selective Testing**: Use `test-core` for faster feedback
5. **IDE Integration**: Configure your IDE to run tools automatically

## 📈 Metrics

- **Code Coverage**: Available in `build/coverage/`
- **Static Analysis**: 0 errors in all tools
- **Test Results**: 53 tests, 133 assertions
- **Performance**: Optimized for CI/CD pipelines