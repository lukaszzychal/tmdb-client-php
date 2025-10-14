# Static Analysis Tools Documentation

## Overview

This TMDB Client PHP package uses three complementary static analysis tools to ensure code quality, consistency, and reliability. Each tool serves a specific purpose in our development workflow.

## Tools Used

### 1. PHP CS Fixer - Code Style & Formatting

**Purpose:**
- ✅ **Code Style Consistency** - Ensures consistent formatting across the codebase
- ✅ **PSR Standards** - Enforces PSR-1, PSR-2, PSR-12 coding standards
- ✅ **Auto-fixing** - Can automatically fix style issues
- ✅ **Team Consistency** - Prevents style debates in teams

**What it checks:**
- Indentation, spacing, line endings
- Class/method formatting
- Import statements ordering
- Braces placement
- Naming conventions

**Configuration:** `.php-cs-fixer.php`

**Usage:**
```bash
# Check for style issues (dry run)
vendor/bin/php-cs-fixer fix --dry-run --diff

# Fix style issues automatically
vendor/bin/php-cs-fixer fix

# Check specific directory
vendor/bin/php-cs-fixer fix src/ --dry-run
```

### 2. PHPStan - Static Type Analysis

**Purpose:**
- ✅ **Type Safety** - Finds type-related bugs without running code
- ✅ **Dead Code Detection** - Identifies unreachable code
- ✅ **Property/Method Issues** - Catches undefined properties/methods
- ✅ **Logic Errors** - Finds potential runtime errors

**What it checks:**
- Type mismatches
- Undefined variables/properties
- Unreachable code
- Method signature mismatches
- Return type inconsistencies

**Configuration:** `phpstan.neon`

**Usage:**
```bash
# Run analysis
vendor/bin/phpstan analyse

# Run with specific level
vendor/bin/phpstan analyse --level=8

# Generate baseline (ignore current errors)
vendor/bin/phpstan analyse --generate-baseline
```

### 3. Psalm - Advanced Type Analysis

**Purpose:**
- ✅ **Advanced Type Inference** - More sophisticated type checking than PHPStan
- ✅ **Psalm-specific Annotations** - Supports advanced type annotations
- ✅ **False Positive Reduction** - Better at avoiding false positives
- ✅ **Security Analysis** - Can detect security vulnerabilities

**What it checks:**
- Advanced type inference
- Security issues (SQL injection, XSS)
- Array/string manipulation bugs
- More precise type checking
- Complex type relationships

**Configuration:** `psalm.xml`

**Usage:**
```bash
# Run analysis
vendor/bin/psalm

# Run with specific level
vendor/bin/psalm --level=3

# Show info about issues
vendor/bin/psalm --show-info=true
```

## Why All Three Together?

### Complementary Strengths:

1. **PHP CS Fixer** = "How does it look?" (Style & Formatting)
2. **PHPStan** = "Will it work?" (Type Safety & Logic)
3. **Psalm** = "Is it secure and correct?" (Advanced Analysis & Security)

### Real-World Example:

```php
// Before: PHP CS Fixer would fix formatting
class MyClass{
    public function test($data){
        return $data['key']+1;
    }
}

// After: PHPStan would catch type issues
public function test(array $data): int
{
    return $data['key'] + 1; // Error: might not exist
}

// After: Psalm would catch security issues
public function test(string $input): string
{
    return "<div>" . $input . "</div>"; // Security: XSS risk
}
```

## CI/CD Integration

All three tools are integrated into our GitHub Actions workflow:

```yaml
- name: Run PHP CS Fixer
  run: vendor/bin/php-cs-fixer fix --dry-run --diff

- name: Run PHPStan
  run: vendor/bin/phpstan analyse

- name: Run Psalm
  run: vendor/bin/psalm
```

## Alternative Configurations

### Option 1: Just PHPStan (Recommended minimum)
```yaml
- name: Run PHPStan
  run: vendor/bin/phpstan analyse
```
- Covers most type safety issues
- Good balance of features vs complexity

### Option 2: Just PHP CS Fixer
```yaml
- name: Run PHP CS Fixer
  run: vendor/bin/php-cs-fixer fix --dry-run --diff
```
- Only handles code style
- Missing type safety checks

### Option 3: Just Psalm
```yaml
- name: Run Psalm
  run: vendor/bin/psalm
```
- Most advanced analysis
- Can be slower and more complex

## Composer Scripts

Available composer commands for running these tools:

```json
{
    "scripts": {
        "phpstan": "phpstan analyse",
        "phpcs": "phpcs src/ tests/",
        "phpcbf": "phpcbf src/ tests/",
        "php-cs-fixer": "php-cs-fixer fix --dry-run --diff --allow-risky=yes",
        "php-cs-fixer-fix": "php-cs-fixer fix --allow-risky=yes",
        "psalm": "psalm",
        "quality": [
            "@php-cs-fixer",
            "@phpstan",
            "@psalm",
            "@test"
        ],
        "quality-core": [
            "@php-cs-fixer",
            "@phpstan",
            "@psalm"
        ],
        "quality-check": [
            "@php-cs-fixer"
        ],
        "test-core": "vendor/bin/phpunit --testsuite=Unit,Integration"
    }
}
```

**Usage:**
```bash
# Run individual tools
composer phpstan
composer psalm
composer php-cs-fixer

# Core quality check (recommended for daily use)
composer quality-core    # Style + Type Safety + Advanced Analysis

# Quick style check only
composer quality-check

# Full quality check (includes contract tests - needs real API key)
composer quality

# Run core tests (Unit + Integration - no API key needed)
composer test-core

# Auto-fix style issues
composer php-cs-fixer-fix
```

## Benefits for This Project

1. **Packagist Quality** - Shows we care about code quality
2. **Team Collaboration** - Multiple contributors will appreciate consistent style
3. **Bug Prevention** - Catches issues before they reach production
4. **Professional Standard** - Industry best practice for PHP packages
5. **Maintainability** - Easier to maintain and extend the codebase

## Troubleshooting

### Common Issues:

1. **PHPStan errors after adding new code:**
   ```bash
   vendor/bin/phpstan analyse --generate-baseline
   ```

2. **Psalm false positives:**
   - Add `@psalm-suppress` annotations
   - Update `psalm.xml` configuration

3. **PHP CS Fixer conflicts:**
   - Run `vendor/bin/php-cs-fixer fix` to auto-fix
   - Update `.php-cs-fixer.php` rules if needed

## Resources

- [PHP CS Fixer Documentation](https://cs.symfony.com/)
- [PHPStan Documentation](https://phpstan.org/user-guide/getting-started)
- [Psalm Documentation](https://psalm.dev/docs/)

## Conclusion

Using all three tools together provides comprehensive code quality assurance that covers:
- **Code Style** (PHP CS Fixer)
- **Type Safety** (PHPStan)
- **Advanced Analysis** (Psalm)

This multi-layered approach ensures our TMDB Client PHP package maintains high quality standards suitable for production use and Packagist publication.
