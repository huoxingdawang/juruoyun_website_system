var jry_wb_background_music = new function()
{
	var inited=false;
	var timer=null;
	var timer_close=null;
	this.playing_status;
	this.oncontrol=false;
	this.leave=false;
	this.init=function(list)
	{
		this.list=list==null?[]:list;
		if(list!=null)
			this.list=list;
		if(!inited)
		{
			if(typeof jry_wb_login_user!='undefined')
				this.song_list=jry_wb_get_songs_by_mid(jry_wb_login_user.background_music_list);
			this.backgroundmusic_icon=document.createElement("p");document.body.appendChild(this.backgroundmusic_icon);
			this.backgroundmusic_icon.id='jry_wb_left_button_backgroundmusic_icon';
			this.backgroundmusic_icon.classList.add('jry_wb_icon_changpian','jry_wb_icon');
			this.backgroundmusic_icon.style="z-index:9999;margin:0px;right:0px;position:fixed;width:35px;height:35px;font-size:35px";
			this.backgroundmusic_control=document.createElement("div");document.body.appendChild(this.backgroundmusic_control);
			this.backgroundmusic_control.id='jry_wb_left_button_backgroundmusic_control';
			this.backgroundmusic_control.style="z-index:9999;right:35px;display:none;position:fixed;width:auto;overflow:unset;width:300px;";
			this.backgroundmusic_control.onmouseover=this.backgroundmusic_icon.onmouseover=this.backgroundmusic_icon.onclick=()=>
			{
				if(timer!=null)
					clearTimeout(timer);
				if(timer_close!=null)
					clearTimeout(timer_close);
				timer=timer_close=null;
				if(!jry_wb_test_is_pc())
					timer_close=setTimeout(()=>{this.backgroundmusic_control.style.display="none";clearTimeout(timer);},10000);				
				this.beautiful.beautiful_scroll.style.height=this.beautiful.list_area.clientHeight-parseFloat(getComputedStyle(this.beautiful.list_area,false)['border-bottom-right-radius']);
				this.backgroundmusic_control.style.display="";
				this.backgroundmusic_control.style.top=parseInt(this.backgroundmusic_icon.style.top)-this.backgroundmusic_control.clientHeight*0.5;
			};
			this.backgroundmusic_control.onmouseout=this.backgroundmusic_icon.onmouseout=()=>
			{
				if(typeof jry_wb_dev_mode=='undefined' || jry_wb_dev_mode==false)
					timer=setTimeout(()=>{this.backgroundmusic_control.style.display="none";if(timer_close!=null)clearTimeout(timer_close);},500);
			};
			jry_wb_add_onresize(()=>
			{
				var hight=document.body.clientHeight;
				var jiange=hight*0.4/3;
				hight*=0.2;
				this.backgroundmusic_icon.style.top=hight+jiange*3;	
			});
			if(document.visibilityState != 'hidden') 
			{
				jry_wb_js_session.send(1,'get');
				this.oncontrol|=true;
			}			
			
			if(this.oncontrol)
			{
				jry_wb_cache.set('background_music_playing',true);
			};
			this.passive=false;
			this.audio=document.createElement("audio");document.body.appendChild(this.audio);
			this.beautiful= new jry_wb_beautiful_music(this.audio,this.backgroundmusic_control,true);
			this.beautiful.do_reply_count(1);
			var old_onplay=this.audio.onplay;
			var old_onpause=this.audio.onpause;
			var old_ontimeupdate=this.audio.ontimeupdate;
			this.audio.onplay=()=>
			{
				this.playing_status=true;
				this.oncontrol=true;
				this.playing=this.song_list.find((a)=>{ return a.music_url==this.audio.src});
				this.beautiful.push_song_list(this.song_list,this.playing==null?'':this.playing.music_url);	
				this.backgroundmusic_icon.classList.add("jry_wb_rotate");
				this.save(true);
				old_onplay();
			};
			this.audio.onpause=()=>
			{
				if(!this.passive)
					this.playing_status=false;
				this.backgroundmusic_icon.classList.remove("jry_wb_rotate");
				this.save(false);
				old_onpause();
			};
			this.audio.ontimeupdate=()=>
			{
				old_ontimeupdate();
				this.save(true);
			};
			this.jry_background_music_flag=true;
			inited=true;
			jry_wb_add_onbeforeunload(()=>
			{
				var playing=jry_wb_cache.get('background_music');
				playing.status=(!this.passive)?this.status():(this.playing_status);
				if(this.oncontrol)
				{
					jry_wb_cache.set('background_music',playing);
					jry_wb_cache.set('background_music_playing',false);
					this.leave=true;
				}
			});
			this.backup();
			if(this.playing==null||this.playing.mid==null)
			{
				this.beautiful.push_song_list(this.song_list);
				jry_wb_beautiful_right_alert.alert("您可以打开BGM",5000,'auto','ok');
			}			
			return true;
		}
		return false;
	};
	this.save=function(status)
	{
		if(status==undefined)
			status=this.status();
		jry_wb_cache.set('background_music',{'status':status,'time':this.currenttime(),'volume':this.audio.volume,'mid':this.playing.mid,'type':this.playing.type,'cycle':this.beautiful.reply_count});
	};
	this.setsrc=function(type,mid,callback)
	{
		if(!inited)
			this.init(document.body);
		this.mid=mid;
		this.type=type;
		this.playing=jry_wb_get_songs_by_mid([{'type':type,'mid':mid}])[0];
		this.beautiful.set_background_picture(this.playing.pic_url,this.playing.type);
		if(this.audio.src!=this.playing.music_url)
			this.audio.src=this.playing.music_url;
		if( typeof callback=='function')
			callback();
		return this.audio.src;
	};
	this.backup=function(set)
	{
		var playing=jry_wb_cache.get('background_music');
		this.beautiful.reply_count=playing==null?1:playing.cycle;
		this.beautiful.cycle_button_update();
		if(playing==null||playing.mid==null)
		{
		}
		else
		{
			this.playing=playing;
			this.playing.music_url=this.setsrc(playing.type,playing.mid);
			this.audio.oncanplay=()=>
			{
				if(this.oncontrol)
				{
					this.status(playing.status==null?true:(playing.status));
				}
				this.audio.oncanplay=function(){};
			};
			this.beautiful.push_song_list(this.song_list,this.playing==null?'':this.playing.music_url);			
			this.currenttime(playing.time==null?0:playing.time);
			this.volume(playing.volume==null?0.2:playing.volume);
			this.beautiful.update_volume_bar();
		}
	};
	this.push_song_list=function(list_old)
	{
		this.song_list=jry_wb_get_songs_by_mid(list_old);
		this.beautiful.push_song_list(this.song_list,this.playing==null?'':this.playing.music_url);
	};
	this.volume=function(volume)
	{
		return (volume==undefined?this.audio.volume:(this.audio.volume=volume));
	};
	this.currenttime=function(currenttime)
	{
		return (currenttime==undefined?this.audio.currentTime:(this.audio.currentTime=currenttime));
	};
	this.status=function(status)
	{
		return status==undefined?(!this.audio.paused):(status?this.audio.play():this.audio.pause());
	};
	this.break=function()
	{	
		this.passive=true;
		this.status(false);
		this.save(true);
	};
	this.continue=function()
	{
		console.log('continue');
		this.backup();		
		this.passive=false;
		if(this.playing!=null)
			this.beautiful.do_reply_count(this.playing.cycle);
		this.status(this.playing_status);
	};
};
