function jry_wb_nd_fresh(qiangzhi)
{
	jry_nd_load_count=2;
	if(qiangzhi)
	{
		jry_wb_cache.delete('nd_group');
		jry_wb_cache.delete('nd_area');
	}
	if(jry_wb_compare_time(jry_wb_cache.get_last_time('nd_group').split(/ /)[0],jry_nd_fast_save_message.group)<0||qiangzhi)
		jry_wb_sync_data_with_server('nd_group',jry_wb_message.jry_wb_host+'jry_wb_netdisk/jry_nd_get_information.php?action=group&lasttime='+jry_wb_cache.get_last_time('nd_group',qiangzhi),null,function(a)
		{
			return this.buf.jry_nd_group_id==a.jry_nd_group_id;
		},function(data)
		{
			jry_nd_group=data;
			jry_nd_load_count--;
			if(jry_nd_load_count==0)
				jry_wb_nd_show_files_by_dir(decodeURI(document.location.hash)!=''?decodeURI(document.location.hash).split('#')[1]:'/');
		});
	else
		jry_nd_load_count--,jry_nd_group=jry_wb_cache.get('nd_group');
	if(jry_wb_compare_time(jry_wb_cache.get_last_time('nd_area').split(/ /)[0],jry_nd_fast_save_message.area)<0||qiangzhi)
		jry_wb_sync_data_with_server('nd_area',jry_wb_message.jry_wb_host+'jry_wb_netdisk/jry_nd_get_information.php?action=area&lasttime='+jry_wb_cache.get_last_time('nd_area',qiangzhi),null,function(a)
		{
			return this.buf.area_id==a.area_id;
		},function(data)
		{
			jry_nd_area=data;
			jry_nd_load_count--;
			if(jry_nd_load_count==0)
				jry_wb_nd_show_files_by_dir(decodeURI(document.location.hash)!=''?decodeURI(document.location.hash).split('#')[1]:'/');
		});
	else
		jry_nd_load_count--,jry_nd_area=jry_wb_cache.get('nd_area');
	if(!jry_nd_share_mode_flag)
		jry_wb_nd_fresh_file_list(qiangzhi);
	
}