jry_wb_onload_function_data=null;
function jry_wb_add_load(func) 
{  
  	var oldonload = jry_wb_onload_function_data;  
  	if(typeof jry_wb_onload_function_data!='function') 
		jry_wb_onload_function_data=func;
	else 
		jry_wb_onload_function_data=function(){if(oldonload)oldonload();func();};
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
}
function jry_wb_add_onclick(func) 
{  
  	var oldonclick=window.onclick;  
  	if(typeof window.onclick!='function')  
    	window.onclick=func;
	else 
    	window.onclick=function(event){if(oldonclick)oldonclick(event);func(event);};  
}
function jry_wb_add_onmouseup(func) 
{  
  	var oldonmouseup=window.onmouseup;  
  	if(typeof window.onmouseup!='function')  
    	window.onmouseup=func;
	else 
    	window.onmouseup=function(event){if(oldonmouseup)oldonmouseup(event);func(event);};  
}
function jry_wb_add_onmousemove(func) 
{  
  	var oldmousemove = window.onmousemove;  
  	if(typeof window.onmousemove != 'function') 
    	window.onmousemove=func;  
	else 
    	window.onmousemove=function(event){if(oldmousemove);oldmousemove(event);func(event);};
}
function jry_wb_add_onscroll(func) 
{  
  	var oldonscroll = window.onscroll;  
  	if(typeof window.onscroll != 'function') 
    	window.onscroll=func;  
	else 
    	window.onscroll=function(event){if(oldonscroll);oldonscroll(event);func(event);};
}
function jry_wb_add_onbeforeunload(func) 
{  
  	var oldonbeforeunload = window.onbeforeunload;  
  	if(typeof window.onbeforeunload != 'function') 
    	window.onbeforeunload=func;  
	else 
    	window.onbeforeunload=function(event){if(oldonbeforeunload);oldonbeforeunload(event);func(event);};
}
