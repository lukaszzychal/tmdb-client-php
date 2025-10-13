#!/bin/bash

# Contract Test Cron Script for TMDB Client PHP
# This script runs contract tests against the real TMDB API
# It's designed to be run as a cron job for scheduled API contract verification

set -e

# Configuration
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"
LOG_FILE="$PROJECT_DIR/logs/contract-tests.log"
TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S')

# Create logs directory if it doesn't exist
mkdir -p "$(dirname "$LOG_FILE")"

# Function to log messages
log() {
    echo "[$TIMESTAMP] $1" | tee -a "$LOG_FILE"
}

log "Starting scheduled contract tests..."

# Check if API key is set
if [ -z "$TMDB_API_KEY" ]; then
    log "ERROR: TMDB_API_KEY environment variable not set"
    exit 1
fi

# Change to project directory
cd "$PROJECT_DIR"

# Check if composer dependencies are installed
if [ ! -d "vendor" ]; then
    log "Installing composer dependencies..."
    composer install --no-dev --optimize-autoloader
fi

# Run contract tests
log "Running contract tests..."
if php vendor/bin/phpunit --testsuite=Contract --stop-on-failure >> "$LOG_FILE" 2>&1; then
    log "Contract tests passed successfully"
    exit 0
else
    log "Contract tests failed"
    
    # Send notification (you can customize this)
    if command -v mail >/dev/null 2>&1; then
        echo "TMDB API contract tests failed at $TIMESTAMP. Check logs at $LOG_FILE" | \
        mail -s "TMDB Contract Test Failure" admin@example.com 2>/dev/null || true
    fi
    
    exit 1
fi
