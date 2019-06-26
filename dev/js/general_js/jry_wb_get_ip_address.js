var jry_wb_get_ip_address_buf=new Map();
function jry_wb_get_ip_address(ip,callback)
{
	var data=jry_wb_cache.get('ip');
		if(data!=undefined)
			if((data=data.find(function(a){return a.data.ip==ip}))!=undefined)
				return callback(data.data);	
	if(jry_wb_get_ip_address_buf[ip]!=null)
		return jry_wb_get_ip_address_buf[ip].push(callback);
	jry_wb_get_ip_address_buf[ip]=[];
	jry_wb_get_ip_address_buf[ip].push(callback);
	jry_wb_sync_data_with_server('ip',jry_wb_message.jry_wb_host+'jry_wb_tools/jry_wb_get_ip_address.php?ip='+ip,null,function(a){return a.data.ip==this.buf.data.ip},function(data)
	{
		if(data!=undefined)
			if((data=data.find(function(a){return a.data.ip==ip}))!=undefined)
				for(var i=0,n=jry_wb_get_ip_address_buf[ip].length;i<n;i++)
					jry_wb_get_ip_address_buf[ip][i](data.data);
		jry_wb_get_ip_address_buf[ip]=null;
	});
}