function jry_wb_get_browser_from_database($data)
{
	if($data==1)
		return "IE8";
	else if($data==2)
		return "IE7";
	else if($data==3)
		return "IE6";
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
	else if($data==16)
		return "IE11";
	else if($data==17)
		return "IE10";
	else if($data==18)
		return "IE9";	
	else
		return 'unknow';		
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