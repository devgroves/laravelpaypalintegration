<?php 
return [ 
    'client_id' => env('PAYPAL_CLIENT_ID','xxx'),
    'secret' => env('PAYPAL_SECRET','xyz'),
    'currency_type' => env('CURRENCY_TYPE','EUR'),
    'api_url' => env('API_URL','https://api-m.sandbox.paypal.com/'),
    'settings' => array(
        'mode' => env('PAYPAL_MODE','sandbox'),
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR'
    ),
];