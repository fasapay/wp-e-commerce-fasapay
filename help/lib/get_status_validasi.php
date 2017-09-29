<?php
function get_hmac_hash(){
	$merchantStoreName = get_option('store_name');
	$merchantSecurityWord = get_option('word_scurity');
	$msg = '';
	$msg .= filter_input(INPUT_POST, 'fp_amnt') . '|';
	$msg .= filter_input(INPUT_POST, 'fp_batchnumber') . '|';
	$msg .= filter_input(INPUT_POST, 'fp_currency') . '|';
	$msg .= filter_input(INPUT_POST, 'fp_fee_amnt') . '|';
	$msg .= filter_input(INPUT_POST, 'fp_fee_mode') . '|';
	$msg .= filter_input(INPUT_POST, 'fp_merchant_ref') . '|';
	$msg .= filter_input(INPUT_POST, 'fp_paidby') . '|';
	$msg .= filter_input(INPUT_POST, 'fp_paidto') . '|';
	$msg .= filter_input(INPUT_POST, 'fp_sec_field') . '|';
	$msg .= filter_input(INPUT_POST, 'fp_store') . '|';
	$msg .= filter_input(INPUT_POST, 'fp_timestamp') . '|';
	$msg .= filter_input(INPUT_POST, 'fp_total') . '|';
	$msg .= filter_input(INPUT_POST, 'fp_unix_time') . '|';
	$msg .= filter_input(INPUT_POST, 'order_id') . '|';
	$msg .= filter_input(INPUT_POST, 'track_id') . '|';
	$fp_hmac_temp = hash_hmac('sha256', $msg, $merchantSecurityWord);
	$fp_hmac = $fp_hmac_temp.''.$msg;
	return $fp_hmac;
}
function get_check($session_id){
	global $wpdb;
	$check = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE sessionid = "' . $session_id . '"');
	return $check;
}
function get_merchantAccountNumber(){
	if (get_option('fasa_id') == filter_input(INPUT_POST, 'fp_paidto')) {
    	$merchantAccountNumber = get_option('fasa_id');
	} else if (get_option('fasa_co_id') == filter_input(INPUT_POST, 'fp_paidto')) {
    	$merchantAccountNumber = get_option('fasa_co_id');
	} else if (get_option('fasa_com') == filter_input(INPUT_POST, 'fp_paidto')) {
    	$merchantAccountNumber = get_option('fasa_com');
	}
	return $merchantAccountNumber;
}
function get_curency(){
	global $wpdb;
	$selz = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix . 'wpsc_currency_list WHERE id = "'.get_option('currency_type').'"' );
	$curensi = $selz[0]->code;
	return $curensi;
}
function get_delete_visitor_meta($visitor_id){
	global $wpdb;
	$selz = $wpdb->delete($wpdb->prefix . 'wpsc_visitor_meta', ['wpsc_visitor_id' => $visitor_id], ['%d']);
	return $selz;
}
function get_visitor_id($transact){
	global $wpdb;
	$aa = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE transactid = " ' . $transact . ' "');
    $wpsc_visitor = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wpsc_visitors WHERE user_id = "' . $aa[0]->user_ID . '"');
	$wpsc = $wpsc_visitor[0]->id;
	return $wpsc;
}
function update_purchase_log($transact,$track,$order){
					global $wpdb;
					$wpdb->query(
            		"UPDATE $wpdb->wpsc_purchase_logs 
					SET transactid = ' $transact ' , track_id = ' $track '
    				WHERE sessionid = $order");
}
function get_validation($fp_hmac,$check){
	if (strtoupper(filter_input(INPUT_POST, 'fp_paidto')) == strtoupper(get_merchantAccountNumber()) &&
        strtoupper(filter_input(INPUT_POST, 'fp_store')) == strtoupper(get_option('store_name')) &&
        strtoupper(filter_input(INPUT_POST, 'fp_hmac')) == strtoupper(get_hmac_hash()) &&
        strtoupper(filter_input(INPUT_POST, 'fp_currency')) == strtoupper(get_curency()) &&
        strtoupper(filter_input(INPUT_POST, 'fp_amnt')) == strtoupper($check[0]->totalprice)) {
    		return $validate = true;
	}else{
			return $validate = false;
	}
}
?>