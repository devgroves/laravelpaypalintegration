<?php
// use app\Http\Controllers\PaymentController;
Route::post('/payment/paypal/createTransaction', 'PaymentController@payWithpaypal');
Route::post('/payment/paypal/capture/{orderid}', 'PaymentController@getPaymentStatus');
Route::get('/suceess/{order_no}', function (){
    return view('suceess');
});
Route::get('/', function (){
    return view('index');
});

Route::post('/cod', 'PaymentController@cashOnDelivery');
Route::post('/cancel/{order_no}', 'PaymentController@orderCancel');
