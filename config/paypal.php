<?php 
return [ 
    'client_id' => env('PAYPAL_CLIENT_ID','AdPKNbrS4rBr87luLQRtV441lZCXTpYzUUAOj0ccwUweSjy1LwfVw03Z-_7B7FHyYOHJc3qTfphV3twm'),
    'secret' => env('PAYPAL_SECRET','ENaMTr17_419pCJFIdZaC_AxGZs31DTWGzHpmZwDzsP78Ioo3bCKSdPHHlMvLtrcDlO-f0FL_t8QkgfT'),
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