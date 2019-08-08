<?php 
	include_once(dirname(dirname(__FILE__)).'/jry_wb_configs/jry_wb_config_includes.php');	
	function jry_wb_test_mail($email_address)
	{
		$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
		if ( preg_match( $pattern, $email_address ) )
			 if(JRY_WB_CHECK_MAIL_SWITCH||checkdnsrr(array_pop(explode("@",$email_address)),"MX") === true) 
				return true;
		return false;
	}
?>