<?php

if (!isset($_COOKIE['Grupousrdev']) && !isset($_COOKIE['Grupousrcode'])) {
	echo "Please login with admin account. Cookie not have";
	exit();
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grupo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (!mysqli_set_charset($conn, "utf8mb4")) {
    printf("Error loading character set utf8mb4: %s\n", mysqli_error($conn));
    exit();
} 
function check_user()
{
	$code = $_COOKIE['access_code'];

	$user_check = query("SELECT gr_site_users.* FROM `gr_login_sessions` INNER JOIN gr_site_users ON gr_site_users.user_id=gr_login_sessions.user_id   WHERE gr_login_sessions.access_code='$code'");

	if (count($user_check)==0) {
		echo "Please login with admin account. Account not have";
		exit();
	} else {
		if ($user_check[0]['site_role_id']!='2') {
			echo "Please login with admin account. Access denied";
			exit();
		} else {
			return $user_check[0];
		}
	}
}


function query($sql='')
{
	global $conn;
	$result = $conn->query($sql);
	if (!$result) {
		 printf("Error loading character set utf8mb4: %s\n", mysqli_error($conn));
	}
	$data = array();
	if ($result->num_rows > 0) {
	  while($row = $result->fetch_assoc()) {
	  	$data[] = $row;
 	  }
	} 
	return $data;
}





