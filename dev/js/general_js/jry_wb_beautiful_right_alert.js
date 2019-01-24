function jry_wb_beautiful_right_alert_function() 
{
	this.inited=false;
	this.init=function() 
	{
		if  (this.inited)  
			return ;
		this.bgObj = document.createElement("div");document.body.appendChild(this.bgObj) ; 
		this.bgObj.className='jry_wb_beautiful_alert';
		this.inited = true;
	};
	jry_wb_add_load (()=>{this.init();});
	this.alert = function(message,time,width,type)  
	{
		if (!this.inited)  
			this.init () ;
		time = time==null?2000:time;
		width = width==null?'auto':width;
		var one = document.createElement ("div");this.bgObj.appendChild(one); 
		one.classList.add('h55');one.classList.add('jry_wb_beautiful_alert_one');
		if(type=='error')
			one.classList.add('jry_wb_color_error');
		else if(type=='ok')
			one.classList.add('jry_wb_color_ok');
		else if(type=='warn') 
			one.classList.add('jry_wb_color_warn');
		else
			one.classList.add('jry_wb_color_normal');
		one.innerHTML=message;
		one.style.width=width;
		one.onclick=function(){one.parentNode.removeChild(one)} ;
		setTimeout(function(){if(one.parentNode!=null)one.parentNode.removeChild(one)},time) ;
	}
}
var jry_wb_beautiful_right_alert= new jry_wb_beautiful_right_alert_function;
