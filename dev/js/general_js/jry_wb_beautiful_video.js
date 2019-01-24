function jry_wb_beautiful_video(video_old)
{
	var video_bigbuf={'x':0,'y':0};
	var video_body = document.createElement("div");video_old.parentElement.insertBefore(video_body,video_old);
	video_body.classList.add("jry_wb_beautiful_video_body");
	video_bigbuf.x = video_body.style.width = video_old.style.width==''?document.defaultView.getComputedStyle(video_old,null)['width']:video_old.style.width;
	var video = video_old;
	video_old.parentElement.removeChild(video_old);video_body.appendChild(video);
	video_old = null;
	video.style.width="100%";
	video.setAttribute("controls","");
	if(jry_wb_test_is_pc())
	{
		video.removeAttribute("controls");
		var buttom_bar = document.createElement("div");video_body.appendChild(buttom_bar);
		buttom_bar.classList.add("jry_wb_beautiful_video_buttom_bar");
		buttom_bar.style.display='none';
		var start_button = document.createElement("div");buttom_bar.appendChild(start_button);
		start_button.classList.add("jry_wb_beautiful_video_button","iconfont","icon-bofang");
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
		full_button.classList.add("jry_wb_beautiful_video_button","iconfont");
		if(document.webkitIsFullScreen)
		{
			full_button.classList.add("icon-quitquanping");
			full_button.onclick = function()
			{
				video_body.style.width = __video_bigbuf.x;
				video_body.style.height = __video_bigbuf.y;	
				document.webkitCancelFullScreen();
			}
		}
		else
		{
			full_button.classList.add("icon-quanping");
			full_button.onclick = function()
			{
				__video_bigbuf.x = video_body.style.width;
				__video_bigbuf.y = video_body.style.height;
				video.webkitRequestFullScreen();
			}	
		}
		document.addEventListener('webkitfullscreenchange',function(){
			video_body.onresize();
			full_button.className="jry_wb_beautiful_video_button fa ";
			if(document.webkitIsFullScreen)
			{
				full_button.classList.remove("icon-quanping");full_button.classList.add("icon-quitquanping");
				full_button.onclick = function()
				{
					document.webkitCancelFullScreen();
				};
			}
			else
			{
				video_body.style.width = __video_bigbuf.x;
				video_body.style.height = __video_bigbuf.y;			
				full_button.classList.add("icon-quanping");full_button.classList.remove("icon-quitquanping");
				full_button.onclick = function()
				{
					video.webkitRequestFullScreen();
				};
			}
		});
		video_body.onmouseover = function()
		{
			buttom_bar.style.display='';
			video_body.style.width = video_bigbuf.x;
			video_body.onresize();
			video.removeAttribute("controls");
			if(!document.webkitIsFullScreen)
				return ;
			if(video.ended)
				start_button.classList.add("jry_wb_beautiful_video_button","iconfont","icon-bofang");
		};
		video_body.onmouseout = function()
		{
			buttom_bar.style.display='none';
		};
		video_ontimeupdate = video.ontimeupdate;
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
			start_button.classList.add("icon-zantingtingzhi");start_button.classList.remove("icon-bofang");
			jry_wb_midia_control_all.onplay(video);
		};
		video.onpause = function()
		{
			start_button.classList.remove("icon-zantingtingzhi");start_button.classList.add("icon-bofang");
			jry_wb_midia_control_all.onpause(video);
		};
		video.onload = video_body.onresize = video.onresize = function()
		{
			var paddingright = document.defaultView.getComputedStyle(buttom_bar,null)['paddingRight'];paddingright = parseInt(paddingright==""?"0":paddingright);
			var paddingleft = document.defaultView.getComputedStyle(buttom_bar,null)['paddingLeft'];paddingleft = parseInt(paddingleft==""?"0":paddingleft);
			var width = Math.min(video.scrollWidth,video.videoWidth/video.videoHeight*video.scrollHeight);
			var hight = video.videoHeight/video.videoWidth*width;
			buttom_bar.style.top = hight-buttom_bar.scrollHeight;
			buttom_bar.style.width = width-paddingleft-paddingright;
			video_body.style.height = hight;
			video_body.style.width = width;
			window.onresize();
		};
		video_body.onmouseover();
	}
	video_body.style.width = video_bigbuf.x;
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
