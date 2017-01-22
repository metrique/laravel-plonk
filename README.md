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
3. `composer update`
4. `php artisan migrate`

### Config
You can publish the  `config/plonk.php` config file to your application config directory by running `php artisan vendor:publish --tag="laravel-plonk"`

### Migrations
laravel-building migrations will be automatically run when the `php artisan migrate` command is executed.

### Routes
laravel-plonk ships with a default set of resource controllers of which you can easily adjust the url 'prefix' for via the config file.

If you prefer more fine grained control then you may extend the `PlonkServiceProvider.php` file into your own application, and override the `bootRoutes` method.

### Views
If you wish to customise the views for your own application then you may extend the PlonkController and change the views properties to reflect your own views. Copying the default plonk views makes a good base for building on.
