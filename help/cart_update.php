<?php
if(isset($_POST['Update'])){
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
        add_option('shopingcard', 'wpecomerce', '', 'yes');
        add_option('store_name', $_POST['store_name'], '', 'yes');
        add_option('word_scurity', $_POST['word_scurity'], '', 'yes');
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
    endif;
    if (isset($_POST['sci']) && $_POST['sci'] === 'lapakinstan'):
			update_option('shopingcard', 'lapakinstan');
            update_option('store_name', filter_input(INPUT_POST, 'store_name'));
            update_option('word_scurity', filter_input(INPUT_POST, 'word_scurity'));
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
    endif;
}
?>