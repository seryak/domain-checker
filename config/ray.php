<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable Ray
    |--------------------------------------------------------------------------
    |
    | This option can be used to enable or disable Ray. By default, Ray will
    | always send data when you're not in production. This setting allows
    | you to disable Ray in all environments.
    |
    */

    'enable' => env('RAY_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Send Remote Requests
    |--------------------------------------------------------------------------
    |
    | This option can be used to enable or disable sending remote requests
    | to the Ray app. By default, Ray will send remote requests when you're
    | not in production. This setting allows you to disable remote requests
    | in all environments.
    |
    */

    'send_requests' => env('RAY_SEND_REQUESTS', true),

    /*
    |--------------------------------------------------------------------------
    | Remote Path
    |--------------------------------------------------------------------------
    |
    | This option is used to specify the remote path where Ray is running.
    | This is useful when you're running Ray on a different machine than
    | your Laravel application.
    |
    */

    'remote_path' => env('RAY_REMOTE_PATH', ''),

    /*
    |--------------------------------------------------------------------------
    | Remote Host
    |--------------------------------------------------------------------------
    |
    | This option is used to specify the remote host where Ray is running.
    | This is useful when you're running Ray on a different machine than
    | your Laravel application.
    |
    */

    'remote_host' => env('RAY_REMOTE_HOST', 'localhost'),

    /*
    |--------------------------------------------------------------------------
    | Remote Port
    |--------------------------------------------------------------------------
    |
    | This option is used to specify the remote port where Ray is running.
    | This is useful when you're running Ray on a different machine than
    | your Laravel application.
    |
    */

    'remote_port' => env('RAY_REMOTE_PORT', 23517),

    /*
    |--------------------------------------------------------------------------
    | Remote Scheme
    |--------------------------------------------------------------------------
    |
    | This option is used to specify the remote scheme where Ray is running.
    | This is useful when you're running Ray on a different machine than
    | your Laravel application.
    |
    */

    'remote_scheme' => env('RAY_REMOTE_SCHEME', 'http'),

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable Sending Anonymous Data
    |--------------------------------------------------------------------------
    |
    | This option can be used to enable or disable sending anonymous data
    | to the Ray app. This data is used to improve the Ray app.
    |
    */

    'send_anonymous_data' => env('RAY_SEND_ANONYMOUS_DATA', true),

    /*
    |--------------------------------------------------------------------------
    | Default Colors
    |--------------------------------------------------------------------------
    |
    | This option can be used to set the default colors for the Ray app.
    | The colors are used to colorize the output in the Ray app.
    |
    */

    'colors' => [
        'background' => env('RAY_COLOR_BACKGROUND', 'blue'),
        'foreground' => env('RAY_COLOR_FOREGROUND', 'black'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Size
    |--------------------------------------------------------------------------
    |
    | This option can be used to set the default size for the Ray app.
    | The size is used to set the size of the Ray app window.
    |
    */

    'size' => env('RAY_SIZE', 'normal'),

    /*
    |--------------------------------------------------------------------------
    | Default Title
    |--------------------------------------------------------------------------
    |
    | This option can be used to set the default title for the Ray app.
    | The title is used to set the title of the Ray app window.
    |
    */

    'title' => env('RAY_TITLE', 'Laravel Ray'),

    /*
    |--------------------------------------------------------------------------
    | Default Location
    |--------------------------------------------------------------------------
    |
    | This option can be used to set the default location for the Ray app.
    | The location is used to set the location of the Ray app window.
    |
    */

    'location' => env('RAY_LOCATION', 'tray'),

    /*
    |
    | This option can be used to enable or disable the cache.
    | The cache is used to cache the Ray app settings.
    |
    */

    'cache' => [
        'enabled' => env('RAY_CACHE_ENABLED', true),
        'path' => env('RAY_CACHE_PATH', storage_path('framework/cache/ray.php')),
    ],

];