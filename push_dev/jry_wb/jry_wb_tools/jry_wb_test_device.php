<?php
	function jry_wb_test_is_mobile() 
	{ 
		if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) 
			return true;
		if(isset($_SERVER['HTTP_VIA'])) 
			return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
		if (isset($_SERVER['HTTP_USER_AGENT'])) 
			if (preg_match("/(" . implode('|',array('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile')) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) 
				return true;
		if (isset ($_SERVER['HTTP_ACCEPT'])) 
			if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
				return true;
		return false;
	}
	function jry_wb_test_is_weixin() 
	{ 
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) 
			return true; 
		return false; 
	}
?>