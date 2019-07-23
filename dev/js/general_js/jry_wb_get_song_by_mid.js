var jry_wb_get_songs_by_mid=new function()
{
	this.get_qq=function(mid,callback)
	{
		if(typeof callback!='function')
			return;
		jry_wb_add_on_indexeddb_open(function()
		{
			var re=jry_wb_indexeddb.transaction(['qq_music'],'readwrite').objectStore('qq_music').get(mid);
			re.onsuccess=function()
			{
				if(this.result==undefined||((jry_wb_get_server_time().getDate()-new Date(this.result.lasttime.replace(/\-/g, "/")).getDate())>=1))
				{
					jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_spiders/qq_music_spider.php?mid='+mid,(data)=>
					{
						jry_wb_loading_off();
						data=JSON.parse(data);
						data.lyric=data.lyric.split('\n');
						for(var k=0,o=data.lyric.length,t=0;k<o;data.lyric[k]={'t':t,'w':data.lyric[k].split(']')[1]},k++,t=0)
							for(var j=0,a=data.lyric[k].split(']')[0].slice(1).split(':'),m=a.length,t=0;j<m;j++)
								t+=Math.pow(60,m-j-1)*a[j];
						for(var j=0;j<data.lyric.length;j++)
							if(typeof data.lyric[j].w=='undefined'||data.lyric[j].w==''||isNaN(data.lyric[j].t))
								data.lyric.splice(j,1),j--;
						jry_wb_indexeddb.transaction(['qq_music'],'readwrite').objectStore('qq_music').put(data);
						callback(data);
					});				
				}
				else
					callback(this.result);
			};
		});
	};
	this.get_163=function(mid,callback)
	{
		if(typeof callback!='function')
			return;		
		jry_wb_add_on_indexeddb_open(function()
		{
			var re=jry_wb_indexeddb.transaction(['163_music'],'readwrite').objectStore('163_music').get(mid);
			re.onsuccess=function()
			{
				if(this.result==undefined||((jry_wb_get_server_time()-new Date(this.result.lasttime.replace(/\-/g, "/")))>(1000*60*60*0.25)))
				{
					jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_spiders/163_music_spider.php?mid='+mid,(data)=>
					{
						jry_wb_loading_off();
						data=JSON.parse(data);
						data.lyric=data.lyric.split('\n');
						for(var k=0,o=data.lyric.length,t=0;k<o;data.lyric[k]={'t':t,'w':data.lyric[k].split(']')[1]},k++,t=0)							
							for(var j=0,a=data.lyric[k].split(']')[0].slice(1).split(':'),m=a.length,t=0;j<m;j++)
								t+=Math.pow(60,m-j-1)*a[j];
						for(var j=0;j<data.lyric.length;j++)
							if(typeof data.lyric[j].w=='undefined'||data.lyric[j].w==''||isNaN(data.lyric[j].t))
								data.lyric.splice(j,1),j--;							
						jry_wb_indexeddb.transaction(['163_music'],'readwrite').objectStore('163_music').put(data);
						callback(data);
					});				
				}
				else
					callback(this.result);
			};
		});
	};
	var get_qq=this.get_qq;
	var get_163=this.get_163;
	this.get_list=function(slid,callback)
	{
		if(typeof callback!='function')
			return;		
		jry_wb_add_on_indexeddb_open(function()
		{
			var re=jry_wb_indexeddb.transaction(['songlist'],'readwrite').objectStore('songlist').get(parseInt(slid));
			re.onsuccess=function()
			{
				if(this.result==undefined)
				{
					jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_spiders/music_list_spider.php?slid='+slid,(data)=>
					{
						jry_wb_loading_off();
						data=JSON.parse(data);
						var save=[],ans={};
						ans.slid=data.slid;
						ans.makerid=data.makerid;
						ans.data=new Array();
						for(var i=0;i<data.data.length;i++)
						{
							ans.data.push({'mid':data.data[i].mid,'type':data.data[i].type});
							data.data[i].lyric=data.data[i].lyric.split('\n');
							for(var k=0,o=data.data[i].lyric.length,t=0;k<o;data.data[i].lyric[k]={'t':t,'w':data.data[i].lyric[k].split(']')[1]},k++,t=0)
								for(var j=0,a=data.data[i].lyric[k].split(']')[0].slice(1).split(':'),m=a.length,t=0;j<m;j++)
									t+=Math.pow(60,m-j-1)*a[j];
							for(var j=0;j<data.data[i].lyric.length;j++)
								if(typeof data.data[i].lyric[j].w=='undefined'||data.data[i].lyric[j].w==''||isNaN(data.data[i].lyric[j].t))
									data.data[i].lyric.splice(j,1),j--;								
							if(data.data[i].type=='qq')
								jry_wb_indexeddb.transaction(['qq_music'],'readwrite').objectStore('qq_music').put(data.data[i]);
							else if(data.data[i].type=='163')
								jry_wb_indexeddb.transaction(['163_music'],'readwrite').objectStore('163_music').put(data.data[i]);
						}
						jry_wb_indexeddb.transaction(['songlist'],'readwrite').objectStore('songlist').put(ans);
						callback(data);
					});				
				}
				else
				{
					var loading_cnt=this.result.data.length;
					for(let i=0,n=this.result.data.length;i<n;i++)
						if(this.result.data[i].type=='qq')
							get_qq(this.result.data[i].mid,(data)=>
							{
								this.result.data[i]=data;
								if((--loading_cnt)==0)
									callback(this.result);
							});
						else if(this.result.data[i].type=='163')
							get_163(this.result.data[i].mid,(data)=>
							{
								this.result.data[i]=data;
								if((--loading_cnt)==0)
									callback(this.result);
							});							
				}
			};
		});
	};
	this.get=function(list,callback)
	{
		var ans=[];
		var loading_cnt=list.length;
		for(let i=0,n=list.length;i<n;i++)
			if(list[i].type=='qq')
				this.get_qq(list[i].mid,function(data){ans.push(data);if((--loading_cnt)==0)callback(ans);});
			else if(list[i].type=='163')
				this.get_163(list[i].mid,function(data){ans.push(data);if((--loading_cnt)==0)callback(ans);});
			else if(list[i].type=='songlist')
				this.get_list(list[i].slid,function(data){ans=ans.concat(data.data);if((--loading_cnt)==0)callback(ans);});
	};
};