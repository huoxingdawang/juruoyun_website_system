function jry_wb_sync_data_with_server(syncname,dataurl,array,cmp,after,sort_cmp)
{
	jry_wb_ajax_load_data(dataurl,function(data)
	{
		var buf = JSON.parse(data);
		var data=null;
		if(syncname!='')
			var data = jry_wb_cache.get(syncname);
		if(buf!=null)
			if(typeof buf.code!='undefined'&&buf.code==false)
			{
				if(buf.reason==100000)
					jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
				else if(buf.reason==100001)
					jry_wb_beautiful_alert.alert("权限缺失","缺少"+buf.extern,"window.location.href=''");
				else
					jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
				jry_wb_loading_off();					
				return ;	
			}	
		if(data==null)
			data = buf;
		else 
			if(buf!=null)
				for( var i = 0,n = buf.length;i<n;i++)
				{
					this.buf = buf[i];
					var now = data.find(cmp);
					if(now==null)
						data.push(buf[i]);
					else
						data.splice(data.indexOf(now),1,buf[i]);
				}
		if( typeof sort_cmp=='function')
			if(data!=null)
				data.sort(sort_cmp);
		if(syncname!='')
			jry_wb_cache.set(syncname,data);
		jry_wb_loading_off();	
		after(data);
	},array);
}