
<html>

<head>
  <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
    <!-- <meta http-equiv="refresh" content="5;url=/index"> -->
</head>
<style>
  body {
    text-align: center;
    padding: 40px 0;
    background: #EBF0F5;
  }

  .storename
  {
    color: #008000;
  }

  p {
    
    font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
    font-size: 20px;
    margin: 0;
    text-align: center;
  }

  i {
    color: #9ABC66;
    font-size: 100px;
    line-height: 200px;
    margin-left: -15px;
  }

  .card {
    background: white;
    padding: 60px;
    border-radius: 4px;
    box-shadow: 0 2px 3px #C8D0D8;
    display: inline-block;
    margin: 0 auto;
  }
  .styled-table {
    border-collapse: collapse;
    margin: 50px 0;
    font-size: 0.9em;
    font-family: sans-serif;
    min-width: 800px;
    /* box-shadow: 0 0 20px rgba(0, 0, 0, 0.15); */
}
.styled-table thead tr {
    
    color: black;
    text-align: center;
}
.styled-table th,

.styled-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #dddddd;
}
.styled-table tbody tr {
    border-bottom: 1px solid #dddddd;
}
.styled-table tbody tr :nth-of-type(even) {
    color: #FF7F50;
}
</style>

  <script>
//Using setTimeout to execute a function after 5 seconds.
setTimeout(function () {                                
window.location.href="{{URL('/')}}";
}, 6000);
</script>

<body>
  <div class="card">
    <div style="border-radius:200px; height:200px; width:800px; margin:0 auto;">
      <i class="checkmark">✓</i>
    </div>
    
    <p>Your Order has been placed</p>
    <table class="styled-table">
        <thead>
                                <tr>
                                    <th colspan="2">Orders</th>
                                </tr>

                                <tr>
                                    <th>Store Name</th>
                                    <th>Order Number</th>
                                </tr>
                            </thead>
   
    <tbody>
        <tr>
            <td><p class="storename">Example Store</p></td>
            <td ><p >{{ request()->order_no }}</p></td>
        </tr>
       
      
    </tbody>
</table>
  </div>
</body>

</html>