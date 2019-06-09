function get_dir(i)
{
	if(typeof i=='undefined')
		return '/';	
	if(typeof i=='object')
		var data=i;
	else
		var data=jry_nd_file_list[i];
	if(data==null)
		return '/';
	if(typeof data.dir=='undefined'||data.dir=='')
	{
		var father=jry_nd_file_list.find(function(a){return a.file_id==data.father});
		if(father==null)
			return data.dir='/';
		return data.dir=get_dir(father)+father.name+'/';
	}
	else
		return data.dir;
}