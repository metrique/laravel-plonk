# laravel-plonk

Library for image management in Laravel 5

## Installation

1. Add the following to the `repositories` section of your composer.json

```
"repositories": [
    {
        "url": "https://github.com/Metrique/laravel-plonk",
        "type": "git"
    }
],
```

2. Add `"Metrique/laravel-plonk": "dev-master"` to the require section of your composer.json.
3. `composer update`
4. Add `Metrique\Plonk\PlonkServiceProvider::class,` to your list of service providers. in `config/app.php`.
5. `php artisan metrique:migrate-plonk` to install the migrations to the database/migrations in your application. 

### Config

Config defaults can be configured by editing `config/plonk.php` in your main application directory.

You can publish the  `config/plonk.php` config file to your application config directory by running `php artisan vendor:publish --tag="plonk-config"`


## Usage

** These are just notes for now... **

Route::resource('plonk', '\Metrique\Plonk\Http\Controllers\PlonkController', ['only' => ['index', 'create', 'store']]);


