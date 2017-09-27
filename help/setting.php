<h3>Setting</h3><hr />
<p></p>
<table>
	<tr>
		<td style="width:30%;"><b>Restore</b><p>Proses restore akan menonaktifkan sistem payment fasapay dari shoping cart anda</p></td>
		<td>
			<form  method="POST" id="restore_form" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<input type="checkbox" name="accept" value="yes"> Sertujui proses restore<hr />
				<input type="submit" name="restore" value="Restore">
			</form>
		</td>
	</tr>
</table><hr />

<?php
	if (get_option('shopingcard')) {
        echo "Fasapay payment gateway status <i style='color:red;'> Active</i> <b>" . get_option('shopingcard')."</b>";
    }else{
		echo "Fasapay payment gateway status <i style='color:red;'> Nonactive</i>";
	}
	if(isset($_POST['restore']) && $_POST['accept']=='yes'){
		if(get_option('shopingcard') == 'lapakinstan'){
		$statusid = get_page_by_title( 'status' )->ID;
		$successid = get_page_by_title( 'success' )->ID;
		$failid = get_page_by_title( 'fail' )->ID;
		wp_delete_post($statusid, true);
		wp_delete_post($successid, true);
		wp_delete_post($failid, true);
		
		if(get_option('shopingcard')){	
		delete_option( 'shopingcard' );
		delete_option( 'store_name' );
		delete_option( 'word_scurity' );
		if(get_option('mode') == 'sandbox_mode'){
		  	delete_option( 'fasa_id' );
		  	delete_option( 'mode' );
		}else{
			delete_option( 'mode' );
			delete_option( 'fasa_co_id' );
			delete_option( 'fasa_com' );
			if(get_option( 'fasa_id' )){
				delete_option( 'fasa_id' );
			}
		}
		
		include "../wp-content/plugins/fasapay/help/restore.php";
		$message = "Fasapay plugins success to restore";
  		echo "<script type='text/javascript'>alert('$message');</script>";
		}
		}else if(get_option('shopingcard') == 'wpecomerce'){
			$statusid = get_page_by_title( 'status' )->ID;
			$successid = get_page_by_title( 'success' )->ID;
			$failid = get_page_by_title( 'fail' )->ID;
			wp_delete_post($statusid, true);
			wp_delete_post($successid, true);
			wp_delete_post($failid, true);
			delete_option( 'shopingcard' );
			delete_option( 'store_name' );
			delete_option( 'word_scurity' );
			
			if(get_option('mode') == 'sandbox_mode'){
		  	delete_option( 'mode' );
		  	delete_option( 'fasa_id' );
				if(delete_option( 'fasa_co_id' )){
					delete_option( 'fasa_co_id' );
				}
				if(delete_option( 'fasa_com' )){					
					delete_option( 'fasa_com' );
				}
			
		}else{
			delete_option( 'mode' );
			delete_option( 'fasa_co_id' );
			delete_option( 'fasa_com' );
			if(get_option( 'fasa_id' )){
				delete_option( 'fasa_id' );
			}
		}
			$fileLocation = "../wp-content/plugins/wp-e-commerce/wpsc-merchants/fasapay.merchant.php";
			unlink($fileLocation);
			$myFile1 = "../wp-content/plugins/fasapay/backup/wpsc-transaction_results_functions.php";
			$fileLocation1 = "../wp-content/plugins/wp-e-commerce/wpsc-components/theme-engine-v1/templates/functions/wpsc-transaction_results_functions.php";
			
			unlink($fileLocation1);
			
			$myFileLink1 = fopen($myFile1, 'r');
        	$myFileContents1 = fread($myFileLink1, filesize($myFile1));
        	fclose($myFileLink1);
        	$file1 = fopen($fileLocation1, "w");
        	$content1 = $myFileContents1;
        	fwrite($file1, $content1);
        	fclose($file1);
			unlink($myFile1);
			
			$custom_gateways = get_option('custom_gateway_options');
			for($i = 0; $i < count($custom_gateways); $i++){
				if($custom_gateways[$i] == "fp_fasapay_merchant"){
					unset($custom_gateways[$i]);
				}
			}
			update_option('custom_gateway_options', $custom_gateways);	
		}
	}

?>