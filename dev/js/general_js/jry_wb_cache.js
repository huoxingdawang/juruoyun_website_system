var jry_wb_cache=new function()
{
	this.set=function(name,data,longtime,time)
	{
		if(time==undefined)
			time=jry_wb_get_server_time();
		else
			if(typeof time=="number")
				time=new Date(time);
		if(longtime==null||longtime==undefined)
			longtime = true;
		if(longtime)
			localStorage.setItem(name,JSON.stringify({lasttime:time,data:data}));
		else 
			sessionStorage.setItem(name,JSON.stringify({lasttime:time,data:data}));
	};
	this.set_last_time=function(name,time,longtime)
	{
		if(longtime==null)
			longtime = true;
		var data = jry_wb_cache.get(name);
		if(longtime)
			localStorage.setItem(name,JSON.stringify({lasttime:time,data:data}));
		else
			sessionStorage.setItem(name,JSON.stringify({lasttime:time,data:data}));	
	};
	this.get=function(name)
	{
		var buf = JSON.parse(localStorage.getItem(name));
		if(buf==null)
			buf = JSON.parse(sessionStorage.getItem(name));
		if(buf==null)
			return null;
		if(buf.data==null||( typeof buf.data=="object"&&buf.data.length==0))
			return null;
		return buf.data;
	};
	this.get_last_time=function(name,qiangzhi)
	{
		if(qiangzhi)
			return '1926-08-17 00:00:00';
		var buf = JSON.parse(localStorage.getItem(name));
		if(buf==null)
			buf = JSON.parse(sessionStorage.getItem(name));
		if(buf==null)
			return '1926-08-17 00:00:00';
		if(buf.data==null||( typeof buf.data=="object"&&buf.data.length==0))
			return '1926-08-17 00:00:00';
		if(typeof buf.lasttime.to_time=='function')
			return buf.lasttime.to_time().s();
		else
			return new Date(buf.lasttime).s();
	};
	this.delete=function(name)
	{
		localStorage.removeItem(name);	
		sessionStorage.removeItem(name);
	};
	this.check_if_delete=function(key)
	{
		return !(key=='background_music');
	};
	this.delete_all=function()
	{
		var background_music = jry_wb_cache.get('background_music');
		localStorage.clear();
		sessionStorage.clear();
		jry_wb_cache.set('background_music',background_music);
	};
	this.size=function(longtime)
	{
		if(longtime==null)
			longtime = true;
		var count = 0;
		if(longtime)
			for(var i = 0;i<localStorage.length;i++)
				count+=localStorage.getItem(localStorage.key(i)).length+localStorage.key(i).length;
		else
			for(var i = 0;i<sessionStorage.length;i++)
				count+=sessionStorage.getItem(sessionStorage.key(i)).length+sessionStorage.key(i).length;			
		return count;
	};
};