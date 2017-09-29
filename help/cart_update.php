<?php
if(isset($_POST['Update'])){
if (isset($_POST['sci']) && $_POST['sci'] === 'wpecomerce'):
			update_option('shopingcard', 'wpecomerce');
            update_option('store_name', filter_input(INPUT_POST, 'store_name'));
            update_option('word_scurity', filter_input(INPUT_POST, 'word_scurity'));
            update_option('fee_mode', filter_input(INPUT_POST, 'fee_mode'));
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
  		echo "<script type='text/javascript'>window.location.reload();</script>";
    endif;
    if (isset($_POST['sci']) && $_POST['sci'] === 'lapakinstan'):
			update_option('shopingcard', 'lapakinstan');
            update_option('store_name', filter_input(INPUT_POST, 'store_name'));
            update_option('word_scurity', filter_input(INPUT_POST, 'word_scurity'));
            update_option('fee_mode', filter_input(INPUT_POST, 'fee_mode'));
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
  		echo "<script type='text/javascript'>window.location.reload();</script>";
    endif;
}
?>