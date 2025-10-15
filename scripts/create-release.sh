#!/bin/bash

# TMDB Client PHP - Release Creation Script
# Usage: ./scripts/create-release.sh [version]
# Example: ./scripts/create-release.sh 1.0.0

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if version is provided
if [ -z "$1" ]; then
    print_error "Version is required!"
    echo "Usage: $0 [version]"
    echo "Example: $0 1.0.0"
    exit 1
fi

VERSION=$1
TAG="v$VERSION"

# Validate version format (basic check)
if ! [[ $VERSION =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
    print_error "Invalid version format. Use semantic versioning (e.g., 1.0.0)"
    exit 1
fi

print_status "Creating release for version: $VERSION"

# Check if we're in the right directory
if [ ! -f "composer.json" ]; then
    print_error "composer.json not found. Please run this script from the project root."
    exit 1
fi

# Check if git is clean
if [ -n "$(git status --porcelain)" ]; then
    print_error "Working directory is not clean. Please commit or stash your changes."
    git status --short
    exit 1
fi

# Check if tag already exists
if git rev-parse "$TAG" >/dev/null 2>&1; then
    print_error "Tag $TAG already exists!"
    exit 1
fi

# Run quality checks
print_status "Running quality checks..."
if ! composer quality-core; then
    print_error "Quality checks failed. Please fix issues before creating release."
    exit 1
fi

# Run tests
print_status "Running tests..."
if ! composer test-core; then
    print_error "Tests failed. Please fix issues before creating release."
    exit 1
fi

print_success "All checks passed!"

# Update CHANGELOG.md
print_status "Updating CHANGELOG.md..."

# Create a temporary file for the new changelog entry
TEMP_CHANGELOG=$(mktemp)

# Add the new version entry
cat > "$TEMP_CHANGELOG" << EOF
## [$VERSION] - $(date +%Y-%m-%d)

### Added
- Release $VERSION

### Changed
- Updated dependencies

### Fixed
- Various bug fixes and improvements

EOF

# Prepend to existing CHANGELOG.md (after the header)
if [ -f "CHANGELOG.md" ]; then
    # Find the line number after the header
    HEADER_END=$(grep -n "^## \[Unreleased\]" CHANGELOG.md | cut -d: -f1)
    if [ -n "$HEADER_END" ]; then
        # Insert new content after the header
        head -n "$HEADER_END" CHANGELOG.md > "${TEMP_CHANGELOG}_new"
        cat "$TEMP_CHANGELOG" >> "${TEMP_CHANGELOG}_new"
        tail -n +$((HEADER_END + 1)) CHANGELOG.md >> "${TEMP_CHANGELOG}_new"
        mv "${TEMP_CHANGELOG}_new" CHANGELOG.md
    else
        # If no unreleased section, prepend to the beginning
        cat "$TEMP_CHANGELOG" CHANGELOG.md > "${TEMP_CHANGELOG}_new"
        mv "${TEMP_CHANGELOG}_new" CHANGELOG.md
    fi
else
    mv "$TEMP_CHANGELOG" CHANGELOG.md
fi

# Commit changes
print_status "Committing changes..."
git add CHANGELOG.md
git commit -m "chore: prepare release $VERSION

- Updated CHANGELOG.md
- Version $VERSION ready for release"

# Create and push tag
print_status "Creating tag $TAG..."
git tag -a "$TAG" -m "Release $VERSION"

print_status "Pushing changes and tag..."
git push origin main
git push origin "$TAG"

print_success "Release $VERSION created successfully!"
print_success "Tag: $TAG"
print_success "The GitHub Actions release workflow will now run automatically."
print_status "You can monitor the release process at:"
echo "  https://github.com/lukaszzychal/tmdb-client-php/actions"
echo "  https://github.com/lukaszzychal/tmdb-client-php/releases"

# Clean up
rm -f "$TEMP_CHANGELOG"

print_warning "Don't forget to update Packagist manually or configure webhook for automatic updates."
print_status "Packagist URL: https://packagist.org/packages/lukaszzychal/tmdb-client-php"


