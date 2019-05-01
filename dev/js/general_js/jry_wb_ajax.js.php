<?php if(false){ ?><script><?php } ?>
function jry_wb_ajax_load_data(url,func,array,tong)
{
	jry_wb_loading_on();
<?php if(constant('jry_wb_debug_mode')){ ?>
	console.time('ajax:'+url.substring(url.indexOf(jry_wb_message.jry_wb_host)+jry_wb_message.jry_wb_host.length));
<?php } ?>
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
		if (xmlhttp.readyState==4)
		{
			clearTimeout(clearTO);
			if(xmlhttp.status==200)
				func(xmlhttp.responseText);
			else
			{
				if(xmlhttp.status==500)
					jry_wb_beautiful_alert.alert("吔!服务器异常!","请您把开发组从床上叫起来修复BUG后再试");
				else if(xmlhttp.status==404)
					jry_wb_beautiful_alert.alert("吔!找不到了耶!","请您带上黑框眼镜后再试");
				else if(xmlhttp.status==503)
					jry_wb_beautiful_alert.alert("吔!禁止访问!","小孩子不能进米奇妙妙屋哦");
				xmlhttp.abort();
				jry_wb_loading_off();		
			}
<?php if(constant('jry_wb_debug_mode')){ ?>
			console.timeEnd('ajax:'+url.substring(url.indexOf(jry_wb_message.jry_wb_host)+jry_wb_message.jry_wb_host.length));
<?php } ?>
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
<?php if(false){ ?></script><?php } ?>
