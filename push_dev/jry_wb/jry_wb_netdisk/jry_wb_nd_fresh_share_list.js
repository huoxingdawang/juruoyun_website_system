function jry_wb_nd_fresh_share_list(qiangzhi,callback)
{
	if(jry_nd_share_mode_flag)
		return;
	if(qiangzhi==undefined)
		qiangzhi=false;
	jry_nd_load_count++;
	if(qiangzhi===true)
		jry_wb_cache.delete('nd_share_list');
	var share_list;
	if(jry_wb_compare_time(jry_wb_cache.get_last_time('nd_share_list').split(/ /)[0],jry_wb_login_user.nd_ei.lasttime)<0||qiangzhi)
		jry_wb_sync_data_with_server('nd_share_list',jry_wb_message.jry_wb_host+'jry_wb_netdisk/jry_nd_get_information.php?action=share_list&lasttime='+jry_wb_cache.get_last_time('nd_share_list',qiangzhi===true),null,function(a)
		{
			return this.buf.share_id==a.share_id;
		},function(data)
		{
			share_list=data;
			var flag=false;
			var time="1926-08-17 00:00:00".to_time();
			if(share_list!=null)
				for(var i=0;i<share_list.length;i++)
					if(share_list[i].delete)
						share_list.splice(i,1),flag=true,i--;
					else
						time=Math.max(time,share_list[i].lasttime.to_time());
			if(flag)
				jry_wb_cache.set('nd_share_list',share_list,undefined,time);
			jry_nd_load_count--;
			if(!jry_nd_share_mode_flag)
			{
				jry_nd_share_list=share_list;			
				if(jry_nd_load_count==0)
					jry_wb_nd_show_files_by_dir(decodeURI(document.location.hash)!=''?decodeURI(document.location.hash).split('#')[1]:'/');
			}
			jry_wb_beautiful_right_alert.alert('文件数据已同步',2000,'auto','ok');
			if(typeof callback=='function')
				callback();			
		});
	else
	{
		jry_nd_load_count--,share_list=jry_wb_cache.get('nd_share_list');
		if(!jry_nd_share_mode_flag)
			jry_nd_share_list=share_list;
		if(typeof callback=='function')
			callback();			
	}
	return share_list;
}