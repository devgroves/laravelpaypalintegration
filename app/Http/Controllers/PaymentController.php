<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use App\Order;
use App\OrderItem;
use App\Http\Controllers\CommonController;
use App\Jobs\StoreOrderPlacedEmailSender;
use App\Jobs\StoreOrderCancelledEmailSender;
use App\Http\Controllers\ShopController;



use Redirect;
use Session;
use URL;
use Log;
use DB;

class PaymentController extends Controller
{
    // private $_token_response;
    // private $_paypalApiUrl;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   
 public function __construct()
    {
  /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');   
        // Creating an environment
        $clientId = $paypal_conf['client_id'];
        $clientSecret = $paypal_conf['secret'];
        $this->_paypalApiUrl = $paypal_conf['api_url'];
        $params=['name'=>$clientId, 'surname'=>$clientSecret];
        $ch = curl_init($this->_paypalApiUrl."v1/oauth2/token");
        curl_setopt($ch, CURLOPT_POST, 1);
        $headers = array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic '. base64_encode($clientId.":".$clientSecret) 
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, rawurldecode(http_build_query(array(
            'grant_type' => 'client_credentials'
          ))));
          $this->_token_response = json_decode(curl_exec($ch));
          $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          Log::info('token generation status: '.$status.'response'.gettype($this->_token_response));      
    }

    
    public function payWithpaypal(Request $request)
    {
        $paypal_conf = \Config::get('paypal');
        $paypalApiUrl = $paypal_conf['api_url'];
        $input = $request->all();
        $order_no = $input['order_no'];
        $amount = $input['amount'];
        $ch = curl_init($this->_paypalApiUrl."v2/checkout/orders");
        curl_setopt($ch, CURLOPT_POST, 1);
        $headers = array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer '. $this->_token_response->access_token
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{
            "intent": "CAPTURE",
            "purchase_units": [
              {
                "amount": {
                  "currency_code": "'.$paypal_conf['currency_type'].'",
                  "value": "'.$amount.'"
                }
              }
            ]
          }');
          $_order_response = json_decode(curl_exec($ch), true);
          $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          Log::info('order generation response: '.$status.'response'.gettype($_order_response));
        return response()->json($_order_response);
    }

    public function getPaymentStatus(Request $request)
    {
     
         $input = $request->all();
          $payerId = $request->payerID; 
           $paypalOrderId = $request->orderID; 
            $facilitorAccessToken =  $request->facilitatorAccessToken;
       log::info('facilitorAccessToken is'.$facilitorAccessToken);
        $ch = curl_init($this->_paypalApiUrl."v2/checkout/orders/".$paypalOrderId."/capture");
        curl_setopt($ch, CURLOPT_POST, 1);
        
        $headers = array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer '. $this->_token_response->access_token,
            'PayPal-Request-Id:'.$facilitorAccessToken
        );
        log::info('order_no'.$paypalOrderId.'facilitortoken'.$facilitorAccessToken);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $_capture_response = json_decode(curl_exec($ch), true, 512);
          $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          Log::info($_capture_response);
          
           Log::info($paypalOrderId);
        Log::info('order capture data : '.$status.'for'.$facilitorAccessToken.'response'.gettype($_capture_response['purchase_units'][0]['payments']['captures']));
        //fetch the order
       // window.location.href="{{URL('/checkout/init/order/response/message/')}}"+"/"+"{{$order_no}}";
               return response()->json($_capture_response);
    }

public function orderCancel(Request $request)
{
Log::info($request->all());
return json_encode(array('statusMsg' => "SUCCESS"), JSON_FORCE_OBJECT);
 
}
}