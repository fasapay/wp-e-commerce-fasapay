<?php  
        $myFile1 = "../wp-content/themes/".get_option('template')."/layout/".get_smart('tj_layout')."/sidebar-right.php";	
        $myFile2 = "../wp-content/plugins/fasapay/help/master/fasapay.php";        
        $myFile3 = "../wp-content/plugins/fasapay/help/master/sidebar-right.php";        
        $myFile4 = "../wp-content/themes/".get_option('template')."/lib/cart/checkout.php";        
        $myFile5 = "../wp-content/plugins/fasapay/help/master/checkout.php";   
		$fileLocation1 = "../wp-content/plugins/fasapay/backup/sidebar-right-backup.php";
		$fileLocation2 = "../wp-content/themes/".get_option('template')."/layout/".get_smart('tj_layout')."/fasapay.php";
		$fileLocation3 = "../wp-content/themes/".get_option('template')."/layout/".get_smart('tj_layout')."/sidebar-right.php";
		$fileLocation4 = "../wp-content/plugins/fasapay/backup/checkout-backup.php";
		$fileLocation5 = "../wp-content/themes/".get_option('template')."/lib/cart/checkout.php";

	 	$myFileLink1 = fopen($myFile1, 'r');
        $myFileContents1 = fread($myFileLink1, filesize($myFile1));
        fclose($myFileLink1);
        $file1 = fopen($fileLocation1, "w");
        $content1 = $myFileContents1;
        fwrite($file1, $content1);
        fclose($file1);
		
		$myFileLink2 = fopen($myFile2, 'r');
        $myFileContents2 = fread($myFileLink2, filesize($myFile2));
        fclose($myFileLink2);
        $file2 = fopen($fileLocation2, "w");
        $content2 = $myFileContents2;
        fwrite($file2, $content2);
        fclose($file2);

		unlink($myFile);
		$myFileLink3 = fopen($myFile3, 'r');
        $myFileContents3 = fread($myFileLink3, filesize($myFile3));
        fclose($myFileLink3);
        $file3 = fopen($fileLocation3, "w");
        $content3 = $myFileContents3;
        fwrite($file3, $content3);
        fclose($file3);

		$myFileLink4 = fopen($myFile4, 'r');
        $myFileContents4 = fread($myFileLink4, filesize($myFile4));
        fclose($myFileLink4);
        $file4 = fopen($fileLocation4, "w");
        $content4 = $myFileContents4;
        fwrite($file4, $content4);
        fclose($file4);

		unlink($myFile4);
		$myFileLink5 = fopen($myFile5, 'r');
        $myFileContents5 = fread($myFileLink5, filesize($myFile5));
        fclose($myFileLink5);
        $file5 = fopen($fileLocation5, "w");
        $content5 = $myFileContents5;
        fwrite($file5, $content5);
        fclose($file5);

		if(get_smart('tj_layout') == 'TJv6'){
			$myFile6 = "../wp-content/plugins/fasapay/help/master/sidebar.php"; 
			$fileLocation6 = "../wp-content/themes/".get_option('template')."/layout/".get_smart('tj_layout')."/sidebar.php"; 
			$myFileLink6 = fopen($myFile6, 'r');
        	$myFileContents6 = fread($myFileLink6, filesize($myFile6));
        	fclose($myFileLink6);
        	$file6 = fopen($fileLocation6, "w");
        	$content6 = $myFileContents6;
        	fwrite($file6, $content6);
        	fclose($file6);	
		}
?>