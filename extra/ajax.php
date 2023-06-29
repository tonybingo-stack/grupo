<?php

include 'inc/db.php';
$user = check_user();




function dt() {
    $arg = func_get_args();
    $f = 'Y-m-d H:i:s';
    $nw = null;
    $datetime = new DateTime($nw);
    $la_time = new DateTimeZone('Asia/Kolkata');
    $datetime->setTimezone($la_time);
    return $datetime->format($f);
    
}



if (isset($_POST['action'])) {

	if ($_POST['action'] == 'checkAccess') {
		echo $user['role'];
	}
	if ($_POST['action'] == 'checkAgency') {
		echo $user['agency'];

		
	} else if ($_POST['action'] == 'reportUser') {
		echo 'ok';
		$_POST['sender'] = $user['id'];
		$json_string = json_encode($_POST);
		$file_handle = fopen('reportLog.json', 'a');
		fwrite($file_handle, $json_string."\r\n");
		fclose($file_handle);
		exit();
	} else if ($_POST['action'] == 'googlePayments') {
		$uId = $user['id'];
		$orderId = $_POST['orderId'];
		$accessToken = $_POST['accessToken'];
		query("INSERT INTO `googlePayments`( `user_id`, `order_id`, `accessToken`) VALUES ($uId,'$orderId','$accessToken')");
		echo "ok";
		exit();

	} else if ($_POST['action'] == 'checkMemberships') {
		echo $user['subs'];
	}
	else if ($_POST['action'] == 'getCredit') {
		echo $user['credits'];
	} else if ($_POST['action'] == 'sendGift'  && isset($_POST['target']) && isset($_POST['giftId'])) {
		$gift = query('SELECT * FROM `gr_gifts` WHERE id='.$_POST['giftId']);
		$targetUser = query("SELECT gr_users.*,(SELECT v2 FROM `gr_options` WHERE type = 'profile' and v1 = 'name'and  v3 = '".$_POST['target']."') as username FROM `gr_users` WHERE id=".$_POST['target']);

		if (count($gift) == 0) {
			echo json_encode(array('err'=>"$.i18n('gift_not_exist')"));
			exit();
		}
		if (count($targetUser) == 0) {
			echo json_encode(array('err'=>"$.i18n('invalid_user')"));
			exit();
		}
		if (intval($user['credits']) < intval($gift[0]['credits'])) {
			echo json_encode(array('err'=>"$.i18n('enough_credits')"));
			exit();
		} 

		if (intval($_POST['target'])<intval($user['id'])) {
			$gid = $_POST['target'].'-'.$user['id'];
		} else {
			$gid = $user['id'].'-'.$_POST['target'];
		}
		$t1 = dt();
		$sender = $user['id'];

		$message = "#GIFT ".$gift[0]['image']." ".$gift[0]['credits'];

		//add new message to database
		$sql = "INSERT INTO `gr_msgs` (`id`, `gid`, `uid`, `msg`, `type`, `rtxt`, `rid`, `rmid`, `rtype`, `cat`, `lnurl`, `lntitle`, `lndesc`, `lnimg`, `xtra`, `tms`) VALUES (NULL, '$gid', '$sender', '$message', 'msg', '0', '0', '0', 'msg', 'user', '', NULL, NULL, '', '0', '$t1')";

		query($sql);

		$used = $gift[0]['credits'];
		$process_msg = "You sent a gift to ".$targetUser[0]['username']."(".$targetUser[0]['name'].") with use ".$gift[0]['credits'].' credits';


		//remove from current credits
		query("UPDATE `gr_users` SET `credits`=`credits` - $used WHERE id=".$user['id']);


		if ($targetUser[0]['agency']==1) {
			$uid2 = $targetUser[0]['id'];
			$msg1 = $user['username']." sent a gift ($used credits) to you.";
			query("INSERT INTO `gr_agency_earnings`(`uid`, `coins`, `details`) VALUES ($uid2,$used,'$msg1')");
			query("UPDATE `gr_users` SET `agency_coins`=`agency_coins` + $used WHERE id=".$targetUser[0]['id']);
		}

		//add log
		query("INSERT INTO `gr_credit_used`( `uid`, `details`, `credit`, `used_time`) VALUES ($sender,'$process_msg','-$used','$t1')");


		if (isset($_POST['giftTarChannel']) && intval($_POST['giftTarChannel'])) {
			$cId = $_POST['giftTarChannel'];
			if (!empty($targetUser[0]['username']))
				$t_nick = $targetUser[0]['username'];
			else
				$t_nick = $targetUser[0]['name'];
			
			$m_nick = $user['username'];

			$gift_image = $gift[0]['image'];

			$sql = "INSERT INTO `gr_msgs` (`id`, `gid`, `uid`, `msg`, `type`, `rtxt`, `rid`, `rmid`, `rtype`, `cat`, `lnurl`, `lntitle`, `lndesc`, `lnimg`, `xtra`, `tms`) VALUES (NULL, '$cId', '0', 'addons gift $t_nick $m_nick $used $gift_image', 'msg', '0', '0', '0', 'msg', 'group', '', NULL, NULL, '', '0', '$t1')";
			query($sql);
		}

		$okMessage = $used." ".$targetUser[0]['name'];

		echo json_encode(array('ok'=>$okMessage));
		exit();

	} else if ($_POST['action'] == 'sendCredit'  && isset($_POST['target']) && isset($_POST['credits'])) {

		$sentCredit = intval($_POST['credits']);
		$targetUser = query("SELECT gr_users.*,(SELECT v2 FROM `gr_options` WHERE type = 'profile' and v1 = 'name'and  v3 = '".$_POST['target']."') as username FROM `gr_users` WHERE id=".$_POST['target']);
		if (intval($user['credits']) < intval($sentCredit)) {
			echo json_encode(array('err'=>"$.i18n('enough_credits')"));
			exit();
		} 
		if (count($targetUser) == 0) {
			echo json_encode(array('err'=>"$.i18n('invalid_user')"));
			exit();
		}


		if (intval($_POST['target'])<intval($user['id'])) {
			$gid = $_POST['target'].'-'.$user['id'];
		} else {
			$gid = $user['id'].'-'.$_POST['target'];
		}
		$t1 = dt();
		$sender = $user['id'];
		$target = $_POST['target'];
		$message = "#GIFT credit ".$sentCredit;

		if (!empty($targetUser[0]['username']))
			$t_nick = $targetUser[0]['username'];
		else
			$t_nick = $targetUser[0]['name'];


		//add new message to database
		$sql = "INSERT INTO `gr_msgs` (`id`, `gid`, `uid`, `msg`, `type`, `rtxt`, `rid`, `rmid`, `rtype`, `cat`, `lnurl`, `lntitle`, `lndesc`, `lnimg`, `xtra`, `tms`) VALUES (NULL, '$gid', '$sender', '$message', 'msg', '0', '0', '0', 'msg', 'user', '', NULL, NULL, '', '0', '$t1')";

		query($sql);

		$process_msg = "You sent credits ".$sentCredit." to ".$targetUser[0]['username'];

		$process_msg2 = "You received credits ".$sentCredit." to ".$targetUser[0]['username'];

		//remove from current credits
		query("UPDATE `gr_users` SET `credits`=`credits` - $sentCredit WHERE id=".$user['id']);

		//add received credit 
		query("UPDATE `gr_users` SET `credits`=`credits` + $sentCredit WHERE id=".$_POST['target']);

		//add log
		query("INSERT INTO `gr_credit_used`( `uid`, `details`, `credit`, `used_time`) VALUES ($sender,'$process_msg','-$sentCredit','$t1')");
		query("INSERT INTO `gr_credit_used`( `uid`, `details`, `credit`, `used_time`) VALUES ($target,'$process_msg2',$sentCredit,'$t1')");


		if (isset($_POST['giftTarChannel']) && intval($_POST['giftTarChannel'])) {
			$cId = $_POST['giftTarChannel'];
			$m_nick = $user['username'];
			$sql = "INSERT INTO `gr_msgs` (`id`, `gid`, `uid`, `msg`, `type`, `rtxt`, `rid`, `rmid`, `rtype`, `cat`, `lnurl`, `lntitle`, `lndesc`, `lnimg`, `xtra`, `tms`) VALUES (NULL, '$cId', '0', 'addons credit $t_nick $m_nick $sentCredit', 'msg', '0', '0', '0', 'msg', 'group', '', NULL, NULL, '', '0', '$t1')";
			query($sql);
		}
		$okMessage = $sentCredit." ".$targetUser[0]['name'];

		echo json_encode(array('ok'=>$okMessage));
		exit();

	} else if ($_POST['action'] == 'creditsLog') {

		$logs = query("SELECT * FROM `gr_credit_used` WHERE uid=".$user['id']);
		echo json_encode($logs);
		exit();
	} else if ($_POST['action'] == 'ordersLog') {

		$logs = query("SELECT * FROM `gr_order_records` WHERE uid=".$user['id']);
		echo json_encode($logs);
		exit();
	}

	
}



 
?>