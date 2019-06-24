<?php
	include_once('jry_wb_test_domain_is_ip.php');
	function jry_wb_get_domain()
	{
		if(jry_wb_test_domain_is_ip())
			return $_SERVER['HTTP_HOST'];
		$all = explode('.',$_SERVER['HTTP_HOST']);
		$n=count($all);
		return '.'.$all[$n-2].'.'.$all[$n-1];
	}
?>