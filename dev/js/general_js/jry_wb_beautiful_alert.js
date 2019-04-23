function  jry_wb_beautiful_alert_function() 
{
	var buff=function(){};
	this.frame = function(msgtitle,width,height,x,y,important)
	{
		buff=window.onmousewheel;
		if(document.addEventListener)
			document.removeEventListener('DOMMouseScroll',window.onmousewheel);
		window.onmousewheel=function(){};
		if(important==undefined)
			important=false;
		this.bgObj = document.createElement("div");
		this.bgObj.onmousewheel=function(event){ return false;};
		this.bgObj.classList.add('jry_wb_beautiful_alert_background_div');
		this.bgObj.style.zIndex=important?'10001':'9999';
		document.body.appendChild(this.bgObj); 
		this.msgObj = document.createElement("div") ;
		this.msgObj.classList.add('jry_wb_beautiful_alert_message_div'); 
		this.msgObj.style.top  =  y; 
		this.msgObj.style.left  = x; 
		this.msgObj.style.width = width;
		this.msgObj.style.height = height;
		this.msgObj.style.zIndex=important?'10001':'9999';
		document.body.appendChild(this.msgObj); 
		var title = document.createElement("div"); 
		title.classList.add('jry_wb_beautiful_alert_title'); 
		title.style="overflow:hidden;";
		title.align="center";
		title.style.width = width;
		if(msgtitle) 
			title.innerHTML = msgtitle; 
		else
			title.innerHTML="系统提示";
		this.msgObj.appendChild(title); 		
		bgObj = null;
		return title;
	};
	this.close = function()
	{
		document.body.removeChild(this.bgObj); 	
		document.body.removeChild(this.msgObj);
		window.onmousewheel=buff;
		if(document.addEventListener)
			document.addEventListener('DOMMouseScroll',window.onmousewheel,false);		
		jry_wb_beautiful_scroll_run_flag=false;
	};
	this.prompt = function(msgtitle,func)
	{
		this.frame(msgtitle,400,200,(document.body.clientWidth-400)/2,(document.body.clientHeight-400)/2);
		var txt = document.createElement("input");this.msgObj.appendChild(txt);
		txt.classList.add("jry_wb_beautiful_alert_text","h56");
		txt = null;
		var buttom = document.createElement("div");this.msgObj.appendChild(buttom); 
		buttom.classList.add("jry_wb_beautiful_alert_button");
		buttom.style="width:100%;buttom:0px;text-align:center;";
		var Confirm = document.createElement("input");buttom.appendChild(Confirm);  
		Confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
		Confirm.type='button'; 
		Confirm.value="确认"; 
		Confirm.style.bottom = 0;
		Confirm.onclick = function(event)
		{
			var value = Confirm.parentNode.parentNode.getElementsByTagName('input')[0].value;
			if(value!='')
				func(value);
			else
				jry_wb_beautiful_right_alert.alert('未执行操作',2000,'auto');
			document.body.removeChild(Confirm.parentNode.parentNode.previousSibling); 
			document.body.removeChild(Confirm.parentNode.parentNode);
			window.onmousewheel=buff;
			if(document.addEventListener)
				document.addEventListener('DOMMouseScroll',window.onmousewheel,false);		
			jry_wb_beautiful_scroll_run_flag=false;
		};
		this.msgObj.onmousewheel=function(event){ return false;};
	};
	this.check = function(msgtitle,funcy,funcn,wordy,wordn)
	{
		if(wordy==null)
			wordy='确定';
		if(wordn==null)
			wordn='取消';
		this.frame(msgtitle,400,200,(document.body.clientWidth-400)/2,(document.body.clientHeight-400)/2,true);
		var buttom = document.createElement("div");this.msgObj.appendChild(buttom);
		buttom.classList.add("jry_wb_beautiful_alert_button");
		buttom.style="width:100%;buttom:0px;text-align:center;"; 
		var Confirm = document.createElement("input"); 
		Confirm.type="button"; 
		Confirm.value=wordy; 
		Confirm.style.bottom = 0;
		Confirm.onclick = function(event)
		{
			document.body.removeChild(event.target.parentNode.parentNode.previousSibling); 
			document.body.removeChild(event.target.parentNode.parentNode); 
			window.onmousewheel=buff;
			if(document.addEventListener)
				document.addEventListener('DOMMouseScroll',window.onmousewheel,false);		
			jry_wb_beautiful_scroll_run_flag=false;
			funcy();
		};
		Confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
		buttom.appendChild(Confirm);    
		Confirm = null;
		var Confirm = document.createElement("input");buttom.appendChild(Confirm);  
		Confirm.type="button"; 
		Confirm.value=wordn; 
		Confirm.style.bottom = 0;
		Confirm.onclick = function(event)
		{
			document.body.removeChild(event.target.parentNode.parentNode.previousSibling); 
			document.body.removeChild(event.target.parentNode.parentNode);
			window.onmousewheel=buff;
			if(document.addEventListener)
				document.addEventListener('DOMMouseScroll',window.onmousewheel,false);		
			jry_wb_beautiful_scroll_run_flag=false;
			funcn();
		};
		Confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_error");
		Confirm = null;
		msgbutton = null;
		this.msgObj.onmousewheel=function(event){ return false;};
	};
	this.alert = function(msgtitle,msgbody,func)
	{
		this.frame(msgtitle,400,200,(document.body.clientWidth-400)/2,(document.body.clientHeight-400)/2,true);
		var txt = document.createElement("div");this.msgObj.appendChild(txt);
		txt.classList.add("jry_wb_beautiful_alert_text");
		txt.innerHTML=msgbody; 
		txt = null;
		var buttom = document.createElement("div");this.msgObj.appendChild(buttom);   
		buttom.classList.add("jry_wb_beautiful_alert_button");
		buttom.style="width:100%;buttom:0px;text-align:center;"; 
		var Confirm = document.createElement("input");buttom.appendChild(Confirm);  
		Confirm.setAttribute("type","button"); 
		Confirm.setAttribute("value","确认"); 
		Confirm.style.bottom = 0;
		var old_onkeydown=document.onkeydown;
		document.onkeydown=function(e)
		{
			if (!e) 
				e  =  window.event;
			var keycode=(e.keyCode||e.which);
			if(keycode==jry_wb_keycode_enter)
				Confirm.onclick(e);
			return false;
		};
		if( typeof func==='function')
		{
			Confirm.onclick = function(event)
			{
				document.body.removeChild(Confirm.parentNode.parentNode.previousSibling); 
				document.body.removeChild(Confirm.parentNode.parentNode);
				window.onmousewheel=buff;
				if(document.addEventListener)
					document.addEventListener('DOMMouseScroll',window.onmousewheel,false);		
				jry_wb_beautiful_scroll_run_flag=false;
				func();
				document.onkeydown=old_onkeydown;
			};
		}
		else
		{
			Confirm.onclick = function(event)
			{
				document.body.removeChild(Confirm.parentNode.parentNode.previousSibling); 	
				document.body.removeChild(Confirm.parentNode.parentNode);
				if(document.addEventListener)
					document.addEventListener('DOMMouseScroll',window.onmousewheel,false);		
				jry_wb_beautiful_scroll_run_flag=false;
				if(typeof func!="undefined")
					eval(func);
				document.onkeydown=old_onkeydown;
			};		
		}
		Confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
		this.msgObj.onmousewheel=function(event){ return false;};
	};
	this.open = function(msgtitle,width,height,url,func)
	{
		var title = this.frame(msgtitle,width,height,(document.body.clientWidth-width)/2,(document.body.clientHeight-height)/2);
		var txt = document.createElement("iframe"); 
		txt.setAttribute("src",url);
		txt.setAttribute("seamless",'seamless');
		txt.setAttribute("frameborder",'0');
		txt.setAttribute("width",'100%');
		txt.style.width = height-title.getBoundingClientRect().top;
		this.msgObj.appendChild(txt);
		jry_wb_beautiful_right_alert.alert('加载内联页面中，请稍等',1000,'auto','warn');jry_wb_loading_on();
		txt.onload=function()
		{
			jry_wb_beautiful_right_alert.alert('加载内联页面完毕',500,'auto','ok');	jry_wb_loading_off();
			window.onresize();					
		};
		txt = null; 
		var Confirm = document.createElement("button");title.appendChild(Confirm); 
		Confirm.type="button"; 
		Confirm.innerHTML="关闭"; 
		Confirm.style='float:right;margin-right:20px;';
		if( typeof func==='function')
		{
			Confirm.onclick = function(event)
			{
				document.body.removeChild(Confirm.parentNode.parentNode.previousSibling); 
				document.body.removeChild(Confirm.parentNode.parentNode);
				window.onmousewheel=buff;
				if(document.addEventListener)
					document.addEventListener('DOMMouseScroll',window.onmousewheel,false);		
				jry_wb_beautiful_scroll_run_flag=false;
				func();
			};
		}
		else
		{
			Confirm.onclick = function(event)
			{
				document.body.removeChild(Confirm.parentNode.parentNode.previousSibling); 	
				document.body.removeChild(Confirm.parentNode.parentNode);
				window.onmousewheel=buff;
				if(document.addEventListener)
					document.addEventListener('DOMMouseScroll',window.onmousewheel,false);		
				jry_wb_beautiful_scroll_run_flag=false;
				if(typeof func!="undefined")
					eval(func);
			};		
		}
		Confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_normal");
		new jry_wb_beautiful_scroll(this.msgObj);
	};
	this.openvideo = function(msgtitle,width,height,url,func,show_close_button)
	{
		if(show_close_button==null || typeof show_close_button!="function")
			show_close_button = function(){ return true;};
		var title = this.frame(msgtitle,width,height,(document.body.clientWidth-width)/2,(document.body.clientHeight-height)/2);
		var div = document.createElement("div"); this.msgObj.appendChild(div);
		div.style="width:100%;margin:0;padding:0;overflow-y:scroll;overflow-x:hidden;";
		div.style.height = height-title.clientHeight;
		div.align='center';		
		var video = document.createElement("video");div.appendChild(video);
		video.style='height:100%;width:100%;';
		video.src = url;
		var timer=setInterval(function()
		{
			ratio=video.videoHeight/video.videoWidth;
			if(!isNaN(ratio))
			{
				clearInterval(timer);
				maxwidth=width;
				maxheight=height-title.clientHeight;
				height=maxwidth*ratio;
				width=maxheight/ratio;
				if(height>maxheight)
				{
					div.style.height=maxheight;
					div.style.width=maxheight/ratio;
					div.style.paddingLeft=(maxwidth-(maxheight/ratio))/2;
				}
				if(width>maxwidth)
				{
					div.style.width=maxwidth;
					div.style.height=maxwidth*ratio;
					div.style.paddingLeft=0;
				}				
			}
		},400);
		video.ontimeupdate = function()
		{
			if(show_close_button(video))
			{
				if(title.getElementsByTagName("button").length!=0)
					return;
				var Confirm = document.createElement("button");title.appendChild(Confirm);
				Confirm.type="button"; 
				Confirm.innerHTML="关闭"; 
				Confirm.style='float:right;margin-right:20px;';
				if( typeof func==='function')
				{
					Confirm.onclick = function(event)
					{
						jry_wb_midia_control_all.onpause(video);
						document.body.removeChild(Confirm.parentNode.parentNode.previousSibling); 
						document.body.removeChild(Confirm.parentNode.parentNode);
						window.onmousewheel=buff;
						if(document.addEventListener)
							document.addEventListener('DOMMouseScroll',window.onmousewheel,false);		
						jry_wb_beautiful_scroll_run_flag=false;
						func(video);
					};
				}
				else
				{
					Confirm.onclick = function(event)
					{
						document.body.removeChild(Confirm.parentNode.parentNode.previousSibling); 	
						document.body.removeChild(Confirm.parentNode.parentNode);
						window.onmousewheel=buff;
						if(document.addEventListener)
							document.addEventListener('DOMMouseScroll',window.onmousewheel,false);		
						jry_wb_beautiful_scroll_run_flag=false;
						jry_wb_midia_control_all.onpause(video);
						if(typeof func!="undefined")
							eval(func);
					};			
				}
				Confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_normal");   
			}
		};
		video.ontimeupdate();
		var video_body = new jry_wb_beautiful_video(video);
		video_body.body.style.height = height-title.clientHeight;
		this.msgObj.onmousewheel=function(event){ return false;};
		new jry_wb_beautiful_scroll(this.msgObj);
	};
	this.openpicture = function(msgtitle,width,height,url)
	{
		var title = this.frame(msgtitle,width,height,(document.body.clientWidth-width)/2,(document.body.clientHeight-height)/2);
		var div = document.createElement("div"); this.msgObj.appendChild(div);
		div.style="width:100%;margin:0;padding:0;overflow:hidden;";
		div.align='center';
		var txt = document.createElement("img");
		txt.style.maxWidth='100%';
		txt.onload=function()
		{
			window.onresize();
			if(txt.naturalWidth<width*0.2)
				jry_wb_beautiful_right_alert.alert('使用shift+滚轮缩放<br>ctrl+0恢复原尺寸',5000,'auto');
		};
		if(url.indexOf("?")==-1)
			txt.src = url+'?size='+parseInt(width);
		else
			txt.src = url+'&size='+parseInt(width);
		div.appendChild(txt);
		var Confirm = document.createElement("button"); title.appendChild(Confirm);
		Confirm.type="button"; 
		Confirm.innerHTML="关闭"; 
		Confirm.style='float:right;margin-right:20px;';
		if( typeof func==='function')
		{
			Confirm.onclick = function(event)
			{
				document.body.removeChild(Confirm.parentNode.parentNode.previousSibling); 
				document.body.removeChild(Confirm.parentNode.parentNode);
				window.onmousewheel=buff;
				if(document.addEventListener)
					document.addEventListener('DOMMouseScroll',window.onmousewheel,false);		
				jry_wb_beautiful_scroll_run_flag=false;
				jry_wb_set_shortcut([jry_wb_keycode_control,jry_wb_keycode_0],function(){});
				func();
			};
		}
		else
		{
			Confirm.onclick = function(event)
			{
				document.body.removeChild(Confirm.parentNode.parentNode.previousSibling); 	
				document.body.removeChild(Confirm.parentNode.parentNode);
				window.onmousewheel=buff;
				if(document.addEventListener)
					document.addEventListener('DOMMouseScroll',window.onmousewheel,false);		
				jry_wb_beautiful_scroll_run_flag=false;
				jry_wb_set_shortcut([jry_wb_keycode_control,jry_wb_keycode_0],function(){});
				if(typeof func!="undefined")
					eval(func);
			};
		}
		Confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_normal");
		new jry_wb_beautiful_scroll(this.msgObj);
		old_onwheel=this.msgObj.onmousewheel;
		this.msgObj.onmousewheel=(e)=>
		{
			e=e||window.event;
			if(e.shiftKey)
			{
				txt.style.width=Math.max(10,txt.clientWidth+(e.deltaY||e.detail*50)/10);
				jry_wb_set_shortcut([jry_wb_keycode_control,jry_wb_keycode_0],function()
				{
					div.style.width="100%";
					div.style.margin="0";
					txt.style.maxWidth='100%';
					txt.style.width=txt.naturalWidth;
					title.style.top=0;
					div.style.top=0;
				});
			}
			else
			{
				old_onwheel(e);
			}
			return false;
		};
		if(this.msgObj.addEventListener)this.msgObj.addEventListener('DOMMouseScroll',this.msgObj.onmousewheel,false);
	};
};
var jry_wb_beautiful_alert  =  new jry_wb_beautiful_alert_function;
