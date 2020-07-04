# Auth-rbac library for laravel 

Requirements
------------
 - PHP >= 7.0.0
 - Laravel >= 5.5.0

Installation
------------

> This package requires PHP 7+ and Laravel 5.5

First, install laravel 5.5, and make sure that the database connection settings are correct.

```
composer require lson/auth
```

Then run these commands to publish assets and config：

```
php artisan vendor:publish --provider="Lson\Authorization\AuthorizationServiceProvider"
```

Configurations
------------
The file `config/authorization.php` contains an array of configurations, you can find the default configurations in there.