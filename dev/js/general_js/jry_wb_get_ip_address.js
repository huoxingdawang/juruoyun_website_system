var jry_wb_get_ip_address_buf=new Map();
function jry_wb_get_ip_address(ip,callback)
{
	var re=jry_wb_indexeddb.transaction(['ip'],'readwrite').objectStore('ip').get(ip);
	re.onsuccess=function()
	{
		if(this.result!=undefined)
			return callback(this.result.data);
		if(jry_wb_get_ip_address_buf[ip]!=null)
			return jry_wb_get_ip_address_buf[ip].push(callback);
		jry_wb_get_ip_address_buf[ip]=[];
		jry_wb_get_ip_address_buf[ip].push(callback);
		jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_tools/jry_wb_get_ip_address.php?ip='+ip,function(data)
		{
			jry_wb_loading_off();
			data=JSON.parse(data);
			data=data[0];
			jry_wb_indexeddb.transaction(['ip'],'readwrite').objectStore('ip').add(data);			
			for(var i=0,n=jry_wb_get_ip_address_buf[ip].length;i<n;i++)
				jry_wb_get_ip_address_buf[ip][i](data.data);
			jry_wb_get_ip_address_buf[ip]=null;			
		});		
	};
}