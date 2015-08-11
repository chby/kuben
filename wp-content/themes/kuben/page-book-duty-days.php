<?php
/*
Template Name: Book Duty Days
*/

global $userdata; get_currentuserinfo();

if (!empty($_POST['action'])) {
	update_user_meta($user_ID, 'booked_duty_days', $_POST['booked_duty_days']);
}

?>


<?php get_header(); ?>
			
			<div id="content" class="clearfix row">
			
				<div id="main" class="col-sm-8 clearfix" role="main">

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
						
						<header>
							
							<div class="page-header"><h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1></div>
						
						</header> <!-- end article header -->
					
						<section class="post_content clearfix" itemprop="articleBody">
							<?php the_content(); ?>					
						</section> <!-- end article section -->
						
						<footer>
			
							<?php the_tags('<p class="tags"><span class="tags-title">' . __("Tags","wpbootstrap") . ':</span> ', ', ', '</p>'); ?>
							
						</footer> <!-- end article footer -->
					
					</article> <!-- end article -->
					

 				   	<?php 
						$date = get_option('book_duty_days_start_date');
 				   	 	$end_date = get_option('book_duty_days_end_date');
						$booked_duty_days = array();
						$user_booked_duty_days = array();
						$users = get_users(array('meta_key' => 'booked_duty_days'));
						foreach ($users as $user) { 
							$data = get_userdata($user->ID);
							$meta = get_user_meta($user->ID, 'booked_duty_days', true);
							$user_booked_duty_days[$user->ID] = array('booked_duty_days' => (is_array($meta) ? $meta : array()), 'name' => $data->first_name." ".$data->last_name);
							$booked_duty_days = array_merge($booked_duty_days, $user_booked_duty_days[$user->ID]['booked_duty_days']);
						} 	
											
					?>

					<div class="alert alert-success" role="alert">
						Du har bokat <strong class="nr-of-duty-days"><?php echo count($user_booked_duty_days[$user_ID]['booked_duty_days']); ?></strong> jourdag(ar)!
					</div>
					
					<form class="book-duty-days-form" action="" method="post" role="form">
					  	<input type="hidden" name="action" value="book_duty_table" />
						<table class="book-duty-days-table">
							<?php while (strtotime($date) <= strtotime($end_date)) { ?>
								<tr class="<?php if (is_weekend($date)) { echo "weekend"; } else if (in_array($date, $user_booked_duty_days[$user_ID]['booked_duty_days'])) { echo "checked"; } else if (in_array($date, $booked_duty_days)) { echo "booked"; } ?> ">
									<td class="input"><?php if (!is_weekend($date)) { ?><input type="checkbox" name="booked_duty_days[]" value="<?php echo $date; ?>" <?php if (in_array($date, $booked_duty_days)) { ?>checked="checked"<?php } ?> <?php if (in_array($date, $booked_duty_days) && !in_array($date, $user_booked_duty_days[$user_ID]['booked_duty_days'])) { ?>disabled="disabled"<?php } ?> /><?php } ?></td>
									<td class="date"><?php echo $date; ?></td>
		 					   		<td class="weekday"><?php echo weekday($date); ?></td>
									<td class="booked-by">
										<?php
											 if (in_array($date, $booked_duty_days)) {
												foreach ($user_booked_duty_days as $data) {
													if (in_array($date, $data['booked_duty_days'])) {
														echo $data['name'];
													}
												}
											}
										?>
									</td>
		 					   		<?php $date = date("Y-m-d", strtotime("+1 day", strtotime($date))); ?>
								</tr>
		 				   	<?php } ?>
						</table>
					</form>
					
					<script type="text/javascript">
						jQuery(document).ready(function() {
							window.currentUserName = "<?php echo $userdata->first_name." ".$userdata->last_name ?>";
							jQuery(".book-duty-days-table td").click(function(e) {
								var tr = jQuery(this).parent("tr");
								if (!jQuery(e.target).is("input") && !tr.hasClass("weekend") && !tr.hasClass("booked")) {
									var input = tr.find("input");
									if (input.is(":checked")) {
										input.removeAttr("checked").trigger("change");								
									} else {
										input.attr("checked","checked").trigger("change");
									}
								}
							});
							jQuery(".book-duty-days-form input").change(function() {
								var input = jQuery(this);
								if (input.is(":checked")) {
									input.parents("tr").addClass("checked");
									input.parents("tr").find("td.booked-by").text(window.currentUserName);
								} else {
									input.parents("tr").removeClass("checked");
									input.parents("tr").find("td.booked-by").text("");
								}
								jQuery.post("", jQuery(".book-duty-days-form").serialize());
								jQuery(".alert strong.nr-of-duty-days").text(jQuery("tr.checked").length);
							});
						});
					</script>
					
					<?php //comments_template('',true); ?>
					
					<?php endwhile; ?>		
					
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
    
				<?php get_sidebar(); // sidebar 1 ?>
    
			</div> <!-- end #content -->

<?php get_footer(); ?>