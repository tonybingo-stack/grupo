<?php 
	include 'main.php';
	if(isset($_GET['token'])) {
		query("UPDATE `gr_order_records` SET status=-2 WHERE order_details='".$_GET['token']."' and status = 0 ");
	}

	header('Location: ../../');
?>