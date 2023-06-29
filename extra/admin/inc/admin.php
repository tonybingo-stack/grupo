<?php

if (!isset($_COOKIE['access_code'])) {
	echo "Please login again. Cookie not have access code.";
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
include 'functions.php';

function query($sql='')
{
	global $conn;
	$result = $conn->query($sql);
	$data = array();
	
	if (getType($result) === "object" && $result->num_rows > 0) {
	  while($row = $result->fetch_assoc()) {
		if(isset($row)){
			$data[] = $row;
		}
 	  }
	} 
	return $data;
}





