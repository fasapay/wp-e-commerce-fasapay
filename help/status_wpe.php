<?php

$merchantAccountNumber = get_option('fasa_id');
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
$fp_hmac = $fp_hmac_temp . '' . $msg;
global $wpdb;
$session_id = filter_input(INPUT_POST, 'order_id');
$check = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE sessionid = "' . $session_id . '"');

if (strtoupper(filter_input(INPUT_POST, 'fp_paidto')) == strtoupper($merchantAccountNumber) &&
        strtoupper(filter_input(INPUT_POST, 'fp_store')) == strtoupper($merchantStoreName) &&
        strtoupper(filter_input(INPUT_POST, 'fp_hmac')) == strtoupper($fp_hmac) &&
        strtoupper(filter_input(INPUT_POST, 'fp_amnt')) == strtoupper($check[0]->totalprice)) {
    $order = filter_input(INPUT_POST, 'order_id');
    $track = filter_input(INPUT_POST, 'track_id');
    $transact = filter_input(INPUT_POST, 'fp_batchnumber');
    //put visit_id
    //update log
    $wpdb->query(
            "
    UPDATE $wpdb->wpsc_purchase_logs 
	SET transactid = ' $transact ' , track_id = ' $track '
    WHERE sessionid = $order
    ");
    //delete visitor meta
    $aa = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE transactid = " ' . $transact . ' "');
    $wpsc_visitor = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wpsc_visitors WHERE user_id = "' . $aa[0]->user_ID . '"');
    $visitor_id = $wpsc_visitor[0]->id;
    $wpdb->delete($wpdb->prefix . 'wpsc_visitor_meta', ['wpsc_visitor_id' => $visitor_id], ['%d']);
}
?>