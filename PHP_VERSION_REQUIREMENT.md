# PHP Version Requirement

This project requires **PHP 8.5+** as specified in `composer.json`.

## Installation

Install PHP 8.5 alongside your current version:
```bash
# macOS with Homebrew
brew install php@8.5
brew link php@8.5 --force

# Verify
php -v
```

## Install Pint Separately

If needed, you can install Pint globally for development:
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