<html>
<head>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
  
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
</head>
    <style>
        #paypal-button-container {
               
                width:70% !important;
                margin-top:5% !important;
                margin-left:10%  !important;
        }
    </style>
</head>
<body>
    <div>
        
    	<form class="w3-container w3-display-middle w3-card-4 w3-padding-16" method="POST" id="payment-form">
    	  <div class="w3-container w3-teal w3-padding-16">Paywith Paypal</div>
    	  {{ csrf_field() }}
    	  <h2 class="w3-text-blue">Payment Form</h2>
    	  <p>Demo PayPal form - Integrating paypal in laravel</p>
    	  <label class="w3-text-blue"><b>Enter Amount</b></label>
    	  <input class="w3-input w3-border" id="amount" type="text" name="amount" onchange="myFunction(this.value)">
          <div id="amount_err" style="display:none;"className="amount_err">
              <p  style="color:red;">Enter valid amount</p>
            </div>
    	 
             <div id="paypal-button-container"></div>

          <script src="https://www.paypal.com/sdk/js?client-id=xxx &currency=EUR"></script>
           <?php
                    $paypal_clientId = 'xxx';
                    $paypal_currencyType = 'EUR';
                  $d2 = new Datetime("now");
                    $order_no="order".$d2->format('U');  // Get the last order id
                    ?> 
        
          <script>
              // Render the PayPal button into #paypal-button-container

              paypal.Buttons({
            
                  // Set up the transaction
                  
                  createOrder: function(data, actions) {                   
                        let amount=document.getElementById('amount').value;
                        let dataBody ={
                                  'order_no':'{{$order_no}}',
                                  'amount': amount
                                }
                                console.log("dataBody is",dataBody);
                  
            return fetch('/payment/paypal/createTransaction', {
                                method: 'post',
                                headers: {
                                  'Accept': 'application/json',
                                  'Content-Type': 'application/json',
                                 'X-CSRF-TOKEN':'{{ csrf_token() }}'
                                },
                                body:  JSON.stringify({
                                
                                  'order_no': '{{$order_no}}',
                                  'amount': amount
                                })
                                 
                            }).then(function(res) {
                                console.log("response ", res);
                                return res.json();
                            }).then(function(paypalTxnData) {
                              console.log("paypal txn data ", paypalTxnData);
                                return paypalTxnData.id;
                            }).catch(function(err) {
                              console.error("error in create transaction ", err);
                            });
                
            },


            // Finalize the transaction
            onApprove: function(data, actions) {
                
                console.log("on approve", data);
                return fetch('/payment/paypal/capture/{{$order_no}}', {
                    method: 'post',
                    headers: {
                          'Accept': 'application/json',
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': '{{ csrf_token() }}'
                      },
                    body: JSON.stringify(data),
                }).then(function(res) {
                    return res.json();
                }).then(function(orderData) {
                  
                    // Three cases to handle:
                    //   (1) Recoverable INSTRUMENT_DECLINED -> call actions.restart()
                    //   (2) Other non-recoverable errors -> Show a failure message
                    //   (3) Successful transaction -> Show confirmation or thank you

                    // This example reads a v2/checkout/orders capture response, propagated from the server
                    // You could use a different API or structure for your 'orderData'
                    var errorDetail = Array.isArray(orderData.details) && orderData.details[0];

                    if (errorDetail && errorDetail.issue === 'INSTRUMENT_DECLINED') {
                        return actions.restart(); // Recoverable state, per:
                        // https://developer.paypal.com/docs/checkout/integration-features/funding-failure/
                    }

                    if (errorDetail) {
                        var msg = 'Sorry, your transaction could not be processed.';
                        if (errorDetail.description) msg += '\n\n' + errorDetail.description;
                        if (orderData.debug_id) msg += ' (' + orderData.debug_id + ')';
                        $.ajax({
                          type: "POST",
                          url: '{{URL("/order/cancel")}}',
                          headers: {
                              'X-CSRF-TOKEN':'{{ csrf_token() }}'
                          },
                          data:"order_no={{$order_no}}",
                          success:function(response) {
                            if(response.status == 'SUCCESS') {
                             
                              swal("Cancelled!", "Your Order cancelled!", "success");
                             
                                window.location.href="{{URL('/myorders')}}";
                              
                            }
                            else {
                              swal("Oops!", "Your Order cancellation Failed. Please contact Team!", "error");
                                window.location.href="{{URL('/myorders')}}";
                              }
                            }
                        });
                        console.log(msg);
                        return; // Show a failure message (try to avoid alerts in production environments)
                    }

                    // Successful capture! For demo purposes:
                    console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                    var transaction = orderData.purchase_units[0].payments.captures[0];
                    window.location.href="{{URL('/success')}}"+"/"+"{{$order_no}}";
                });

            },
    onCancel: function(data) {
          console.log("oncancel data", data);
            fetch('/cancel/{{$order_no}}', {
                    method: 'post',
                    headers: {
                          'Accept': 'application/json',
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': '{{ csrf_token() }}'
                      },
                    body: JSON.stringify(data),
                }).then(function(res) {
                                console.log("cancel response is ", res.json());
                               swal("Cancelled!", "Your Order cancelled!", "error");
                               window.location.href="{{URL('/')}}";
                            })
                  
          },
                                  onError: function(err) {
                                    console.log("on error data", err);
                                },
                                onInit: function(data, actions) {
                                    console.log("on init", JSON.stringify(data));
                                },

        }).render('#paypal-button-container');

       
function myFunction(val) {

const paypalbtn=document.getElementById('paypal-button-container');

  console.log("The input value has changed. The new value is: " + val);
  if(val===null || val==="")
  {
    console.log("empty");
   paypalbtn.style.display="none";
   amount_err.style.display="block";
   
  }
  else{
paypalbtn.style.display="block";
 amount_err.style.display="none";
  }
}

    </script>	</form>
</body>
</html>