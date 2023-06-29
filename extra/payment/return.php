<?php 


if (!isset($_GET['token'])) {
	header('Location: ../../');
	exit();
}


include 'main.php';

ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');



$order_record = query("SELECT * FROM `gr_order_records` WHERE order_details = '".$_GET['token']."'");



if (count($order_record)==0) {
	echo 'Order not exits';
	exit;
}

if ($order_record[0]['status']!=='0') {
	echo 'Order already processed';
	exit;
}
$willAdd = json_decode($order_record[0]['wilBeAdded']);
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

$request = new OrdersCaptureRequest($_GET['token']);
$request->prefer('return=representation');
try {
    // Call API with your client and get a response for your call
    $response = $client->execute($request);
   	if ($response->statusCode==201) {
   		if ($response->result->status=='COMPLETED') {
   			if ($willAdd->credits>0) {
   				query("UPDATE `gr_users` SET credits=credits+".$willAdd->credits." WHERE id=".$user['id']);
   			}
   			if ($willAdd->subs>0) {
   				$totalTime = (intval($willAdd->days)*86400);
   				if (intval($user['subs_end'])>time()) {
   					$totalTime = $user['subs_end']+$totalTime;
   				} else {
   					$totalTime = time()+$totalTime;
   				}
   				query("UPDATE `gr_users` SET subs=".$willAdd->credits.", subs_end=$totalTime  WHERE id=".$user['id']);
   			}
   			query("UPDATE `gr_order_records` SET status=1 WHERE order_details='".$_GET['token']."' and status = 0 ");
   			header('Location: ../../');
   			exit;
   		}
   	} 

   	echo 'Please contact system admins for order details.';
   
}catch (Exception $ex) {
   echo 'Please contact system admins for order details.';
   exit;
}



?>