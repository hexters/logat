<?php

return [

    /*
    |--------------------------------------------------------------------------
    | logat.default
    |--------------------------------------------------------------------------
    |
    | This is the default locale that will be used to auto-fill the values
    | for untranslated keys. Typically, this should be set to the main
    | language of your application (e.g., 'id' for Indonesian).
    |
    */

    'default' => 'en',

    /*
    |--------------------------------------------------------------------------
    | logat.locales
    |--------------------------------------------------------------------------
    |
    | Define the list of supported locales. Language files will be generated
    | for each of these locales inside the /lang directory in JSON format.
    |
    */

    'locales' => ['en', 'ms', 'id', 'ja'],

    /*
    |--------------------------------------------------------------------------
    | logat.sources
    |--------------------------------------------------------------------------
    |
    | These are the source directories where your application will search
    | for translation function calls like __('...'). All files inside
    | these directories will be scanned and keys will be collected.
    |
    */

    'sources' => [
        'resources/views',
        'app',
    ],

    /*
    |--------------------------------------------------------------------------
    | logat.functions
    |--------------------------------------------------------------------------
    |
    | Define the translation functions that should be scanned
    | when collecting language keys from your source files. Each entry is
    | matched as a translation call pattern, such as __('...') or @lang('...').
    |
    */

    'functions' => ['__', 'trans', '@lang'],
];
