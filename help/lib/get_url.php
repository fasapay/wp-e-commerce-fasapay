<?php
	function get_status(){
		if(get_option('mode') == 'lapakinstan'){
			$url_status = get_page_by_title( 'status' )->guid;
			return $url_status;
		}else{
			$url_status = get_option('transact_url') ."?fpres=status";
			return $url_status;
		}
	}
	function get_success(){
		if(get_option('mode') == 'lapakinstan'){
			$url_success = get_page_by_title( 'success' )->guid;
			return $url_success;
		}else{
			$url_success = get_option('transact_url') ."?fpres=success";
			return $url_success;
		}
	}
	function get_fail(){
		if(get_option('mode') == 'lapakinstan'){
			$url_fail = get_page_by_title( 'fail' )->guid;
			return $url_fail;
		}else{
			$url_fail = get_option('transact_url') ."?fpres=fail";
			return $url_fail;
		}
	}

?>