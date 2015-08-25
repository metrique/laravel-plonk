<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Plonk settings.
    |--------------------------------------------------------------------------
    |
    | This is a list of settings for laravel-plonk.
    |
    */

    'crop' => [
        '1' => '1',
        '16' => '9',
        '9' => '16'
    ],

    'mime' => [
        'image/gif',
        'image/jpeg',
        'image/png',
    ],

    'output' => [
        'disk' => 's3',
        'paths' => [
            'base' => '/plonk',
            'originals' => '/originals',
        ]
    ],

    'paginate' => [
        'items' => 24,
    ],

    'size' => [
        [
            'name' => 'small',
            'width' => 640,
            'height' => 360,
            'quality' => 80,
        ],[
            'name' => 'medium',
            'width' => 1024,
            'height' => 576,
            'quality' => 60,
        ],[
            'name' => 'large',
            'width' => 1440,
            'height' => 810,
            'quality' => 50,
        ],[
            'name' => 'xlarge',
            'width' => 1920,
            'height' => 1080,
            'quality' => 40,
        ]
    ]
];