# laravel-repovices

This package streamlines the creation of repositories and services in Laravel applications.

## Installation

```bash
composer require wmt-web-pruthvip/laravel-repovices
```

Copy the package config to your local config with the publish command::

```bash
php artisan vendor:publish --provider="Laravel\Repovices\RepovicesServiceProvider"
```

## Usage

```bash
php artisan repovice:create {modelName}
```

This will create a new files in your provided directory for the repository and services.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
```