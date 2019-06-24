<?php
	function jry_wb_test_domain_is_ip()
	{
		$all = explode('.',$_SERVER['HTTP_HOST']);
		$n=count($all);
		$flag=true;
		for($i=0;$i<$n;$i++)
			$flag&=is_int($all[$i]);
		return $flag;
	}
?>