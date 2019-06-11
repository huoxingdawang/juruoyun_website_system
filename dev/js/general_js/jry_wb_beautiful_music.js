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
	if(list==false)
		body.style.height="100px";
	else
	{ 
		this.list_area=document.createElement("div");body.appendChild(this.list_area);
		this.list_area.className='jry_wb_beautiful_music_list_area'
	} 
	var bg_pic=document.createElement("div");body.appendChild(bg_pic);
	bg_pic.classList.add('jry_wb_beautiful_music_picture');
	var bg_pic_jiao=document.createElement("div");body.appendChild(bg_pic_jiao);
	bg_pic_jiao.classList.add('jry_wb_beautiful_music_picture_jiao','jry_wb_icon');
	var mode=document.createElement("div");body.appendChild(mode);
	mode.classList.add('jry_wb_beautiful_music_picture_mode');	
	mode.innerHTML="一遍";
	var controls=document.createElement("div");body.appendChild(controls);
	controls.classList.add('jry_wb_beautiful_music_controls');
	var start_button=document.createElement("div");controls.appendChild(start_button);
	start_button.classList.add('jry_wb_beautiful_music_start_button','jry_wb_icon','jry_wb_icon_bofang');
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
		"jry_wb_beautiful_music_progress_bar",'',true,false,'ok'
	);
	progress_bar.progress_body.style.height="20px";
	var volume_button = document.createElement("div");controls.appendChild(volume_button);
	volume_button.classList.add('jry_beautiful_music_volume_button','jry_wb_icon','jry_wb_icon_shengyin');
	volume_button.onclick = function()
	{
		if(audio.muted)
		{
			audio.muted = false;
			volume_button.classList.remove('jry_wb_icon_jingyin');
			volume_button.classList.add('jry_wb_icon_shengyin');
		}
		else
		{
			audio.muted = true;
			volume_button.classList.remove('jry_wb_icon_shengyin');
			volume_button.classList.add('jry_wb_icon_jingyin');
		}
	};
	var vioce_bar= new jry_wb_progress_bar(controls,"10%",audio.volume,parseInt(audio.volume*100)+"%",
		function(x)
		{
			if(x!=0)
			{
				audio.muted = false;
				volume_button.classList.remove('jry_wb_icon_jingyin');
				volume_button.classList.add('jry_wb_icon_shengyin');
			}
			vioce_bar.update(audio.volume = x,parseInt(audio.volume*1000)/10+"%");
		},
		function(x)
		{
			vioce_bar.span.innerText = parseInt(x*100)+"%";
		},
		"jry_wb_beautiful_music_voice_bar",'',false,true
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
	cycle_button.classList.add("jry_beautiful_music_cycle_button","jry_wb_icon","jry_wb_icon_cuowu");
	cycle_button.onclick=()=>
	{
		this.reply_count++;
		this.reply_count%=4;
		this.cycle_button_update();
	};
	this.cycle_button_update=()=>
	{
		if(this.reply_count==0)
			cycle_button.classList.remove('jry_wb_icon_danquxunhuan','jry_wb_icon_quanbuxunhuan','jry_wb_icon_-suiji'),cycle_button.classList.add('jry_wb_icon_cuowu'),mode.innerHTML="一遍";
		else if(this.reply_count==1)
			cycle_button.classList.remove('jry_wb_icon_cuowu','jry_wb_icon_quanbuxunhuan','jry_wb_icon_-suiji'),cycle_button.classList.add('jry_wb_icon_danquxunhuan'),mode.innerHTML="单曲";
		else if(this.reply_count==2)
			cycle_button.classList.remove('jry_wb_icon_danquxunhuan','jry_wb_icon_cuowu','jry_wb_icon_-suiji'),cycle_button.classList.add('jry_wb_icon_quanbuxunhuan'),mode.innerHTML="全部";
		else if(this.reply_count==3)
			cycle_button.classList.remove('jry_wb_icon_danquxunhuan','jry_wb_icon_quanbuxunhuan','jry_wb_icon_cuowu'),cycle_button.classList.add('jry_wb_icon_-suiji'),mode.innerHTML="随机";
	};
	audio.onprogress=function()
	{
		var loaded=0;
		for(var i=0;i<audio.buffered.length;i++)
			if(audio.buffered.start(i)<audio.currentTime&&audio.currentTime<audio.buffered.end(i))
				loaded=audio.buffered.end(i);
		progress_bar.update_buttom(loaded/audio.duration);
	};
	audio.ontimeupdate = function()
	{
		audio.onprogress();
		progress_bar.update(audio.currentTime/audio.duration,parseInt(audio.currentTime)+"/"+parseInt(audio.duration));
	};
	audio.onplay = function()
	{
		/*bg_pic.className="jry_wb_beautiful_music_picture";*/
		start_button.classList.remove('jry_wb_icon_bofang');
		start_button.classList.add("jry_wb_icon_zantingtingzhi");
		jry_wb_midia_control_all.onplay(audio);
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
		/*bg_pic.className="jry_wb_beautiful_music_picture";*/
		start_button.classList.remove('jry_wb_icon_zantingtingzhi');
		start_button.classList.add("jry_wb_icon_bofang");
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
		this.bg_pic_jiao.classList.remove('jry_wb_icon_wangyiyunyinle','jry_wb_icon_yinyue'),this.bg_pic_jiao.classList.add('jry_wb_icon_qq_yinyue_music_'),this.bg_pic_jiao.style.color="#02B053",this.bg_pic_jiao.style.background="#F8C913",this.bg_pic_jiao.style.borderRadius='10px';
	else if(type=='163'||type=='wangyi')
		this.bg_pic_jiao.classList.remove('jry_wb_icon_qq_yinyue_music_','jry_wb_icon_yinyue'),this.bg_pic_jiao.classList.add('jry_wb_icon_wangyiyunyinle'),this.bg_pic_jiao.style.color="#D81E06",this.bg_pic_jiao.style.background="",this.bg_pic_jiao.style.borderRadius=0;
	else
		this.bg_pic_jiao.classList.remove('jry_wb_icon_wangyiyunyinle','jry_wb_icon_qq_yinyue_music_'),this.bg_pic_jiao.classList.add('jry_wb_icon_yinyue'),this.bg_pic_jiao.style.color="#0F88EB",this.bg_pic_jiao.style.background="",this.bg_pic_jiao.style.borderRadius=0;
	this.bg_pic.style='background:url("'+(url==''?'':url)+'")';
	this.bg_pic.style.backgroundSize='cover';
};
jry_wb_beautiful_music.prototype.push_song_list = function(list,highlighturl)
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
		this.song_list_area.classList.add('jry_wb_beautiful_music_list_area_song');
	}
	else
		this.song_list_area.innerHTML='';
	for( var i = 0,n = this.song_list.length;i<n;i++)
	{
		var one = document.createElement("div");this.song_list_area.appendChild(one);
		one.classList.add('jry_wb_beautiful_music_list_area_song_one');
		one.innerHTML = this.song_list[i].name;
		one.value = i;
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
			if(this.song_list[i].mid=="000oxiWq0t7aZ2")
			{
				jry_wb_launch_full_screen(document.documentElement);
				var a = document.createElement('div');document.body.appendChild(a);a.style.backgroundColor='#ff0000';a.style.height='100%';a.style.width='100%';a.style.position='absolute';a.style.top = 0;a.style.left = 0;a.style.opacity = 1;a.style.zIndex = 99999999999;
				var flag = 0; var timer = setInterval(function (){ if(flag%2==0){a.style.backgroundColor='#ffff00';}else{a.style.backgroundColor='#ff0000';}if(flag>20){document.body.removeChild(a);clearInterval(timer);a = undefined;flag = undefined;timer = undefined;jry_wb_exit_full_screen();}flag++;},200);
			}
		};
		if(highlighturl==this.song_list[i].music_url&&highlighturl!=undefined&&this.song_list[i].music_url!=undefined)
			one.classList.add('active'),this.lastone = one;
	}
	if(this.beautiful_scroll==null)
		this.beautiful_scroll=new jry_wb_beautiful_scroll(this.list_area).jry_wb_scroll_body_y;
};