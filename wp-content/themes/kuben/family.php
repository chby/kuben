<?php
	function custom_post_type_family() {
		$labels = array(
			'name'               => _x( 'Familjer', 'post type general name' ),
		    'singular_name'      => _x( 'Familj', 'post type singular name' ),
		    'add_new'            => _x( 'Lägg till', 'book' ),
		    'add_new_item'       => __( 'Lägg till familj' ),
		    'edit_item'          => __( 'Ändra familj' ),
		    'new_item'           => __( 'Ny familj' ),
		    'all_items'          => __( 'Alla familjer' ),
		    'view_item'          => __( 'Visa familj' ),
		    'search_items'       => __( 'Sök familjer' ),
		    'not_found'          => __( 'Inga familjer hittades' ),
		    'not_found_in_trash' => __( 'Inga familjer hittades i Papperskorgen' ), 
		    'parent_item_colon'  => '',
		    'menu_name'          => 'Familjer'
		);
		$args = array(
		  	'labels'        => $labels,
		    'description'   => 'Familjer och barn',
		    'public'        => true,
		    'menu_position' => 27,
		    'supports'      => array('title', 'custom-fields'),
		    'has_archive'   => false
		);
		register_post_type('family', $args); 
	}
	
	function custom_post_type_family_box_setup() {
	 	add_action('add_meta_boxes', 'custom_post_type_family_add_box');
	}
	
	function custom_post_type_family_add_box() {
		add_meta_box(
	    	'family-users-box', 
	    	'Familj',
	    	'custom_post_type_family_box', 
	    	'family',
	    	'side',
	    	'default'
	  	);
	}
	
	function custom_post_type_family_box($object, $box) { 
?>
	<p><strong><?php echo $object->post_title; ?></strong></p>
	<?php $parents = get_users(array('meta_key' => 'family_id', 'meta_value' => $object->ID)); ?>
	<?php if (sizeof($parents) > 0) { ?>
		<?php $map = function($p) { $d = get_userdata($p->ID); return $d->first_name." ".$d->last_name; }; ?>
		<?php $parents = array_map($map, $parents); ?>
		<p><strong>Föräldrar:</strong> <?php echo join($parents, ', '); ?></p>
	<?php } ?>
	<?php $children = get_post_meta($object->ID, 'Barn'); ?> 
	<?php if (sizeof($children) > 0) { ?>
		<p><strong>Barn:</strong> <?php echo join($children, ', '); ?></p>
	<?php } ?>
<?php 
	}
	
	add_action('init', 'custom_post_type_family');
	add_action('load-post.php', 'custom_post_type_family_box_setup' );
?>