<?php 
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

function last_actions()
{
	return query('SELECT gr_credit_used.*,gr_site_users.display_name FROM `gr_credit_used` INNER JOIN gr_site_users ON gr_site_users.user_id = gr_credit_used.uid ORDER BY `used_time` DESC LIMIT 10');
}

function last_orders()
{
	return query('SELECT gr_order_records.*,gr_site_users.display_name FROM `gr_order_records` INNER JOIN gr_site_users ON gr_site_users.user_id = gr_order_records.uid ORDER BY gr_order_records.action_time DESC LIMIT 10');
}
function all_orders()
{
	return query('SELECT gr_order_records.*,gr_site_users.display_name FROM `gr_order_records` INNER JOIN gr_site_users ON gr_site_users.user_id = gr_order_records.uid ORDER BY gr_order_records.action_time DESC');
}

function get_credits_list()
{
	return query('SELECT * FROM `gr_credits` WHERE 1');
}

function get_subs_list()
{
	return query('SELECT * FROM `gr_subs` WHERE 1');
}

function users()
{
	return query('SELECT gr_site_users.user_id,gr_site_users.display_name,gr_site_users.credits,gr_site_users.subs_end,gr_site_users.subs,gr_permissions.name as role,gr_site_users.site_role_id as real_role FROM `gr_site_users` INNER JOIN gr_permissions ON gr_permissions.id = gr_site_users.site_role_id');
}
function subs_users()
{
	return query('SELECT gr_site_users.user_id,gr_site_users.display_name,gr_site_users.credits,gr_site_users.subs_end,gr_site_users.subs,gr_site_roles.site_role_attribute as role,gr_site_users.site_role_id as real_role FROM `gr_site_users` INNER JOIN gr_site_roles ON gr_site_roles.site_role_id = gr_site_users.site_role_id WHERE gr_site_users.subs_end > UNIX_TIMESTAMP()');
}
function gifts()
{
	return query('SELECT * FROM `gr_gifts`');
}
function plans()
{
	return query('SELECT * FROM `gr_subs`');
}

function today_reports()
{
	$data=array();
	$data['reg'] = query('SELECT count(user_id) as total FROM `gr_site_users`WHERE created_on >= CURDATE() AND created_on < CURDATE() + INTERVAL 1 DAY');
	$data['total_visit'] = query('SELECT count(access_log_id) as total FROM `gr_site_users_device_logs`WHERE created_on >= CURDATE() AND created_on < CURDATE() + INTERVAL 1 DAY');
	$data['used'] = query('SELECT count(id) as total FROM `gr_credit_used`WHERE used_time >= CURDATE() AND used_time < CURDATE() + INTERVAL 1 DAY');
	$data['orders'] = query('SELECT count(id) as total FROM `gr_order_records`WHERE action_time >= CURDATE() AND action_time < CURDATE() + INTERVAL 1 DAY');
	return $data;
}
function force_end_subs($user_id)
{
	$update = "UPDATE `gr_site_users` SET subs = 0, subs_end = 0 WHERE user_id = $user_id";
	query($update);
}

function add_sub($user_id,$plan,$days)
{
	global $user;
	$username = $user['display_name'];
	$mytime = 86400*$days+time();
	$update = "UPDATE `gr_site_users` SET subs = $plan, subs_end = $mytime WHERE user_id = $user_id";
	query($update);
	$insert =("INSERT INTO `gr_order_records`(`uid`, `details`, `method`, `order_details`, `prices`, `status`) VALUES ($user_id,'$plan subs loaded by system admins','For admins','$username load subs with $days days',0,1)");
	echo $insert;
	query($insert);

}

function add_credit($user_id,$credit)
{

	global $user;
	$username = $user['display_name'];
	query("UPDATE `gr_site_users` SET credits = credits + $credit WHERE user_id = $user_id");
	query("INSERT INTO `gr_order_records`(`uid`, `details`, `method`, `order_details`, `prices`, `status`) VALUES ($user_id,'$credit loaded by system admins','For admins','$username load credit',0,1)");
}
?>