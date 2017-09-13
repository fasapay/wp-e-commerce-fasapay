<?php
		
		
		$myFile = "../wp-content/plugins/fasapay/backup/sidebar-right-backup.php";	
		$myFile3 = "../wp-content/plugins/fasapay/backup/checkout-backup.php";

		$fileLocation = "../wp-content/themes/".get_option('template')."/layout/".get_smart('tj_layout')."/sidebar-right.php";
		$fileLocation2 = "../wp-content/themes/".get_option('template')."/layout/".get_smart('tj_layout')."/fasapay.php";
		$fileLocation3 = "../wp-content/themes/".get_option('template')."/lib/cart/checkout.php"; 
		
		unlink($fileLocation);
		unlink($fileLocation2);
		unlink($fileLocation3);
	
		$myFileLink = fopen($myFile, 'r');
        $myFileContents = fread($myFileLink, filesize($myFile));
        fclose($myFileLink);
        $file = fopen($fileLocation, "w");
        $content = $myFileContents;
        fwrite($file, $content);
        fclose($file);
		
		$myFileLink3 = fopen($myFile3, 'r');
        $myFileContents3 = fread($myFileLink3, filesize($myFile3));
        fclose($myFileLink3);
        $file3 = fopen($fileLocation3, "w");
        $content3 = $myFileContents3;
        fwrite($file3, $content3);
        fclose($file3); 
			
			unlink($myFile);
			unlink($myFile3);	
		
?>