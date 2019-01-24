<?php
	function jry_wb_get_browser($db=false)
	{
		if(!$db)
		{
			if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE8.0"))
				return "IE8.0";
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE7.0"))
				return "IE7.0";
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE6.0"))
				return "IE6.0";
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"Edge"))
				return "Edge";
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"MQQBrowser"))
				return 'Mobile QQ browser';
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"QQBrowser"))
				return 'QQ browser';			
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"FxiOS"))
				return 'Firefox for iOS';
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"CriOS"))
				return 'Chrome for iOS';
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"Opera")||strpos($_SERVER["HTTP_USER_AGENT"],"OPR"))
				return "Opera";	
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"Firefox17"))
				return "Firefox17";
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"Firefox16"))
				return "Firefox16";
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"Firefox"))
				return "Firefox";			
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"Chrome"))
				return "Chrome";
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"Safari"))
				return "Safari";
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"MQBHD"))
				return 'Mobile QQ browser HD';
			else
				return 'unknow';
		}
		else
		{
			if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE8.0"))
				return 1;
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE7.0"))
				return 2;
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE6.0"))
				return 3;
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"Edge"))
				return 9;
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"MQQBrowser"))
				return 12;
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"QQBrowser"))
				return 15;	
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"FxiOS"))
				return 13;
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"CriOS"))
				return 14;				
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"Opera")||strpos($_SERVER["HTTP_USER_AGENT"],"OPR"))
				return 8;	
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"Firefox17"))
				return 4;
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"Firefox16"))
				return 5;
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"Firefox"))
				return 10;			
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"Chrome"))
				return 6;
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"Safari"))
				return 7;
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"MQBHD"))
				return 11;			
			else
				return 0;			
		}
	}
	function jry_wb_get_browser_from_database($data)
	{
		if($data==1)
			return "IE8.0";
		else if($data==2)
			return "IE7.0";
		else if($data==3)
			return "IE6.0";
		else if($data==4)
			return "Firefox17";
		else if($data==5)
			return "Firefox16";
		else if($data==6)
			return "Chrome";
		else if($data==7)
			return "Safari";
		else if($data==8)
			return "Opera";	
		else if($data==9)
			return "Edge";
		else if($data==10)
			return "Firefox";			
		else if($data==11)
			return 'Mobile QQ browser HD';
		else if($data==12)
			return 'Mobile QQ browser';
		else if($data==13)
			return 'Firefox for iOS';
		else if($data==14)
			return 'Chrome for iOS';
		else if($data==15)
			return 'QQ browser';		
		else
			return 'unknow';		
	}
	
	function jry_wb_get_device($db=false)
	{
		if($db)
		{
			if(strpos($_SERVER["HTTP_USER_AGENT"],"iPad"))
				return 1;
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"iPhone"))
				return 2;	
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"Android"))
				return 3;	
			else
				return 0;	
		}
		else
		{
			if(strpos($_SERVER["HTTP_USER_AGENT"],"iPad"))
				return 'ipad';
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"iPhone"))
				return 'iphone';	
			else if(strpos($_SERVER["HTTP_USER_AGENT"],"Android"))
				return 'android';	
			else
				return 'pc';			
		}
	}
	function jry_wb_get_device_from_database($db)
	{
		if($db==1)
			return "iPad";
		else if($db==2)
			return 'iPhone';	
		else if($db==3)
			return "Android";	
		else
			return 'pc';
	}
?>