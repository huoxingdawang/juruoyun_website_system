<?php 
function jry_wb_test_mail($email_address)
{
	$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
	if ( preg_match( $pattern, $email_address ) )
		 if(checkdnsrr(array_pop(explode("@",$email_address)),"MX") === true) 
		 	return true;
	return false;
}
?>