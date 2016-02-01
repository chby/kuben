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
	add_object_page('Jourdagar', 'Jourdagar', 'edit_book_duty_days', 'book_duty_days_page', 'book_duty_days_page');
	add_action('admin_init', 'export_duty_days');
	add_action('admin_init', 'delete_duty_days');
	add_action('admin_init', 'register_book_duty_days_settings');
}

function register_book_duty_days_settings() {
  register_setting('book-duty-days-options', 'book_duty_days_start_date');
  register_setting('book-duty-days-options', 'book_duty_days_end_date');
  register_setting('book-duty-days-options', 'book_duty_days_not_bookable_dates');
}

function book_duty_days_page() {
?>
<div class="wrap">
	<h2 style="padding-bottom: 20px;">Jourdagar</h2>
	<div class="postbox">
		<div class="inside">
			<h3 class="hndle">Inställningar</h3>
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
			        <tr valign="top">
			        	<th scope="row">Ej bokningsbara datum</th>
			        	<td><textarea name="book_duty_days_not_bookable_dates"><?php echo esc_attr(get_option('book_duty_days_not_bookable_dates')); ?></textarea></td>
			        </tr>
			    </table>
				<input type="submit" name="submit" id="submit" class="button button-primary" value="Spara ändringar" style="margin-top: 10px;">
			</form>
		</div>
	</div>
	<div class="postbox">
		<div class="inside">
			<h3 class="hndle">Ladda ner Excel</h3>
			<a href="?action=export_duty_days" class="button button-primary">Ladda ner Excel</a>
		</div>
	</div>
	<div class="postbox">
		<div class="inside">
			<h3 class="hndle">Rensa alla jourdagar</h3>
			<a href="?action=delete_duty_days" class="button button-primary">Rensa jourdagar</a>
		</div>
	</div>
	<div class="postbox">
		<div class="inside">
			<h3 class="hndle">Föräldrar utan familjekoppling</h3>
			<?php $parents = get_users(array('role' => 'parent')); ?>
			<ul>
				<?php foreach ($parents as $parent) { ?>
					<?php $family_id = get_user_meta($parent->ID, 'family_id', true); ?>
					<?php if (!$family_id || $family_id == "") { ?>
						<?php $data = get_userdata($parent->ID); ?>
						<li><a href="<?php echo get_edit_user_link($parent->ID); ?>"><?php echo $data->first_name ?> <?php echo $data->last_name ?></a></li>
					<?php } ?>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>
<?php 
}

function export_duty_days() {
	if ($_GET['action'] == 'export_duty_days') {
		require_once('library/PHPExcel.php');
	
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Föräldrakooperativet Kuben")
									 ->setLastModifiedBy("Föräldrakooperativet Kuben")
									 ->setTitle("Jourdagar")
									 ->setSubject("Jourdagar");
			
		$objPHPExcel->getActiveSheet()->setTitle('Datum');
						
		$objPHPExcel->getActiveSheet()->setCellValue('A' . 1, "Datum")
			                          ->setCellValue('B' . 1, "Bokad av");

		$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
		
		$date = get_option('book_duty_days_start_date');
   	 	$end_date = get_option('book_duty_days_end_date');
	
		$user_booked_duty_days = array();
		$users = get_users(array('meta_key' => 'booked_duty_days'));
		foreach ($users as $user) { 
			$data = get_userdata($user->ID);
			$meta = get_user_meta($user->ID, 'booked_duty_days', true);
			if (is_array($meta)) {
				asort($meta);
			} else {
				$meta = array();
			}
			$user_booked_duty_days[$user->ID] = array('booked_duty_days' => $meta, 'name' => $data->first_name." ".$data->last_name);
		}
		
		$sorted_user_booked_duty_days = $user_booked_duty_days;
		usort($sorted_user_booked_duty_days, create_function('$a, $b', 'return strnatcasecmp($a["name"], $b["name"]);'));
	
		$i = 2;
		while (strtotime($date) <= strtotime($end_date)) {
			$booked_by = '';
			foreach ($user_booked_duty_days as $data) {
				if (in_array($date, $data['booked_duty_days'])) {
					$booked_by = $data['name'];
				}
			}
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $date)
				                          ->setCellValue('B' . $i, $booked_by);
			
			if (is_weekend($date)) {
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFont()->getColor()->setRGB('dd3737');
			}
			
			$i++;
	   		$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
		}
		
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex(1); 
		$objPHPExcel->getActiveSheet()->setTitle('Föräldrar');
		
		$objPHPExcel->getActiveSheet()->setCellValue('A' . 1, "Förälder")
			                          ->setCellValue('B' . 1, "Antal bokade dagar")
									  ->setCellValue('C' . 1, "Bokade dagar");

		$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
		
		$i = 2;
		foreach ($sorted_user_booked_duty_days as $data) {
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $data['name'])
				                          ->setCellValue('B' . $i, count($data['booked_duty_days']));
			foreach ($data['booked_duty_days'] as $date) {
				$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $date);
				$i++;
			}
			$i++;
		}
		
		$families = new WP_Query(array('post_type' => 'family', 'posts_per_page' => '9999'));
		if ($families->have_posts()) {
			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex(2); 
			$objPHPExcel->getActiveSheet()->setTitle('Familjer');
		
			$objPHPExcel->getActiveSheet()->setCellValue('A' . 1, "Familj")
                						  ->setCellValue('B' . 1, "Antal barn")
				                          ->setCellValue('C' . 1, "Antal bokade dagar")
										  ->setCellValue('D' . 1, "Bokade dagar");

			$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
		
			$i = 2;
			while ($families->have_posts()) {
				$families->the_post();
				$family_users = get_users(array('meta_key' => 'family_id', 'meta_value' => get_the_ID()));
				$family_booked_duty_days = array();
				foreach($family_users as $family_user) {
					if ($user_booked_duty_days[$family_user->ID]['booked_duty_days']) {
						$family_booked_duty_days = array_merge($family_booked_duty_days, $user_booked_duty_days[$family_user->ID]['booked_duty_days']);
					}
				}
				$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, get_the_title())
				  							  ->setCellValue('B' . $i, count(get_post_meta(get_the_ID(), 'Barn')))
											  ->setCellValue('C' . $i, count($family_booked_duty_days));
				sort($family_booked_duty_days);
				foreach($family_booked_duty_days as $date) {
					$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $date);
					$i++;
				}
				$i++;
			}
		}
	
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Jourdagar_'.date("Y-m-d_Hi").'.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}		
}

function delete_duty_days() {
	if ($_GET['action'] == 'delete_duty_days') {
		$users = get_users(array('meta_key' => 'booked_duty_days'));
		foreach ($users as $user) { 
			delete_user_meta($user->ID, 'booked_duty_days');
		}
		header('Location: ?page=book_duty_days_page');
		exit;
	}
}
?>