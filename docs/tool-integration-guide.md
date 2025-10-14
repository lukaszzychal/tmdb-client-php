# Static Analysis Tools Integration Guide

## How the Three Tools Work Together

This guide explains how PHP CS Fixer, PHPStan, and Psalm are configured to work together seamlessly in the TMDB Client PHP project.

## Configuration Overview

### 1. PHP CS Fixer Configuration (`.php-cs-fixer.php`)

**Purpose:** Code style and formatting enforcement

```php
<?php
$config = new PhpCsFixer\Config();
return $config
    ->setRules([
        '@PSR12' => true,        // PSR-12 coding standard
        '@PSR1' => true,         // PSR-1 basic coding standard
        '@Symfony' => true,      // Symfony coding standard
        'array_syntax' => ['syntax' => 'short'],  // Use [] instead of array()
        'blank_line_after_opening_tag' => true,
        'blank_line_before_statement' => [
            'statements' => ['return', 'throw', 'try'],
        ],
        // ... more rules for consistent formatting
    ])
    ->setRiskyRules([
        'array_push' => true,
        'dir_constant' => true,
        'ereg_to_preg' => true,
        // ... risky but safe transformations
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
            ->exclude('vendor')      // Don't check vendor files
            ->exclude('tests')       // Don't check test files
            ->exclude('coverage')    // Don't check coverage files
            ->name('*.php')
    );
```

**Key Features:**
- ✅ Enforces PSR-12 and PSR-1 standards
- ✅ Uses Symfony coding standard as base
- ✅ Excludes vendor, tests, and coverage directories
- ✅ Only checks `.php` files
- ✅ Includes risky rules for better code quality

### 2. PHPStan Configuration (`phpstan.neon`)

**Purpose:** Static type analysis and error detection

```yaml
parameters:
    level: 8                     # Maximum strictness level (0-8)
    paths:
        - src                    # Only analyze src directory
    ignoreErrors:
        - '#Call to an undefined method.*#'  # Ignore specific patterns
    excludePaths:
        - tests                  # Don't analyze test files
    checkMissingIterableValueType: false     # Suppress iterable warnings
    checkGenericClassInNonGenericObjectType: false  # Suppress generic warnings
    reportUnmatchedIgnoredErrors: false      # Don't report unmatched ignores
```

**Key Features:**
- ✅ Maximum strictness level (8) for production code
- ✅ Only analyzes `src/` directory (production code)
- ✅ Excludes `tests/` directory
- ✅ Suppresses common false positives
- ✅ Focuses on type safety and logic errors

### 3. Psalm Configuration (`psalm.xml`)

**Purpose:** Advanced type analysis and security detection

```xml
<?xml version="1.0"?>
<psalm
    errorLevel="2"              <!-- Level 2: Good balance of strictness -->
    resolveFromConfigFile="true"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns="https://getpsalm.org/schema/config"
    xsi:schemaLocation="https://getpsalm.org/schema/config vendor/vimeo/psalm/config.xsd"
>
    <projectFiles>
        <directory name="src" />   <!-- Only analyze src directory -->
        <ignoreFiles>
            <directory name="vendor" />  <!-- Ignore vendor files -->
            <directory name="tests" />   <!-- Ignore test files -->
        </ignoreFiles>
    </projectFiles>

    <issueHandlers>
        <MixedAssignment>
            <errorLevel type="suppress">
                <directory name="tests" />  <!-- Suppress mixed assignment in tests -->
            </errorLevel>
        </MixedAssignment>
    </issueHandlers>
</psalm>
```

**Key Features:**
- ✅ Error level 2 (good balance of strictness vs usability)
- ✅ Only analyzes `src/` directory
- ✅ Excludes vendor and test files
- ✅ Suppresses mixed assignment warnings in tests
- ✅ Advanced type inference and security analysis

## How They Work Together

### 1. **Complementary Analysis**

```php
// Example: A problematic piece of code

class ExampleClass
{
    public function processData($data)  // ❌ No type hints
    {
        return $data['key']+1;          // ❌ Wrong spacing, no error handling
    }
}
```

**PHP CS Fixer fixes:**
```php
class ExampleClass
{
    public function processData($data)  // ✅ Still no type hints (PHPStan's job)
    {
        return $data['key'] + 1;        // ✅ Fixed spacing
    }
}
```

**PHPStan detects:**
```php
class ExampleClass
{
    public function processData($data): int  // ✅ Added return type
    {
        if (!isset($data['key'])) {          // ✅ Added error handling
            throw new InvalidArgumentException('Key not found');
        }
        return $data['key'] + 1;
    }
}
```

**Psalm enhances:**
```php
class ExampleClass
{
    /**
     * @param array<string, mixed> $data
     */
    public function processData(array $data): int  // ✅ Added param type + docblock
    {
        if (!isset($data['key'])) {
            throw new InvalidArgumentException('Key not found');
        }
        
        if (!is_numeric($data['key'])) {           // ✅ Added type validation
            throw new InvalidArgumentException('Key must be numeric');
        }
        
        return (int) $data['key'] + 1;             // ✅ Added explicit casting
    }
}
```

### 2. **Directory Separation**

| Tool | Analyzes | Ignores | Why |
|------|----------|---------|-----|
| **PHP CS Fixer** | `src/`, `tests/` | `vendor/`, `coverage/` | Style consistency across all code |
| **PHPStan** | `src/` only | `tests/`, `vendor/` | Type safety for production code |
| **Psalm** | `src/` only | `tests/`, `vendor/` | Advanced analysis for production code |

### 3. **CI/CD Integration**

```yaml
# .github/workflows/ci.yml
- name: Run PHP CS Fixer
  run: vendor/bin/php-cs-fixer fix --dry-run --diff  # Check style (don't fix)

- name: Run PHPStan
  run: vendor/bin/phpstan analyse                     # Check types

- name: Run Psalm
  run: vendor/bin/psalm                              # Check advanced issues
```

**Execution Order:**
1. **PHP CS Fixer** first - ensures consistent formatting
2. **PHPStan** second - catches type-related issues
3. **Psalm** third - performs advanced analysis

### 4. **Composer Scripts Integration**

```json
{
    "scripts": {
        "phpstan": "phpstan analyse",        // PHPStan
        "phpcs": "phpcs src/ tests/",        // PHP CodeSniffer
        "phpcbf": "phpcbf src/ tests/",      // PHP CodeSniffer (auto-fix)
        "php-cs-fixer": "php-cs-fixer fix --dry-run --diff --allow-risky=yes", // PHP CS Fixer
        "php-cs-fixer-fix": "php-cs-fixer fix --allow-risky=yes", // PHP CS Fixer (auto-fix)
        "psalm": "psalm",                    // Psalm
        "quality": [                         // Run all tools (includes contract tests)
            "@php-cs-fixer",
            "@phpstan", 
            "@psalm",
            "@test"
        ],
        "quality-core": [                    // Core quality check (no contract tests)
            "@php-cs-fixer",
            "@phpstan",
            "@psalm"
        ],
        "quality-check": [                   // Quick style check only
            "@php-cs-fixer"
        ],
        "test-core": "vendor/bin/phpunit --testsuite=Unit,Integration" // Core tests only
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

## Configuration Benefits

### 1. **No Conflicts**
- Each tool focuses on different aspects
- No overlapping rules or conflicting suggestions
- Tools complement each other

### 2. **Performance Optimized**
- PHPStan and Psalm only analyze production code (`src/`)
- PHP CS Fixer handles all code for consistency
- Excluded directories reduce analysis time

### 3. **Balanced Strictness**
- PHPStan level 8: Maximum type safety
- Psalm level 2: Good balance of strictness vs usability
- PHP CS Fixer: Comprehensive style enforcement

### 4. **False Positive Management**
- Specific error suppressions where needed
- Different handling for production vs test code
- Unmatched error reporting disabled

## Workflow Integration

### Development Workflow:

1. **Write Code** → PHP CS Fixer ensures consistent style
2. **Commit Code** → PHPStan catches type issues
3. **Push Code** → Psalm performs advanced analysis
4. **CI/CD** → All tools run automatically

### Local Development:

```bash
# Before committing
composer quality    # Run all quality checks

# Individual fixes
composer phpcbf     # Fix style issues automatically
composer phpstan    # Check type issues
composer psalm      # Check advanced issues
```

## Troubleshooting Integration Issues

### Common Scenarios:

1. **PHP CS Fixer configuration issues:**
   ```bash
   # If you get risky rules error
   composer php-cs-fixer-fix  # Auto-fix with --allow-risky=yes
   
   # If you get conflicting rules error
   # Check .php-cs-fixer.php for conflicting rules
   ```

2. **PHPStan type issues:**
   ```bash
   # Fix ternary operator issues
   # Change: $response ? $response->getStatusCode() : 0
   # To: $response->getStatusCode()
   
   # Re-run PHPStan
   composer phpstan
   ```

3. **Psalm advanced type issues:**
   ```bash
   # Add type annotations for arrays
   # /** @var array<string, mixed> $query */
   $query = $options['query'] ?? [];
   
   # Fix array handling
   # Use count() check before array access
   ```

4. **Test failures after URI changes:**
   ```bash
   # Update test expectations to match new URI format
   # Change: '/movie/popular'
   # To: 'movie/popular'
   ```

5. **Contract test failures:**
   ```bash
   # Ensure you have valid API key in .env
   # Or use core tests only
   composer test-core
   ```

## Best Practices

1. **Run tools in order:** CS Fixer → PHPStan → Psalm
2. **Fix style first:** Always run PHP CS Fixer before type analysis
3. **Handle false positives:** Use appropriate suppressions
4. **Keep configurations in sync:** Update all tools when changing standards
5. **Monitor CI/CD:** Watch for new issues in automated runs

## Conclusion

The three tools work together to provide:
- **Consistent Code Style** (PHP CS Fixer)
- **Type Safety** (PHPStan)
- **Advanced Analysis** (Psalm)

This multi-layered approach ensures high-quality, maintainable, and secure PHP code for the TMDB Client package.
