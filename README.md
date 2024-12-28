# Package Generator

All packages are installed in `app/Modules/$packageName`

## Requirements
- Laravel 11

## Installation

```php
composer require avido/package-generator
# Publish concord config file.
php artisan vendor:publish --provider="Konekt\Concord\ConcordServiceProvider" --tag=config
```
## CLI Command

```php
php artisan module:make

follow instructions

# Don't forget to register the Module in config/concord.php
```
