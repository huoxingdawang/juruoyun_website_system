var jry_wb_cache=
{
	set:function(name,data,longtime)
	{
		if(longtime==null)
			longtime = true;
		if(longtime)
			localStorage.setItem(name,JSON.stringify({lasttime:jry_wb_get_server_time(),data:data}));
		else 
			sessionStorage.setItem(name,JSON.stringify({lasttime:jry_wb_get_server_time(),data:data}));
	},
	set_last_time:function(name,time,longtime)
	{
		if(longtime==null)
			longtime = true;
		var data = jry_wb_cache.get(name);
		if(longtime)
			localStorage.setItem(name,JSON.stringify({lasttime:time,data:data}));
		else
			sessionStorage.setItem(name,JSON.stringify({lasttime:time,data:data}));	
	},
	get:function(name)
	{
		var buf = JSON.parse(localStorage.getItem(name));
		if(buf==null)
			buf = JSON.parse(sessionStorage.getItem(name));
		if(buf==null)
			return null;
		if(buf.data==null||( typeof buf.data=="object"&&buf.data.length==0))
			return null;
		return buf.data;
	},
	get_last_time:function(name,qiangzhi)
	{
		if(qiangzhi)
			return '1926-08-17 01:01:01';
		var buf = JSON.parse(localStorage.getItem(name));
		if(buf==null)
			buf = JSON.parse(sessionStorage.getItem(name));
		if(buf==null)
			return '1926-08-17 01:01:01';
		if(buf.data==null||( typeof buf.data=="object"&&buf.data.length==0))
			return '1926-08-17 01:01:01';
		return buf.lasttime;
	},
	delete:function(name)
	{
		localStorage.removeItem(name);	
		sessionStorage.removeItem(name);
	},
	check_if_delete:function(key)
	{
		return !(key=='showed'||key=='index_note'||key=='background_music'||key=='jry_wb_tree_note');
	},
	delete_all:function()
	{
		var showed = jry_wb_cache.get('showed');
		var index_note = jry_wb_cache.get('index_note');
		var background_music_playing = jry_wb_cache.get('background_music_playing');
		var background_music = jry_wb_cache.get('background_music');
		var jry_wb_tree_note = jry_wb_cache.get('jry_wb_tree_note');
		localStorage.clear();
		sessionStorage.clear();
		jry_wb_cache.set('showed',showed);
		jry_wb_cache.set('index_note',index_note);
		jry_wb_cache.set('background_music_playing',background_music_playing);
		jry_wb_cache.set('background_music',background_music);
		jry_wb_cache.set('jry_wb_tree_note',jry_wb_tree_note);
	},
	size:function(longtime)
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
	}
};