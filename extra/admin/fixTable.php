<?php 

include 'inc/admin.php';
$user = check_user();


if (isset($_GET['fixNow'])) {

	query("DELETE FROM gr_options WHERE NOT EXISTS(SELECT NULL FROM gr_users u WHERE u.id = v2) and gr_options.type = 'gruser'");
	query("DELETE FROM gr_options WHERE NOT EXISTS(SELECT NULL FROM gr_users u WHERE u.id = v2) and gr_options.type = 'lview'");
	query("DELETE FROM gr_options WHERE NOT EXISTS(SELECT NULL FROM gr_users u WHERE u.id = v3) and gr_options.type = 'profile'");
	//query("DELETE FROM gr_profiles WHERE NOT EXISTS(SELECT NULL FROM gr_users u WHERE u.id = uid) and gr_profiles.uid!=0 and gr_profiles.type = 'profile'");

	header("Location: fixTable.php");
	die();
}

$a0=query("SELECT count(v2) as total FROM gr_options")[0]['total'];

$a1=query("SELECT count(v2) as total FROM gr_options WHERE NOT EXISTS(SELECT NULL FROM gr_users u WHERE u.id = v2) and gr_options.type = 'gruser'")[0]['total'];

$a2=query("SELECT count(v2) as total FROM gr_options WHERE NOT EXISTS(SELECT NULL FROM gr_users u WHERE u.id = v2) and gr_options.type = 'lview'")[0]['total'];

$a3=query("SELECT count(v2) as total FROM gr_options WHERE NOT EXISTS(SELECT NULL FROM gr_users u WHERE u.id = v3) and gr_options.type = 'profile'")[0]['total'];

$a4=query("SELECT count(uid) as total FROM gr_profiles WHERE NOT EXISTS(SELECT NULL FROM gr_users u WHERE u.id = uid) and gr_profiles.uid!=0 and gr_profiles.type = 'profile'")[0]['total'];



echo 'All this records in gr_options table.</br>';
echo 'This table have total '.$a0.' row.</br>';


echo $a1.' user is not have exist user record in channel user table. (gr_options)</br>';
echo $a2.' user is not have exist user record in channel view table. (gr_options)</br>';
echo $a3.' user is not have exist user record in user profile table. (gr_options)</br>';
//echo $a4.' user is not have exist user record in user profile table. (gr_profiles)</br>';

$total = (intval($a1)+intval($a2)+intval($a3));

echo $total. ' row are useless</br>';


$percent = $total/$a0;
$percent_friendly = number_format( $percent * 100, 2 ) . '%';

echo  $percent_friendly.' datas useless</br>';


echo '<a href="fixTable.php?fixNow=true">Fix this records</a></br><a href="index.php">Return dashboard</a>';




 ?>