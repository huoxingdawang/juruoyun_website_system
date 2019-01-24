var jry_wb_cookie  =  
{
	set:function(key,val,time)
	{
		var date = new Date();
		var expiresDays = time;
		date.setTime(date.getTime()+expiresDays*1000);
		if(jry_wb_test_domain_is_ip())
			document.cookie = key + "=" + escape(val) +";expires="+date.toGMTString()+";path=/;";
		else
			document.cookie = key + "=" + escape(val) +";expires="+date.toGMTString()+";path=/;domain=."+jry_wb_get_domain()+";";
	},
	get:function(key)
	{
		var arrCookie=document.cookie.replace(/(^\s+)|(\s+$)/g,"").replace(/\s/g,"").split(";");
		var tips;
		for( var i = 0;i<arrCookie.length;i++)
		{
			var arr = arrCookie[i].split("=");
			if(key==arr[0])
			{
				tips = arr[1];
				break;
			}
		}
		return tips;
	},
	delete:function(key)
	{
		var date  =  new Date();
		date.setTime(date.getTime()-10000);
		document.cookie  =  key + "=v; expires =" +date.toGMTString();
	}
};
