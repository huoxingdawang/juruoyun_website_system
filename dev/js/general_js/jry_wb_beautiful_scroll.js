var jry_wb_beautiful_scroll_run_flag=false;
function jry_wb_beautiful_scroll(area,absolute,move,x_switch)
{
	if(absolute==undefined)
		absolute=false;
	if(move==undefined)
		move=false;
	if(x_switch==undefined)
		x_switch=false;
	var timer1=null;
	var timer3=null;
	var timer4=null;
	area.style.overflow='hidden';
	var top_toolbar=document.getElementsByClassName('jry_wb_top_toolbar')[0];
	if(top_toolbar==undefined||!move)
		top_toolbar={'clientHeight':0};	
	var arae_old_onmouseout=area.onmouseout;
	var arae_old_onmouseover=area.onmouseover;
	area.onmouseover=function()
	{
		var h=get_all_child_height();		
		if(parseInt(area.clientHeight)>=h)
			return;		
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
	var jry_wb_scroll_body_y=document.createElement("div");area.appendChild(jry_wb_scroll_body_y);
	jry_wb_scroll_body_y.style.position='absolute';
	jry_wb_scroll_body_y.style.right='0';
	jry_wb_scroll_body_y.style.height=area.clientHeight;
	jry_wb_scroll_body_y.style.top=Math.max(0,top_toolbar.clientHeight);		
	jry_wb_scroll_body_y.style.opacity='0';
	jry_wb_scroll_body_y.classList.add('jry_wb_beautiful_scroll_body');
	var jry_wb_scroll_body_x=document.createElement("div");area.appendChild(jry_wb_scroll_body_x);
	jry_wb_scroll_body_x.style.position='absolute';
	jry_wb_scroll_body_x.style.left='0';
	jry_wb_scroll_body_x.style.width=area.clientWidth-jry_wb_scroll_body_y.clientWidth;
	jry_wb_scroll_body_x.style.bottom='0';		
	jry_wb_scroll_body_x.style.opacity='0';	
	jry_wb_scroll_body_x.classList.add('jry_wb_beautiful_scroll_body');
	setTimeout(function()
	{
		jry_wb_scroll_body_y.style.transitionDuration='1s';	
		jry_wb_scroll_body_x.style.transitionDuration='1s';	
	},1000);
	if(move)
		jry_wb_scroll_body_y.style.zIndex='10000';
	var jry_wb_scroll_kuai_y=document.createElement("div");jry_wb_scroll_body_y.appendChild(jry_wb_scroll_kuai_y);
	jry_wb_scroll_kuai_y.style.height=area.clientHeight/get_all_child_height()*parseInt(jry_wb_scroll_body_y.style.height);			
	jry_wb_scroll_kuai_y.style.position='absolute';
	jry_wb_scroll_kuai_y.classList.add('jry_wb_beautiful_scroll_kuai');
	var jry_wb_scroll_kuai_x=document.createElement("div");jry_wb_scroll_body_x.appendChild(jry_wb_scroll_kuai_x);
	jry_wb_scroll_kuai_x.style.width=area.clientWidth/get_all_child_width()*parseInt(jry_wb_scroll_body_x.style.width);			
	jry_wb_scroll_kuai_x.style.position='absolute';
	jry_wb_scroll_kuai_x.classList.add('jry_wb_beautiful_scroll_kuai');	
	jry_wb_add_onresize(()=>
	{
		var h=get_all_child_height();
		var w=get_all_child_width();
		jry_wb_scroll_body_y.style.height=area.clientHeight;
		jry_wb_scroll_kuai_y.style.height=area.clientHeight/h*parseInt(jry_wb_scroll_body_y.style.height);			
		jry_wb_scroll_body_x.style.width=area.clientWidth-jry_wb_scroll_body_y.clientWidth;
		jry_wb_scroll_kuai_x.style.width=area.clientWidth/w*parseInt(jry_wb_scroll_body_x.style.width);			
		if(area.clientHeight>=h)
		{
			if(get_scrolly()!=0)
				scrollto(0,0);
			for(var i=0,n=area.children.length;i<n;i++)
				area.children[i].yuan_top=area.children[i].offsetTop;
		}
		else
		{
			var yy=now_y;
			scrollto(undefined,0);
			for(var i=0,n=area.children.length;i<n;i++)
			{
				if(typeof area.children[i].yuan_top=='undefined')
					area.children[i].yuan_top=area.children[i].offsetTop;
				else
					area.children[i].yuan_top=(area.children[i].offsetTop);
			}
			scrollto(undefined,yy);
		}
		if(area.clientHeight>=h)
		{
			if(get_scrolly()!=0)
				scrollto(undefined,0);
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
			if(area.children[i]!=jry_wb_scroll_body_y&&area.children[i]!=jry_wb_scroll_body_x)
			{
				ans+=parseInt(area.children[i].clientHeight);
				ans2=Math.max(ans2,area.children[i].clientHeight+area.children[i].yuan_top);
			}
		}
		if(absolute)
			return ans2;
		return ans;
	}
	function get_all_child_width()
	{
		var ans=0;
		for(var i=0,n=area.children.length;i<n;i++)
			if(area.children[i]!=jry_wb_scroll_body_y&&area.children[i]!=jry_wb_scroll_body_x)
				ans=Math.max(ans,area.children[i].clientWidth);
		return ans;
	}	
	var all_flag=false;
	var chaju=0;
	var old_body_style;
	jry_wb_scroll_body_y.onmouseover=function()
	{
		var h=get_all_child_height();		
		if(parseInt(area.clientHeight)>=h)
			return;
		if(timer1!=null)clearTimeout(timer1),timer1=null;
		if(timer4!=null)clearInterval(timer4),timer4=null;
		jry_wb_scroll_body_y.style.height=area.clientHeight;
		jry_wb_scroll_body_y.style.top=Math.max(0,top_toolbar.clientHeight);		
		jry_wb_scroll_body_y.style.opacity=1;
		jry_wb_scroll_kuai_y.style.height=area.clientHeight/h*parseInt(jry_wb_scroll_body_y.style.height);			
		jry_wb_scroll_kuai_y.style.top=Math.max(0,get_scrolly()/h*parseInt(jry_wb_scroll_body_y.style.height));
		if(move)
			jry_wb_right_tools.left(jry_wb_scroll_body_y.clientWidth);
	};
	jry_wb_scroll_body_y.onmouseout=function()
	{
		if(timer1!=null)clearTimeout(timer1),timer1=null;
		if(timer4!=null)clearInterval(timer4),timer4=null;
		timer1=setTimeout(function()
		{
			timer1=null;
			jry_wb_scroll_body_y.style.opacity=0;
			jry_wb_right_tools.right();
		},1000);
	};
	if(x_switch)
	{
		jry_wb_scroll_body_x.onmouseover=function()
		{
			var w=get_all_child_width();		
			if(parseInt(area.clientWidth)>=w)
				return;
			if(timer1!=null)clearTimeout(timer1),timer1=null;
			if(timer4!=null)clearInterval(timer4),timer4=null;
			jry_wb_scroll_body_x.style.width=area.clientWidth-jry_wb_scroll_body_y.clientWidth;
			jry_wb_scroll_body_x.style.opacity=1;
			jry_wb_scroll_kuai_x.style.width=area.clientWidth/w*parseInt(jry_wb_scroll_body_x.style.width);				
			jry_wb_scroll_kuai_x.style.left=Math.max(0,get_scrollx()/w*parseInt(jry_wb_scroll_body_x.style.width));
		};
		jry_wb_scroll_body_x.onmouseout=function()
		{
			if(timer1!=null)clearTimeout(timer1),timer1=null;
			if(timer3!=null)clearInterval(timer3),timer3=null;	
			if(timer4!=null)clearInterval(timer4),timer4=null;	
			timer1=setTimeout(function()
			{
				timer1=null;
				jry_wb_scroll_body_x.style.opacity=0;
			},1000);
		};
	}
	var now_y=0;
	var now_x=0;
	function scrollto(x,y)
	{
		if(!isNaN(y))
		{
			y=Math.max(0,Math.min(y,get_all_child_height()-area.clientHeight));
			now_y=y;
			for(var i=0,n=area.children.length;i<n;i++)
				if(area.children[i]!=jry_wb_scroll_body_y&&area.children[i]!=jry_wb_scroll_body_x)
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
		if(!isNaN(x)&&x_switch)
		{
			x=Math.max(0,Math.min(x,get_all_child_width()-area.clientWidth));
			now_x=x;
			for(var i=0,n=area.children.length;i<n;i++)
				if(area.children[i]!=jry_wb_scroll_body_y&&area.children[i]!=jry_wb_scroll_body_x)
				{
					area.children[i].style.position='relative';
					area.children[i].style.left=-x;
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
	function get_scrollx()
	{
		if(isNaN(now_x))
			return 0;
		return now_x;
	}	
	var last_y=0;
	var last_x=0;
	area.addEventListener("touchstart",(e)=>
	{
		jry_wb_beautiful_scroll_run_flag=true;
		document.body.style.overflowY='hidden';
		if(typeof e=='undefined'||e==undefined)
			e=window.event;
		if(e.touches!=null&&e.touches.length==1)
			e.clientY=e.touches[0].clientY,
			e.clientX=e.touches[0].clientX;
		else if(e.changedTouches!=null&&e.changedTouches.length==1)
			e.clientY=e.changedTouches[0].clientY,
			e.clientX=e.changedTouches[0].clientX;
		last_y=e.clientY;
		last_x=e.clientX;
	},false);
	area.addEventListener("touchmove",(e)=>
	{
		jry_wb_beautiful_scroll_run_flag=true;
		document.body.style.overflowY='hidden';
		if(typeof e=='undefined'||e==undefined)
			e=window.event;
		if(e.touches!=null&&e.touches.length==1)
			e.clientY		=e.touches[0].clientY,
			e.clientX		=e.touches[0].clientX;
		else if(e.changedTouches!=null&&e.changedTouches.length==1)
			e.clientY		=e.changedTouches[0].clientY,
			e.clientX		=e.changedTouches[0].clientX;
		area.onmousewheel({'deltaY':last_y-e.clientY,'deltaX':last_x-e.clientX});
		last_y=e.clientY;		
		last_x=e.clientX;
	},false);
	area.addEventListener("touchend",(e)=>
	{
		document.body.style.overflowY='scroll';		
		jry_wb_beautiful_scroll_run_flag=false;
	},false);
	area.onmousewheel=(e)=>
	{
		var h=get_all_child_height();
		var w=get_all_child_width();
		if(area.clientHeight>=h)
		{
			if(get_scrolly()!=0)
				scrollto(undefined,0);
			return;
		}	
		e=e||window.event;
		if(typeof e.preventDefault=='function')
			e.preventDefault();
		var dx=(e.deltaX||e.detail*50);
		var dy=(e.deltaY||0);
		scrollto(get_scrollx()+dx,get_scrolly()+dy);
		jry_wb_scroll_body_y.style.height=area.clientHeight-Math.max(0,top_toolbar.clientHeight-get_scrolly());
		jry_wb_scroll_body_y.style.top=Math.max(0,top_toolbar.clientHeight);
		jry_wb_scroll_kuai_y.style.height=area.clientHeight/h*parseInt(jry_wb_scroll_body_y.style.height);			
		jry_wb_scroll_kuai_y.style.top=Math.max(0,get_scrolly()/h*parseInt(jry_wb_scroll_body_y.style.height));
		jry_wb_scroll_body_x.style.width=area.clientWidth-jry_wb_scroll_body_y.clientWidth;
		jry_wb_scroll_kuai_x.style.width=area.clientWidth/w*parseInt(jry_wb_scroll_body_x.style.width);				
		jry_wb_scroll_kuai_x.style.left=Math.max(0,get_scrollx()/w*parseInt(jry_wb_scroll_body_x.style.width));		
		if(dy!=0)
			jry_wb_scroll_body_y.style.opacity=1;
		if(dx!=0&&x_switch)
			jry_wb_scroll_body_x.style.opacity=1;
		if(move)
			jry_wb_right_tools.left(jry_wb_scroll_body_y.clientWidth);
		if(timer1!=null)clearTimeout(timer1),timer1=null;
		if(timer4!=null)clearInterval(timer4),timer4=null;
		timer1=setTimeout(function()
		{		
			timer1=null;
			jry_wb_scroll_body_y.style.opacity=0;
			jry_wb_scroll_body_x.style.opacity=0;
			jry_wb_right_tools.right();
		},1000);
		return false;
	};
	jry_wb_scroll_body_y.onclick=function(e)
	{
		var h=get_all_child_height();
		if(area.clientHeight>=h)
			return;
		e=e||window.event;
		if(timer1!=null)clearTimeout(timer1),timer1=null;
		if(timer3!=null)clearInterval(timer3),timer3=null;	
		if(timer4!=null)clearInterval(timer4),timer4=null;	
		var mubiao=Math.max(0,Math.min((e.clientY-jry_wb_scroll_body_y.getBoundingClientRect().top)/area.clientHeight*h,h-area.clientHeight));;
		timer4=setInterval(function()
		{
			if(Math.abs(get_scrolly()-mubiao)<10)
			{
				clearInterval(timer4);
				timer4=null;
				return ;
			}
			scrollto(undefined,get_scrolly()+((mubiao-get_scrolly())/400)*50);
			jry_wb_scroll_kuai_y.style.height=area.clientHeight/h*parseInt(jry_wb_scroll_body_y.style.height);			
			jry_wb_scroll_kuai_y.style.top=Math.max(0,get_scrolly()/h*parseInt(jry_wb_scroll_body_y.style.height));
		},25);		
	};
	jry_wb_scroll_body_x.onclick=function(e)
	{
		var w=get_all_child_width();
		if(area.clientWidth>=w)
			return;
		e=e||window.event;
		if(timer1!=null)clearTimeout(timer1),timer1=null;
		if(timer3!=null)clearInterval(timer3),timer3=null;	
		if(timer4!=null)clearInterval(timer4),timer4=null;	
		var mubiao=Math.max(0,Math.min((e.clientX-jry_wb_scroll_body_x.getBoundingClientRect().left)/area.clientWidth*w,w-area.clientWidth));;
		timer3=setInterval(function()
		{
			if(Math.abs(get_scrollx()-mubiao)<10)
			{
				clearInterval(timer3);
				timer3=null;
				return ;
			}
			scrollto(get_scrollx()+((mubiao-get_scrollx())/400)*50,undefined);
			jry_wb_scroll_kuai_x.style.left=Math.max(0,get_scrollx()/w*parseInt(jry_wb_scroll_body_x.style.width));	
		},25);		
	};		
	if(area.addEventListener)
		area.addEventListener('DOMMouseScroll',area.onmousewheel,false);
	var start_down='';
	jry_wb_add_onmousemove(function(e)
	{
		if(!all_flag)
			return ;
		if(start_down=='y')
		{
			var h=get_all_child_height();
			if(area.clientHeight>=h)
				return;	
			e=e||window.event;
			scrollto(undefined,(e.clientY-jry_wb_scroll_body_y.getBoundingClientRect().top-chaju)/area.clientHeight*h);
			jry_wb_scroll_kuai_y.style.height=area.clientHeight/h*parseInt(jry_wb_scroll_body_y.style.height);			
			jry_wb_scroll_kuai_y.style.top=Math.max(0,get_scrolly()/h*parseInt(jry_wb_scroll_body_y.style.height));
		}
		if(start_down=='x'&&x_switch)
		{
			var w=get_all_child_width();
			if(area.clientWidth>=w)
				return;	
			e=e||window.event;
			scrollto((e.clientX-jry_wb_scroll_body_x.getBoundingClientRect().left-chaju)/area.clientWidth*w,undefined);
			jry_wb_scroll_kuai_x.style.left=Math.max(0,get_scrollx()/w*parseInt(jry_wb_scroll_body_x.style.width));			
		}		
		if(timer1!=null)clearTimeout(timer1),timer1=null;
		if(timer3!=null)clearInterval(timer3),timer3=null;	
		if(timer4!=null)clearInterval(timer4),timer4=null;	
	});
	jry_wb_scroll_kuai_y.onmousedown=function(e)
	{
		start_down='y';
		all_flag=true;
		if(area.clientHeight>=get_all_child_height())
			return;
		if(timer1!=null)clearTimeout(timer1),timer1=null;
		if(timer4!=null)clearInterval(timer4),timer4=null;
		e=e||window.event;
		chaju=e.clientY-jry_wb_scroll_kuai_y.getBoundingClientRect().top;
		old_body_style=document.body.style;
		document.body.style+='-webkit-user-select:none;-moz-user-select:none;-khtml-user-select: none;-ms-user-select: none;';
		area.onselectstart=function(){return false;};
	};
	if(x_switch)
		jry_wb_scroll_kuai_x.onmousedown=function(e)
		{
			start_down='x';
			all_flag=true;
			if(area.clientWidth>=get_all_child_width())
				return;
			if(timer1!=null)clearTimeout(timer1),timer1=null;
			if(timer4!=null)clearInterval(timer4),timer4=null;
			e=e||window.event;
			chaju=e.clientX-jry_wb_scroll_kuai_x.getBoundingClientRect().left;
			old_body_style=document.body.style;
			document.body.style+='-webkit-user-select:none;-moz-user-select:none;-khtml-user-select: none;-ms-user-select: none;';
			area.onselectstart=function(){return false;};
		};	
	jry_wb_add_onmouseup(function()
	{
		if(start_down=='y')
			jry_wb_scroll_body_y.onmouseup();
		if(start_down=='x')
			jry_wb_scroll_body_x.onmouseup();
	});
	jry_wb_scroll_body_y.onmouseup=function(e)
	{
		all_flag=false;
		document.body.style=old_body_style;
		jry_wb_scroll_body_y.onmouseout();
		area.onselectstart=function(){};
	};
	if(x_switch)
		jry_wb_scroll_body_x.onmouseup=function(e)
		{
			all_flag=false;
			document.body.style=old_body_style;
			jry_wb_scroll_body_x.onmouseout();
			area.onselectstart=function(){};
		};	
	this.get_all_child_height=get_all_child_height;
	this.get_all_child_width=get_all_child_width;
	this.jry_wb_scroll_body_y=jry_wb_scroll_body_y;
	this.jry_wb_scroll_body_x=jry_wb_scroll_body_x;
}
jry_wb_add_onload(function()
{
	var timer1=null;/*鼠标离开*/
	var timer4=null;/*点击动画*/
	if(!jry_wb_test_is_pc())
		document.body.style.overflowX='hidden',document.body.style.overflowY='scroll';
	var jry_wb_scroll_body=document.createElement("div");document.body.appendChild(jry_wb_scroll_body);
	jry_wb_scroll_body.style.position='fixed';
	jry_wb_scroll_body.style.right='0';
	var top_toolbar=document.getElementsByClassName('jry_wb_top_toolbar')[0];
	if(top_toolbar==undefined)
		top_toolbar={'clientHeight':0};
	jry_wb_scroll_body.style.height=window.innerHeight-Math.max(0,top_toolbar.clientHeight-window.scrollY);
	jry_wb_scroll_body.style.top=Math.max(0,top_toolbar.clientHeight-window.scrollY);
	jry_wb_scroll_body.style.opacity='0';
	jry_wb_scroll_body.style.zIndex='9999';
	setTimeout(function()
	{
		jry_wb_scroll_body.style.transitionDuration='1s';
	},1000);	
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
		if(timer1!=null)clearTimeout(timer1),timer1=null;
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
		if(timer1!=null)clearTimeout(timer1),timer1=null;
		if(window.innerHeight==document.body.offsetHeight)
			return;
		timer1=setTimeout(function()
		{		
			jry_wb_scroll_body.style.opacity=0;
			jry_wb_right_tools.right();
			timer1=null;
		},1000);
	};
	jry_wb_scroll_body.onclick=function(e)
	{
		if(window.innerHeight==document.body.offsetHeight)
			return;
		e=e||window.event;
		if(timer1!=null)clearTimeout(timer1),timer1=null;
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
		if(timer1!=null)clearTimeout(timer1),timer1=null;
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
		if(timer1!=null)clearTimeout(timer1),timer1=null;
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
		if(timer1!=null)clearTimeout(timer1),timer1=null;
		if(timer4!=null)clearInterval(timer4),timer4=null;
		document.body.style=old_body_style;
	};
	jry_wb_scroll_kuai.onselectstart=function()
	{
		return false;
	};
/*	var last_y=0;
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
*/
	window.onmousewheel=function(e)
	{
		if(timer1!=null)clearTimeout(timer1),timer1=null;
		if(timer4!=null)clearTimeout(timer4),timer4=null;
		if(!jry_wb_test_is_pc())
			document.body.style.overflowX='hidden',document.body.style.overflowY='scroll';		
		if(window.innerHeight==document.body.offsetHeight||jry_wb_beautiful_scroll_run_flag)
			return;
		e=e||window.event;
		if(e!=null&&jry_wb_test_is_pc())
			window.scrollTo(window.scrollX,window.scrollY+(e.deltaY||e.detail*50));
		jry_wb_scroll_body.style.height=window.innerHeight-Math.max(0,top_toolbar.clientHeight-window.scrollY);
		jry_wb_scroll_body.style.top=Math.max(0,top_toolbar.clientHeight-window.scrollY);
		jry_wb_scroll_kuai.style.height=window.innerHeight/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height);
		jry_wb_scroll_kuai.style.top=Math.min(window.innerHeight-parseInt(jry_wb_scroll_kuai.style.height),Math.max(parseInt(jry_wb_scroll_body.style.top),window.scrollY/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height)));
		jry_wb_scroll_body.style.opacity=1;
		jry_wb_right_tools.left(jry_wb_scroll_body.clientWidth);
		timer1=setTimeout(function()
		{		
			jry_wb_scroll_body.style.opacity=0;
			jry_wb_right_tools.right();
			timer1=null;
		},1000);
	};
	if(!jry_wb_test_is_pc())
		jry_wb_add_onscroll(window.onmousewheel);
	document.addEventListener('DOMMouseScroll',window.onmousewheel,false);
});