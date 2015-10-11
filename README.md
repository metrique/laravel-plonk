# laravel-plonk

Library for image management in Laravel 5

## Installation

Add the following to the `repositories` section of your composer.json

```
"repositories": [
    {
        "url": "https://github.com/Metrique/laravel-plonk",
        "type": "git"
    }
],
```

1. Add `"Metrique/laravel-plonk": "dev-master"` to the require section of your composer.json.
2. Add `Metrique\Plonk\PlonkServiceProvider::class,` to your list of service providers. in `config/app.php`.
3. Add `'Plonk' => Metrique\Plonk\PlonkFacade::class` to your list of aliases in `config/app.php`.
4. `composer update`
5. `php artisan metrique:migrate-plonk` to install the migrations to the database/migrations in your application. 

### Config
You can publish the  `config/plonk.php` config file to your application config directory by running `php artisan vendor:publish --tag="plonk-config"`

## Usage
resource('plonk', '\Metrique\Plonk\Http\Controllers\PlonkController');

## Views
If you wish to customise the views for your own application then you may extend the PlonkController and change the views properties to reflect your own views. Copying the default plonk views makes a good base for building on.


