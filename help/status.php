<?php
	require 'lib/get_status_validasi.php';
			if (get_validation_lapak() == 'true'){
				get_update_smart_report_log(filter_input(INPUT_POST, 'order_id'));
			}
	?>
