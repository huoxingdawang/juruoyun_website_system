function jry_wb_sync_data_with_server(syncname,dataurl,array,callback,sort_cmp)
{
	jry_wb_indexeddb_get_lasttime(syncname,function(data)
	{
		dataurl+=((dataurl.includes('?')?'&':'?')+'lasttime='+data.s());
		jry_wb_ajax_load_data(dataurl,function(data)
		{
			jry_wb_loading_off();	
			var data = JSON.parse(data);
			var newdata=false;
			if(data==null)
				data=[];
			if((typeof data.length=='number'&&data.length>0)||(typeof data.length=='undefined'&&typeof data.data!='undefined'&&data.data.length>0))
				newdata=true;
			if(typeof data.code!='undefined'&&data.code==false)
			{
				if(data.reason==100000)
					jry_wb_beautiful_alert.alert("没有登录","","window.location.href='"+jry_wb_message.jry_wb_host+'jry_wb_mainpages/login.php'+"'");
				else if(data.reason==100001)
					jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href='"+jry_wb_message.jry_wb_host+'jry_wb_mainpages/index.php'+"'");
				else
					jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
				return ;	
			}
			jry_wb_sync_data_with_array(syncname,data.data,function(data)
			{
				data=data.sort(sort_cmp);
				var a=callback(data,newdata);
				if(a!=undefined)
					jry_wb_indexeddb_set_lasttime(syncname,a);
			});
		},array);
		
	});
}
function jry_wb_sync_data_with_array(syncname,data,callback)
{
	var re=jry_wb_indexeddb.transaction([syncname],'readwrite').objectStore(syncname);
	for(var i=0,n=data.length;i<n;i++)
		re.put(data[i]);
	jry_wb_indexeddb_get_all(syncname,callback);
}