function jry_wb_sync_data_with_server(syncname,dataurl,array,cmp,after,sort_cmp)
{
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
				jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
			else if(data.reason==100001)
				jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
			else
				jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
			return ;	
		}
		if(data==null||typeof data.length=='number')
			after(jry_wb_sync_data_with_array(syncname,data,cmp,sort_cmp),newdata);
		else if(typeof data.length=='undefined')
			after(jry_wb_sync_data_with_array(syncname,data.data,cmp,sort_cmp),newdata);
	},array);
}
function jry_wb_sync_data_with_array(syncname,data,cmp,sort_cmp)
{
	var origin=null;
	if(syncname!='')
		origin=jry_wb_cache.get(syncname);
	if(origin==null)
		origin=data;
	else
		if(data!=null)
			for(var i=0,n=data.length;i<n;i++)
			{
				this.buf=data[i];
				var now=origin.find(cmp);
				if(now==null)
					origin.push(data[i]);
				else
					origin.splice(origin.indexOf(now),1,data[i]);
			}
	if(typeof sort_cmp=='function')
		if(origin!=null)
			origin.sort(sort_cmp);
	if(syncname!='')
		jry_wb_cache.set(syncname,origin);
	return origin;
}