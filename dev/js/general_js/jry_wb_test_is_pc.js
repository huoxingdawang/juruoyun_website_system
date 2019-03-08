function jry_wb_test_is_pc()
{    
	var userAgentInfo  =  navigator.userAgent;  
	var Agents  =  new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");    
	for ( var v  =  0; v < Agents.length; v++) 
		if (userAgentInfo.indexOf(Agents[v]) > 0)
			return false;
	return true;    
}