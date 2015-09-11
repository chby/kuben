<?php
	require('../wp-blog-header.php' );
	
	$parents = get_users(array('role' => 'parent'));
	
	var_dump($parents);
?>
