<?php
	header("content-type: application/x-javascript");
	header('Etag: '.$etag);
	if($_SERVER['HTTP_IF_NONE_MATCH']==$etag)  
	{
		header('HTTP/1.1 304');  
		exit();  
	}
	include_once("../jry_wb_configs/jry_wb_config_user_extern_message.php");
	include_once(dirname(dirname(__FILE__)).'/jry_wb_configs/jry_wb_config_includes.php');
	if(!JRY_WB_DEBUG_MODE)
	{
		if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
		{
			header('Last-Modified: '.$_SERVER['HTTP_IF_MODIFIED_SINCE'],true,304);
			exit();
		}
		header("Cache-Control: private, max-age=10800, pre-check=10800");
		header("Pragma: private");
		header("Expires: " . date(DATE_RFC822,strtotime(" 2 day")));			
	}
?>
<?php if(false){ ?><script><?php } ?>
if(typeof jry_wb_message=='undefined')
	var jry_wb_message={"jry_wb_name":"","jry_wb_title":"","jry_wb_host":"","jry_wb_get_message":"","jry_wb_logo":"","jry_wb_data_host":"","jry_wb_index_page":"","jry_wb_chenge_page":"","jry_wb_background_music_switch":0};
if(typeof jry_wb_login_user=='undefined')
	var jry_wb_login_user={"id":-1,"color":null,"use":null,"head":null,"green_money":null,"enroldate":null,"logdate":null,"greendate":null,"competencename":null,"name":null,"sex":null,"tel":null,"mail":null,"language":"zh-CN","zhushi":"","style":{"update":[{"time":"2018-10-28 16:08:00","data":"随新版主题管理器发布"},{"time":"2018-10-29 10:21:00","data":"测试系统更新"}],"data":{"blog":{"draft":{"mobile":"default/blog/draft.css","desktop":"default/blog/draft.css"},"index":{"mobile":"default/blog/index.css","desktop":"default/blog/index.css"}},"chat":{"index":{"mobile":"default/chat/index.css","desktop":"default/chat/index.css"}},"general":{"mobile":"default/mobile.css","desktop":"default/desktop.css"},"netdisk":{"file":{"mobile":"default/netdisk/file.css","desktop":"default/netdisk/file.css"},"index":{"mobile":"default/netdisk/index.css","desktop":"default/netdisk/index.css"}},"base_url":"http://www.juruoyun.top/jry_wb/jry_wb_css/","mainpages":{"add":{"mobile":"","desktop":""},"index":{"mobile":"default/mainpages/index.css","desktop":"default/mainpages/index.css"},"login":{"mobile":"","desktop":""},"chenge":{"mobile":"","desktop":""},"forget":{"mobile":"","desktop":""}},"online_judge":{"index":{"mobile":"default/online_judge/index.css","desktop":"default/online_judge/index.css"},"show_question":{"mobile":"default/online_judge/show_question.css","desktop":"default/online_judge/show_question.css"}}},"style_id":1,"id":1,"name":"蒟蒻云灰色主题","note":"蒟蒻云默认主题，支持最好，更新最及时"},"login_addr":null,"tel_show":null,"ip_show":null,"mail_show":null,"word_special_fact":null,"follow_mouth":null,"background_music_list":[],"oauth_qq":null,"oauth_github":null,"oauth_gitee":null,"oauth_mi":null};
if(typeof jry_wb_save_browsing_history=='undefined')
	var jry_wb_save_browsing_history='';
if(typeof jry_wb_time_different=='undefined')
	jry_wb_time_different=0;
jry_wb_onload_function_data=null;
function jry_wb_add_onload(func) 
{  
  	var oldonload = jry_wb_onload_function_data;  
  	if(typeof jry_wb_onload_function_data!='function') 
		jry_wb_onload_function_data=func;
	else 
		jry_wb_onload_function_data=function(){if(oldonload)oldonload();func();};
	return func;
}
jry_wb_onbody_function_data=null;
function jry_wb_add_onbody(func) 
{  
  	var oldonbody=jry_wb_onbody_function_data;  
  	if(typeof jry_wb_onbody_function_data!='function') 
		jry_wb_onbody_function_data=func;
	else 
		jry_wb_onbody_function_data=function(){if(oldonload)oldonload();func();};
	return func;
}
var jry_wb_loading_count=1;
var jry_wb_loading_count_max=1;
var jry_wb_loading_progress=undefined;
jry_wb_add_onbody(function()
{
	jry_wb_loading_progress=new jry_wb_progress_bar(document.body,"100%",0,"",function(x){},function(x){},"progress_bar",'',true,false,'ok');
	jry_wb_loading_progress.progress_body.style.position='fixed';
	if(jry_wb_loading_count>0)
		jry_wb_loading_progress.progress_body.style.height='2px',jry_wb_loading_progress.update(jry_wb_loading_count/jry_wb_loading_count_max,'');
});
function jry_wb_loading_on()
{
	jry_wb_loading_count++;
	jry_wb_loading_count_max++;
	if(jry_wb_loading_progress!=undefined)
		jry_wb_loading_progress.progress_body.style.height='2px',jry_wb_loading_progress.update(jry_wb_loading_count/jry_wb_loading_count_max,'');
}
function jry_wb_loading_off()
{
	jry_wb_loading_count--;
	if(jry_wb_loading_count<=0)
		jry_wb_loading_count_max=0;
	if(jry_wb_loading_progress!=undefined)
		jry_wb_loading_progress.progress_body.style.height=(jry_wb_loading_count<=0?'0px':'2px'),jry_wb_loading_progress.update(jry_wb_loading_count/jry_wb_loading_count_max,'');		
}
function jry_wb_add_onresize(func) 
{  
  	var oldonresize=window.onresize;  
  	if(typeof window.onresize!='function')
    	window.onresize=func;
	else
    	window.onresize=function(){if(oldonresize)oldonresize();func();};
	return func;
}
function jry_wb_add_onclick(func) 
{  
  	var oldonclick=window.onclick;  
  	if(typeof window.onclick!='function')  
    	window.onclick=func;
	else 
    	window.onclick=function(event){if(oldonclick)oldonclick(event);func(event);};  
	return func;
}
function jry_wb_add_onmouseup(func) 
{  
  	var oldonmouseup=window.onmouseup;  
  	if(typeof window.onmouseup!='function')  
    	window.onmouseup=func;
	else 
    	window.onmouseup=function(event){if(oldonmouseup)oldonmouseup(event);func(event);};  
	return func;
}
function jry_wb_add_onmousemove(func) 
{  
  	var oldmousemove = window.onmousemove;  
  	if(typeof window.onmousemove != 'function') 
    	window.onmousemove=func;  
	else 
    	window.onmousemove=function(event){if(oldmousemove);oldmousemove(event);func(event);};
	return func;
}
function jry_wb_add_onscroll(func) 
{  
  	var oldonscroll = window.onscroll;  
  	if(typeof window.onscroll != 'function') 
    	window.onscroll=func;  
	else 
    	window.onscroll=function(event){if(oldonscroll);oldonscroll(event);func(event);};
	return func;
}
jry_wb_onclick_flag=false;
/*jry_wb_add_onload(function()
{
	window.ontouchstart=function(event)
	{
		event.preventDefault()
		console.log(event);
		if(typeof event=='undefined'||event==undefined)
			event=window.event; 					
		if(event.touches!=null&&event.touches.length>1)
			return;
		else if(event.changedTouches!=null&event.changedTouches.length>1)
			return;		
		document.body.ontouchstart_timer=new Date();
		return false;
	};
	window.ontouchmove=function(event)
	{
		event.preventDefault()
		console.log(event);
		if(typeof event=='undefined'||event==undefined)
			event=window.event; 					
		if(event.touches!=null&&event.touches.length>1)
			document.body.ontouchstart_timer=undefined;
		else if(event.changedTouches!=null&event.changedTouches.length>1)
			document.body.ontouchstart_timer=undefined;	
		
		return false;
	};
	window.ontouchend=function(event)
	{
		if(typeof event=='undefined'||event==undefined)
			event=window.event;
		if(event.touches!=null&&event.touches.length==1)
		{
			event.clientX		=event.touches[0].clientX;
			event.clientY		=event.touches[0].clientY;
			event.force			=event.touches[0].force;
			event.identifier	=event.touches[0].identifier;
			event.pageX			=event.touches[0].pageX;
			event.pageY			=event.touches[0].pageY;
			event.radiusX		=event.touches[0].radiusX;
			event.radiusY 		=event.touches[0].radiusY;
			event.rotationAngle	=event.touches[0].rotationAngle;
			event.screenX		=event.touches[0].screenX;
			event.screenY		=event.touches[0].screenY;		
		}
		else if(event.changedTouches!=null&&event.changedTouches.length==1)
		{
			event.clientX		=event.changedTouches[0].clientX;
			event.clientY		=event.changedTouches[0].clientY;
			event.force			=event.changedTouches[0].force;
			event.identifier	=event.changedTouches[0].identifier;
			event.pageX			=event.changedTouches[0].pageX;
			event.pageY			=event.changedTouches[0].pageY;
			event.radiusX		=event.changedTouches[0].radiusX;
			event.radiusY 		=event.changedTouches[0].radiusY;
			event.rotationAngle	=event.changedTouches[0].rotationAngle;
			event.screenX		=event.changedTouches[0].screenX;
			event.screenY		=event.changedTouches[0].screenY;
		}
		if(typeof document.body.ontouchstart_timer=='undefined'||document.body.ontouchstart_timer==undefined)
			return ;
		if((new Date()-document.body.ontouchstart_timer)<200)
		{
			if(jry_wb_onclick_flag)
				return;
			if(typeof document.body.onclick=='function')
				document.body.onclick(event);
			if(typeof window.onclick=='function')
				window.onclick(event);		
		}
		else if((new Date()-document.body.ontouchstart_timer)<10000)
		{
			if(typeof document.body.oncontextmenu=='function')
				document.body.oncontextmenu(event);
			if(typeof window.oncontextmenu=='function')
				window.oncontextmenu(event);					
		}
		document.body.ontouchstart_timer=undefined;
		return false;
	};
	jry_wb_add_onclick(function()
	{
		jry_wb_onclick_flag=true;
	});
});
*/
function jry_wb_add_oncontextmenu(func)
{
	if(typeof func=='object')
	{
		func.old_onclick=func.onclick;
		func.old_oncontextmenu=func.oncontextmenu;
		func.onclick=function(event)
		{
			if(typeof func.old_onclick=='function')			
				if((new Date()-func.last_onclick_time)>1000||typeof func.last_onclick_time=='undefined')
					return (func.last_onclick_time=new Date()),func.old_onclick(event);

		};
		func.oncontextmenu=function(event)
		{
			if(typeof func.old_oncontextmenu=='function')			
				if((new Date()-func.last_oncontextmenu_time)>1000||typeof func.last_oncontextmenu_time=='undefined')
					return (func.last_oncontextmenu_time=new Date()),func.old_oncontextmenu(event);

		};
		func.ontouchstart=function(event)
		{
			if(typeof event=='undefined'||event==undefined)
				event=window.event; 					
			if(event.touches!=null&&event.touches.length>1)
				return;
			else if(event.changedTouches!=null&event.changedTouches.length>1)
				return;		
			func.ontouchstart_timer=new Date();
		};
		func.ontouchmove=function(event)
		{
			if(typeof event=='undefined'||event==undefined)
				event=window.event; 					
			if(event.touches!=null&&event.touches.length>1)
				func.ontouchstart_timer=undefined;
			else if(event.changedTouches!=null&event.changedTouches.length>1)
				func.ontouchstart_timer=undefined;	
		};
		func.ontouchend=function(event)
		{
			if(typeof func.ontouchstart_timer=='undefined'||func.ontouchstart_timer==undefined)
				return ;
			if((new Date()-func.ontouchstart_timer)<200)
			{
				if(typeof func.old_onclick=='function')
					if((new Date()-func.last_onclick_time)>1000||typeof func.last_onclick_time=='undefined')
						return (func.last_onclick_time=new Date()),func.old_onclick(event);
			}
			else if((new Date()-func.ontouchstart_timer)<10000)
			{
				if(typeof func.old_oncontextmenu=='function')
					if((new Date()-func.last_oncontextmenu_time)>1000||typeof func.last_oncontextmenu_time=='undefined')
						return (func.last_oncontextmenu_time=new Date()),func.old_oncontextmenu(event);
			}
			func.ontouchstart_timer=undefined;
		};
		func.addEventListener('touchstart',func.ontouchstart);
		func.addEventListener('touchmove',func.ontouchmove);
		func.addEventListener('touchend',func.ontouchend);
	}
	else
	{
		var oldoncontextmenu= window.oncontextmenu;  
		if(typeof window.oncontextmenu != 'function') 
			window.oncontextmenu=func;  
		else 
			window.oncontextmenu=function(event){if(oldoncontextmenu);oldoncontextmenu(event);func(event);};
	}
	return func;
}
function jry_wb_add_onbeforeunload(func) 
{  
  	var oldonbeforeunload = window.onbeforeunload;  
  	if(typeof window.onbeforeunload != 'function') 
    	window.onbeforeunload=func;  
	else 
    	window.onbeforeunload=function(event){if(oldonbeforeunload);oldonbeforeunload(event);func(event);};
	return func;
}
function setinterval(func,time)
{
	func();
	return setInterval(func,time);
}
<?php if(false){ ?></script><?php } ?>