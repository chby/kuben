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
	
	add_action('init', 'custom_post_type_family');
?>