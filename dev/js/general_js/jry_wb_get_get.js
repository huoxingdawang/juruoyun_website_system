function jry_wb_get_get()
{
	var get=new Object();
	if(location.search.indexOf("?")!=-1)
		for(var i=0,strs=location.search.substr(1).split("&");i<strs.length;i++)
			get[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
	return get;
}