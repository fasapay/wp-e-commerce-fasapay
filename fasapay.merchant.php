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
		$selz = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix . 'wpsc_currency_list WHERE id = "'.get_option('currency_type').'"' );
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

		$fp_acc = get_option('fasa_id');
        $fp_acc_id = get_option('fasa_co_id');
        $fp_acc_com = get_option('fasa_com');
		$fp_store = get_option('store_name');
		$fp_item = '';
		$fp_amnt = 0;
		$fp_currency = $selz[0]->code;
		$fp_comments = '';
		$fp_merchant_ref = $sessionid;
		$success = get_option('transact_url') ."?sessionid=$sessionid&fpres=success";
		$fail = get_option('transact_url') ."?sessionid=$sessionid&fpres=failed";
		$status = get_option('transact_url') ."?sessionid=$sessionid&fpres=status";	
	
		
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
		$fp_amnt = $wpsc_cart->calculate_total_price();		
	
		$time = date("Y-m-d h:i:sa");
		$paid = $fp_amnt;
		$ab = $time."".$paid;
		$track_id = hash('tiger192,3', $ab);	
	
		//BUILDING OUTPUT FORM
		$output = "";
		$sandbox = '
		<style>
.spinner {
  margin: 100px auto 0;
  width: 70px;
  text-align: center;
}

.spinner > div {
  width: 18px;
  height: 18px;
  background-color: #333;

  border-radius: 100%;
  display: inline-block;
  -webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
  animation: sk-bouncedelay 1.4s infinite ease-in-out both;
}

.spinner .bounce1 {
  -webkit-animation-delay: -0.32s;
  animation-delay: -0.32s;
}

.spinner .bounce2 {
  -webkit-animation-delay: -0.16s;
  animation-delay: -0.16s;
}

@-webkit-keyframes sk-bouncedelay {
  0%, 80%, 100% { -webkit-transform: scale(0) }
  40% { -webkit-transform: scale(1.0) }
}

@keyframes sk-bouncedelay {
  0%, 80%, 100% { 
    -webkit-transform: scale(0);
    transform: scale(0);
  } 40% { 
    -webkit-transform: scale(1.0);
    transform: scale(1.0);
  }
}
input {
  visibility: hidden;
}
.Absolute-Center {
  margin: auto;
  position: absolute;
  top: 0; left: 0; bottom: 0; right: 0;
}
</style>
		<script>
		window.setTimeout(function() {
			document.getElementById("fasapay_form").submit();
		}, 5000);
		</script>
		<center>
		<center>
		<br />
		<br />
		<img src="https://fasapay.com/images/fasapay_logo.png" alt="Fasapay - Sistem Pembayaran Online Cepat dan Aman" width="300" height="90">
		<div class="spinner">
  			<div class="bounce1"></div>
  			<div class="bounce2"></div>
  			<div class="bounce3"></div>
  		</div>
		<form id="fasapay_form" method="POST" action="https://sandbox.fasapay.com/sci/"> 		
		<input type="hidden" name="fp_acc" value="'.$fp_acc.'">
		<input type="hidden" name="fp_store" value="'.$fp_store.'">
		<input type="hidden" name="fp_item" value="'.$fp_item.'">
		<input type="hidden" name="fp_amnt" value="'.$fp_amnt.'">
    			<input type="hidden" name="fp_fee_mode" value="'.get_option( 'fee_mode' ).'">
		<input type="hidden" name="fp_currency" value="'.$fp_currency.'">
		<input type="hidden" name="fp_comments" value="">
		<input type="hidden" name="fp_merchant_ref" value="" /> 
		<input type="hidden" name="fp_success_url" value="'.$success.'" />
		<input type="hidden" name="fp_success_method" value="POST" />
		<input type="hidden" name="fp_fail_url" value="'.$fail.'" />
		<input type="hidden" name="fp_fail_method" value="POST" />
		<input type="hidden" name="fp_status_url" value="'.$status.'" />
		<input type="hidden" name="fp_status_method" value="POST" />	
		<input type="hidden" name="track_id" value="'.$track_id.'">
    			<input type="hidden" name="order_id" value="'.$sessionid.'">
		<input name="pay_with_fasapay" value="Pay with FasaPay" type="submit" display:"none">
		</form>
		</center>
		';
		$live = '
		<style>
.spinner {
  margin: 100px auto 0;
  width: 70px;
  text-align: center;
}

.spinner > div {
  width: 18px;
  height: 18px;
  background-color: #333;

  border-radius: 100%;
  display: inline-block;
  -webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
  animation: sk-bouncedelay 1.4s infinite ease-in-out both;
}

.spinner .bounce1 {
  -webkit-animation-delay: -0.32s;
  animation-delay: -0.32s;
}

.spinner .bounce2 {
  -webkit-animation-delay: -0.16s;
  animation-delay: -0.16s;
}

@-webkit-keyframes sk-bouncedelay {
  0%, 80%, 100% { -webkit-transform: scale(0) }
  40% { -webkit-transform: scale(1.0) }
}

@keyframes sk-bouncedelay {
  0%, 80%, 100% { 
    -webkit-transform: scale(0);
    transform: scale(0);
  } 40% { 
    -webkit-transform: scale(1.0);
    transform: scale(1.0);
  }
}
.btn-group .button {
    background-color: #4CAF50; /* Green */
    border: 1px solid green;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    cursor: pointer;
}
.btn-group .button:not(:last-child) {
    border-right: none; /* Prevent double borders */
}
.btn-group .button:hover {
    background-color: #3e8e41;
}
.Absolute-Center {
  margin: auto;
  position: absolute;
  top: 0; left: 0; bottom: 0; right: 0;
}
#loaders{
margin-top:30%;
display:none;
}
#hiders{
display:block;
}
</style>
<script>
function facom(){
		var x = document.getElementById("loaders");
		var y = document.getElementById("hiders");
        x.style.display = "block";
        y.style.display = "none";
		
		window.setTimeout(function() {
			document.getElementById("fasapay_form_com").submit();
		}, 5000);
		}
function faid(){
		var x = document.getElementById("loaders");
		var y = document.getElementById("hiders");
        x.style.display = "block";
        y.style.display = "none";
		
		window.setTimeout(function() {
			document.getElementById("fasapay_form_id").submit();
		}, 5000);
		}
</script>
	
		<center>
		<div id="hiders">
		<br />
		<br />
		<img src="https://fasapay.com/images/fasapay_logo.png" alt="Fasapay - Sistem Pembayaran Online Cepat dan Aman" width="300" height="90">
		<form id="fasapay_form_com" method="POST" action="https://sci.fasapay.com/"> 		
		<input type="hidden" name="fp_acc" value="'.$fp_acc_com.'">
		<input type="hidden" name="fp_store" value="'.$fp_store.'">
		<input type="hidden" name="fp_item" value="'.$fp_item.'">
		<input type="hidden" name="fp_amnt" value="'.$fp_amnt.'">
    			<input type="hidden" name="fp_fee_mode" value="'.get_option( 'fee_mode' ).'">
		<input type="hidden" name="fp_currency" value="'.$fp_currency.'">
		<input type="hidden" name="fp_comments" value="">
		<input type="hidden" name="fp_merchant_ref" value="" /> 
		<input type="hidden" name="fp_success_url" value="'.$success.'" />
		<input type="hidden" name="fp_success_method" value="POST" />
		<input type="hidden" name="fp_fail_url" value="'.$fail.'" />
		<input type="hidden" name="fp_fail_method" value="POST" />
		<input type="hidden" name="fp_status_url" value="'.$status.'" />
		<input type="hidden" name="fp_status_method" value="POST" />	
		<input type="hidden" name="track_id" value="'.$track_id.'">
    			<input type="hidden" name="order_id" value="'.$sessionid.'">	
		</form>
		<form id="fasapay_form_id" method="POST" action="https://fasapay.co.id/sci"> 		
		<input type="hidden" name="fp_acc" value="'.$fp_acc_id.'">
		<input type="hidden" name="fp_store" value="'.$fp_store.'">
		<input type="hidden" name="fp_item" value="'.$fp_item.'">
		<input type="hidden" name="fp_amnt" value="'.$fp_amnt.'">
    			<input type="hidden" name="fp_fee_mode" value="'.get_option( 'fee_mode' ).'">
		<input type="hidden" name="fp_currency" value="'.$fp_currency.'">
		<input type="hidden" name="fp_comments" value="">
		<input type="hidden" name="fp_merchant_ref" value="" /> 
		<input type="hidden" name="fp_success_url" value="'.$success.'" />
		<input type="hidden" name="fp_success_method" value="POST" />
		<input type="hidden" name="fp_fail_url" value="'.$fail.'" />
		<input type="hidden" name="fp_fail_method" value="POST" />
		<input type="hidden" name="fp_status_url" value="'.$status.'" />
		<input type="hidden" name="fp_status_method" value="POST" />	
		<input type="hidden" name="track_id" value="'.$track_id.'">
    			<input type="hidden" name="order_id" value="'.$sessionid.'">	
		</form>
		<div class="btn-group">  			
		';
		$facom = '<button class="button" onclick="facom()">FASAPAY.COM</button>';
		$faid='<button class="button" onclick="faid()">FASAPAY.CO.ID</button>';
	    $foot_plus = '</div>
		</div>
		</center>
		<center>
		<div class="spinner" id="loaders">
  			<div class="bounce1"></div>
  			<div class="bounce2"></div>
  			<div class="bounce3"></div>
  		</div>
		</center>';
			
		if(get_option('mode') == 'sandbox_mode'){
			$output = $sandbox;
		}else if(get_option('mode') == 'live_mode'){
			if($selz[0]->code == "IDR"){
				$output = $live.' '.$facom.' '.$faid.' '.$foot_plus;
			}else if($selz[0]->code == "USD"){
				$output = $live.' '.$facom.' '.$foot_plus;
			}			
		}
		echo $output;
	 	exit();		
}



function submit_fasapay(){
			
}

function form_fasapay() {
	global $wpdb, $wpsc_cart;
	$selz = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix . 'wpsc_currency_list WHERE id = "'.get_option('currency_type').'"' );
	$fasapay_sandbox = get_option('fasa_id');
	$fasapay_fa_id = get_option('fasa_co_id');
	$fasapay_fa_com = get_option('fasa_com');
	$fasapay_store = get_option('store_name');
	$fasapay_scurity = get_option('word_scurity');
	$fasapay_kurensi = $selz[0]->code;
	$success = get_page_by_title("success")->guid;
	$fail = get_page_by_title( "fail" )->guid;
	$status = get_page_by_title( "status" )->guid;
	$output = '
	<style>
	input[type=text] {
    width: 100%;
    padding: 7px 20px;
    margin: 4px 0;
    box-sizing: border-box;
	}
	</style>
	  <tr>
		  <td style="width:30%;"><b>Url Success</b><br><small>link halaman landing page pembayaran sukses</small></td>
		  <td> <input disabled style="width:80%;" type="text" id="cp_success" value="'.$success.'"></td>
	  </tr>
	  <tr>
		  <td style="width:30%;"><b>Url Fail</b><br><small>link halaman landing page pembayaran gagal</small></td>
		  <td> <input disabled style="width:80%" type="text" id="cp_fail" value="'.$fail.'"></td>
	  </tr>	
	  <tr>
		  <td style="width:30%;"><b>Url Status</b><br><small>link halaman  untuk ,melakukan proses validasi ketika pembayaran lunas</small></td>
		  <td> <input disabled style="width:80%" type="text" id="cp_status" value="'.$status.'"></td>
	  </tr> 
	  
	<tr>
		<td style="width:30%;"><b>Store Name</b></td>
		<td> <input disabled style="width:80%" type="text" id="cp_store_name" value="'.$fasapay_store.'"></td>
	  </tr>
	  <tr>
		  <td style="width:30%;"><b>Scurity Word</b></td>
		  <td> <input disabled style="width:80%" type="text" id="cp_word_scurity" value="'.$fasapay_scurity.'"></td>
	  </tr>
	<tr>
		  <td style="width:30%;"><b>Fasapay Store Id</b></td>
		  <td>
			  <input disabled style="width:60%" type="text" id="cp_fasaid" value="'.$fasapay_sandbox.'">
			  <input disabled style="width:60%" type="text" id="cp_fasacoid" value="'.$fasapay_fa_id.'">
			  <input disabled style="width:60%" type="text" id="cp_fasacom" value="'.$fasapay_fa_com.'">
		  </td>
	  </tr>
	  
		<tr>
			<td style="width:120px"><label for="fasapay_kurensi">FasaPay Kurensi</label></td>
			<td>
			  <input disabled name="fasapay_kurensi" type="text" id="fasapay_kurensi" value="'.$fasapay_kurensi.'">
			  <br />
			<small>Kurensi yang digunakan untuk transaksi (IDR,USD)</small></td>
		</tr>
		<tr>
			<td></td>
			<td>Untuk melakukan editing silahkan masuk option menu fasapay plugins</td>
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