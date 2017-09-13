<?php if (!defined('IS_IN_SCRIPT')) { die();  exit; } ?>
<?php
//var_dump($_SESSION['cart']);
require_once TEMPLATEPATH."/lib/formvalidator.php";
$obj1 = LapakInstan_FrameworkCart::get_alamat_user(get_current_user_id(),1);
$obj2 = LapakInstan_FrameworkCart::get_alamat_user(get_current_user_id(),2);
$ggdoku = get_smart('dokumyshortcrt');
$curent_us = wp_get_current_user();
if(isset($_POST['submit']))
{// The form is submitted

    //Setup Validations
    $validator = new FormValidator();
	if(!$_POST['shippingaddress']){
    $validator->addValidation("pm_email","email","Pastikan Email Yang di Masukan Valid");
    $validator->addValidation("pm_nama","req","Silahkan Isi Nama Lengkap Anda");
    $validator->addValidation("pm_nohp","req","Silahkan Isi Nomor HP Anda");
	$validator->addValidation("pm_alamat","req","Silahkan Isi Alamat Lengkap Anda");
	$validator->addValidation("prov","dontselect","Silahkan Pilih Provinsi Anda");
	$validator->addValidation("kota","dontselect","Silahkan Pilih Kota Anda");
	$validator->addValidation("pm_kecamatan","dontselect","Silahkan Pilih Kecamatan Anda");
	$validator->addValidation("pm_kode_pos","req","Silahkan Isi Kode Pos Anda");
	}
	if(get_smart('tj_ongkiropt') == 'yes' || $_POST['ongkirpkg'] != 'Gratis Ongkir') $validator->addValidation("ongkir","req","Silahkan Pilih Paket Ongkir nya");
	$validator->addValidation("termsandcondition","req","Anda belum menyetujui Syarat & Ketentuan");
    //Now, validate the form
    if($validator->ValidateForm())
    {
	//Validate the form key
		//Do the rest of your validation here
			global $wpdb, $user_ID;
			$table_name = $wpdb->prefix . "smart_report";
			$id_or = substr(number_format(time() * rand(),0,'',''),0,9);
			$sql1 = "SELECT id_order from ".$table_name." WHERE id_order = ".$id_or;
			$vid3 = $wpdb->get_results($sql1);
			if($vid3[0]->id_order){ 
			$id_order = $vid3[0]->id_order; }else{ $id_order = substr(number_format(time() * rand(),0,'',''),0,9); }
			date_default_timezone_set("Asia/Jakarta");
			$tanggal_order = date("d-m-Y H:i:s");
			$nilai_pesanan = LapakInstan_FrameworkCart::get_order_total();
			$pay_order = $_POST['pay_order'];
			if($pay_order == 'COD'){
				$ongkirpkg = 'COD';
				$ongkir = 0;
			}else{
				$ongkirpkg = $_POST['ongkirpkg'];
				$ongkir = $_POST['ongkir'];
			}
			$uang_terima = '0';
			$status = '';
			
			$max=count($_SESSION['cart']);
				for($i=0;$i<$max;$i++){
					$pdid = $_SESSION['cart'][$i]['productid'];
					$pdtitle = get_the_title($pdid);
					$qty = $_SESSION['cart'][$i]['qty'];
					if($_SESSION['cart'][$i]['valueopsi']){
					$hargaa = $_SESSION['cart'][$i]['valueopsi']*$qty;
					$hargaa = number_format($hargaa);
					$hargaa = 'Rp '.str_replace(',','.',$hargaa);	
					}else{
					$hargaa = LapakInstan_Function::jmlah($pdid,$qty);	
					}
					$opsi = $_SESSION['cart'][$i]['valueopsi'];
					$nmopsi = LapakInstan_Function::smart_meta($pdid, 'my_nama_opsis');
					if($nmopsi) $harus = $nmopsi['multi'];
					$opsi2=$_SESSION['cart'][$i]['opsi'];
					if($opsi){ $hargak = LapakInstan_Function::formatnom($opsi); }else if(LapakInstan_Function::proch($pdid)){ $hargak = LapakInstan_Function::proch($pdid); }else{ $hargak = LapakInstan_Function::prich($pdid,$qty); }
					$prod_id = LapakInstan_Function::smart_meta($pdid, 'my_meta_kode_produk');
					$stok = LapakInstan_Function::smart_meta($pdid, 'my_meta_stock2');
					$stok = $stok -$qty;
					if($opsi){ $opsi = $opsi; }else{$opsi = '';}
					if($opsi2){ $opsi2 = $opsi2; }else{$opsi2 = '';}
					$opsi3 = $_SESSION['cart'][$i]['opsinew'];
					$opsi4 = $_SESSION['cart'][$i]['opsinew2'];
					//update_post_meta($pdid,'my_meta_stock2',$stok);
					if($opsi3){ 
					$opupdate = opsiupdate($pdid,$opsi3,$qty);
					update_post_meta( $pdid, 'my_nama_opsis_new', wp_kses( $opupdate, $allowed ) );
					}
					update_post_meta($pdid,'my_meta_stock2',$stok);
					if(get_smart('tj_affiliasi') == 'yes'){
					$kodecat = get_aff();
					if(is_array($kodecat)){
						$komisi = $kodecat[get_pdcatid($pdid)];
					}else{
						$komisi = 'null';
					}
					}else{
						$komisi = 'null';
					}
					
			$hori = array('namaproduk' => $pdtitle, 'prod_id' => $prod_id, 'id_pd' => $pdid, 'jumlah' => $qty, 'opsinew' => $opsi3, 'opsinew2' => $opsi4, 'opsi' => $opsi2, 'opsivalue' => $opsi, 'hargaawal' => $hargak, 'hargaakhir' => $hargaa, 'aff_kom' => $komisi);
				$horia[] = $hori;
				$komkom[] = $pdid;
				//echo array_search(get_pdcatid($pdid),get_aff());
				//$kodecat = get_aff();
				//echo $kodecat[get_pdcatid($pdid)];
				//var_dump($kodecat[10]);
				//echo get_pdcatid($pdid);
				//var_dump(get_pdcatid($pdid));
				//echo get_aff(get_pdcatid(238));
				}
				/*
				echo '<pre>';
				var_dump($horia);
				echo '</pre>';
				die();
				*/
				//echo '<pre>';die(var_dump($komisi_aff));echo '</pre>';
			if(!$_POST['shippingaddress']){
			$formVarse = array();
			foreach ($_POST as $key=>$value){
		    if ($value != ''){
  		    $formVarse[$key] = $value;
 			  }
			}
			if(get_smart('tj_showunik') == 'yes'){
					if($pay_order == 'Transfer Bank'){
					if(substr($_POST['totalorder'],-3) == 000){ $angk = 3; }else if(substr($_POST['totalorder'],-2) == 00){ 
					$angk = 2; }else{ $angk = 1; }
					$unik = substr(number_format(time() * rand(),0,'',''),0,$angk);
					$nilai_pesananz = round( $_POST['totalorder'], -$angk ) + $unik ;
					//$orderttl = $nilai_pesanans+($unik);
					}else{
					$nilai_pesananz = $_POST['totalorder'];	
					}
				}else{
					$nilai_pesananz = $_POST['totalorder'];
				}
			//$pm_detail = json_encode($formVarse);
			if(get_smart('tj_showunik') == 'yes'){
				$pm_detail = replace_key('totalorder', $nilai_pesananz, $formVarse);
			}
			
			}else{
			if(get_smart('tj_showunik') == 'yes'){
					if($pay_order == 'Transfer Bank'){
					if(substr($_POST['totalorder'],-3) == 000){ $angk = 3; }else if(substr($_POST['totalorder'],-2) == 00){ 
					$angk = 2; }else{ $angk = 1; }
					$unik = substr(number_format(time() * rand(),0,'',''),0,$angk);
					$nilai_pesananz = round( $_POST['totalorder'], -$angk ) + $unik ;
					//$orderttl = $nilai_pesanans+($unik);
					}else{
					$nilai_pesananz = $_POST['totalorder'];	
					}
				}else{
					$nilai_pesananz = $_POST['totalorder'];
				}
			//$pm_detail = json_encode($formVarse);
			if(get_smart('tj_showunik') == 'yes'){
				$pm_detail = json_encode(replace_key('totalorder', $nilai_pesananz, $formVarse));
			}	
			$datp = LapakInstan_FrameworkCart::get_alamat_user(get_current_user_id(),$_POST['shippingaddress']);
			$formVarse = array("pm_email"=>$curent_us->user_email,"pm_nama"=>$datp->pm_nama,"pm_nohp"=>$datp->pm_nohp,"pm_pinbb"=>$datp->pm_pinbb,"pm_alamat"=>$datp->pm_alamat,"prov"=>$datp->prov,"kota"=>$datp->kota,"pm_kecamatan"=>$datp->pm_kecamatan,"pm_kode_pos"=>$datp->pm_kode_pos,"pm_note"=>$_POST['pm_note'],"pay_order"=>$pay_order,"ongkir"=>$ongkir,"ongkirpkg"=>$ongkirpkg,"totalorder"=>$nilai_pesananz);	
			}
			$pm_detail = json_encode($formVarse);
			if(get_smart('tj_showunik') == 'yes'){
				$pm_detail = json_encode(replace_key('totalorder', $nilai_pesananz, $formVarse));
			}
			$pm_produk = json_encode($horia);
			$sortorder = '';
			if($id_aff = $_COOKIE['smarttoko_ref_id']){ $aff_id = $id_aff; }else{ $aff_id = 0; }
			
			if($_POST['daftary'] == "0"){
				require_once(ABSPATH . WPINC . '/registration.php');
				$email_p = $_POST['pm_email'];
				$username_p = $_POST['pm_username'];
				$password_p = $_POST['pm_password'];
				$nama_p = $_POST['pm_nama'];
				$hp_p = $_POST['pm_nohp'];
				$pinbb_p = $_POST['pm_pinbb'];
				$alamat_p = $_POST['pm_alamat'];
				$provinsi_p = $_POST['prov'];
				$kota_p = $_POST['kota'];
				$kodepos_p = $_POST['pm_kode_pos'];
				$kecamatan_p = $_POST['pm_kecamatan'];
				$daftar = wp_create_user( $username_p, $password_p, $email_p );
				wp_new_user_notification($daftar);
				add_user_meta( $daftar, '_alamat_1', $pm_detail);
			}
			$current_user = wp_get_current_user();
			if($current_user->ID){
				$daftar = $current_user->ID;
				add_user_meta( $daftar, '_alamat_1', $pm_detail);
			}else if(!$_POST['shippingaddress']){
				$daftar = get_current_user_id();
				$user_adlast = get_user_meta( $daftar, '_alamat_1', $pm_detail ); 
				if($user_adlast->pm_nama){
					add_user_meta( $daftar, 'alamat_2', $pm_detail);
				}else{
					add_user_meta( $daftar, 'alamat_1', $pm_detail);
				}
			}else{
				$daftar = '0';
			}
			/*
			if($pay_order == 'PayPal' && $ggdoku['tj_active_paypal'] == 'yes'){
				$keypaypal = $ggdoku['tj_paypal_email'];
				$transactionNo = 'Order,'.$id_order;
				//$currency = "IDR";
				//$sharedkey = get_smart('tj_doku_sharedkey');
				//$transactionDate = str_replace('-','/',$tanggal_order); // PHP Date Format: d/m/Y H:i:s
				$totalAmount = LapakInstan_Function::strip_to_numbers_only($nilai_pesanan);
				$keterangan = 'Pembayaran untuk order di '.home_url();
				$miscFee = LapakInstan_Function::strip_to_numbers_only($_POST['ongkir']);	
				$ttaal = $totalAmount+$miscFee;
				
				$table_name = $wpdb->prefix . "smart_report_log";
				$results = $wpdb->insert($table_name, array('id_order'=>$id_order, 'id_mem'=>$daftar, 'tanggal_order'=>$tanggal_order,  'nilai_pesanan'=>$nilai_pesanan, 'pay_order'=>$pay_order, 'uang_terima'=>$totalAmount, 'status'=>$status, 'pm_detail'=>$pm_detail, 'pm_produk'=>$pm_produk, 'sortorder'=>'', 'aff_id'=>$aff_id));
				paypalorder($keypaypal,$ttaal,$transactionNo,$keterangan);	
				$_SESSION['nid_order'] = $id_order;
				//unset($_SESSION['cart']);
				//unset($_SESSION['max_cart']);
				//unset($_SESSION['id_order']);
				$pser = '01';
				//var_dump($request);
				echo '</div>';
			}
			*/
			if($pay_order == 'iPaymu' && $ggdoku['tj_active_ipaymu'] == 'yes'){
				$keyipaymu = $ggdoku['tj_ipaymu_apikey'];
				$transactionNo = 'Order,'.$id_order;
				//$currency = "IDR";
				//$sharedkey = get_smart('tj_doku_sharedkey');
				//$transactionDate = str_replace('-','/',$tanggal_order); // PHP Date Format: d/m/Y H:i:s
				$totalAmount = LapakInstan_Function::strip_to_numbers_only($nilai_pesanan);
				$keterangan = 'Pembayaran untuk order di '.home_url();
				$miscFee = LapakInstan_Function::strip_to_numbers_only($_POST['ongkir']);	
				$ttaal = $totalAmount+$miscFee;
				
				$table_name = $wpdb->prefix . "smart_report_log";
				$results = $wpdb->insert($table_name, array('id_order'=>$id_order, 'id_mem'=>$daftar, 'tanggal_order'=>$tanggal_order,  'nilai_pesanan'=>$nilai_pesanan, 'pay_order'=>$pay_order, 'uang_terima'=>$totalAmount, 'status'=>$status, 'pm_detail'=>$pm_detail, 'pm_produk'=>$pm_produk, 'sortorder'=>'', 'aff_id'=>$aff_id));
				ipaymuorder($keyipaymu,$ttaal,$transactionNo,$keterangan);	
				$_SESSION['nid_order'] = $id_order;
				//unset($_SESSION['cart']);
				//unset($_SESSION['max_cart']);
				//unset($_SESSION['id_order']);
				$pser = '01';
				//var_dump($request);
				echo '</div>';
			}
			if($pay_order == 'Fasapay' || $pay_order == 'Fasapaycoid' || $pay_order == 'Fasapaycom'){
				$totalAmount = LapakInstan_Function::strip_to_numbers_only($nilai_pesanan);
				$miscFee = LapakInstan_Function::strip_to_numbers_only($_POST['ongkir']);	
				$transactionNo = $id_order;
				$sharedkey = get_option( 'shopingcard' );
				$ttaal = $totalAmount+$miscFee;
				$words = sha1($ttaal.$sharedkey.$transactionNo);
				$url_fasa = '';
				if($pay_order == 'Fasapay'){
					$url_fasa = 'http://sandbox.fasapay.com/sci/';
				}else if($pay_order == 'Fasapaycoid'){
					$url_fasa = 'http://sandbox.fasapay.com/sci/';
				}else if($pay_order == 'Fasapaycom'){
					$url_fasa = 'https://sci.fasapay.com/';
				}
				
				$table_name = $wpdb->prefix . "smart_report_log";
				$results = $wpdb->insert($table_name, array('id_order'=>$id_order, 'id_mem'=>$daftar, 'tanggal_order'=>$tanggal_order,  'nilai_pesanan'=>$nilai_pesanan, 'pay_order'=>$pay_order, 'uang_terima'=>$totalAmount, 'status'=>$status, 'pm_detail'=>$pm_detail, 'pm_produk'=>$pm_produk, 'sortorder'=>$words, 'aff_id'=>$aff_id));
				if($words){
				echo '<div class="sup-bar" style="background: #DBFFD0;border-color: #2D9014;text-align: center;color: #333;"><h3><img src="'.get_bloginfo('template_url').'/images/ajax-loader.gif" /> Fasapay Shoping cart interface redirect !</h3><p>This page will be redirect to Fasapay shoping cart interface page in 3 seconds.</p></div>
				<div style="display:none;">';
				echo '<form id="form1" id="form_auto_posts" name="form_auto_posts" method="post" action="'.$url_fasa.'">
				<input type="hidden" name="fp_acc" value="'.get_option( 'fasa_id' ).'">
    			<input type="hidden" name="fp_store" value="ECOMERCE">
    			<input type="hidden" name="fp_item" value="Order ID #">
    			<input type="hidden" name="fp_amnt" value="'.($ttaal).'">
    			<input type="hidden" name="fp_currency" value="IDR">
    			<input type="hidden" name="fp_comments" value="Pembayaran menggunakan store variable">
    			<input type="hidden" name="fp_merchant_ref" value="'.$storeid.'" />
				<input type="hidden" name="fp_success_url" value="'.get_page_by_title( 'success' )->guid.'" />
				<input type="hidden" name="fp_success_method" value="POST" />
				<input type="hidden" name="fp_fail_url" value="'.get_page_by_title( 'fail' )->guid.'" />
				<input type="hidden" name="fp_fail_method" value="POST" />
				<input type="hidden" name="fp_status_url" value="'.get_page_by_title( 'status' )->guid.'" />
				<input type="hidden" name="fp_status_method" value="POST" />
		
    			<input type="hidden" name="track_id" value="trak123456">
    			<input type="hidden" name="order_id" value="'.$id_order.'">
  				<input name="" type="submit" onclick="do_submite();" value="Check Out" />
				</form>
				<script language="javascript" type="text/javascript">
    					//<![CDATA[
    					var redirectTimeout = 3;
    					setTimeout("do_submite()", redirectTimeout * 1000);
    					function do_submite() {
       						 document.form_auto_posts.submit();
    					}
   						 //]]>
						</script>';	
			}
			}
	//punya doku mirip dengan fasapay yaitu menggunakan action post
		
			else if($pay_order == 'DOKU Myshortcart' && $ggdoku['tj_active_doku'] == 'yes'){
				$url = 'https://apps.myshortcart.com/payment/request-payment/';
				$storeid = $ggdoku['tj_doku_storeid'];
				$transactionNo = $id_order;
				$currency = "IDR";
				$sharedkey = $ggdoku['tj_doku_sharedkey'];
				$transactionDate = str_replace('-','/',$tanggal_order); // PHP Date Format: d/m/Y H:i:s
				$totalAmount = LapakInstan_Function::strip_to_numbers_only($nilai_pesanan).'.00';
				$payType = '01';
				$miscFee = LapakInstan_Function::strip_to_numbers_only($_POST['ongkir']).'.00';	
				$ttaal = $totalAmount+$miscFee;
				$words = sha1($ttaal.$sharedkey.$transactionNo);
				
				$table_name = $wpdb->prefix . "smart_report_log";
				$results = $wpdb->insert($table_name, array('id_order'=>$id_order, 'id_mem'=>$daftar, 'tanggal_order'=>$tanggal_order,  'nilai_pesanan'=>$nilai_pesanan, 'pay_order'=>$pay_order, 'uang_terima'=>$totalAmount, 'status'=>$status, 'pm_detail'=>$pm_detail, 'pm_produk'=>$pm_produk, 'sortorder'=>$words, 'aff_id'=>$aff_id));
				
				if($words){
				echo '<div class="sup-bar" style="background: #DBFFD0;border-color: #2D9014;text-align: center;color: #333;"><h3><img src="'.get_bloginfo('template_url').'/images/ajax-loader.gif" /> DOKU Myshortcart redirect page !</h3><p>This page will be redirect to DOKU Myshortcart page in 3 seconds.</p></div>
<div style="display:none;">';
				echo '<form id="form_auto_post" name="form_auto_post" action="'.$url.'" method="post">
<input type=hidden name="BASKET" value="Order ID #'.$transactionNo.','.$totalAmount.',1,'.$totalAmount.';Ongkos Kirim,'.$miscFee.',1,'.$miscFee.'"> 
<input type=hidden name="STOREID" value="'.$storeid.'">
<input type=hidden name="TRANSIDMERCHANT" value="'.$transactionNo.'">
<input type=hidden name="AMOUNT" value="'.($totalAmount+$miscFee).'">
<input type=hidden name="URL" value="'.get_bloginfo('url').'">
<input type=hidden name="WORDS" value="'.$words.'">
<input type=hidden name="CNAME" value="'.$_POST['pm_nama'].'">
<input type=hidden name="CEMAIL" value="'.$_POST['pm_email'].'">
        <div id="checkoutSteps">
            <button class="redirect_button" onclick="do_submit();" title="Confirm redirect" type="button">
                <span>
                    <span>Confirm redirect</span>
                </span>
            </button>
        </div>	
    </form>
<script language="javascript" type="text/javascript">
    //<![CDATA[
    var redirectTimeout = 3;
    setTimeout("do_submit()", redirectTimeout * 1000);

    function do_submit() {
        document.form_auto_post.submit();
    }
    //]]>
</script>';		
				$_SESSION['nid_order'] = $transactionNo;
				//unset($_SESSION['cart']);
				//unset($_SESSION['max_cart']);
				//unset($_SESSION['id_order']);
				}else{
					echo '<div class="sup-bar" style="background: #DBFFD0;border-color: #2D9014;text-align: center;color: #333;"><h3><img src="'.get_bloginfo('template_url').'/images/ajax-loader.gif" /> Error !</h3></div>';
				}
				$pser = '01';
				//var_dump($request);
				echo '</div>';
			}else if($pay_order == 'PayPal' && $ggdoku['tj_active_paypal'] == 'yes'){
				$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
				$storeid = $ggdoku['tj_paypal_email'];
				$transactionNo = $id_order;
				$currency = "IDR";
				//$sharedkey = $ggdoku['tj_doku_sharedkey'];
				//$transactionDate = str_replace('-','/',$tanggal_order); // PHP Date Format: d/m/Y H:i:s
				$totalAmount = LapakInstan_Function::strip_to_numbers_only($nilai_pesanan);
				$payType = '01';
				$miscFee = LapakInstan_Function::strip_to_numbers_only($_POST['ongkir']);	
				$ttaal = ($totalAmount+$miscFee);
				$dlar = convt_idr();
				$alop = $ttaal/$dlar;
				$desimal = round($alop,2);
				//$words = sha1($ttaal.$sharedkey.$transactionNo);
				//echo $ttaal;
				//echo 'dd'.$alop;
				//die(round($alop,1));
				$table_name = $wpdb->prefix . "smart_report_log";
				$results = $wpdb->insert($table_name, array('id_order'=>$id_order, 'id_mem'=>$daftar, 'tanggal_order'=>$tanggal_order,  'nilai_pesanan'=>$nilai_pesanan, 'pay_order'=>$pay_order, 'uang_terima'=>$totalAmount, 'status'=>$status, 'pm_detail'=>$pm_detail, 'pm_produk'=>$pm_produk, 'sortorder'=>$desimal, 'aff_id'=>$aff_id));
				
				
				echo '<div class="sup-bar" style="background: #DBFFD0;border-color: #2D9014;text-align: center;color: #333;"><h3><img src="'.get_bloginfo('template_url').'/images/ajax-loader.gif" /> PayPal redirect page !</h3><p>This page will be redirect to Paypal page in 3 seconds.</p></div>
<div style="display:none;">';
				echo '<form id="form_auto_post" name="form_auto_post" action="'.$url.'" method="post">
<input type=hidden name="business" value="'.$storeid.'">
<input type=hidden name="item_number" value="'.$transactionNo.'">
<input type=hidden name="amount" value="'.$desimal.'">
<input type=hidden name="item_name" value="Order ID #'.$transactionNo.'">
<input type=hidden name="cmd" value="_xclick">
<input type=hidden name="currency_code" value="USD">
<input type=hidden name="cancel_return" value="'.home_url().'/shop/checkout">
<input type=hidden name="return" value="'.home_url().'/gateway/checkout/finish">
        <div id="checkoutSteps">
            <button class="redirect_button" onclick="do_submit();" title="Confirm redirect" type="button">
                <span>
                    <span>Confirm redirect</span>
                </span>
            </button>
        </div>	
    </form>
<script language="javascript" type="text/javascript">
    //<![CDATA[
    var redirectTimeout = 3;
    setTimeout("do_submit()", redirectTimeout * 1000);

    function do_submit() {
        document.form_auto_post.submit();
    }
    //]]>
</script>';		
				$_SESSION['nid_order'] = $transactionNo;
				//unset($_SESSION['cart']);
				//unset($_SESSION['max_cart']);
				//unset($_SESSION['id_order']);
				
				$pser = '01';
				//var_dump($request);
				echo '</div>';
			}else if($pay_order == 'Faspay'){
			
			echo 'nano';
			}else{
				if($pay_order == 'COD'){
				$pm_detail = replace_key('totalorder', LapakInstan_Function::strip_to_numbers_only($nilai_pesanan), json_decode($pm_detail));
				$pm_detail = replace_key('ongkir', '0', $pm_detail);
				$pm_detail = replace_key('ongkirpkg', 'COD', $pm_detail);
				$pm_detail = json_encode($pm_detail);
				//die(var_dump($pm_detail));
				}
				//die(var_dump($pm_detail));
			$results = $wpdb->insert($table_name, array('id_order'=>$id_order, 'id_mem'=>$daftar, 'tanggal_order'=>$tanggal_order,  'nilai_pesanan'=>$nilai_pesananz, 'pay_order'=>$pay_order, 'uang_terima'=>$uang_terima, 'status'=>$status, 'uang_terima'=>$uang_terima, 'status'=>$status, 'pm_detail'=>$pm_detail, 'pm_produk'=>$pm_produk, 'sortorder'=>$sortorder,'aff_id'=>$aff_id));
			
			if ($results){
				unset($_SESSION['cart']);
				unset($_SESSION['max_cart']);
				$_SESSION['odid'] = $id_order;
				wp_redirect(get_bloginfo('url').'/shop/checkout/finish');
		
		}
			}
	}

    else
    {
        echo "<div class='notify'><b>Ada Kesalahan dalam validasi:</b>";

        $error_hash = $validator->GetErrors();
        foreach($error_hash as $inpname => $inp_err)
        {
            echo "<p><b>* $inp_err</b></p>\n";
        } 
		echo "</div>";       
    }//else
}
?>
<?php if($pser !== '01'){ ?><div class="box-checkout box-shad">

<div class="box-inside">
<form name="chckout" method="post" enctype="multipart/form-data">
<h2><span class="checkout-section-no"><i class="icon-exclamation-sign"></i></span> Detail Informasi</h2>
</div>
<div class="ptn">
<fieldset id="checkout-register" class="ui-fieldset">
<?php if(is_user_logged_in()) {?><div class="opsidaf">
<div class="bxadresnew"><?php include TEMPLATEPATH."/lib/cart/form-address.php"; ?>
</div>

<div class="bxbutnreg">
<?php if($obj1->pm_nama){ ?>
<input class="inputbox" onclick="toggle('#checkout-address', this)" type="checkbox" value="1" name="inputadres" id="inputadres" />
<label for="inputadres" class="required">Kirim ke Alamat lainnya</label>
<?php }else{ ?>
<a href="<?php bloginfo('url');?>/myaccount/home" class="btn btn-info ds"><i class="icon-user"></i> Customer Area</a>
<?php } ?>
</div>
<div class="bxbutnreg">
<a href="<?php bloginfo('url');?>" class="btn btn-info ds"><i class="icon-shopping-cart"></i> Lanjutkan Belanja</a></div>
<div class="clear"></div></div><?php } ?>
<style>.bxbutndropship {padding: 5px;border: 1px solid #ddd;color: #999;}div#dropshipper {border-bottom: 2px solid #ccc;padding-bottom: 10px;font-weight: bold;color: #7F7F7F;}</style>
<script>function toggle(className, obj) {
    var $input = $(obj);
    if ($input.prop('checked')){
	$(className).fadeIn("slow");
	if(className != '#checkout-address') return;
	$("#checkout-address").fadeIn("slow");
	$("#ongkir").html("Pilih Alamat");
	$("input[name=shippingaddress]").prop("checked", !1);
	$("#checkout-address :input").removeAttr("disabled");
	$("input[name=shippingaddress]").attr("disabled", "disabled");
	}else{ $(className).fadeOut("slow");
	if(className != '#checkout-address') return;
	$("#checkout-address").fadeOut("slow");
	$("input[name=shippingaddress]").prop("checked", !1);
	$("#checkout-address :input").attr("disabled", "disabled");
	$("input[name=shippingaddress]").removeAttr("disabled");
	}
}</script>
<?php if(get_smart('tj_display_dropship') == 'yes'){ ?>
<div class="bxbutndropship">
<input class="inputbox" type="checkbox" onclick="toggle('#dropshipper', this)" value="0" name="dropship" id="dropship">
<label for="dropship" class="required">Kirim sebagai dropshipper</label>
</div>
<div id="dropshipper" style="display:none;">
<div class="ui-formRow">
<label for="pm_nama_dropship" class="required">Nama Dropshipper</label>
<div class="collection" id="pm_nama_dropship">
<input class="ui-inputText" name="pm_nama_dropship" value="<?php echo isset($_POST["pm_nama_dropship"]) ? $_POST["pm_nama_dropship"] : ''; ?>" id="pm_nama_dropship" type="text"></div>
</div>

<div class="ui-formRow">
<label for="pm_tlp_dropship" class="required">Nomor Telepon</label>
<div class="collection" id="pm_tlp_dropship">
<input autocomplete="off" class="ui-inputText" name="pm_tlp_dropship" value="<?php echo isset($_POST["pm_tlp_dropship"]) ? $_POST["pm_tlp_dropship"] : ''; ?>" id="pm_tlp_dropship" type="text"></div>
</div>
</div>
<?php } ?>
<div id="toggle" style="display:none;">
<div class="ui-formRow">
<label for="pm_username" class="required">Username <span class="required">*</span></label>
<div class="collection" id="pm_username">
<input class="ui-inputText" name="pm_username" id="pm_username" type="text"></div>
</div>

<div class="ui-formRow">
<label for="pm_password" class="required">Password <span class="required">*</span></label>
<div class="collection" id="pm_password">
<input autocomplete="off" class="ui-inputText" name="pm_password" id="pm_password" type="password"></div>
</div>
</div>

</div>

</fieldset>
<fieldset id="checkout-address" class="ui-fieldset" <?php if($obj1->pm_nama){ echo 'style="display:none;"'; } ?>>
<div class="ui-formRow">
<label for="pm_email" class="required">Email <span class="required">*</span></label>
<div class="collection" id="email">
<?php  
if($curent_us->user_email){ ?>
<input class="ui-inputText" name="pm_email" id="pm_email" type="text" value="<?php echo $curent_us->user_email; ?>" readonly="readonly" required="required">
<?php }else{ ?>
<input class="ui-inputText" name="pm_email" id="pm_email" type="text" required="required">
<?php } ?>
</div>
<div class="ui-formRow" id="checkout-name">
    <div>
        <label for="pm_nama" class="required">Nama Lengkap <span class="required">*</span></label>
        <div class="collection" id="first-name">
    <input class="ui-inputText" name="pm_nama" id="pm_nama" value="<?php echo isset($_POST["pm_nama"]) ? $_POST["pm_nama"] : ''; ?>" type="text" maxlength="50">
    </div>
    </div>
</div>

<div class="ui-formRow">
        <label for="pm_nohp" class="required">Nomor Handphone <span class="required">*</span></label>
        <div class="collection" id="phone">
            <input class="ui-inputText" id="pm_nohp" name="pm_nohp" value="<?php echo isset($_POST["pm_nohp"]) ? $_POST["pm_nohp"] : ''; ?>" type="text">
            </div>
</div>

<div class="ui-formRow">
        <label for="pm_pinbb">Pin BB (Jika ada)</label>
        <div class="collection" id="pinbb">
            <input class="ui-inputText" id="pm_pinbb" value="<?php echo isset($_POST["pm_pinbb"]) ? $_POST["pm_pinbb"] : ''; ?>" name="pm_pinbb" type="text">
            </div>
</div>

<div class="ui-formRow">
    <label for="pm_alamat" class="required">Alamat <span class="required">*</span></label>
    <div class="collection" id="address-1">
        <input class="ui-inputText" name="pm_alamat" value="<?php echo isset($_POST["pm_alamat"]) ? $_POST["pm_alamat"] : ''; ?>" id="pm_alamat" type="text">
        </div>
</div>
<div class="ui-formRow">
<div>
<label for="prov" class="required">Provinsi <span class="required">*</span></label>
<div class="collection" id="fk_customer_address_region">
<select name="prov" id="prov" class="pulwidth" required>
<option value="" selected="selected">Pilih</option>
<option value="1">Bali</option><option value="2">Bangka Belitung</option><option value="3">Banten</option><option value="4">Bengkulu</option><option value="5">DI Yogyakarta</option><option value="6">DKI Jakarta</option><option value="7">Gorontalo</option><option value="8">Jambi</option><option value="9">Jawa Barat</option><option value="10">Jawa Tengah</option><option value="11">Jawa Timur</option><option value="12">Kalimantan Barat</option><option value="13">Kalimantan Selatan</option><option value="14">Kalimantan Tengah</option><option value="15">Kalimantan Timur</option><option value="16">Kalimantan Utara</option><option value="17">Kepulauan Riau</option><option value="18">Lampung</option><option value="19">Maluku</option><option value="20">Maluku Utara</option><option value="21">Nanggroe Aceh Darussalam (NAD)</option><option value="22">Nusa Tenggara Barat (NTB)</option><option value="23">Nusa Tenggara Timur (NTT)</option><option value="24">Papua</option><option value="25">Papua Barat</option><option value="26">Riau</option><option value="27">Sulawesi Barat</option><option value="28">Sulawesi Selatan</option><option value="29">Sulawesi Tengah</option><option value="30">Sulawesi Tenggara</option><option value="31">Sulawesi Utara</option><option value="32">Sumatera Barat</option><option value="33">Sumatera Selatan</option><option value="34">Sumatera Utara</option>
</select>
</div>
</div>

</div>
<div class="ui-formRow">
<div class="">
    <label for="kota" class="required">Kota <span class="required">*</span></label>
    <div class="collection" id="city">
    <div class="waitting" id="loadingmessage" style='display:none'></div>
        <select name="kota" id="dom_kota" class="pulwidth" disabled="disabled" required>
					<option value="#">Pilih kota</option>
				</select>
        </div>
</div>
<div class="ui-formRow">
<div class="">
    <label for="pm_kecamatan" class="required">Kecamatan <span class="required">*</span></label>
    <div class="collection" id="city">
    <div class="waitting" id="loadingmessage2" style='display:none'></div>
        <select name="pm_kecamatan" id="pm_kecamatan" disabled="disabled" class="pulwidth" required>
					<option value="#">Pilih Kecamatan</option>
				</select>
        </div>
 
</div>
<div class="ui-formRow">
<label for="pm_kode_pos" class="required">Kodepos <span class="required">*</span></label>
<div class="collection" id="postcode">
            <input class="ui-inputText" value="<?php echo isset($_POST["pm_kode_pos"]) ? $_POST["pm_kode_pos"] : ''; ?>" id="pm_kode_pos" name="pm_kode_pos" type="text">
            </div>
</div>
</div>

</div>
               <p class="requiredInfo mvm">* Wajib Diisi</p>
                                </div>
                            </div>

<div class="box-checkout box-shad late"><h2><span class="checkout-section-no"><i class="icon-shopping-cart"></i></span> Info Pesanan</h2>
<div class="sumcart">
<fieldset class="ui-fieldset">
<div id="checkoutGrandTotal">
<table cellpadding="5px" cellspacing="1px" class="ui-grid" id="checkoutCart" bgcolor="#f4f4f4">
    <?php
			if(is_array($_SESSION['cart'])){
            	echo '<thead class="ui-bggrey">
       				 <tr>
            		 <th class="pas" width="35%">Produk</th>
           			 <th class="pas rght">Harga</th>
       				 </tr>
   					 </thead>
    				 <tbody class="cartItems">';
				$max=count($_SESSION['cart']);
				for($i=0;$i<$max;$i++){
					$pid=$_SESSION['cart'][$i]['productid'];
					$q=$_SESSION['cart'][$i]['qty'];
					$opsi=$_SESSION['cart'][$i]['valueopsi'];
                    $nmopsi = LapakInstan_Function::smart_meta($pid, 'my_nama_opsis');
					if($nmopsi)$harus = $nmopsi['multi'];
					if($harus){ $harus = $harus; }else{ $harus = '-'; }
                    $opsi2=$_SESSION['cart'][$i]['opsi'];
					if($opsi2){ $opsi2 = $opsi2; }else{ $opsi2 = '='; }
                    if(strpos($opsi2,$harus) !== false){ $hargak = LapakInstan_Function::formatnom($opsi); }else{ if(LapakInstan_Function::proch($pid)){ $hargak = LapakInstan_Function::proch($pid); }else{ $hargak = LapakInstan_Function::prich($pid,$q); }}
					$opsi3=$_SESSION['cart'][$i]['opsinew'];
					$opsi4=$_SESSION['cart'][$i]['opsinew2'];
					$pname=get_the_title($pid);
					if($q==0) continue;
					$sing_image_1 = LapakInstan_Function::smart_meta($pid, 'smart_pd_image_lite_a'); 
					$max_cart = $i+1;
					if($metrix = LapakInstan_Function::berat_metrix($_SESSION['cart'][$i]['productid'])){ 
$has += number_format($metrix, 2, '.', '')*$_SESSION['cart'][$i]['qty'];
}else{
	$beratt = $_SESSION['cart'][$i]['ship'];
	if(strpos($beratt, ".") !== true){ $has1 = $beratt; }else{ $has1 = ($beratt*1000);  }
	$has += $has1;
}
					$_SESSION['max_cart'] = $max_cart;
					
			?>
<tr class="ui-borderBottom">
            <td class="article pas2 vMid">
            
                 <div class="cart-txt">
                    <div><?php if(LapakInstan_Function::smart_meta($pid,'my_meta_status_preorder') == 'yes'){ ?><span class="label label-info">Preorder</span><?php } ?> <b><?php echo $pname; ?></b></div>
                    <?php if($opsi){ echo '<div class="opsipdcrt">'.$opsi2.'</div>'; }
						  if($opsi3){echo '<div class="opsipdcrt">'.$opsi3.'</div>'; }
						  if($opsi4){echo '<div class="opsipdcrt">'.$opsi4.'</div>'; }
				    ?><div class="clear"></div>
                    <div>

				</div>
                </div>
                
                </td>
            <td valign="top" class="txtblod rght"><?php echo $q; ?> x <?php echo $hargak; //if($opsi){ $skak = $opsi*$q; echo formatnom($skak); } else{ echo jmlah($pid,$q); } ?></td>
        </tr>
        <?php }} ?>
    </tbody>
    <tfoot class="cartSummary visible-desktop">
        <tr>
            <td>Subtotal</td>
            <td class="rght"><?php echo LapakInstan_FrameworkCart::get_order_total(); ?></td>
        </tr>
        <tr class="beratproduk">
            <td class="ui-bordertop">Berat Total</td>
            <td class="rght txtblod"><?php echo ($has*1000);?> gram</td>
        </tr>
<tr class="shipping">
                <td>Biaya Pengiriman</td>
                <td class="rght" style="font-size:11px;"><div class="waitting ongk" id="loadingmessage2" style="display: none;"></div><?php if(get_smart('tj_showongkir') == 'yes'){ ?><div id="ongkirz">Tanya CS</div><?php }else{ ?><div id="ongkir">Tentukan Alamat</div><?php } ?></td>
            </tr>
             <tr class="grandtotal">
            <td class="ui-bordertop rght">Total</td>
            <td class="rght txtblod" id="total"><?php echo LapakInstan_FrameworkCart::get_order_total(); ?></td>
        </tr>
    </tfoot>
    
</table><div class="fdfd visible-phone">
    Subtotal : 
            <span class="rght txtblod"><?php echo LapakInstan_FrameworkCart::get_order_total(); ?></span><br />
            Bea Kirim : <?php if(get_smart('tj_showongkir') == 'yes'){ ?><span style="font-size:11px;" id="ongkirz">Tanya CS</span><?php }else{ ?><span style="font-size:11px;" id="ongkir2">Belum Memilih alamat</span><?php } ?><br />
            Total : <strong id="total2"><?php echo LapakInstan_FrameworkCart::get_order_total(); ?></strong>
            </div>
</div>
</fieldset>
<div class="ui-formRow">
    <label for="pm_note" class="required">Catatan</label>
    <div class="collection" id="address-1">
        <textarea name="pm_note" id="pm_note" cols="38" rows="5" style="border: 1px solid #eee;"></textarea>
        </div>
</div>
<section class="ac-container">
				<div> 
					<input id="ac-1" name="pay_order" type="radio" checked="" value="Transfer Bank">
					<label for="ac-1"><span class="titrad">Transfer Bank</span></label>
					<article class="ac-small">
                    <p class="kece">Bayar via ATM, SMS, atau Internet Banking. Konfirmasi pembayaran, setelah itu pesanan akan dikirim.</p>
					</article>
				</div>
                <?php if($ggdoku['tj_active_doku'] == 'yes') { ?>
                <div> 
					<input id="ac-2" name="pay_order" type="radio" value="DOKU Myshortcart">
					<label for="ac-2"><span class="titrad">DOKU Myshortcart</span></label>
					<article class="ac-small">
                    <p class="kece">Pembayaran melalui DOKU Myshortcart</p>
                    						<p><img src="<?php echo get_bloginfo('template_url'); ?>/images/myshortcart.png"></p>
                            					</article>
				</div>
                <?php } ?>
                <?php if($ggdoku['tj_active_paypal'] == 'yes') { ?>
                <div> 
					<input id="ac-3" name="pay_order" type="radio" value="PayPal">
					<label for="ac-3"><span class="titrad">PayPal</span></label>
					<article class="ac-small">
                    <p class="kece">Pembayaran melalui PayPal, & Kartu Kredit</p>
                    						<p><img src="<?php echo get_bloginfo('template_url'); ?>/images/paypal.png"></p>
                            					</article>
				</div>
                <?php } ?>
                <?php if($ggdoku['tj_active_ipaymu'] == 'yes') { ?>
                <div> 
					<input id="ac-3" name="pay_order" type="radio" value="iPaymu">
					<label for="ac-3"><span class="titrad">iPaymu</span></label>
					<article class="ac-small">
                    <p class="kece">Pembayaran melalui iPaymu</p>
                    						<p><img src="<?php echo get_bloginfo('template_url'); ?>/images/ipaymu.png"></p>
                            					</article>
				</div>
                <?php } ?>
	<!-- ini disiipkan untuk membuat pilihan pembayaran versi fasapay e-payment gateway-->
	<?php if(get_option('mode')== 'sandbox_mode'){?>
				<div> 
					<input id="ac-5" name="pay_order" type="radio" value="Fasapay">
					<label for="ac-5"><span class="titrad">FasaPay Sandbox</span></label>
					<article class="ac-small">
                    <p class="kece">Pembayaran melalui fasapay e-payment</p>
                    						<p><img src="https://fasapay.com/images/fasapay_logo.png"></p>
                            					</article>
				</div>
	<?php } else if(get_option('mode')== 'live_mode'){ ?>
				<div> 
					<input id="ac-6" name="pay_order" type="radio" value="Fasapaycoid">
					<label for="ac-6"><span class="titrad">FasaPay.Co.Id</span></label>
					<article class="ac-small">
                    <p class="kece">Pembayaran melalui fasapay.co.id e-payment</p>
                    						<p><img src="https://fasapay.com/images/fasapay_logo.png"></p>
                            					</article>
				</div>
				<div> 
					<input id="ac-7" name="pay_order" type="radio" value="Fasapaycom">
					<label for="ac-7"><span class="titrad">FasaPay.Com</span></label>
					<article class="ac-small">
                    <p class="kece">Pembayaran melalui fasapay.com e-payment</p>
                    						<p><img src="https://fasapay.com/images/fasapay_logo.png"></p>
                            					</article>
				</div>
	<?php } ?>
	<!-- -->
	
                <?php if(get_smart('tj_paycod') == 'yes') { ?>
                <div> 
					<input id="ac-4" name="pay_order" type="radio" value="COD">
					<label for="ac-4"><span class="titrad">Bayar di Tempat / COD</span></label>
					<article class="ac-small">
                    <p class="kece">COD / Bayar di Tempat</p>
                    						<p>Kami menerima Pembayaran di Tempat / COD</p>
                            					</article>
				</div>
                <?php } ?>
			</section>
<div class="sub-ui">

<?php if(get_smart('tj_showongkir') == 'yes'){ ?>
<input type="hidden" name="ongkir" id="ongkir" value="0" required="required">
<?php }else{ ?>
<input type="hidden" name="ongkir" id="ongkirt" value="" required="required">
<input type="hidden" name="ongkirpkg" id="ongkirpkg" value="" required="required">
<?php }?>
<input type="hidden" name="totalorder" id="totalorder" value="" required="required">
<div class="termsand"><input name="termsandcondition" type="checkbox" id="terms" value="1" required="required"><label for="terms">Saya bersedia melakukan pemesanan dan saya menyetujui Syarat dan ketentuan yang berlaku</label></div>
<button type="submit" class="btn btn-info cl" name="submit">Konfirmasi Pesanan</button></div>

</form>
</div></div>
<?php } ?>