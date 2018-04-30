<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Route settings
    |--------------------------------------------------------------------------
    |
    | Disable routes and configure url prefixes.
    |
    */
    
    'routes' => [
        'api' => false,
        'web' => true,
    ],
    
    'prefix' => [
        'api' => 'api',
        'web' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Crop settings
    |--------------------------------------------------------------------------
    |
    | Set crop settings, for limiting uploads to a certain ratio.
    |
    */
   
    'crop' => [
        '1' => '1',
        '16' => '9',
        '9' => '16'
    ],

    'crop_tolerance' => 0.1,

    /*
    |--------------------------------------------------------------------------
    | Filtering and uploading query parameters
    |--------------------------------------------------------------------------
    |
    | A list of parameters that can be used for filtering,
    | and validation when storing images to Plonk.
    |
    */
   
    'query' => [
        'filter' => [
            'search',
            'ratio',
            'orientation',
        ],
        'store' => [
            'ratio',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Mime types
    |--------------------------------------------------------------------------
    |
    | A list of allowed mime types that can be used with plonk.
    |
    */
   
    'mime' => [
        'image/gif',
        'image/jpeg',
        'image/png',
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    |
    | Set the disk and paths that Plonk should use for storing images.
    |
    */
    'cache' => true,
    'input' => [
        'paths' => [
            'base' => '/plonk',
        ]
    ],
    'output' => [
        'disk' => 's3',
        'paths' => [
            'base' => '/public/plonk',
            'originals' => '/originals',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Paginate
    |--------------------------------------------------------------------------
    |
    | Set how many images are shown per page.
    |
    */

    'paginate' => [
        'items' => 24,
    ],

    /*
    |--------------------------------------------------------------------------
    | Output sizes
    |--------------------------------------------------------------------------
    |
    | This is the list of image sizes that plonk will output.
    |
    */

    'size' => [
        [
            'name' => 'xsmall',
            'width' => 640,
            'height' => 360,
            'quality' => 80,
        ],[
            'name' => 'small',
            'width' => 1024,
            'height' => 576,
            'quality' => 70,
        ],[
            'name' => 'medium',
            'width' => 1440,
            'height' => 810,
            'quality' => 70,
        ],[
            'name' => 'large',
            'width' => 1920,
            'height' => 1080,
            'quality' => 60,
        ],[
            'name' => 'xlarge',
            'width' => 2560,
            'height' => 1440,
            'quality' => 50,
        ]
    ]
];
