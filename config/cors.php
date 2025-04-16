<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:3000', 
        'http://*.localhost:3000', 

        'http://reactims.hpchft.local', 
        'http://*.reactims.hpchft.local', 

        'http://reactims.hpchft.ai',
        'http://*.reactims.hpchft.ai',

        'http://laravelims.hpchft.ai/',
        'http://*.laravelims.hpchft.ai/',
    ], // Allow subdomains

    'allowed_origins_patterns' => [
        '#^http://.*\.localhost(:\d+)?$#', 
        '#^http://.*\.localhost(:\d+)?$#'], // Allow any subdomain under localhost

    //'allowed_origins_patterns' => ['#^http://.*\.laravelims\.hpchft\.local(:\d+)?$#'],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
