<?php

function is_weekend($date) {
    return (date('N', strtotime($date)) >= 6);
}

function weekday($date) { 	
	return explode(";", "Måndag;Tisdag;Onsdag;Torsdag;Fredag;Lördag;Söndag")[date('N', strtotime($date))-1];
} 

if (is_admin()) {
	add_action('admin_menu', 'book_duty_days_create_menu');
}

function book_duty_days_create_menu() {
	add_menu_page('Boka jourdagar', 'Boka jourdagar', 'administrator', __FILE__, 'book_duty_days_page');
  	add_action('admin_init', 'register_book_duty_days_settings');
}

function register_book_duty_days_settings() {
  register_setting('book-duty-days-options', 'book_duty_days_start_date');
  register_setting('book-duty-days-options', 'book_duty_days_end_date');
}

function book_duty_days_page() { 
?>
<div class="wrap">
<h2>Boka jourdagar</h2>
	<form method="post" action="options.php">
	    <?php settings_fields('book-duty-days-options'); ?>
	    <?php do_settings_sections('book-duty-days-options'); ?>
	    <table class="form-table">
	        <tr valign="top">
	        	<th scope="row">Startdatum</th>
	        	<td><input type="text" name="book_duty_days_start_date" value="<?php echo esc_attr(get_option('book_duty_days_start_date')); ?>" /></td>
	        </tr>
	        <tr valign="top">
	        	<th scope="row">Slutdatum</th>
	        	<td><input type="text" name="book_duty_days_end_date" value="<?php echo esc_attr(get_option('book_duty_days_end_date')); ?>" /></td>
	        </tr>
	    </table>
	    <?php submit_button(); ?>
	</form>
</div>
<?php 
}
?>