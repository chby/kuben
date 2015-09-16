<?php
	require('../wp-blog-header.php' );
	
	$tomorrow = date("Y-m-d", strtotime("+1 day", time()));
		
	$users = get_users(array('meta_key' => 'booked_duty_days'));
	
	foreach ($users as $user) { 
		$data = get_userdata($user->ID);
		$meta = get_user_meta($user->ID, 'booked_duty_days', true);
		if (in_array($tomorrow, $meta)) {
			$text = "Du är bokad för jour imorgon på Kuben (".$tomorrow.").\n\nDu kommer att bli kontaktad innan kl 8.00 imorgon bitti ifall du behövs på Kuben.";
			$mobile = get_user_meta($user->ID, 'mobile', true);
			if ($mobile) { 
				send_sms($mobile, $text);
			}
			wp_mail($user->user_email, "[Kubens föräldrawebb] Du är bokad för jour imorgon", $text);
		}
	} 	
	
	http_response_code(200);
	
	die();
?>
