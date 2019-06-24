jry_wb_spider_qq_music_spider=jry_wb_message.jry_wb_host+"jry_wb_spiders/qq_music_spider.php";
jry_wb_spider_163_music_spider=jry_wb_message.jry_wb_host+"jry_wb_spiders/163_music_spider.php";
jry_wb_spider_music_list_spider=jry_wb_message.jry_wb_host+"jry_wb_spiders/music_list_spider.php";
function jry_wb_get_songs_by_mid (list)
{
	this.get_qq=function(mid)
	{
		var cache=jry_wb_cache.get("qq_music_spider_cache");
		if(cache=='')
			cache='null';
		cache=JSON.parse(cache);
		if(cache==null)
			cache= new Array();
		if(cache==null||((b=cache.find(function(a){ return a.mid==mid}))==null)||(typeof b!='object')||((jry_wb_get_server_time().getDate()-new Date(b.lasttime.replace(/\-/g, "/")).getDate())>=1))
		{
			if(b!=null&&(typeof b=='object'))
				cache.splice(b,1);
			jry_wb_ajax_load_data(jry_wb_spider_qq_music_spider+'?mid='+mid,(a)=>{
				a=JSON.parse(a);
				cache.push(a);
				jry_wb_cache.set("qq_music_spider_cache",JSON.stringify(cache));
				jry_wb_loading_off();
			},null,false);
			var a=JSON.parse(jry_wb_cache.get("qq_music_spider_cache")).find(function(a){ return a.mid==mid});
			return {'name':a.name,'music_url':a.music_url,'type':'qq','pic_url':a.pic_url,'mid':mid};
		}
		else
		{
			var a=cache.find(function(a){ return a.mid==mid});
			return {'name':a.name,'music_url':a.music_url,'type':'qq','pic_url':a.pic_url,'mid':mid};
		}
	};
	this.get_163=function(mid)
	{
		var cache=jry_wb_cache.get("163_music_spider_cache");
		if(cache=='')
			cache='null';
		cache=JSON.parse(cache);
		if(cache==null)
			cache= new Array();
		if(cache==null||(b=cache.find(function(a){ return a.mid==mid}))==null||(typeof b!='object')||((jry_wb_get_server_time()-new Date(b.lasttime.replace(/\-/g, "/")))>(1000*60*60*0.25)))
		{
			if(b!=null&&(typeof b=='object'))
				cache.splice(b,1);				
			jry_wb_ajax_load_data(jry_wb_spider_163_music_spider+'?mid='+mid,(a)=>{
				a=JSON.parse(a);
				cache.push(a);
				jry_wb_cache.set("163_music_spider_cache",JSON.stringify(cache));
				jry_wb_loading_off();
			},null,false);
			var a=JSON.parse(jry_wb_cache.get("163_music_spider_cache")).find(function(a){ return a.mid==mid});
			return {'name':a.name,'music_url':a.music_url,'type':'163','pic_url':a.pic_url,'mid':mid};
		}
		else
		{
			var a=cache.find(function(a){ return a.mid==mid});
			return {'name':a.name,'music_url':a.music_url,'type':'163','pic_url':a.pic_url,'mid':mid};
		}		
	};	
	this.get_list=function(slid)
	{
		var cache=jry_wb_cache.get("music_list_spider_cache");
		if(cache=='')
			cache='null';
		cache=JSON.parse(cache);
		if(cache==null)
			cache= new Array();
		if(cache==null||(b=cache.find(function(a){ return a.slid==slid}))==null||(typeof b!='object')||(typeof b.data!='object'))
		{
			if(b!=null&&(typeof b=='object')&&(typeof b.data=='object'))
				cache.splice(b,1);
			var get_data_souce;
			jry_wb_ajax_load_data(jry_wb_spider_music_list_spider+'?slid='+slid,(a)=>
			{
				get_data_souce=a=JSON.parse(a);
				var ans= new Object();
				ans.slid=a.slid;
				ans.makerid=a.makerid;
				ans.data=new Array();
				var cache_qq	=jry_wb_cache.get("qq_music_spider_cache");if(cache_qq=='')cache_qq='null';cache_qq=JSON.parse(cache_qq);if(cache_qq==null)cache_qq=new Array();
				var cache_163	=jry_wb_cache.get("163_music_spider_cache");if(cache_163=='')cache_163='null';cache_163=JSON.parse(cache_163);if(cache_163==null)cache_163=new Array();
				for(var i=0,n=a.data.length;i<n;i++)
				{
					ans.data.push({'mid':a.data[i].mid,'type':a.data[i].type});
					if(a.data[i].type=='qq')
					{
						var b=cache_qq.find(function(aaa){ return aaa.mid==a.data[i].mid});
						if(b!=null)
							cache_qq.splice(b,1);
						cache_qq.push(a.data[i]);
					}
					else if(a.data[i].type=='163')
					{
						var b=cache_163.find(function(aaa){ return aaa.mid==a.data[i].mid});
						if(b!=null)
							cache_163.splice(b,1);
						cache_163.push(a.data[i]);						
					}
				}
				jry_wb_cache.set("qq_music_spider_cache",JSON.stringify(cache_qq));
				jry_wb_cache.set("163_music_spider_cache",JSON.stringify(cache_163));
				cache.push(ans);
				jry_wb_cache.set("music_list_spider_cache",JSON.stringify(cache));
				jry_wb_loading_off();
			},null,false);
			return get_data_souce;			
		}
		else
		{
			var a=cache.find(function(a){return a.slid==slid});
			for(var i=0,n=a.data.length;i<n;i++)
				if(a.data[i].type=='qq')
					a.data[i]=this.get_qq(a.data[i].mid);
				else if(a.data[i].type=='163')
					a.data[i]=this.get_163(a.data[i].mid);
			return a;
		}
	};
	var ans=new Array();
	for(var i=0,n=list.length;i<n;i++)
		if(list[i].type=='qq')
			ans.push(this.get_qq(list[i].mid));
		else if(list[i].type=='163')
			ans.push(this.get_163(list[i].mid));
		else if(list[i].type=='songlist')
			for(var j=0,buf=this.get_list(list[i].slid),nn=buf.data.length;j<nn;j++)
				ans.push(buf.data[j]);
		else if(list[i].type=='test')
			ans.push(list[i]);
	var flags={'qq':{},'163':{}};
	for(var i=0;i<ans.length;i++)
		if(ans[i].type=='qq')
			if(flags['qq'][ans[i].mid]==true)
				ans.splice(i,1),i--;
			else 
				flags['qq'][ans[i].mid]=true;
		else if(ans[i].type=='163')
			if(flags['163'][ans[i].mid]==true)
				ans.splice(i,1),i--;
			else 
				flags['163'][ans[i].mid]=true;
	return ans;	
}