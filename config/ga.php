<?php

use Laravel\Telescope\Http\Middleware\Authorize;
use Laravel\Telescope\Watchers;

return [

    /*
    |--------------------------------------------------------------------------
    | Telescope Domain
    |--------------------------------------------------------------------------
    |
    | This is the subdomain where Telescope will be accessible from. If the
    | setting is null, Telescope will reside under the same domain as the
    | application. Otherwise, this value will be used as the subdomain.
    |
    */

    'property' => env('GA_PROPERTY', 'G-ZM103LD445'),

    'tag_id' => env('GA_TAG_ID', 'https://www.googletagmanager.com/gtag/js?id=G-ER0YHVJ99M'),

];
