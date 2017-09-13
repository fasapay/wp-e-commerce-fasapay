<?php

/**
	* FasaPay Payment Gateway for WP eCommerce
	* this file is to add FasaPay Payment Gateway
	* @version	0.3
	* @by FasaPay
*/
$nzshpcrt_gateways[$num] = array(
	'name' => 'FasaPay Payment Gateway',
	'api_version' => 0.1,
	'function' => 'fp_fasapay_merchant',
	'has_recurring_billing' => false,
	'display_name' => 'FasaPay',	
	'wp_admin_cannot_cancel' => false,
	'requirements' => array(
		 /// so that you can restrict merchant modules to PHP 5, if you use PHP 5 features
		///'php_version' => 5.0,
	),
	
	'form' => 'form_fasapay',
	'submit_function'=>'submit_fasapay',
	
	// this may be legacy, not yet decided
	'internalname' => 'fp_fasapay_merchant',
	'image'=> 'https://www.fasapay.com/images/fasapay_wp_ecom.gif',//WPSC_URL . '/images/fasapay_ecom.gif',
);

function fp_fasapay_merchant($separator, $sessionid) {	
		global $wpdb, $wpsc_cart;

    // Initialize the trans result to null
    $_SESSION['fasapay'] = "";

    $purchase_log_sql = "SELECT * FROM `".WPSC_TABLE_PURCHASE_LOGS."` WHERE `sessionid`= '".$sessionid."' LIMIT 1";
    $purchase_log = $wpdb->get_results($purchase_log_sql,ARRAY_A) ;
    $purchase_log=$purchase_log[0];

    $cart_sql = "SELECT * FROM `".WPSC_TABLE_CART_CONTENTS."` WHERE `purchaseid`='".$purchase_log['id']."'";
    $cart = $wpdb->get_results($cart_sql,ARRAY_A) ;
	
	//echo "<pre>";
//	var_dump($cart);
//	echo "</pre>";

		//VARIABLE`````````````````````````````````````````````````````````````````````````````````````````````````````````````
		$fp_url = "https://sandbox.fasapay.com/sci/";
		$fp_acc = get_option('fasapay_akun');
		$fp_store = get_option('fasapay_store');
		$fp_item = '';
		$fp_amnt = 0;
		$fp_currency = get_option('fasapay_kurensi');
		$fp_comments = '';
		$fp_merchant_ref = $sessionid;
		
		if($fp_currency == 'USD'){
			$curcode = "$ ";
		}
		
		///GET ITEMS
		foreach($cart as $item){
			$fp_item .= $item['name'].", qty:".$item['quantity']."\n";
			$fp_comments .= $item['name'].", qty:".$item['quantity']." @".$item['price'];
			if($item['pnp'] != 0)
				$fp_comments .= ' shipping : '.$item['pnp'];
								
			$fp_comments .= "\n";
		}
		////ADD SHIPPING DETAIL
		
		
		
		///CALCULATE TOTAL PRICE
		$fp_amnt = $wpsc_cart->calculate_total_price();		
			
		///SCI-IPN Setting
		$fp_success_url = get_option('transact_url') ."/?sessionid=$sessionid&fpres=success";
		$fp_success_method = 'POST';
		$fp_fail_url = get_option('transact_url') ."/?sessionid=$sessionid&fpres=failed";
		$fp_fail_method = 'POST';
		$fp_status_url = get_option('transact_url') ."/?sessionid=$sessionid&fpres=status";
		$fp_status_method = 'POST';			
				
		//BUILDING OUTPUT FORM
		$output = '
		<script>
		window.setTimeout(function() {
			document.getElementById("fasapay_form").submit();
		}, 5000);
		</script>
		<center>
		<center>
		<img src="https://www.fasapay.com/images/logo-gede.gif" alt="Fasapay - Sistem Pembayaran Online Cepat dan Aman" width="300" height="90">
		<br>
		Redirected to FasaPay
		<form id="fasapay_form" method="POST" action="'.$fp_url.'"> 		
		<input type="hidden" name="fp_acc" value="'.$fp_acc.'">
		<input type="hidden" name="fp_store" value="'.$fp_store.'">
		<input type="hidden" name="fp_item" value="'.$fp_item.'">
		<input type="hidden" name="fp_amnt" value="'.$fp_amnt.'">
		<input type="hidden" name="fp_currency" value="'.$fp_currency.'">
		<input type="hidden" name="fp_comments" value="'.$fp_comments.'">
		<input type="hidden" name="fp_merchant_ref" value="'.$fp_merchant_ref.'" /> 
		<input type="hidden" name="fp_success_url" value="'.$fp_success_url.'" />
		<input type="hidden" name="fp_success_method" value="'.$fp_success_method.'" />
		<input type="hidden" name="fp_fail_url" value="'.$fp_fail_url.'" />
		<input type="hidden" name="fp_fail_method" value="'.$fp_fail_method.'" />
		<input type="hidden" name="fp_status_url" value="'.$fp_status_url.'" />
		<input type="hidden" name="fp_status_method" value="'.$fp_status_method.'" />		
		<input name="pay_with_fasapay" value="Pay with FasaPay" type="submit">
		</form>
		</center>
		';
		
		echo $output;
	 	exit();		
}



function submit_fasapay(){
	if(isset($_POST['fasapay_akun']))
    {
    	update_option('fasapay_akun', $_POST['fasapay_akun']);
    }
	if(isset($_POST['fasapay_store']))
    {
    	update_option('fasapay_store', $_POST['fasapay_store']);
    }
	if(isset($_POST['fasapay_kurensi']))
    {
    	update_option('fasapay_kurensi', $_POST['fasapay_kurensi']);
    }	
	return true;		
}

function form_fasapay() {
	$fasapay_akun = get_option('fasapay_akun');
	$fasapay_store = get_option('fasapay_store');
	$fasapay_kurensi = (get_option('fasapay_kurensi')== "") ? "IDR" : get_option('fasapay_kurensi');
	
	$output = '
  		<tr>
			<td style="width:120px"><label for="fasapay_akun">FasaPay Akun</label></td>
			<td>
			  <input name="fasapay_akun" type="text" id="fasapay_akun" value="'.$fasapay_akun.'">
			  <br />
			<small>Nomor Akun Fasapay anda</small></td>
		</tr>
		<tr>
			<td><label for="fasapay_store">FasaPay Store</label></td>
			<td><input name="fasapay_store" type="text" id="fasapay_store" value="'.$fasapay_store.'">
			  <br />
			<small>
			Nama Merchant store, akan muncul sebagai Header di halaman transaksi.<br />
			Jika Anda sudah membuat store di FasaPay (khusus anggota FasaPay berstatus Store) maka dia dapat memanfaatkan advance mode. Buat store <a href="https://www.fasapay.com/store" target="_blank">disini</a><br />
            <br />Success Url : <br /><input type="text" value="'.get_option('transact_url')."/?sessionid=$sessionid&fpres=success".'" readonly="readonly" />
			<br />Fail Url : <br /><input type="text" value="'.get_option('transact_url')."/?sessionid=$sessionid&fpres=fail".'" readonly="readonly" />
			<br />Status Url : <br /><input type="text" value="'.get_option('transact_url')."/?sessionid=$sessionid&fpres=status".'" readonly="readonly" />			
			</small>
			
			</td>
		</tr>
		<tr>
			<td style="width:120px"><label for="fasapay_kurensi">FasaPay Kurensi</label></td>
			<td>
			  <input name="fasapay_kurensi" type="text" id="fasapay_kurensi" value="'.$fasapay_kurensi.'">
			  <br />
			<small>Kurensi yang digunakan untuk transaksi (IDR,USD)</small></td>
		</tr>
		';
	
	return $output;
}

function nzshpcrt_fasapay_results()
{
	if(isset($_POST['fp_merchant_ref']) && ($_POST['fp_merchant_ref'] !='') && ($_GET['sessionid'] == ''))
	{
		$_GET['sessionid'] = $_POST['fp_merchant_ref'];
	}
	
	
}


add_action('init', 'nzshpcrt_fasapay_results');

?>