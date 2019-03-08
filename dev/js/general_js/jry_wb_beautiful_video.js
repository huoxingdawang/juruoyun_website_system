function jry_wb_beautiful_video(video)
{
	var video_body = document.createElement("div");video.parentElement.insertBefore(video_body,video);
	video.parentElement.removeChild(video);
	video_body.appendChild(video);
	video_body.classList.add("jry_wb_beautiful_video_body");
	yuanshi_width=video.style.width==''?document.defaultView.getComputedStyle(video,null)['width']:video.style.width;
	yuanshi_width=(yuanshi_width==''?'100%':yuanshi_width);
	video_body.style.width='yuanshi_width';
	video.style.width="100%";
	video.setAttribute("controls","");
	if(jry_wb_test_is_pc())
	{
		video.removeAttribute("controls");
		var buttom_bar = document.createElement("div");video_body.appendChild(buttom_bar);
		buttom_bar.classList.add("jry_wb_beautiful_video_buttom_bar");
		buttom_bar.style.display='none';
		var start_button = document.createElement("div");buttom_bar.appendChild(start_button);
		start_button.classList.add("jry_wb_beautiful_video_button","jry_wb_icon","jry_wb_icon_bofang");
		var progress_bar = new jry_wb_progress_bar(buttom_bar,"50%",video.currentTime/video.duration,parseInt(video.currentTime)+"/"+parseInt(video.duration),
			function(x)
			{
				video.currentTime = x*video.duration;
			},
			function(x)
			{
				progress_bar.span.innerText = parseInt(x*video.duration);
			},
			"jry_wb_beautiful_video_progress_bar",'',true,false,'ok'
		);
		var vioce_bar = new jry_wb_progress_bar(buttom_bar,"10%",video.volume,parseInt(video.volume*100)+"%",
			function(x)
			{
				vioce_bar.update(video.volume = x,parseInt(video.volume*100)+"%");
			},
			function(x)
			{
				vioce_bar.span.innerText = parseInt(x*100)+"%";
			},
			"jry_wb_beautiful_video_voice_bar",'',false
		);
		var full_button = document.createElement("div");buttom_bar.appendChild(full_button);
		full_button.classList.add("jry_wb_beautiful_video_button","jry_wb_icon");
		if(document.webkitIsFullScreen)
			full_button.classList.add("jry_wb_icon_quitquanping");
		else
			full_button.classList.add("jry_wb_icon_quanping");
		var ratio=video_body.clientWidth*video.videoHeight/video.videoWidth;
		var timer=setInterval(function()
		{
			ratio=video.videoHeight/video.videoWidth;
			if(!isNaN(ratio))
			{
				clearInterval(timer);
				buttom_bar.style.width=video_body.clientWidth;
				video_body.style.height=video_body.clientWidth*ratio;
				buttom_bar.style.top=video_body.clientHeight-buttom_bar.clientHeight;
				
			}
		},400);
		
		var timer2=null;
		video_body.onmousemove=function()
		{	
			buttom_bar.style.display='';
			buttom_bar.style.opacity=1;
			if(document.webkitIsFullScreen)
			{
				video_body.style.width=document.body.clientWidth;
				video_body.style.height=document.body.clientHeight;
				buttom_bar.style.top=video_body.clientHeight-buttom_bar.clientHeight;
			}
			else
			{
				buttom_bar.style.width=video_body.clientWidth;
				video_body.style.height=video_body.clientWidth*ratio;
				buttom_bar.style.top=video_body.clientHeight-buttom_bar.clientHeight;
			}			
		};
		video_body.onmouseover = function()
		{
			buttom_bar.style.display='';
			buttom_bar.style.opacity=1;
			if(timer2!=null)
				clearInterval(timer2),timer2=null;	
			timer2=setInterval(function()
			{
				ratio=video.videoHeight/video.videoWidth;
				if(parseFloat(buttom_bar.style.opacity)<=0.01)
				{
					clearInterval(timer2);
					timer2=null;
					buttom_bar.style.display='none';	
				}
				else
					buttom_bar.style.opacity=parseFloat(buttom_bar.style.opacity)-0.02;
			},100);			
			if(document.webkitIsFullScreen)
			{
				video_body.style.width=document.body.clientWidth;
				video_body.style.height=document.body.clientHeight;
				buttom_bar.style.top=video_body.clientHeight-buttom_bar.clientHeight;
			}
			else
			{
				buttom_bar.style.width=video_body.clientWidth;
				video_body.style.height=video_body.clientWidth*ratio;
				buttom_bar.style.top=video_body.clientHeight-buttom_bar.clientHeight;
			}
			video.removeAttribute("controls");
			if(video.ended)
				start_button.classList.add("jry_wb_icon_bofang"),start_button.classList.remove("jry_wb_icon_zantingtingzhi");
		};		
		video_body.onmouseout = function()
		{
			if(timer2!=null)
				clearInterval(timer2),timer2=null;				
			buttom_bar.style.display='none';
		};
		video_ontimeupdate=video.ontimeupdate;
		video.ontimeupdate = function()
		{
			video.onprogress();
			if( typeof video_ontimeupdate=="function")
				video_ontimeupdate(video);
			progress_bar.update(video.currentTime/video.duration,parseInt(video.currentTime)+"/"+parseInt(video.duration));
		};
		video.onprogress=function()
		{
			var loaded=0;
			for(var i=0;i<video.buffered.length;i++)
				if(video.buffered.start(i)<video.currentTime&&video.currentTime<video.buffered.end(i))
					loaded=video.buffered.end(i);
			progress_bar.update_buttom(loaded/video.duration);
		};
		video.onclick = start_button.onclick = function()
		{
			if(video.ended)
				video.currentTime = 0;
			if(video.paused)
				video.play();
			else
				video.pause();
		};
		video.onplay = function()
		{
			start_button.classList.add("jry_wb_icon_zantingtingzhi");start_button.classList.remove("jry_wb_icon_bofang");
			jry_wb_midia_control_all.onplay(video);
		};
		video.onpause = function()
		{
			start_button.classList.remove("jry_wb_icon_zantingtingzhi");start_button.classList.add("jry_wb_icon_bofang");
			jry_wb_midia_control_all.onpause(video);
		};

		full_button.onclick=function()
		{
			if(document.webkitIsFullScreen)
			{
				full_button.classList.add("jry_wb_icon_quanping");
				full_button.classList.remove("jry_wb_icon_quitquanping");
				jry_wb_exit_full_screen();
				video_body.style.width=yuanshi_width;
				buttom_bar.style.width=video_body.clientWidth;
				video_body.style.height=video_body.clientWidth*ratio;
				buttom_bar.style.top=video_body.clientHeight-buttom_bar.clientHeight;				
				video_body.style.background="";
				video_body.style.position='';
				video.style.position='';
				buttom_bar.style.position='';				
				video_body.style.zIndex='';
				video.style.zIndex='';
				buttom_bar.style.zIndex='';
			}
			else
			{
				full_button.classList.add("jry_wb_icon_quitquanping");
				full_button.classList.remove("jry_wb_icon_quanping");
				jry_wb_launch_full_screen(document.documentElement);
				video_body.style.position='fixed';
				video_body.style.top=0;
				video_body.style.left=0;
				video_body.style.width=document.body.clientWidth;
				video_body.style.height=document.body.clientHeight;
				buttom_bar.style.width=video_body.clientWidth;
				buttom_bar.style.top=video_body.clientHeight-buttom_bar.clientHeight;		
				video_body.style.background="#000";
				video_body.style.zIndex=100000;
				video.style.zIndex=100000;
				buttom_bar.style.zIndex=100000;
			}
		};
		document.addEventListener('webkitfullscreenchange',function()
		{
			if(document.webkitIsFullScreen)
			{
			}
			else
			{		
				full_button.classList.add("jry_wb_icon_quanping");
				full_button.classList.remove("jry_wb_icon_quitquanping");
				jry_wb_exit_full_screen();
				video_body.style.width=yuanshi_width;
				buttom_bar.style.width=video_body.clientWidth;
				video_body.style.height=video_body.clientWidth*ratio;
				buttom_bar.style.top=video_body.clientHeight-buttom_bar.clientHeight;				
				video_body.style.background="";
				video_body.style.position='';
				video.style.position='';
				buttom_bar.style.position='';				
				video_body.style.zIndex='';
				video.style.zIndex='';
				buttom_bar.style.zIndex='';		
			}
		});		
		
	}
	this.body = video_body;
	this.video = video;
}
jry_wb_beautiful_video.prototype.push_tanmu = function(text,top,type,color)
{
	var danmu_body = document.createElement("div");this.video.after(danmu_body);
	if(type=="up_down")
	{
		danmu_body.style.left = top;
		
		danmu_body.classList.add("jry_wb_beautiful_video_danmu_up_down");
	}
	else
	{
		danmu_body.style.top = top;
		danmu_body.classList.add("jry_wb_beautiful_video_danmu_left_right");
	}
	danmu_body.innerHTML = text;
	danmu_body.style.color = color;
	setTimeout(()=>{this.body.removeChild(danmu_body);},7000);
};
