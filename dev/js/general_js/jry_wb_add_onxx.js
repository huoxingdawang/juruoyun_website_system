jry_wb_onload_function_data=null;
function jry_wb_add_load(func) 
{  
  	var oldonload = jry_wb_onload_function_data;  
  	if(typeof jry_wb_onload_function_data!='function') 
		jry_wb_onload_function_data=func;
	else 
		jry_wb_onload_function_data=function(){if(oldonload)oldonload();func();};
	return func;
}
var __loading_count=1;
function jry_wb_loading_on()
{
	__loading_count++;
	document.getElementById('__LOAD').style.display='';
}
function jry_wb_loading_off()
{
	__loading_count--;
	if(__loading_count<=0)
		document.getElementById('__LOAD').style.display='none';
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
function jry_wb_add_oncontextmenu(func)
{
	if(typeof func=='object')
	{
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
				if(typeof func.onclick=='function')
					func.onclick(event);
			}
			else if((new Date()-func.ontouchstart_timer)<10000)
			{
				if(typeof func.oncontextmenu=='function')
					func.oncontextmenu(event);
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
