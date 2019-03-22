<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_check_type($user,$type)
	{
		return ($user['nd_ei']['allow_type']==-1||in_array($type,$jry_wb_login_user['nd_ei']['allow_type']));
	}		
?>