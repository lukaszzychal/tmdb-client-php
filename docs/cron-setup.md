# Contract Test Cron Setup

This document explains how to set up scheduled contract tests for the TMDB Client PHP package.

## Overview

Contract tests verify that the TMDB API still behaves as expected and that our client implementation remains compatible. These tests make real API calls and should be run sparingly to respect rate limits.

## Setup Instructions

### 1. Get TMDB API Key

1. Visit [themoviedb.org](https://www.themoviedb.org/settings/api)
2. Create an account or log in
3. Request an API key
4. Copy your API key

### 2. Configure API Key

**Option A: Using .env file (Recommended)**

```bash
# Copy the example environment file
cp .env.example .env

# Edit .env and replace 'your-tmdb-api-key-here' with your actual API key
nano .env
```

**Option B: Set Environment Variable**

Add your API key to your system environment:

```bash
# Add to ~/.bashrc, ~/.zshrc, or /etc/environment
export TMDB_API_KEY="your-api-key-here"
```

### 3. Install Dependencies

```bash
cd /path/to/tmdb-client-php
composer install --no-dev --optimize-autoloader
```

### 4. Test the Script

Run the contract test script manually first:

```bash
./scripts/contract-test-cron.sh
```

### 5. Set Up Cron Job

Add a cron entry to run the tests daily at 2 AM:

```bash
# Edit crontab
crontab -e

# Add this line (adjust path as needed)
0 2 * * * cd /path/to/tmdb-client-php && TMDB_API_KEY="your-api-key-here" ./scripts/contract-test-cron.sh
```

### 6. Monitor Results

Check the logs for test results:

```bash
tail -f logs/contract-tests.log
```

## Alternative: GitHub Actions

The project includes GitHub Actions that automatically run contract tests daily. To use this:

1. Fork the repository
2. Add your TMDB API key as a secret named `TMDB_API_KEY`
3. The tests will run automatically on a schedule

## Rate Limiting

- TMDB API has rate limits (typically 40 requests per 10 seconds)
- The script includes delays between requests
- Contract tests are designed to make minimal API calls
- If you hit rate limits, consider running tests less frequently

## Troubleshooting

### Permission Denied
```bash
chmod +x scripts/contract-test-cron.sh
```

### API Key Not Found
- Verify the environment variable is set: `echo $TMDB_API_KEY`
- Check the cron environment has access to the variable

### Dependencies Missing
```bash
composer install --no-dev --optimize-autoloader
```

### Logs Not Created
```bash
mkdir -p logs
chmod 755 logs
```

## Security Notes

- Never commit your API key to version control
- Use environment variables or secure configuration files
- Consider using a dedicated test API key with limited permissions
- Monitor API usage in your TMDB account dashboard
