<?php
	require('../wp-blog-header.php' );
	
	$tomorrow = date("Y-m-d", strtotime("+1 day", time()));

	wp_mail("erik.tehler@kubennacka.se", "[Kubens föräldrawebb] Imorgon", $tomorrow);
		
 	//file_get_contents('https://e116d2bd-5e34-4618-927d-0114ffcd1577:CDVKVoR42tjORBslieC-_g@api.blower.io/messages', false, stream_context_create(array('http' => array('header'  => "Content-type: application/x-www-form-urlencoded\r\n", 'method'  => 'POST', 'content' => http_build_query(array('to' => '+46702464142', 'message' => "Du är bokad för jour imorgon på Kuben (".$tomorrow.").")),),)));
		
	$users = get_users(array('meta_key' => 'booked_duty_days'));
	
	foreach ($users as $user) { 
		$data = get_userdata($user->ID);
		$meta = get_user_meta($user->ID, 'booked_duty_days', true);
		if (in_array($tomorrow, $meta)) {
			wp_mail($user->user_email, "[Kubens föräldrawebb] Du är bokad för jour imorgon", "Du är bokad för jour imorgon på Kuben (".$tomorrow.").");
		}
	} 	
	
	http_response_code(200);
	
	die();
?>
