    <?php
	if(isset($_POST['Submit'])){
    if (isset($_POST['sci']) && $_POST['sci'] === 'wpecomerce'):
        $myFile = "../wp-content/plugins/fasapay/fasapay.merchant.php";
        $myFileLink = fopen($myFile, 'r');
        $myFileContents = fread($myFileLink, filesize($myFile));
        fclose($myFileLink);
        $fileLocation = "../wp-content/plugins/wp-e-commerce/wpsc-merchants/fasapay.merchant.php";
        $file = fopen($fileLocation, "w");
        $content = $myFileContents;
        fwrite($file, $content);
        fclose($file);
		include "../wp-content/plugins/fasapay/help/move_wpe.php";	
		
        add_option('shopingcard', 'wpecomerce', '', 'yes');
        add_option('store_name', $_POST['store_name'], '', 'yes');
        add_option('word_scurity', $_POST['word_scurity'], '', 'yes');
        add_option('fee_mode', $_POST['fee_mode'], '', 'yes');
		if(isset($_POST['mode'])){
			if($_POST['mode'] == 'sandbox_mode'){
			update_option( 'mode', 'sandbox_mode' );
			update_option( 'fasa_id', filter_input(INPUT_POST, 'fasa_id'));
			}else{
			update_option( 'mode', 'live_mode' );
			update_option( 'fasa_co_id', filter_input(INPUT_POST, 'fasa_co_id') );
			update_option( 'fasa_com', filter_input(INPUT_POST, 'fasa_com') );
			}
		}
		//otomatic active plugins
		$custom_gateways = get_option('custom_gateway_options');
		array_push($custom_gateways,"fp_fasapay_merchant");
		update_option('custom_gateway_options', $custom_gateways);
  		echo "<script type='text/javascript'>window.location.reload();</script>";
    endif;
	}
	include "../wp-content/plugins/fasapay/help/cart_update.php";	
?>

