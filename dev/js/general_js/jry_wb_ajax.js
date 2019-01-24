function jry_wb_ajax_load_data(url,func,array,tong)
{
	jry_wb_loading_on();
	jry_wb_beautiful_right_alert.alert('加载数据中，请稍等',500,'auto','warn');
	if(tong==null)
		tong = true;
	var xmlhttp;
	if(window.XMLHttpRequest)
		xmlhttp =  new XMLHttpRequest();
	else
		xmlhttp =  new ActiveXObject("Microsoft.XMLHTTP");
	var clearTO = setTimeout(function()
	{
		xmlhttp.abort();
		jry_wb_beautiful_alert.alert("网络异常","请您刷新页面或稍后再试");
		jry_wb_loading_off();
	},20000);
	xmlhttp.onreadystatechange = function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			clearTimeout(clearTO);
			var ajaxLoadedData = xmlhttp.responseText;
			jry_wb_beautiful_right_alert.alert('加载数据完毕',500,'auto','ok');
			func(ajaxLoadedData);
		}
	};
	xmlhttp.open("POST",url,tong);
	if(tong)
		xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
	var data='';
	if( typeof array=='object'&&array!=null)
	{
		var i = 0;
		for(;i<array.length-1;i++)
			data+=array[i].name+'='+String(array[i].value).replace(/&/g,"/37").replace(/\+/g,"/43")+'&';
		data+=array[i].name+'='+String(array[i].value).replace(/&/g,"/37").replace(/\+/g,"/43");
	}
	xmlhttp.send(data);
}
function jry_wb_ajax_get_text(data)
{
	return data.replace(/\/37/g,"&").replace(/\/43/g,"+")
}