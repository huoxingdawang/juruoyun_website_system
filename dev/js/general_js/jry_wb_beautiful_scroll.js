var jry_wb_beautiful_scroll_run_flag=false;
function jry_wb_beautiful_scroll(area)
{
	var timer=null;/*鼠标离开*/
	var timer2=null;/*开始运动动画*/
	var timer3=null;/*结束运动动画*/
	var timer4=null;/*点击动画*/
	area.style.overflow='hidden';
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
	jry_wb_scroll_body.style.top=0;
	jry_wb_scroll_body.style.height=area.clientHeight;
	jry_wb_scroll_body.style.opacity='0';
	jry_wb_scroll_body.classList.add('jry_wb_beautiful_scroll_body');
	var jry_wb_scroll_kuai=document.createElement("div");jry_wb_scroll_body.appendChild(jry_wb_scroll_kuai);
	jry_wb_scroll_kuai.style.height=document.body.clientHeight/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height);
	jry_wb_scroll_kuai.style.position='absolute';
	jry_wb_scroll_kuai.classList.add('jry_wb_beautiful_scroll_kuai');
	jry_wb_add_onresize(function()
	{
		jry_wb_scroll_kuai.style.height=document.body.clientHeight/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height);
		jry_wb_scroll_body.style.height=area.clientHeight;		
	});
	function get_all_child_height()
	{
		var ans=0;
		var ans2=0;
		for(var i=0,n=area.children.length;i<n;i++)
			if(area.children[i]!=jry_wb_scroll_body)
			{
				ans+=parseInt(area.children[i].clientHeight);
				ans2=Math.max(ans2,area.children[i].clientHeight+area.children[i].offsetTop);
			}
		return Math.max(ans,ans2);
	}
	var all_flag=false;
	var chaju=0;
	var old_body_style;
	jry_wb_scroll_body.onmouseover=function()
	{
		if(parseInt(area.clientHeight)>=get_all_child_height())
			return;
		if(timer!=null)
			clearTimeout(timer);
		if(timer2!=null)
			clearInterval(timer2);
		if(timer3!=null)
			clearInterval(timer3);
		if(timer4!=null)
			clearInterval(timer4);	
		timer3=setInterval(function()
		{
			jry_wb_scroll_kuai.style.height=area.clientHeight/get_all_child_height()*parseInt(jry_wb_scroll_body.style.height);			
			jry_wb_scroll_kuai.style.top=Math.max(0,get_scrolly()/get_all_child_height()*parseInt(jry_wb_scroll_body.style.height));					
			jry_wb_scroll_body.style.opacity=Math.max(0,Math.min(parseFloat(jry_wb_scroll_body.style.opacity)+0.1,1));
			if(parseFloat(jry_wb_scroll_body.style.opacity)>=1)
			{
				clearInterval(timer3);
				timer3=null;
				return ;
			}
		},1);
	};
	jry_wb_scroll_body.onmouseout=function()
	{
		if(area.clientHeight>=get_all_child_height())
			return;
		if(timer!=null)
			clearTimeout(timer);
		if(timer2!=null)
			clearInterval(timer2);
		if(timer3!=null)
			clearInterval(timer3);
		if(timer4!=null)
			clearInterval(timer4);			
		timer=setTimeout(function()
		{		
			timer2=setInterval(function()
			{
				jry_wb_scroll_kuai.style.height=area.clientHeight/get_all_child_height()*parseInt(jry_wb_scroll_body.style.height);			
				jry_wb_scroll_kuai.style.top=Math.max(0,get_scrolly()/get_all_child_height()*parseInt(jry_wb_scroll_body.style.height));		
				if(all_flag)
					return;
				jry_wb_scroll_body.style.opacity=Math.max(0,Math.min(parseFloat(jry_wb_scroll_body.style.opacity)-0.1,1));
				if(parseFloat(jry_wb_scroll_body.style.opacity)<=0)
				{
					clearInterval(timer2);
					timer2=null;
					return ;
				}
			},20);
		},250);
	};
	function scrollto(y)
	{
		y=Math.max(0,Math.min(y,get_all_child_height()-area.clientHeight));
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
		for(var i=0,n=area.children.length;i<n;i++)
			if(area.children[i]!=jry_wb_scroll_body)
				return -parseInt(area.children[i].style.top||'0');
	}
	jry_wb_scroll_body.onmousewheel=area.onmousewheel=function(e)
	{
		if(area.clientHeight>=get_all_child_height())
			return;
		e=e||window.event;
		scrollto(get_scrolly()+(e.deltaY||e.detail*50));
		jry_wb_scroll_kuai.style.height=area.clientHeight/get_all_child_height()*parseInt(jry_wb_scroll_body.style.height);			
		jry_wb_scroll_kuai.style.top=Math.max(0,get_scrolly()/get_all_child_height()*parseInt(jry_wb_scroll_body.style.height));
		if(timer!=null)
			clearTimeout(timer);
		if(timer2!=null)
			clearInterval(timer2);
		if(timer3!=null)
			clearInterval(timer3);
		if(timer4!=null)
			clearInterval(timer4);			
		timer3=setInterval(function()
		{
			jry_wb_scroll_kuai.style.height=area.clientHeight/get_all_child_height()*parseInt(jry_wb_scroll_body.style.height);			
			jry_wb_scroll_kuai.style.top=Math.max(0,get_scrolly()/get_all_child_height()*parseInt(jry_wb_scroll_body.style.height));	
			jry_wb_scroll_body.style.opacity=Math.max(0,Math.min(parseFloat(jry_wb_scroll_body.style.opacity)+0.1,1));
			if(parseFloat(jry_wb_scroll_body.style.opacity)>=1)
			{
				clearInterval(timer3);
				timer3=null;
				return ;
			}
		},1);
		timer=setTimeout(function()
		{		
			timer2=setInterval(function()
			{
				jry_wb_scroll_kuai.style.height=area.clientHeight/get_all_child_height()*parseInt(jry_wb_scroll_body.style.height);			
				jry_wb_scroll_kuai.style.top=Math.max(0,get_scrolly()/get_all_child_height()*parseInt(jry_wb_scroll_body.style.height));		
				if(all_flag)
					return;
				jry_wb_scroll_body.style.opacity=Math.max(0,Math.min(parseFloat(jry_wb_scroll_body.style.opacity)-0.1,1));
				if(parseFloat(jry_wb_scroll_body.style.opacity)<=0)
				{
					clearInterval(timer2);
					timer2=null;
					return ;
				}
			},20);
		},1000);
	};
	jry_wb_scroll_kuai.onselectstart=function()
	{
		return false;
	};
	jry_wb_scroll_body.onclick=function(e)
	{
		if(area.clientHeight>=get_all_child_height())
			return;
		e=e||window.event;
		if(timer!=null)
			clearTimeout(timer);
		if(timer2!=null)
			clearInterval(timer2);
		if(timer3!=null)
			clearInterval(timer3);
		if(timer4!=null)
			clearInterval(timer4);		
		var mubiao=Math.max(0,Math.min((e.clientY-jry_wb_scroll_body.getBoundingClientRect().top)/document.body.clientHeight*get_all_child_height(),get_all_child_height()-area.clientHeight));;
		timer4=setInterval(function()
		{
			if(Math.abs(get_scrolly()-mubiao)<10)
			{
				clearInterval(timer4);
				timer4=null;
				return ;
			}
			scrollto(get_scrolly()+((mubiao-get_scrolly())/400)*50);
			jry_wb_scroll_kuai.style.height=area.clientHeight/get_all_child_height()*parseInt(jry_wb_scroll_body.style.height);			
			jry_wb_scroll_kuai.style.top=Math.max(0,get_scrolly()/get_all_child_height()*parseInt(jry_wb_scroll_body.style.height));
		},25);		
	};	
	if(area.addEventListener)area.addEventListener('DOMMouseScroll',area.onmousewheel,false);
	if(jry_wb_scroll_body.addEventListener)jry_wb_scroll_body.addEventListener('DOMMouseScroll',jry_wb_scroll_body.onmousewheel,false);
	jry_wb_add_onmousemove(function(e)
	{
		if(!all_flag)
			return ;
		if(area.clientHeight>=get_all_child_height())
			return;	
		e=e||window.event;
		scrollto((e.clientY-chaju)/area.clientHeight*get_all_child_height());
		jry_wb_scroll_kuai.style.height=area.clientHeight/get_all_child_height()*parseInt(jry_wb_scroll_body.style.height);			
		jry_wb_scroll_kuai.style.top=Math.max(0,get_scrolly()/get_all_child_height()*parseInt(jry_wb_scroll_body.style.height));
	});
	jry_wb_scroll_kuai.onmousedown=function(e)
	{
		document.body.style+='-webkit-user-select:none;-moz-user-select:none;-khtml-user-select: none;-ms-user-select: none;';
		all_flag=true;
		if(area.clientHeight>=get_all_child_height())
			return;
		if(timer!=null)
			clearTimeout(timer);
		if(timer2!=null)
			clearInterval(timer2);
		if(timer3!=null)
			clearInterval(timer3);
		if(timer4!=null)
			clearInterval(timer4);
		e=e||window.event;
		chaju=e.clientY-jry_wb_scroll_kuai.getBoundingClientRect().top;
		old_body_style=document.body.style;
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
		if(document.body.clientHeight==document.body.offsetHeight)
			return;
		if(timer!=null)
			clearTimeout(timer);
		if(timer2!=null)
			clearInterval(timer2);
		if(timer3!=null)
			clearInterval(timer3);
		if(timer4!=null)
			clearInterval(timer4);
		document.body.style=old_body_style;
	};	
	this.jry_wb_scroll_body=jry_wb_scroll_body;
}
jry_wb_add_load(function()
{
	var timer=null;/*鼠标离开*/
	var timer2=null;/*开始运动动画*/
	var timer3=null;/*结束运动动画*/
	var timer4=null;/*点击动画*/
	document.body.style.overflow='hidden';
	var jry_wb_scroll_body=document.createElement("div");document.body.appendChild(jry_wb_scroll_body);
	jry_wb_scroll_body.style.position='fixed';
	jry_wb_scroll_body.style.right='0';
	jry_wb_scroll_body.style.top=Math.max(0,document.getElementsByClassName('jry_wb_top_toolbar')[0]==undefined?0:document.getElementsByClassName('jry_wb_top_toolbar')[0].clientHeight-window.scrollY);;
	jry_wb_scroll_body.style.height=document.body.clientHeight-Math.max(0,document.getElementsByClassName('jry_wb_top_toolbar')[0]==undefined?0:document.getElementsByClassName('jry_wb_top_toolbar')[0].clientHeight-window.scrollY);
	jry_wb_scroll_body.style.opacity='0';
	jry_wb_scroll_body.classList.add('jry_wb_beautiful_scroll_body');
	var jry_wb_scroll_kuai=document.createElement("div");jry_wb_scroll_body.appendChild(jry_wb_scroll_kuai);
	jry_wb_scroll_kuai.style.height=document.body.clientHeight/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height);
	jry_wb_scroll_kuai.style.position='fixed';
	jry_wb_scroll_kuai.classList.add('jry_wb_beautiful_scroll_kuai');
	jry_wb_add_onresize(function()
	{
		jry_wb_scroll_body.style.top=Math.max(0,document.getElementsByClassName('jry_wb_top_toolbar')[0]==undefined?0:document.getElementsByClassName('jry_wb_top_toolbar')[0].clientHeight-window.scrollY);;
		jry_wb_scroll_body.style.height=document.body.clientHeight-Math.max(0,document.getElementsByClassName('jry_wb_top_toolbar')[0]==undefined?0:document.getElementsByClassName('jry_wb_top_toolbar')[0].clientHeight-window.scrollY);
	});
	jry_wb_scroll_body.onmouseover=function()
	{
		if(document.body.clientHeight==document.body.offsetHeight)
			return;
		if(timer!=null)
			clearTimeout(timer);
		if(timer2!=null)
			clearInterval(timer2);
		if(timer3!=null)
			clearInterval(timer3);		
		timer3=setInterval(function()
		{
			jry_wb_scroll_body.style.height=document.body.clientHeight-Math.max(0,document.getElementsByClassName('jry_wb_top_toolbar')[0]==undefined?0:document.getElementsByClassName('jry_wb_top_toolbar')[0].clientHeight-window.scrollY);
			jry_wb_scroll_kuai.style.height=document.body.clientHeight/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height);
			var right=parseInt(document.getElementById('jry_wb_left_button_up').style.right)+2;
			jry_wb_scroll_body.style.opacity=Math.max(0,Math.min(parseFloat(jry_wb_scroll_body.style.opacity)+0.1,1));
			if(right>Math.max(jry_wb_scroll_kuai.clientWidth,jry_wb_scroll_body.clientWidth))
			{
				clearInterval(timer3);
				timer3=null;
				return ;
			}
			document.getElementById('jry_wb_left_button_up').style.right=document.getElementById('jry_wb_left_button_bug').style.right=document.getElementById('jry_wb_left_button_down').style.right=right;if(document.getElementById('jry_wb_left_button_backgroundmusic_icon')!=null)document.getElementById('jry_wb_left_button_backgroundmusic_icon').style.right=right;			
		},1);
	};
	var all_flag=false;
	var chaju=0;
	var old_body_style;
	jry_wb_scroll_body.onmouseout=function()
	{
		if(document.body.clientHeight==document.body.offsetHeight)
			return;
		if(timer!=null)
			clearTimeout(timer);
		if(timer2!=null)
			clearInterval(timer2);
		if(timer3!=null)
			clearInterval(timer3);
		timer=setTimeout(function()
		{		
			timer2=setInterval(function()
			{
				if(all_flag)
					return;
				jry_wb_scroll_body.style.height=document.body.clientHeight-Math.max(0,document.getElementsByClassName('jry_wb_top_toolbar')[0]==undefined?0:document.getElementsByClassName('jry_wb_top_toolbar')[0].clientHeight-window.scrollY);
				jry_wb_scroll_kuai.style.height=document.body.clientHeight/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height);
				var right=parseInt(document.getElementById('jry_wb_left_button_up').style.right)-2;
				jry_wb_scroll_body.style.opacity=Math.max(0,Math.min(parseFloat(jry_wb_scroll_body.style.opacity)-0.1,1));
				if(right<=0&&parseFloat(jry_wb_scroll_body.style.opacity)<=0)
				{
					clearInterval(timer2);
					timer2=null;
					return ;
				}
				right=Math.max(0,right);
				document.getElementById('jry_wb_left_button_up').style.right=document.getElementById('jry_wb_left_button_bug').style.right=document.getElementById('jry_wb_left_button_down').style.right=right;if(document.getElementById('jry_wb_left_button_backgroundmusic_icon')!=null)document.getElementById('jry_wb_left_button_backgroundmusic_icon').style.right=right;			
			},20);
		},250);
	};
	jry_wb_scroll_body.onclick=function(e)
	{
		if(document.body.clientHeight==document.body.offsetHeight)
			return;
		e=e||window.event;
		if(timer!=null)
			clearTimeout(timer);
		if(timer2!=null)
			clearInterval(timer2);
		if(timer3!=null)
			clearInterval(timer3);
		if(timer4!=null)
			clearInterval(timer4);	
		var mubiao=e.clientY/document.body.clientHeight*document.body.offsetHeight;
		timer4=setInterval(function()
		{
			if(Math.abs(window.scrollY-mubiao)<100)
			{
				clearInterval(timer4);
				timer4=null;
				return ;
			}
			window.scrollTo(window.scrollX,window.scrollY+((mubiao-window.scrollY)/400)*50);
			jry_wb_scroll_body.style.height=document.body.clientHeight-Math.max(0,document.getElementsByClassName('jry_wb_top_toolbar')[0]==undefined?0:document.getElementsByClassName('jry_wb_top_toolbar')[0].clientHeight-window.scrollY);
			jry_wb_scroll_body.style.top=Math.max(0,document.getElementsByClassName('jry_wb_top_toolbar')[0]==undefined?0:document.getElementsByClassName('jry_wb_top_toolbar')[0].clientHeight-window.scrollY);;
			jry_wb_scroll_kuai.style.height=document.body.clientHeight/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height);
			jry_wb_scroll_kuai.style.top=Math.min(document.body.clientHeight-parseInt(jry_wb_scroll_kuai.style.height),Math.max(parseInt(jry_wb_scroll_body.style.top),window.scrollY/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height)));
		},25);		
	};
	jry_wb_add_onmousemove(function(e)
	{
		if(!all_flag)
			return ;
		if(document.body.clientHeight==document.body.offsetHeight)
			return;		
		e=e||window.event;
		window.scrollTo(window.scrollX,(e.clientY-chaju)/document.body.clientHeight*document.body.offsetHeight);
		jry_wb_scroll_body.style.height=document.body.clientHeight-Math.max(0,document.getElementsByClassName('jry_wb_top_toolbar')[0]==undefined?0:document.getElementsByClassName('jry_wb_top_toolbar')[0].clientHeight-window.scrollY);
		jry_wb_scroll_body.style.top=Math.max(0,document.getElementsByClassName('jry_wb_top_toolbar')[0]==undefined?0:document.getElementsByClassName('jry_wb_top_toolbar')[0].clientHeight-window.scrollY);;
		jry_wb_scroll_kuai.style.height=document.body.clientHeight/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height);
		jry_wb_scroll_kuai.style.top=Math.min(document.body.clientHeight-parseInt(jry_wb_scroll_kuai.style.height),Math.max(parseInt(jry_wb_scroll_body.style.top),window.scrollY/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height)));
	});
	jry_wb_scroll_kuai.onmousedown=function(e)
	{
		all_flag=true;
		if(document.body.clientHeight==document.body.offsetHeight)
			return;
		if(timer!=null)
			clearTimeout(timer);
		if(timer2!=null)
			clearInterval(timer2);
		if(timer3!=null)
			clearInterval(timer3);
		if(timer4!=null)
			clearInterval(timer4);
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
		if(document.body.clientHeight==document.body.offsetHeight)
			return;
		if(timer!=null)
			clearTimeout(timer);
		if(timer2!=null)
			clearInterval(timer2);
		if(timer3!=null)
			clearInterval(timer3);
		if(timer4!=null)
			clearInterval(timer4);
		document.body.style=old_body_style;
	};
	jry_wb_scroll_kuai.onselectstart=function()
	{
		return false;
	};
	window.onmousewheel=function(e)
	{
		if(document.body.clientHeight==document.body.offsetHeight||jry_wb_beautiful_scroll_run_flag)
			return;
		e=e||window.event;
		if(e!=null)
			window.scrollTo(window.scrollX,window.scrollY+(e.deltaY||e.detail*50));
		jry_wb_scroll_body.style.height=document.body.clientHeight-Math.max(0,document.getElementsByClassName('jry_wb_top_toolbar')[0]==undefined?0:document.getElementsByClassName('jry_wb_top_toolbar')[0].clientHeight-window.scrollY);
		jry_wb_scroll_body.style.top=Math.max(0,document.getElementsByClassName('jry_wb_top_toolbar')[0]==undefined?0:document.getElementsByClassName('jry_wb_top_toolbar')[0].clientHeight-window.scrollY);;
		jry_wb_scroll_kuai.style.height=document.body.clientHeight/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height);
		jry_wb_scroll_kuai.style.top=Math.min(document.body.clientHeight-parseInt(jry_wb_scroll_kuai.style.height),Math.max(parseInt(jry_wb_scroll_body.style.top),window.scrollY/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height)));
		if(timer!=null)
			clearTimeout(timer);
		if(timer2!=null)
			clearInterval(timer2);
		if(timer3!=null)
			clearInterval(timer3);
		if(timer4!=null)
			clearInterval(timer4);		
		timer3=setInterval(function()
		{
			jry_wb_scroll_body.style.height=document.body.clientHeight-Math.max(0,document.getElementsByClassName('jry_wb_top_toolbar')[0]==undefined?0:document.getElementsByClassName('jry_wb_top_toolbar')[0].clientHeight-window.scrollY);
			jry_wb_scroll_kuai.style.height=document.body.clientHeight/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height);
			var right=parseInt(document.getElementById('jry_wb_left_button_up').style.right)+2;
			jry_wb_scroll_body.style.opacity=Math.max(0,Math.min(parseFloat(jry_wb_scroll_body.style.opacity)+0.1,1));
			if(right>Math.max(jry_wb_scroll_kuai.clientWidth,jry_wb_scroll_body.clientWidth))
			{
				clearInterval(timer3);
				timer3=null;
				return ;
			}
			document.getElementById('jry_wb_left_button_up').style.right=document.getElementById('jry_wb_left_button_bug').style.right=document.getElementById('jry_wb_left_button_down').style.right=right;if(document.getElementById('jry_wb_left_button_backgroundmusic_icon')!=null)document.getElementById('jry_wb_left_button_backgroundmusic_icon').style.right=right;			
		},1);
		timer=setTimeout(function()
		{
			if(timer2!=null)
				clearInterval(timer2);
			if(timer3!=null)
				clearInterval(timer3);
			timer2=setInterval(function()
			{
				jry_wb_scroll_body.style.height=document.body.clientHeight-Math.max(0,document.getElementsByClassName('jry_wb_top_toolbar')[0]==undefined?0:document.getElementsByClassName('jry_wb_top_toolbar')[0].clientHeight-window.scrollY);
				jry_wb_scroll_kuai.style.height=document.body.clientHeight/document.body.offsetHeight*parseInt(jry_wb_scroll_body.style.height);
				var right=parseInt(document.getElementById('jry_wb_left_button_up').style.right)-2;
				jry_wb_scroll_body.style.opacity=Math.max(0,Math.min(parseFloat(jry_wb_scroll_body.style.opacity)-0.1,1));
				if(right<=0&&parseFloat(jry_wb_scroll_body.style.opacity)<=0)
				{
					clearInterval(timer2);
					timer2=null;
					return ;
				}
				right=Math.max(0,right);
				document.getElementById('jry_wb_left_button_up').style.right=document.getElementById('jry_wb_left_button_bug').style.right=document.getElementById('jry_wb_left_button_down').style.right=right;if(document.getElementById('jry_wb_left_button_backgroundmusic_icon')!=null)document.getElementById('jry_wb_left_button_backgroundmusic_icon').style.right=right;			
			},20);
			timer=null;
		},1000);
	};
	if(document.addEventListener)document.addEventListener('DOMMouseScroll',window.onmousewheel,false);
});