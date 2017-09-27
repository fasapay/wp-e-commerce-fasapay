<?php  
        $myFile1 = "../wp-content/plugins/wp-e-commerce/wpsc-components/theme-engine-v1/templates/functions/wpsc-transaction_results_functions.php";	
        $myFile2 = "../wp-content/plugins/fasapay/help/master/wpsc-transaction_results_functions.php";    

		$fileLocation1 = "../wp-content/plugins/fasapay/backup/wpsc-transaction_results_functions.php";
		$fileLocation2 = "../wp-content/plugins/wp-e-commerce/wpsc-components/theme-engine-v1/templates/functions/wpsc-transaction_results_functions.php";

	 	$myFileLink1 = fopen($myFile1, 'r');
        $myFileContents1 = fread($myFileLink1, filesize($myFile1));
        fclose($myFileLink1);
        $file1 = fopen($fileLocation1, "w");
        $content1 = $myFileContents1;
        fwrite($file1, $content1);
        fclose($file1);
		unlink($myFile1);
		
		$myFileLink2 = fopen($myFile2, 'r');
        $myFileContents2 = fread($myFileLink2, filesize($myFile2));
        fclose($myFileLink2);
        $file2 = fopen($fileLocation2, "w");
        $content2 = $myFileContents2;
        fwrite($file2, $content2);
        fclose($file2);
?>