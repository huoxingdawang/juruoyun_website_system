function jry_wb_beautiful_music(audio,area,list,yuandi)
{
	audio.removeAttribute("controls");
	audio.next=false;
	var body=document.createElement("div") ;
	if(yuandi)
		audio.parentElement.insertBefore(body,audio);
	else
		area.appendChild(body);
	body.classList.add('jry_wb_beautiful_music_body');
	this.list_area = null;
	var bg_pic=document.createElement("div");body.appendChild(bg_pic);
	bg_pic.classList.add('picture');
	var bg_pic_jiao=document.createElement("div");body.appendChild(bg_pic_jiao);
	bg_pic_jiao.classList.add('picture_jiao','jry_wb_icon');
	var mode=document.createElement("div");body.appendChild(mode);
	mode.classList.add('picture_mode');	
	mode.innerHTML="一遍";
	var controls=document.createElement("div");body.appendChild(controls);
	controls.classList.add('controls');
	var start_button=document.createElement("div");controls.appendChild(start_button);
	start_button.classList.add('start_button','jry_wb_icon','jry_wb_icon_start');
	start_button.onclick=function() 
	{ 
		if(audio.ended)
			audio.currentTime = 0;
		if(audio.paused)
			audio.play();
		else
			audio.pause();
	} ;
	var progress_bar= new jry_wb_progress_bar(controls,"65%",audio.currentTime/audio.duration,parseInt(audio.currentTime)+"/"+parseInt(audio.duration),
		function(x)
		{
			audio.currentTime = x*audio.duration;
		},
		function(x)
		{
			progress_bar.span.innerText = parseInt(x*audio.duration);
		},
		"progress_bar",'',true,false,'ok'
	);
	progress_bar.progress_body.style.height="20px";
	var volume_button = document.createElement("div");controls.appendChild(volume_button);
	volume_button.classList.add('volume_button','jry_wb_icon','jry_wb_icon_sound_on');
	volume_button.onclick = function()
	{
		if(audio.muted)
		{
			audio.muted = false;
			volume_button.classList.remove('jry_wb_icon_sound_off');
			volume_button.classList.add('jry_wb_icon_sound_on');
		}
		else
		{
			audio.muted = true;
			volume_button.classList.remove('jry_wb_icon_sound_on');
			volume_button.classList.add('jry_wb_icon_sound_off');
		}
	};
	var vioce_bar= new jry_wb_progress_bar(controls,"10%",audio.volume,parseInt(audio.volume*100)+"%",
		function(x)
		{
			if(x!=0)
			{
				audio.muted = false;
				volume_button.classList.remove('jry_wb_icon_sound_off');
				volume_button.classList.add('jry_wb_icon_sound_on');
			}
			vioce_bar.update(audio.volume = x,parseInt(audio.volume*1000)/10+"%");
		},
		function(x)
		{
			vioce_bar.span.innerText = parseInt(x*100)+"%";
		},
		"voice_bar",'',false,true
	);
	vioce_bar.set_mouse_wheel(function(delta){vioce_bar.update(audio.volume+=delta*0.001,parseInt(audio.volume*1000)/10+"%");vioce_bar.span.innerText = parseInt(audio.volume*1000)/10+"%";});
	vioce_bar.progress_body.style.height="100px";
	var old_onmouseover=vioce_bar.progress_body.onmouseover;
	var old_onmouseout=vioce_bar.progress_body.onmouseout;
	vioce_bar.progress_body.onmouseover=volume_button.onmouseover = function()
	{
		vioce_bar.progress_body.style.display="unset";
		old_onmouseover();
	};
	vioce_bar.progress_body.onmouseout=volume_button.onmouseout = function()
	{
		vioce_bar.progress_body.style.display="none";
		old_onmouseout();
	};
	this.reply_count = 0;
	var cycle_button = document.createElement("div");controls.appendChild(cycle_button);
	cycle_button.classList.add("cycle_button","jry_wb_icon","jry_wb_icon_error");
	cycle_button.onclick=()=>
	{
		this.reply_count++;
		this.reply_count%=4;
		this.cycle_button_update();
	};
	if(list==false)
		body.style.height="100px";
	else
	{ 
		this.list_area=document.createElement("div");body.appendChild(this.list_area);
		this.list_area.className='list_area';
		this.list_area.appendChild(this.lyric_area = document.createElement("div"));
		this.lyric_area.classList.add('lyric');	
		this.lyric_area.style.display='none';
		var lyric_button = document.createElement("div");controls.appendChild(lyric_button);
		lyric_button.classList.add("lyric_button","jry_wb_icon","jry_wb_icon_lyric");
		lyric_button.onclick=()=>
		{
			this.reply_count++;
			this.reply_count%=4;
			this.cycle_button_update();
		};
		lyric_button.onclick=()=>
		{
			if(this.lyric_area.style.display=='')
			{
				this.lyric_area.style.display='none';
				if(typeof this.song_list_area!='undefined')
					this.song_list_area.style.display='',lyric_button.classList.add('jry_wb_icon_lyric'),lyric_button.classList.remove('jry_wb_icon_songlist');			
				this.beautiful_scroll.scrollto(0,0);
			}
			else
			{
				this.lyric_area.style.display='';
				if(typeof this.song_list_area!='undefined')
					this.song_list_area.style.display='none',lyric_button.classList.remove('jry_wb_icon_lyric'),lyric_button.classList.add('jry_wb_icon_songlist');
				this.beautiful_scroll.scrollto(0,(this.last_lyric_highlight==undefined?0:this.last_lyric_highlight.offsetTop)-this.list_area.clientHeight/3);
			}
		};
	} 
	this.cycle_button_update=()=>
	{
		if(this.reply_count==0)
			cycle_button.classList.remove('jry_wb_icon_circle1','jry_wb_icon_circle_all','jry_wb_icon_random'),cycle_button.classList.add('jry_wb_icon_error'),mode.innerHTML="一遍";
		else if(this.reply_count==1)
			cycle_button.classList.remove('jry_wb_icon_error','jry_wb_icon_circle_all','jry_wb_icon_random'),cycle_button.classList.add('jry_wb_icon_circle1'),mode.innerHTML="单曲";
		else if(this.reply_count==2)
			cycle_button.classList.remove('jry_wb_icon_circle1','jry_wb_icon_error','jry_wb_icon_random'),cycle_button.classList.add('jry_wb_icon_circle_all'),mode.innerHTML="全部";
		else if(this.reply_count==3)
			cycle_button.classList.remove('jry_wb_icon_circle1','jry_wb_icon_circle_all','jry_wb_icon_error'),cycle_button.classList.add('jry_wb_icon_random'),mode.innerHTML="随机";
	};	
	audio.onprogress=function()
	{
		var loaded=0;
		for(var i=0;i<audio.buffered.length;i++)
			if(audio.buffered.start(i)<audio.currentTime&&audio.currentTime<audio.buffered.end(i))
				loaded=audio.buffered.end(i);
		progress_bar.update_buttom(loaded/audio.duration);
	};
	audio.ontimeupdate=()=>
	{
		audio.onprogress();
		progress_bar.update(audio.currentTime/audio.duration,parseInt(audio.currentTime)+"/"+parseInt(audio.duration));
		var song=null,lyric=null;
		if(this.song_list!=null)
			if((song=this.song_list.find(function(a){return audio.src==a.music_url}))!=null&&song.lyric!=null&&song.lyric.length!=undefined)
				for(var i=0,lyric=song.lyric[i];i<song.lyric.length&&(i+1==song.lyric.length||song.lyric[i+1].t<audio.currentTime);i++,lyric=song.lyric[i]);
		if(lyric!=undefined&&lyric.d!=undefined)
		{
			if(lyric.d.className.includes('active'))
				return;
			if(this.last_lyric_highlight!=undefined)
				this.last_lyric_highlight.classList.remove('active');
			(this.last_lyric_highlight=lyric.d).classList.add('active');
			if(this.lyric_area.style.display=='')
				this.beautiful_scroll.scrollto(0,(this.last_lyric_highlight.offsetTop)-this.list_area.clientHeight/3);
		}
	};
	audio.onplay=function()
	{
		start_button.classList.remove('jry_wb_icon_start');
		start_button.classList.add("jry_wb_icon_pause");
		jry_wb_midia_control_all.onplay(audio);
		if(this.song_list!=null)
		{
			var i=parseInt(this.lastone.value);
			this.set_background_picture(this.song_list[i].pic_url,this.song_list[i].type);		
		}
	};
	audio.onabort=()=>
	{
		var reload_cnt=0;
		setTimeout(()=>
		{
			if(audio.readyState==0)
			{
				var timer=setInterval(()=>
				{
					if(audio.readyState==0)
					{
						reload_cnt++;
						if(reload_cnt>=2)
						{
							audio.next=true;
							audio.onpause();
							reload_cnt=0;
						}
						else
							audio.load();
					}
					else
					{
						audio.play();
						reload_cnt=0;
						clearInterval(timer);
					}
				},1000);
			}
		},500);
	};	
	audio.onpause=()=>
	{
		start_button.classList.remove('jry_wb_icon_pause');
		start_button.classList.add("jry_wb_icon_start");
		if(this.reply_count==1&&(audio.ended||audio.next))
			audio.play();
		if(this.reply_count==2&&(audio.ended||audio.next)&&(this.song_list!=null))
		{
			if(this.lastone.nextElementSibling!=null)
			{
				var target = this.lastone.nextElementSibling;
				var i = parseInt(target.value);
				this.set_background_picture(this.song_list[i].pic_url,this.song_list[i].type);
				this.type=this.song_list[i].type==undefined?'':this.song_list[i].type;
				this.audio.src = this.song_list[i].music_url;
				this.audio.play();
				if(this.lastone!=null)
					this.lastone.classList.remove('active');
				target.classList.add('active');
				this.lyric_area.innerHTML='';
				this.show_lyric(this.song_list[i].lyric);
				this.lastone = target;
			}
			else
			{
				var target = this.lastone.parentNode.children[0];
				var i = parseInt(target.value);
				this.set_background_picture(this.song_list[i].pic_url,this.song_list[i].type);
				this.type=this.song_list[i].type==undefined?'':this.song_list[i].type;
				this.audio.src = this.song_list[i].music_url;
				this.audio.play();
				if(this.lastone!=null)
					this.lastone.classList.remove('active');
				target.classList.add('active');
				this.lyric_area.innerHTML='';
				this.show_lyric(this.song_list[i].lyric);					
				this.lastone = target;				
			}
		}
		if(this.reply_count==3&&(audio.ended||audio.next)&&(this.song_list!=null))
		{
			var target = this.lastone.parentNode.children[parseInt(Math.random()*this.lastone.parentNode.children.length)];
			var i = parseInt(target.value);
			this.set_background_picture(this.song_list[i].pic_url,this.song_list[i].type);
			this.type=this.song_list[i].type==undefined?'':this.song_list[i].type;
			this.audio.src = this.song_list[i].music_url;
			this.audio.play();
			if(this.lastone!=null)
				this.lastone.classList.remove('active');
			target.classList.add('active');
			this.lyric_area.innerHTML='';
			this.show_lyric(this.song_list[i].lyric);			
			this.lastone = target;				
		}
		jry_wb_midia_control_all.onpause(audio);
		audio.next=false;
	};
	window.onresize();
	this.body = body;
	this.audio = audio;
	this.bg_pic_jiao = bg_pic_jiao;
	this.bg_pic = bg_pic;
	this.cycle_button = cycle_button;
	this.mode = mode;
	this.vioce_bar = vioce_bar;
}
jry_wb_beautiful_music.prototype.update_volume_bar = function()
{
	this.vioce_bar.update(this.audio.volume,parseInt(this.audio.volume*100)+"%");
};
jry_wb_beautiful_music.prototype.do_reply_count = function(reply_count)
{
	if(reply_count==null)
		return this.reply_count;
	this.reply_count = reply_count;
	this.reply_count%=4;
	this.cycle_button_update();
};
jry_wb_beautiful_music.prototype.set_background_picture=function(url,type)
{
	if(type=='qq')
		this.bg_pic_jiao.classList.remove('jry_wb_icon_163_music','jry_wb_icon_music'),this.bg_pic_jiao.classList.add('jry_wb_icon_qq_music'),this.bg_pic_jiao.style.color="#02B053",this.bg_pic_jiao.style.background="#F8C913",this.bg_pic_jiao.style.borderRadius='10px';
	else if(type=='163'||type=='wangyi')
		this.bg_pic_jiao.classList.remove('jry_wb_icon_qq_music','jry_wb_icon_music'),this.bg_pic_jiao.classList.add('jry_wb_icon_163_music'),this.bg_pic_jiao.style.color="#D81E06",this.bg_pic_jiao.style.background="",this.bg_pic_jiao.style.borderRadius=0;
	else
		this.bg_pic_jiao.classList.remove('jry_wb_icon_163_music','jry_wb_icon_qq_music'),this.bg_pic_jiao.classList.add('jry_wb_icon_music'),this.bg_pic_jiao.style.color="#0F88EB",this.bg_pic_jiao.style.background="",this.bg_pic_jiao.style.borderRadius=0;
	this.bg_pic.style='background:url("'+(url==''?'':url)+'")';
	this.bg_pic.style.backgroundSize='cover';
};
jry_wb_beautiful_music.prototype.show_lyric=function(lyric)
{
	if(typeof lyric!='undefined')
		for(let j=0,m=lyric.length;j<m;j++)
			if(lyric[j].w!=''&&typeof lyric[j].w!='undefined')
			{
				var one=document.createElement("div");this.lyric_area.appendChild(one);
				one.classList.add('one');
				one.innerHTML=lyric[j].w;
				one.onclick=()=>{this.audio.currentTime=lyric[j].t};
				lyric[j].d=one;
			}
};
jry_wb_beautiful_music.prototype.push_song_list=function(list,highlighturl)
{
	if(list!=null)
		this.song_list = list;
	if(this.song_list==null)
		return false;
	if(this.list_area==null)
		return false;
	if(this.song_list_area==undefined)
	{
		this.list_area.appendChild(this.song_list_area = document.createElement("div"));
		this.song_list_area.classList.add('song');
	}
	else
		this.song_list_area.innerHTML='';
	
	
	
	for(var i = 0,n = this.song_list.length;i<n;i++)
	{
		var one = document.createElement("div");this.song_list_area.appendChild(one);
		one.classList.add('one');
		one.innerHTML = this.song_list[i].name;
		one.value=i;
		one.onclick=(event)=>
		{
			if (!event)
				var event  =  window.event;
			var i = parseInt(event.target.value);			
			this.set_background_picture(this.song_list[i].pic_url,this.song_list[i].type);
			this.audio.src = this.song_list[i].music_url;
			this.audio.play();
			if(this.lastone!=null)
				this.lastone.classList.remove('active');
			event.target.classList.add('active');			
			this.lastone = event.target;
			this.type=this.song_list[i].type==undefined?'':this.song_list[i].type;
			this.lyric_area.innerHTML='';
			this.show_lyric(this.song_list[i].lyric);	
		};
		if(highlighturl==this.song_list[i].music_url&&highlighturl!=undefined&&this.song_list[i].music_url!=undefined)
		{
			one.classList.add('active'),this.lastone = one;
			this.lyric_area.innerHTML='';
			this.show_lyric(this.song_list[i].lyric);			
		}
	}
	if(this.beautiful_scroll==null)
		this.beautiful_scroll=new jry_wb_beautiful_scroll(this.list_area);
};