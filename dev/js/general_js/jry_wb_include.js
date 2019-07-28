jry_wb_include_once_script_cnt=0;
function jry_wb_include_once_script(src,callback)
{
	if(typeof callback!='function')
		callback=function(){};
	src = jry_wb_get_path(src);
	var test = document.getElementsByTagName('script');
	var flag = false;
	for( var i = 0,n = test.length;i<n;i++)
		if(test[i].src==src)
			return callback(),true;
	jry_wb_loading_on();
	jry_wb_include_once_script_cnt++;
	var myscript=document.createElement('script');document.head.appendChild(myscript);
	myscript.src=src;myscript.type='text/javascript';myscript.defer=true;
	myscript.onload=function()
	{
		jry_wb_loading_off();
		jry_wb_include_once_script_cnt--;
		callback();
	};
	return false;
}
function jry_wb_include_once_css(src)
{
	src = jry_wb_get_path(src);
	var test = document.getElementsByTagName('link');
	var flag = false;
	for( var i = 0,n = test.length;i<n;i++)
		if(test[i].type=='text/css'&&test[i].href==src)
			return true;
	jry_wb_loading_on();
	var mylink=document.createElement('link');document.head.appendChild(mylink);
	mylink.href=src;mylink.type='text/css';mylink.rel='stylesheet';
	mylink.onload=function()
	{
		jry_wb_loading_off();
	};
	return false;
}
function jry_wb_include_css(srcc)
{
	src=srcc.split('/');
	var data=jry_wb_login_user.style.data;
	for(var i=0;i<src.length&&data!=undefined;i++)
		data=data[src[i]];
	if(data==undefined)
		return;
	var old=document.getElementById(srcc);
	jry_wb_loading_on();
	var mylink=document.createElement('link');document.head.appendChild(mylink);
	mylink.href=(jry_wb_login_user.style.data.base_url==''?jry_wb_message.jry_wb_host+'jry_wb_css/':jry_wb_login_user.style.data.base_url)+(jry_wb_test_is_pc()?data.desktop:data.mobile);mylink.type='text/css';mylink.rel='stylesheet';
	mylink.onload=function()
	{
		jry_wb_loading_off();
		if(old!=undefined)
			old.remove();
	};
	mylink.id=srcc;
}
