<?php 


require __DIR__ . '/vendor/autoload.php';
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;


include '../inc/db.php';
include '../extraconf.php';

$user = check_user();



$apiData = new ProductionEnvironment($clientId, $clientSecret);
$client = new PayPalHttpClient($apiData);

?>