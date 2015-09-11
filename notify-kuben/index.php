<?php
	require('../wp-blog-header.php' );
	
	$tomorrow = date("Y-m-d", strtotime("+1 day", time()));

	$booked_duty_days = array();
	
	$user_booked_duty_days = array();
	$users = get_users(array('meta_key' => 'booked_duty_days'));
	
	foreach ($users as $user) { 
		$data = get_userdata($user->ID);
		$meta = get_user_meta($user->ID, 'booked_duty_days', true);
		if (in_array($tomorrow, $meta)) {
			wp_mail($user->user_email, "[Kubens föräldrawebb] Du är bokad för jour imorgon", "Du är bokad för jour imorgon (".$tomorrow.").\n\n");
		}
	} 	
	
	http_response_code(200);
	
	die();
?>
