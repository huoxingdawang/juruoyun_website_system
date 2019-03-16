function jry_wb_include_once_script(src,callback)
{
	src = jry_wb_get_path(src);
	var test = document.getElementsByTagName('script');
	var flag = false;
	for( var i = 0,n = test.length;i<n;i++)
		if(test[i].src==src)
			return true;
	jry_wb_loading_on();
	var myscript=document.createElement('script');document.head.appendChild(myscript);
	myscript.src=src;myscript.type='text/javascript';myscript.defer=true;
	myscript.onload=function()
	{
		jry_wb_loading_off();
		if(typeof callback=='function')
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
