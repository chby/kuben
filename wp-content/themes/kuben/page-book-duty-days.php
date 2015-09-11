<?php
/*
Template Name: Book Duty Days
*/

global $userdata; get_currentuserinfo();

$today = date('Y-m-d', time());

$current_user_id = $user_ID;
$current_user_data = $userdata;

if (in_array(get_current_user_role(), array("administrator", "director")) && $_GET['user_id']) {
	$current_user_id = $_GET['user_id'];
	$current_user_data = get_userdata($current_user_id);
}

$current_user_name = $current_user_data->first_name." ".$current_user_data->last_name;


if (!empty($_POST['action'])) {
	$directors = get_users(array('role' => 'director')); 
	$current_user_meta = get_user_meta($current_user_id, 'booked_duty_days', true);
	
	foreach($current_user_meta as $date) {
		if (!in_array($date,$_POST['booked_duty_days']) && date("W", strtotime($today)) == date("W", strtotime($date))) {
			foreach($directors as $director) {
				wp_mail($director->user_email, "[Kubens föräldrawebb] Avbokad jourdag ".$date, $current_user_name." har avbokat sin jourdag ".$date.".");	
			}	
		}
	}
	
	foreach($_POST['booked_duty_days'] as $date) {
		if (!in_array($date,$current_user_meta) && date("W", strtotime($today)) == date("W", strtotime($date))) {
			foreach($directors as $director) {
				wp_mail($director->user_email, "[Kubens föräldrawebb] Bokad jourdag ".$date, $current_user_name." har bokat jourdagen ".$date.".");
			}	
		}
	}
			
	update_user_meta($current_user_id, 'booked_duty_days', $_POST['booked_duty_days']);
	
	die();
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
						$not_bookable_dates = explode("\n", get_option('book_duty_days_not_bookable_dates'));
						foreach ($not_bookable_dates as $i => $not_bookable_date) { $not_bookable_dates[$i] = trim($not_bookable_date); }
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

					<?php if (in_array(get_current_user_role(), array("administrator", "director"))) { ?>
						<?php $parents = get_users(array('role' => 'parent')); ?>
						<form method="get" style="margin-bottom: 20px;">
							<select name="user_id" id="book_for_parent_user_id">
								<option value="">Boka åt förälder</option>
								<?php foreach ($parents as $user) { ?>
									<?php $data = get_userdata($user->ID); ?>
									<option value="<?php echo $user->ID; ?>" <?php if ($_GET['user_id'] == $user->ID){ ?>selected="selected"<?php } ?>><?php echo $data->first_name; ?> <?php echo $data->last_name; ?></option>
								<?php } ?>
							</select>
						</form>
						
						<script type="text/javascript">
							jQuery(document).ready(function() {
								jQuery("#book_for_parent_user_id").change(function() {
									jQuery(this).parent("form").submit();
								});
							});
						</script>
					<?php } ?>

					<div class="alert alert-success" role="alert">
						Du har bokat <strong class="nr-of-duty-days"><?php echo count($user_booked_duty_days[$current_user_id]['booked_duty_days']); ?></strong> jourdag(ar)!
					</div>
					
					<form class="book-duty-days-form" action="?user_id=<?php $current_user_id; ?>" method="post" role="form">
					  	<input type="hidden" name="action" value="book_duty_table" />
						<table class="book-duty-days-table">
							<?php while (strtotime($date) <= strtotime($end_date)) { ?>
								<tr class="<?php if (is_weekend($date)) { echo "weekend"; } else if (strtotime($today) >= strtotime($date) || in_array($date, $not_bookable_dates)) { echo "not_bookable"; } else if (in_array($date, $user_booked_duty_days[$current_user_id]['booked_duty_days'])) { echo "checked"; } else if (in_array($date, $booked_duty_days)) { echo "booked"; } ?> ">
									<td class="input"><?php if (strtotime($today) < strtotime($date) && !is_weekend($date) && !in_array($date, $not_bookable_dates)) { ?><input type="checkbox" name="booked_duty_days[]" value="<?php echo $date; ?>" <?php if (in_array($date, $booked_duty_days)) { ?>checked="checked"<?php } ?> <?php if (in_array($date, $booked_duty_days) && !in_array($date, $user_booked_duty_days[$current_user_id]['booked_duty_days'])) { ?>disabled="disabled"<?php } ?> /><?php } ?></td>
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
							window.currentUserName = "<?php echo $current_user_name; ?>";
							window.currentUserIsAdmin = <?php echo (in_array(get_current_user_role(), array("administrator", "director")) ? "true" : "false"); ?>;
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
								var today = new Date();
								var clickedDate = new Date(input.val());
								if (input.is(":checked")) {
									input.parents("tr").addClass("checked");
									input.parents("tr").find("td.booked-by").text(window.currentUserName);
								} else {
									if (!window.currentUserIsAdmin && (clickedDate-today)/(1000*60*60*24) < 3) {
										input.attr("checked", "checked");
										alert("Du kan inte avboka din jourdag så nära inpå. Kontakta Kubens förskolechef!");
										return false;
									} else {
										input.parents("tr").removeClass("checked");
										input.parents("tr").find("td.booked-by").text("");
									}
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