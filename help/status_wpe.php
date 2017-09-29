	<?php
	require 'lib/get_status_validasi.php';
			if (get_validation(get_hmac_hash(),get_check(filter_input(INPUT_POST, 'order_id'))) == 'true'){
					update_purchase_log(filter_input(INPUT_POST, 'fp_batchnumber'),filter_input(INPUT_POST, 'track_id'),filter_input(INPUT_POST, 'order_id'));
					get_delete_visitor_meta(get_visitor_id(filter_input(INPUT_POST, 'fp_batchnumber')));
			}
	?>