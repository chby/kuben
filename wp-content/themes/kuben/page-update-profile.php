<?php
/*
Template Name: Update Profile
*/

load_textdomain( 'default', WP_LANG_DIR . '/admin-' . get_locale() . '.mo' );

$wpdb->hide_errors(); 
nocache_headers();
 
global $userdata; get_currentuserinfo();
 
if (!empty($_POST['action'])) {
 
	require_once(ABSPATH . 'wp-admin/includes/user.php');
	require_once(ABSPATH . WPINC . '/registration.php');
 
	check_admin_referer('update-profile_' . $user_ID);
 
	$errors = edit_user($user_ID);
		
	if (!is_wp_error($errors)) {
		update_user_meta($user_ID, 'mobile', $_POST['mobile']);
		do_action('personal_options_update', $user_ID);
		$d_url = $_POST['dashboard_url'];
		wp_redirect(get_option("siteurl").'?page_id='.$post->ID.'&updated=true');
	}
}
?>

<?php get_header();  ?>
 
<?php get_currentuserinfo(); ?>
			
			<div id="content" class="clearfix row">
			
				<div id="main" class="col col-lg-12 clearfix" role="main">

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
						
						<header>
							
							<div class="page-header"><h1><?php the_title(); ?></h1></div>
						
						</header> <!-- end article header -->
					
						<section class="post_content">
							<?php the_content(); ?>
						</section> <!-- end article section -->
				
					</article> <!-- end article -->
					
					
					<?php endwhile; ?>	
					
					<form name="profile" action="" method="post" enctype="multipart/form-data"  role="form" class="form-horizontal">
					  	<?php wp_nonce_field('update-profile_' . $user_ID) ?>
						<input type="hidden" name="from" value="profile" />
					  	<input type="hidden" name="action" value="update" />
					  	<input type="hidden" name="checkuser_id" value="<?php echo $user_ID ?>" />
					  	<input type="hidden" name="dashboard_url" value="<?php echo get_option("dashboard_url"); ?>" />
					  	<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_ID; ?>" />
					  	<input type="hidden" name="nickname" id="nickname" value="<?php echo $userdata->nickname ?>" />


						<?php if (is_wp_error($errors)) { ?>
							<?php foreach ($errors->get_error_messages() as $message) { ?>
							 	<div class="alert alert-danger alert-dismissible" data-dismiss="alert" role="alert">
									<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									<?php echo __($message); ?>
								</div>
							<?php } ?>
						<?php } else if (isset($_GET['updated'])) { ?>
							<?php $d_url = $_GET['d']; ?>
							<div class="alert alert-success alert-dismissible" data-dismiss="alert" role="alert">
								<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								Din profil har uppdaterats!
							</div>
						<?php } ?>
						
						<?php $family_id = get_user_meta($user_ID, 'family_id', true); ?>
						<?php if ($family_id) { ?>
							<input type="hidden" name="family_id" id="family_id" value="<?php echo $family_id; ?>" />
							<?php $family = get_post($family_id); ?>
							<?php $children = get_post_meta($family_id, 'Barn'); ?>
							<div class="form-group">					    	
								<label class="col-sm-2 control-label" for="email">Familj</label>							
								<div class="col-sm-6">
									<input type="text" name="family_title" class="form-control" id="family_title" value="<?php echo $family->post_title; ?>" readonly="readonly" />
								</div>
							</div>
							<div class="form-group">					    	
								<label class="col-sm-2 control-label" for="email">Barn</label>							
								<div class="col-sm-6">
									<input type="text" name="children" class="form-control" id="children" value="<?php echo join($children, ", "); ?>" readonly="readonly" />
								</div>
							</div>
						<?php } ?>
						
						<?php $parent_role = get_user_meta($user_ID, 'parent_role', true); ?>
						<?php if ($parent_role) { ?>
							<div class="form-group">					    	
								<label class="col-sm-2 control-label" for="email">Föräldraroll</label>							
								<div class="col-sm-6">
									<input type="text" name="parent_role" class="form-control" id="parent_role" value="<?php echo $parent_role; ?>" readonly="readonly" />
								</div>
							</div>
						<?php } ?>
						
					    <div class="form-group">
					    	<label class="col-sm-2 control-label" for="first_name">Förnamn</label>
							<div class="col-sm-6">
								<input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo $userdata->first_name ?>" />
							</div>
						</div>
						
					    <div class="form-group">
					    	<label class="col-sm-2 control-label" for="last_name">Efternamn</label>
							<div class="col-sm-6">
								<input type="text" name="last_name" class="form-control"  id="last_name" value="<?php echo $userdata->last_name ?>" />						
							</div>
						</div>
						
						<div class="form-group">					    	
							<label class="col-sm-2 control-label" for="email">E-post</label>							
							<div class="col-sm-6">
								<input type="text" name="email" class="form-control" id="email" value="<?php echo $userdata->user_email ?>" />
							</div>
						</div>
						
						
						<div class="form-group">					    	
							<label class="col-sm-2 control-label" for="email">Mobiltelefon</label>							
							<div class="col-sm-6">
								<input type="text" name="mobile" class="form-control" id="mobile" value="<?php echo get_user_meta($user_ID, 'mobile', true); ?>" />
							</div>
						</div>
						
											
						<div class="form-group">					    	
							<label class="col-sm-2 control-label" for="pass1">Byt lösenord</label>							
							<div class="col-sm-6">
								<input type="password" name="pass1" class="form-control" id="pass1" value="" />
							</div>
						</div>

						<div class="form-group">					    	
							<label class="col-sm-2 control-label" for="pass2">Bekräfta lösenord</label>							
							<div class="col-sm-6">						
								<input type="password" name="pass2" class="form-control" id="pass2" value="" />
							</div>
						</div>						
						
						<div class="form-group">
						    <div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Spara" class="btn btn-primary" />
							</div>
						</div>						
						
					  	<input type="hidden" name="action" value="update" />
					</form>
					
					<?php else : ?>
					
					<article id="post-not-found">
					    <header>
					    	<h1><?php _e("Not Found", "wpbootstrap"); ?></h1>
					    </header>
					    <section class="post_content">
					    	<p><?php _e("Sorry, but the requested resource was not found on this site.", "wpbootstrap"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>
					
					<?php endif; ?>
			
				</div> <!-- end #main -->
    
				<?php //get_sidebar(); // sidebar 1 ?>
    
			</div> <!-- end #content -->

<?php get_footer(); ?>