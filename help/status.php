<?php
global $wpdb, $user_ID;
$merchantAccountNumber = get_option('fasa_id');
$merchantStoreName = get_option('store_name');
$merchantSecurityWord = get_option('word_scurity');
$msg = '';

$msg .= filter_input(INPUT_POST, 'fp_amnt').'|';
$msg .= filter_input(INPUT_POST, 'fp_batchnumber').'|';
$msg .= filter_input(INPUT_POST, 'fp_currency').'|';
$msg .= filter_input(INPUT_POST, 'fp_fee_amnt').'|';
$msg .= filter_input(INPUT_POST, 'fp_fee_mode').'|';
$msg .= filter_input(INPUT_POST, 'fp_merchant_ref').'|';
$msg .= filter_input(INPUT_POST, 'fp_paidby').'|';
$msg .= filter_input(INPUT_POST, 'fp_paidto').'|';
$msg .= filter_input(INPUT_POST, 'fp_sec_field').'|';
$msg .= filter_input(INPUT_POST, 'fp_store').'|';
$msg .= filter_input(INPUT_POST, 'fp_timestamp').'|';
$msg .= filter_input(INPUT_POST, 'fp_total').'|';
$msg .= filter_input(INPUT_POST, 'fp_unix_time').'|';
$msg .= filter_input(INPUT_POST, 'order_id').'|';
$msg .= filter_input(INPUT_POST, 'track_id').'|';

$fp_hmac_temp = hash_hmac('sha256', $msg, $merchantSecurityWord);
$fp_hmac = $fp_hmac_temp.''.$msg;

$order = filter_input(INPUT_POST, 'order_id');
if( isset($_POST['fp_paidto']) && strtoupper(filter_input(INPUT_POST, 'fp_paidto')) == strtoupper($merchantAccountNumber) &&
	isset($_POST['fp_store']) && strtoupper(filter_input(INPUT_POST, 'fp_store')) == strtoupper($merchantStoreName) &&
	isset($_POST['fp_hmac']) && strtoupper(filter_input(INPUT_POST, 'fp_hmac')) == strtoupper($fp_hmac)){
		$selz = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix . 'smart_report_log WHERE id_order = "'.$order.'"' );
		$data = $wpdb->insert($wpdb->prefix . "smart_report", array('id_order'=>$selz[0]->id_order, 'id_mem'=>$selz[0]->id_mem, 'tanggal_order'=>$selz[0]->tanggal_order,  'nilai_pesanan'=>$selz[0]->nilai_pesanan, 'pay_order'=>'Fasapay', 'uang_terima'=>$selz[0]->uang_terima, 'uang_terima'=>$selz[0]->uang_terima, 'status'=>'transaksi_lunas', 'pm_detail'=>$selz[0]->pm_detail, 'pm_produk'=>$selz[0]->pm_produk, 'sortorder'=>$selz[0]->sortorder,'aff_id'=>$selz[0]->aff_id));
}
?>