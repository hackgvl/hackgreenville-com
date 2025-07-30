# PHP Version Requirement

This project currently requires **PHP 8.1 to 8.3** due to dependency constraints in `composer.lock`.

## Issue with PHP 8.4

When running `composer install` with PHP 8.4, you'll encounter errors like:
```
- openspout/openspout v4.25.0 requires php ~8.1.0 || ~8.2.0 || ~8.3.0
- halaxa/json-machine 1.1.4 requires php 7.0 - 8.3
```

## Solutions

### Option 1: Use PHP 8.3 (Recommended)
Install PHP 8.3 alongside your current version:
```bash
# macOS with Homebrew
brew install php@8.3
brew link php@8.3 --force

# Verify
php -v
```

### Option 2: Update Dependencies
If you need PHP 8.4, update all dependencies:
```bash
composer update
```
This will update `composer.lock` to support PHP 8.4, but may introduce breaking changes.

### Option 3: Install Pint Separately
For development only, you can install Pint globally:
```bash
composer global require laravel/pint
```

Then ensure it's in your PATH:
```bash
export PATH="$HOME/.composer/vendor/bin:$PATH"
```

## Pre-commit Hook

The project uses a pre-commit hook that runs:
1. `yarn lint` - Prettier for JS/CSS formatting
2. `composer lint` - Laravel Pint for PHP code style

Make sure both are properly installed before committing.