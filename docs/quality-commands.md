# Quality Commands Reference

This document provides a comprehensive reference for all quality-related commands available in the TMDB Client PHP package.

## Overview

The package includes multiple quality tools and commands to ensure code quality, style consistency, and type safety. All commands are available through Composer scripts for easy execution.

## Available Commands

### Core Quality Commands

#### `composer quality-core` ⭐ **Recommended**
**Purpose**: Core quality check for daily development  
**Includes**: Style + Type Safety + Advanced Analysis  
**API Key Required**: No  
**Use Case**: Daily development workflow  

```bash
composer quality-core
```

This command runs:
1. PHP CS Fixer (style check)
2. PHPStan (type safety analysis)
3. Psalm (advanced type analysis)

#### `composer quality-check`
**Purpose**: Quick style check only  
**Includes**: Style formatting only  
**API Key Required**: No  
**Use Case**: Quick style validation  

```bash
composer quality-check
```

#### `composer quality`
**Purpose**: Full quality check including tests  
**Includes**: Style + Type Safety + Advanced Analysis + All Tests  
**API Key Required**: Yes (for contract tests)  
**Use Case**: Complete quality validation before release  

```bash
composer quality
```

This command runs:
1. PHP CS Fixer (style check)
2. PHPStan (type safety analysis)
3. Psalm (advanced type analysis)
4. All tests (Unit + Integration + Contract)

### Individual Tool Commands

#### PHP CS Fixer Commands

```bash
# Check style issues (dry run)
composer php-cs-fixer

# Auto-fix style issues
composer php-cs-fixer-fix
```

#### Static Analysis Commands

```bash
# Type safety analysis (Level 8)
composer phpstan

# Advanced type analysis and security detection
composer psalm
```

#### Legacy PHP CodeSniffer Commands

```bash
# Check code style (legacy)
composer phpcs

# Auto-fix code style (legacy)
composer phpcbf
```

### Test Commands

#### `composer test-core` ⭐ **Recommended for Development**
**Purpose**: Run core tests without API key requirement  
**Includes**: Unit + Integration tests  
**API Key Required**: No  
**Use Case**: Development testing  

```bash
composer test-core
```

#### `composer test`
**Purpose**: Run all tests including contract tests  
**Includes**: Unit + Integration + Contract tests  
**API Key Required**: Yes  
**Use Case**: Complete testing before release  

```bash
composer test
```

#### Coverage Commands

```bash
# Generate HTML coverage report
composer test-coverage
```

## Command Comparison

| Command | Style | Type Safety | Advanced Analysis | Unit Tests | Integration Tests | Contract Tests | API Key |
|---------|-------|-------------|-------------------|------------|-------------------|----------------|---------|
| `quality-check` | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | No |
| `quality-core` | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | No |
| `quality` | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | Yes |
| `test-core` | ❌ | ❌ | ❌ | ✅ | ✅ | ❌ | No |
| `test` | ❌ | ❌ | ❌ | ✅ | ✅ | ✅ | Yes |

## Usage Recommendations

### Daily Development Workflow

```bash
# Quick style check
composer quality-check

# Core quality validation
composer quality-core

# Run core tests
composer test-core
```

### Before Committing

```bash
# Full quality check
composer quality-core

# Or if you have API key configured
composer quality
```

### Before Release

```bash
# Complete validation
composer quality
```

### CI/CD Pipeline

The GitHub Actions workflow uses:
- `composer quality-core` for pull requests
- `composer quality` for releases (with API key)

## Troubleshooting

### Common Issues

#### 1. Contract Test Failures
**Problem**: Contract tests fail with authentication errors  
**Solution**: Ensure you have a valid TMDB API key in your `.env` file  
**Alternative**: Use `composer test-core` to skip contract tests  

#### 2. Style Issues
**Problem**: PHP CS Fixer reports style issues  
**Solution**: Run `composer php-cs-fixer-fix` to auto-fix  

#### 3. Type Analysis Errors
**Problem**: PHPStan or Psalm report type issues  
**Solution**: Fix the reported type issues in your code  

#### 4. Test Failures
**Problem**: Tests fail with URI mismatch errors  
**Solution**: This usually indicates the URI format has changed - check test expectations  

### Performance Tips

1. **Use `quality-core` for daily work** - fastest option without API calls
2. **Run individual tools** when you only need specific analysis
3. **Use `test-core`** for development testing without API key requirements

## Configuration

### Environment Setup

Create a `.env` file for contract tests:
```bash
cp .env.example .env
# Edit .env and add your TMDB API key
```

### Tool Configuration

- **PHP CS Fixer**: `.php-cs-fixer.php`
- **PHPStan**: `phpstan.neon`
- **Psalm**: `psalm.xml`
- **PHPUnit**: `phpunit.xml`

## Integration with IDEs

### VS Code

Add to your `.vscode/settings.json`:
```json
{
    "php.validate.executablePath": "/usr/bin/php",
    "php.suggest.basic": false,
    "phpcs.executablePath": "./vendor/bin/phpcs",
    "phpcs.standard": "PSR12"
}
```

### PhpStorm

1. Enable PHP CS Fixer plugin
2. Configure PHPStan and Psalm as external tools
3. Set up run configurations for quality commands

## Best Practices

1. **Run `quality-core` before each commit**
2. **Use `quality-check` for quick style validation**
3. **Run `test-core` during development**
4. **Use `quality` only when you have a valid API key**
5. **Fix issues immediately rather than accumulating them**
6. **Use auto-fix commands when possible**

## Advanced Usage

### Custom Quality Scripts

You can create custom composer scripts in your `composer.json`:

```json
{
    "scripts": {
        "my-quality": [
            "@php-cs-fixer",
            "@phpstan"
        ]
    }
}
```

### Parallel Execution

For faster execution, you can run tools in parallel:

```bash
# Run multiple tools simultaneously
composer php-cs-fixer &
composer phpstan &
composer psalm &
wait
```

### Integration with Git Hooks

Add to your `.git/hooks/pre-commit`:

```bash
#!/bin/bash
composer quality-core
if [ $? -ne 0 ]; then
    echo "Quality checks failed. Please fix issues before committing."
    exit 1
fi
```

This ensures quality standards are maintained before each commit.
