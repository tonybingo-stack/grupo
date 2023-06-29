<?php 
$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
if(stripos($ua,'ndroid') !== false) { // && stripos($ua,'mobile') !== false) {
   header('Location: ../android.php');
   exit();
}

if (!isset($_GET['package']) || !isset($_GET['id'])) {
    echo 'Select a correct package';
    exit();
}

$package = array();
include 'main.php';
$price = 0;
$details = '';
$willAdd = array('credits' =>0, 'subs'=>0, 'days' => 0 );
if ($_GET['package']=='credit') {
    $package = query('SELECT * FROM `gr_credits` WHERE id='.$_GET['id']);
    if (count($package) == 0) {
        echo 'Select a correct package';
        exit(); 
    } else {
        $willAdd['credits'] = $package[0]['credits'];
        $price = $package[0]['price'];
        $details = $package[0]['name'];
    }
}

if ($_GET['package']=='subs') {

    if (!isset($_GET['periot'])) {
        echo 'Select a correct package';
        exit();
    }

    $package = query('SELECT * FROM `gr_subs` WHERE id='.$_GET['id']);
    if (count($package) == 0) {
        echo 'Select a correct package';
        exit(); 
    } else {
        $willAdd['credits'] = $package[0]['free_credit'];
        $willAdd['subs'] = $package[0]['id'];
        if ($_GET['periot']==1) {
            $willAdd['days'] = 365;
            $price = $package[0]['annual_fee'];
            $details = $package[0]['name'].' with annualy';
        } else {
            $willAdd['days'] = 30;
            $price = $package[0]['monthly_fee'];
            $details = $package[0]['name'].' with monthly';
        }
    }
}

if ($details=='' || $price == 0) {
    echo 'Select a correct package';
    exit(); 
}
$uId = $user['id'];
$method = 'paypal';
$willAddString = json_encode($willAdd);

$sql = "INSERT INTO `gr_order_records`(`uid`, `details`, `wilBeAdded`, `method`,  `prices`) VALUES ($uId,'$details','$willAddString','$method',$price)";

if ($conn->query($sql) !== TRUE) {
  echo "Error: " . $sql . "<br>" . $conn->error;
  exit();
} else {
 $last_id = $conn->insert_id;
}




use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
$request = new OrdersCreateRequest();
$request->prefer('return=representation');
$request->body = [
     "intent" => "CAPTURE",
     "purchase_units" => [[
         "reference_id" => $last_id,
         "amount" => [
             "value" => $price,
             "currency_code" => "USD"
         ]
     ]],
     "application_context" => [
          "cancel_url" => "https://sabayachat.com/chat/extra/payment/cancel.php",
          "return_url" => "https://sabayachat.com/chat/extra/payment/return.php"
     ] 
 ];

try {
    // Call API with your client and get a response for your call
    $response = $client->execute($request);

    if ($response->statusCode==201) {
        $pId = $response->result->id;
        query("UPDATE `gr_order_records` SET order_details='$pId' WHERE id=".$last_id);
    }

    if (isset($response->result->links))  {
      foreach ($response->result->links as $key => $value) {
           if ($value->rel=='approve'){
            header('Location: '.$value->href); 
            exit();
           }
          
      }
    } 
    echo 'There was a problem with the paypal connection. Please try again later.';
}catch (HttpException $ex) {
    echo $ex->statusCode;
    print_r($ex->getMessage());
}

?>