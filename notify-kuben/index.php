<?php
	require('../wp-blog-header.php' );
	
	$tomorrow = date("Y-m-d", strtotime("+1 day", time()));

	wp_mail("erik@tehler.se", "[Kubens föräldrawebb] Imorgon", $tomorrow);
	
	$users = get_users(array('meta_key' => 'booked_duty_days'));
	
	foreach ($users as $user) { 
		$data = get_userdata($user->ID);
		$meta = get_user_meta($user->ID, 'booked_duty_days', true);
		if (in_array($tomorrow, $meta)) {
			wp_mail($user->user_email, "[Kubens föräldrawebb] Du är bokad för jour imorgon", "Du är bokad för jour imorgon (".$tomorrow.").");
		}
	} 	
	
	http_response_code(200);
	
	die();
?>
