function jry_wb_nd_fresh_file_list(qiangzhi,callback)
{
	if(jry_nd_share_mode_flag)
		return;
	if(qiangzhi==undefined)
		qiangzhi=false;
	jry_nd_load_count++;
	var file_list;
	jry_wb_indexeddb_get_lasttime('nd_file_list',function(time)
	{	
		if(jry_wb_compare_time(time,jry_wb_login_user.nd_ei.lasttime)<0||qiangzhi)
			jry_wb_sync_data_with_server('nd_file_list',jry_wb_message.jry_wb_host+'jry_wb_netdisk/jry_nd_get_information.php?action=file_list',null,function(data)
			{
				file_list=data;
				var flag=false;
				var time="1926-08-17 00:00:00".to_time();
				var re=jry_wb_indexeddb.transaction(['nd_file_list'],'readwrite').objectStore('nd_file_list');
				if(file_list!=null)
					for(var i=0;i<file_list.length;i++)
						if(file_list[i].delete)
							re.delete(file_list[i].file_id),file_list.splice(i,1),flag=true,i--;
						else
							file_list[i].name=file_list[i].name.replace(/\/37/g,'&'),time=Math.max(time,file_list[i].lasttime.to_time());
				jry_nd_load_count--;
				if(!jry_nd_share_mode_flag)
				{
					jry_nd_file_list=file_list;
					if(jry_nd_load_count==0)
						jry_wb_nd_show_files_by_dir(decodeURI(document.location.hash)!=''?decodeURI(document.location.hash).split('#')[1]:'/');
				}
				jry_wb_beautiful_right_alert.alert('文件数据已同步',2000,'auto','ok');
				if(typeof callback=='function')
					callback();
				return jry_wb_login_user.nd_ei.lasttime;
			});
		else
			jry_wb_indexeddb_get_all('nd_file_list',(data)=>
			{
				file_list=data;
				if(!jry_nd_share_mode_flag)
					jry_nd_file_list=file_list;
				jry_nd_load_count--;
				if(typeof callback=='function')
					callback();			
			});	
	});
	return file_list;
}