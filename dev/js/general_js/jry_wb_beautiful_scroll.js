var jry_wb_beautiful_scroll_run_flag=false;
function jry_wb_beautiful_scroll(area,absolute,move)
{
	if(absolute==undefined)
		absolute=false;
	if(move==undefined)
		move=false;
	var timer=null;/*鼠标离开*/
	var timer4=null;/*点击动画*/
	area.style.overflow='hidden';
	var top_toolbar=document.getElementsByClassName('jry_wb_top_toolbar')[0];
	if(top_toolbar==undefined||!move)
		top_toolbar={'clientHeight':0};	
	var arae_old_onmouseout=area.onmouseout;
	var arae_old_onmouseover=area.onmouseover;
	area.onmouseover=function()
	{
		jry_wb_beautiful_scroll_run_flag=true;
		if(typeof arae_old_onmouseover=='function')
			arae_old_onmouseover();
	};
	area.onmouseout=function()
	{
		jry_wb_beautiful_scroll_run_flag=false;
		if(typeof arae_old_onmouseout=='function')
			arae_old_onmouseout();
	};
	var jry_wb_scroll_body=document.createElement("div");area.appendChild(jry_wb_scroll_body);
	jry_wb_scroll_body.style.position='absolute';
	jry_wb_scroll_body.style.right='0';
	jry_wb_scroll_body.style.height=area.clientHeight;
	jry_wb_scroll_body.style.top=Math.max(0,top_toolbar.clientHeight);		
	jry_wb_scroll_body.style.opacity='0';
	jry_wb_scroll_body.style.transitionDuration='1s';	
	if(move)jry_wb_scroll_body.style.zIndex='9999';
	jry_wb_scroll_body.classList.add('jry_wb_beautiful_scroll_body');
	var jry_wb_scroll_kuai=document.createElement("div");jry_wb_scroll_body.appendChild(jry_wb_scroll_kuai);
	jry_wb_scroll_kuai.style.height=document.body.clientHeight/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height);
	jry_wb_scroll_kuai.style.position='absolute';
	jry_wb_scroll_kuai.classList.add('jry_wb_beautiful_scroll_kuai');
	jry_wb_add_onresize(()=>
	{
		jry_wb_scroll_kuai.style.height=document.body.clientHeight/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height);
		jry_wb_scroll_body.style.height=area.clientHeight;
		if(area.clientHeight>=get_all_child_height())
		{
			if(get_scrolly()!=0)
				scrollto(0);
			for(var i=0,n=area.children.length;i<n;i++)
				area.children[i].yuan_top=area.children[i].offsetTop;
		}
		else
		{
			var yy=now_y;
			scrollto(0);
			for(var i=0,n=area.children.length;i<n;i++)
			{
				if(typeof area.children[i].yuan_top=='undefined')
					area.children[i].yuan_top=area.children[i].offsetTop;
				else
					area.children[i].yuan_top=(area.children[i].offsetTop);
			}
			scrollto(yy);
		}
		if(area.clientHeight>=get_all_child_height())
		{
			if(get_scrolly()!=0)
				scrollto(0);
			return;
		}		
	});
	function get_all_child_height()
	{
		var ans=0;
		var ans2=0;
		for(var i=0,n=area.children.length;i<n;i++)
		{
			if(typeof area.children[i].yuan_top=='undefined')
				area.children[i].yuan_top=area.children[i].offsetTop;
			if(area.children[i]!=jry_wb_scroll_body)
			{
				ans+=parseInt(area.children[i].clientHeight);
				ans2=Math.max(ans2,area.children[i].clientHeight+area.children[i].yuan_top);
			}
		}
		if(absolute)
			return ans2;
		return ans;
	}
	var all_flag=false;
	var chaju=0;
	var old_body_style;
	jry_wb_scroll_body.onmouseover=function()
	{
		var h=get_all_child_height();		
		if(parseInt(area.clientHeight)>=h)
			return;
		if(timer!=null)clearTimeout(timer),timer=null;
		if(timer4!=null)clearInterval(timer4),timer4=null;
		jry_wb_scroll_kuai.style.height=area.clientHeight/h*parseInt(jry_wb_scroll_body.style.height);			
		jry_wb_scroll_kuai.style.top=Math.max(0,get_scrolly()/h*parseInt(jry_wb_scroll_body.style.height));
		jry_wb_scroll_body.style.height=area.clientHeight;
		jry_wb_scroll_body.style.top=Math.max(0,top_toolbar.clientHeight);		
		jry_wb_scroll_body.style.opacity=1;
		if(move)
			jry_wb_right_tools.left(jry_wb_scroll_body.clientWidth);
	};
	jry_wb_scroll_body.onmouseout=function()
	{
		var h=get_all_child_height();
		if(area.clientHeight>=h)
			return;
		if(timer!=null)clearTimeout(timer),timer=null;
		if(timer4!=null)clearInterval(timer4),timer4=null;		
		timer=setTimeout(function()
		{
			timer=null;
			jry_wb_scroll_body.style.opacity=0;
			jry_wb_right_tools.right();
		},1000);
	};
	var now_y=0;
	function scrollto(y)
	{
		if(isNaN(y))
			return ;
		y=Math.max(0,Math.min(y,get_all_child_height()-area.clientHeight));
		now_y=y;
		for(var i=0,n=area.children.length;i<n;i++)
			if(area.children[i]!=jry_wb_scroll_body)
			{
				if(window.getComputedStyle(area.children[i],null).position=='absolute'||area.children[i].style.position=='absolute')
				{
					if(typeof area.children[i].yuan_top=='undefined')
						area.children[i].yuan_top=area.children[i].offsetTop;
					area.children[i].style.top=area.children[i].yuan_top-y;
				}
				else
				{
					area.children[i].style.position='relative';
					area.children[i].style.top=-y;
				}
			}
	}
	this.scrollto=scrollto;
	function get_scrolly()
	{
		if(isNaN(now_y))
			return 0;
		return now_y;
	}
	var last_y=0;
	area.addEventListener("touchstart",(e)=>
	{
		jry_wb_beautiful_scroll_run_flag=true;
		if(typeof e=='undefined'||e==undefined)
			e=window.event;
		if(e.touches!=null&&e.touches.length==1)
			e.clientY=e.touches[0].clientY;
		else if(e.changedTouches!=null&&e.changedTouches.length==1)
			e.clientY=e.changedTouches[0].clientY;
		last_y=e.clientY;
	},false);
	area.addEventListener("touchmove",(e)=>
	{
		jry_wb_beautiful_scroll_run_flag=true;
		if(typeof e=='undefined'||e==undefined)
			e=window.event;
		if(e.touches!=null&&e.touches.length==1)
			e.clientY		=e.touches[0].clientY;
		else if(e.changedTouches!=null&&e.changedTouches.length==1)
			e.clientY		=e.changedTouches[0].clientY;
		area.onmousewheel({'deltaY':last_y-e.clientY});
		last_y=e.clientY;		
	},false);
	area.addEventListener("touchend",(e)=>
	{
		jry_wb_beautiful_scroll_run_flag=false;
	},false);
	
	jry_wb_scroll_body.onmousewheel=area.onmousewheel=(e)=>
	{
		var h=get_all_child_height();
		if(area.clientHeight>=h)
		{
			if(get_scrolly()!=0)
				scrollto(0);
			return;
		}	
		e=e||window.event;
		scrollto(get_scrolly()+(e.deltaY||e.detail*50));
		jry_wb_scroll_kuai.style.height=area.clientHeight/h*parseInt(jry_wb_scroll_body.style.height);			
		jry_wb_scroll_kuai.style.top=Math.max(0,get_scrolly()/h*parseInt(jry_wb_scroll_body.style.height));
		jry_wb_scroll_body.style.opacity=1;
		jry_wb_scroll_body.style.height=area.clientHeight-Math.max(0,top_toolbar.clientHeight-get_scrolly());
		jry_wb_scroll_body.style.top=Math.max(0,top_toolbar.clientHeight);
		if(move)
			jry_wb_right_tools.left(jry_wb_scroll_body.clientWidth);
		if(timer!=null)clearTimeout(timer),timer=null;
		if(timer4!=null)clearInterval(timer4),timer4=null;
		timer=setTimeout(function()
		{		
			timer=null;
			jry_wb_scroll_body.style.opacity=0;
			jry_wb_right_tools.right();
		},1000);
	};
	jry_wb_scroll_kuai.onselectstart=function()
	{
		return false;
	};
	jry_wb_scroll_body.onclick=function(e)
	{
		var h=get_all_child_height();
		if(area.clientHeight>=h)
			return;
		e=e||window.event;
		if(timer!=null)clearTimeout(timer),timer=null;
		if(timer4!=null)clearInterval(timer4),timer4=null;	
		var mubiao=Math.max(0,Math.min((e.clientY-jry_wb_scroll_body.getBoundingClientRect().top)/document.body.clientHeight*h,h-area.clientHeight));;
		timer4=setInterval(function()
		{
			if(Math.abs(get_scrolly()-mubiao)<10)
			{
				clearInterval(timer4);
				timer4=null;
				return ;
			}
			scrollto(get_scrolly()+((mubiao-get_scrolly())/400)*50);
			jry_wb_scroll_kuai.style.height=area.clientHeight/h*parseInt(jry_wb_scroll_body.style.height);			
			jry_wb_scroll_kuai.style.top=Math.max(0,get_scrolly()/h*parseInt(jry_wb_scroll_body.style.height));
		},25);		
	};	
	if(area.addEventListener)
		area.addEventListener('DOMMouseScroll',area.onmousewheel,false);
	if(jry_wb_scroll_body.addEventListener)
		jry_wb_scroll_body.addEventListener('DOMMouseScroll',jry_wb_scroll_body.onmousewheel,false);
	jry_wb_add_onmousemove(function(e)
	{
		if(!all_flag)
			return ;
		var h=get_all_child_height();
		if(area.clientHeight>=h)
			return;	
		e=e||window.event;
		if(timer!=null)clearTimeout(timer),timer=null;
		if(timer4!=null)clearInterval(timer4),timer4=null;		
		scrollto((e.clientY-chaju)/area.clientHeight*h);
		jry_wb_scroll_kuai.style.height=area.clientHeight/h*parseInt(jry_wb_scroll_body.style.height);			
		jry_wb_scroll_kuai.style.top=Math.max(0,get_scrolly()/h*parseInt(jry_wb_scroll_body.style.height));
	});
	jry_wb_scroll_kuai.onmousedown=function(e)
	{
		all_flag=true;
		if(area.clientHeight>=get_all_child_height())
			return;
		if(timer!=null)clearTimeout(timer),timer=null;
		if(timer4!=null)clearInterval(timer4),timer4=null;
		e=e||window.event;
		chaju=e.clientY-jry_wb_scroll_kuai.getBoundingClientRect().top;
		old_body_style=document.body.style;
		document.body.style+='-webkit-user-select:none;-moz-user-select:none;-khtml-user-select: none;-ms-user-select: none;';
	};
	jry_wb_add_onmouseup(function()
	{
		all_flag=false;
		jry_wb_scroll_body.onmouseout();
		document.body.style=old_body_style;
	});
	jry_wb_scroll_kuai.onmouseup=function(e)
	{
		all_flag=false;
		if(area.clientHeight>=get_all_child_height())
			return;
		if(timer!=null)clearTimeout(timer),timer=null;
		if(timer4!=null)clearInterval(timer4),timer4=null;
		document.body.style=old_body_style;
	};	
	this.get_all_child_height=get_all_child_height;
	this.jry_wb_scroll_body=jry_wb_scroll_body;
}
jry_wb_add_load(function()
{
	var timer=null;/*鼠标离开*/
	var timer4=null;/*点击动画*/
	document.body.style.overflow='hidden';
	var jry_wb_scroll_body=document.createElement("div");document.body.appendChild(jry_wb_scroll_body);
	jry_wb_scroll_body.style.position='fixed';
	jry_wb_scroll_body.style.right='0';
	var top_toolbar=document.getElementsByClassName('jry_wb_top_toolbar')[0];
	if(top_toolbar==undefined)
		top_toolbar={'clientHeight':0};
	jry_wb_scroll_body.style.height=window.innerHeight-Math.max(0,top_toolbar.clientHeight-window.scrollY);
	jry_wb_scroll_body.style.top=Math.max(0,top_toolbar.clientHeight-window.scrollY);
	jry_wb_scroll_body.style.opacity='0';
	jry_wb_scroll_body.style.zIndex='9998';
	jry_wb_scroll_body.style.transitionDuration='1s';
	jry_wb_scroll_body.classList.add('jry_wb_beautiful_scroll_body');
	var jry_wb_scroll_kuai=document.createElement("div");jry_wb_scroll_body.appendChild(jry_wb_scroll_kuai);
	jry_wb_scroll_kuai.style.position='fixed';
	jry_wb_scroll_kuai.style.height=window.innerHeight/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height);
	jry_wb_scroll_kuai.style.top=Math.min(window.innerHeight-parseInt(jry_wb_scroll_kuai.style.height),Math.max(parseInt(jry_wb_scroll_body.style.top),window.scrollY/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height)));
	jry_wb_scroll_kuai.classList.add('jry_wb_beautiful_scroll_kuai');
	jry_wb_add_onresize(function()
	{
		jry_wb_scroll_body.style.top=Math.max(0,top_toolbar.clientHeight-window.scrollY);;
		jry_wb_scroll_body.style.height=window.innerHeight-Math.max(0,top_toolbar.clientHeight-window.scrollY);
	});
	jry_wb_scroll_body.onmouseover=function()
	{
		if(window.innerHeight==document.body.offsetHeight)
			return;
		if(timer!=null)clearTimeout(timer),timer=null;
		jry_wb_scroll_body.style.height=window.innerHeight-Math.max(0,top_toolbar.clientHeight-window.scrollY);
		jry_wb_scroll_kuai.style.height=window.innerHeight/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height);
		jry_wb_scroll_body.style.opacity=1;
		jry_wb_right_tools.left(jry_wb_scroll_body.clientWidth);		
	};
	var all_flag=false;
	var chaju=0;
	var old_body_style;
	jry_wb_scroll_body.onmouseout=function()
	{
		if(timer!=null)clearTimeout(timer),timer=null;
		if(window.innerHeight==document.body.offsetHeight)
			return;
		timer=setTimeout(function()
		{		
			jry_wb_scroll_body.style.opacity=0;
			jry_wb_right_tools.right();
			timer=null;
		},1000);
	};
	jry_wb_scroll_body.onclick=function(e)
	{
		if(window.innerHeight==document.body.offsetHeight)
			return;
		e=e||window.event;
		if(timer!=null)clearTimeout(timer),timer=null;
		if(timer4!=null)clearInterval(timer4),timer4=null;	
		var mubiao=e.clientY/window.innerHeight*document.body.offsetHeight;
		timer4=setInterval(function()
		{
			if(Math.abs(window.scrollY-mubiao)<100)
			{
				clearInterval(timer4);
				timer4=null;
				return ;
			}
			window.scrollTo(window.scrollX,window.scrollY+((mubiao-window.scrollY)/400)*50);
			jry_wb_scroll_body.style.height=window.innerHeight-Math.max(0,top_toolbar.clientHeight-window.scrollY);
			jry_wb_scroll_body.style.top=Math.max(0,top_toolbar.clientHeight-window.scrollY);;
			jry_wb_scroll_kuai.style.height=window.innerHeight/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height);
			jry_wb_scroll_kuai.style.top=Math.min(window.innerHeight-parseInt(jry_wb_scroll_kuai.style.height),Math.max(parseInt(jry_wb_scroll_body.style.top),window.scrollY/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height)));
		},25);		
	};
	jry_wb_add_onmousemove(function(e)
	{
		if(!all_flag)
			return ;
		if(window.innerHeight==document.body.offsetHeight)
			return;		
		e=e||window.event;
		if(timer!=null)clearTimeout(timer),timer=null;
		if(timer4!=null)clearInterval(timer4),timer4=null;			
		window.scrollTo(window.scrollX,(e.clientY-chaju)/window.innerHeight*document.body.offsetHeight);
		jry_wb_scroll_body.style.height=window.innerHeight-Math.max(0,top_toolbar.clientHeight-window.scrollY);
		jry_wb_scroll_body.style.top=Math.max(0,top_toolbar.clientHeight-window.scrollY);;
		jry_wb_scroll_kuai.style.height=window.innerHeight/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height);
		jry_wb_scroll_kuai.style.top=Math.min(window.innerHeight-parseInt(jry_wb_scroll_kuai.style.height),Math.max(parseInt(jry_wb_scroll_body.style.top),window.scrollY/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height)));
	});
	jry_wb_scroll_kuai.onmousedown=function(e)
	{
		all_flag=true;
		if(window.innerHeight==document.body.offsetHeight)
			return;
		if(timer!=null)clearTimeout(timer),timer=null;
		if(timer4!=null)clearInterval(timer4),timer4=null;
		e=e||window.event;
		chaju=e.clientY-jry_wb_scroll_kuai.getBoundingClientRect().top;
		old_body_style=document.body.style;
		document.body.style+='-webkit-user-select:none;-moz-user-select:none;-khtml-user-select: none;-ms-user-select: none;';
	};
	jry_wb_add_onmouseup(function()
	{
		all_flag=false;
		jry_wb_scroll_body.onmouseout();
		document.body.style=old_body_style;
	});
	jry_wb_scroll_kuai.onmouseup=function(e)
	{
		all_flag=false;
		if(window.innerHeight==document.body.offsetHeight)
			return;
		if(timer!=null)clearTimeout(timer),timer=null;
		if(timer4!=null)clearInterval(timer4),timer4=null;
		document.body.style=old_body_style;
	};
	jry_wb_scroll_kuai.onselectstart=function()
	{
		return false;
	};
	var last_y=0;
	document.addEventListener("touchstart",function(evt)
	{
		if(jry_wb_beautiful_scroll_run_flag)
			return;
		if(typeof event=='undefined'||event==undefined)
			event=window.event;
		if(event.touches!=null&&event.touches.length==1)
			event.clientY		=event.touches[0].clientY;
		else if(event.changedTouches!=null&&event.changedTouches.length==1)
			event.clientY		=event.changedTouches[0].clientY;
		last_y=event.clientY;
		
	},false);
	document.addEventListener("touchmove",function(evt)
	{
		if(jry_wb_beautiful_scroll_run_flag)
			return;
		if(typeof event=='undefined'||event==undefined)
			event=window.event;
		if(event.touches!=null&&event.touches.length==1)
			event.clientY		=event.touches[0].clientY;
		else if(event.changedTouches!=null&&event.changedTouches.length==1)
			event.clientY		=event.changedTouches[0].clientY;
		window.onmousewheel({'deltaY':last_y-event.clientY});
		last_y=event.clientY;		
	},false);
	window.onmousewheel=function(e)
	{
		if(timer!=null)clearTimeout(timer),timer=null;
		if(timer4!=null)clearTimeout(timer4),timer4=null;
		if(window.innerHeight==document.body.offsetHeight||jry_wb_beautiful_scroll_run_flag)
			return;
		e=e||window.event;
		if(e!=null)
			window.scrollTo(window.scrollX,window.scrollY+(e.deltaY||e.detail*50));
		jry_wb_scroll_body.style.height=window.innerHeight-Math.max(0,top_toolbar.clientHeight-window.scrollY);
		jry_wb_scroll_body.style.top=Math.max(0,top_toolbar.clientHeight-window.scrollY);
		jry_wb_scroll_kuai.style.height=window.innerHeight/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height);
		jry_wb_scroll_kuai.style.top=Math.min(window.innerHeight-parseInt(jry_wb_scroll_kuai.style.height),Math.max(parseInt(jry_wb_scroll_body.style.top),window.scrollY/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height)));
		jry_wb_scroll_body.style.opacity=1;
		jry_wb_right_tools.left(jry_wb_scroll_body.clientWidth);
		timer=setTimeout(function()
		{		
			jry_wb_scroll_body.style.opacity=0;
			jry_wb_right_tools.right();
			timer=null;
		},1000);
	};
	document.addEventListener('DOMMouseScroll',window.onmousewheel,false);
});