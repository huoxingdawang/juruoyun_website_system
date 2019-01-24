function jry_wb_get_domain()
{
	var all=window.location.host.split(".");
	return all[all.length-2]+"."+all[all.length-1];
}
function jry_wb_test_domain_is_ip()
{
	var all=window.location.host.split(".");
	var flag=false;
	for( var i=0,n=all.length;i<n;i++)
		flag|=isNaN(parseInt(all[i]));
	return !flag;
}