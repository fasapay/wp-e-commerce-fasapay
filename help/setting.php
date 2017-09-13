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
	}

?>