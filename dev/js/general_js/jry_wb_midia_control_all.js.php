<?php if(false){ ?><script><?php } ?>
var jry_wb_midia_control_all  =  new function()
{
	this.playing_buf = null;
	this.playing = 0;
	var timer1=null;
	var timer2=null;
	var timer3=null;
	jry_wb_js_session.add_listener(1,(data)=>
	{
		var playing=jry_wb_cache.get('background_music');
		if(playing.status==true)
			this.stop_background=false;
		if(data=='get')
		{
			if(timer1!=null)
				clearTimeout(timer1),timer1=null;
			if(timer2!=null)
				clearTimeout(timer2),timer2=null;
			if(timer3!=null)
				clearTimeout(timer3),timer3=null;			
			this.pause_all();
		}
		else if(data=='lose')
		{
			if(document.visibilityState != 'hidden')
			{
				jry_wb_js_session.send(1,'get');
				this.start();
			}
			if(timer!=null)
			{
				if(timer1!=null)
					clearTimeout(timer1),timer1=null;
				if(timer2!=null)
					clearTimeout(timer2),timer2=null;
				if(timer3!=null)
					clearTimeout(timer3),timer3=null;				
			}
		}
		else if(data=='close')
		{
			timer1=setTimeout(()=>
			{
				timer1=null;
				if(timer3!=null)
					clearTimeout(timer3),timer3=null;
				if(document.visibilityState != 'hidden')
				{
					jry_wb_js_session.send(1,'get');
					this.start();
				}
				else
				{
					timer2=setTimeout(()=>
					{
						jry_wb_js_session.send(1,'get');
						this.start();						
					},(Math.random()*1000)+1000);
				}
			},Math.random()*1000);
		}
	});
	jry_wb_add_onbeforeunload(()=>
	{
		if(document.visibilityState!='hidden') 
			jry_wb_js_session.send(1,'close');
	});	
	document.addEventListener('visibilitychange',()=> 
	{
		if(document.visibilityState == 'hidden') 
		{
			jry_wb_js_session.send(1,'lose');
		}
		else
		{
			jry_wb_js_session.send(1,'get');
<?php if(constant('jry_wb_background_music_switch')){ ?>			
			if(this.playing_buf==null&&!jry_wb_background_music.status())
				this.start();
<?php } ?>
		}
	});
<?php if(constant('jry_wb_background_music_switch')){ ?>			
	setInterval(()=>
	{
		if(document.visibilityState != 'hidden'&& !jry_wb_background_music.status())
		{
			jry_wb_js_session.send(1,'get');
			this.start();
		}
	},2000);
<?php } ?>
	this.pause_all=()=>
	{
		if(this.playing_buf!=null)
			this.playing_buf.pause();
<?php if(constant('jry_wb_background_music_switch')){ ?>			
		if(this.playing_buf==null)
			jry_wb_background_music.break(),jry_wb_background_music.oncontrol=false;
<?php } ?>
	};
	this.start=()=>
	{
		if(this.playing_buf==null)
		{
<?php if(constant('jry_wb_background_music_switch')){ ?>			
			if((this.stop_background==false||typeof this.stop_background=='undefined'))
				jry_wb_background_music.oncontrol=true,jry_wb_background_music.continue();	
<?php } ?>
		}
		else
			this.playing_buf.play();
	};
	this.onplay = function(audio)
	{
		if(this.playing_buf!=null)
			this.playing_buf.pause();
<?php if(constant('jry_wb_background_music_switch')){ ?>			
		if(audio.id=='jry_wb_background_music')
			return this.stop_background=false;
		if(this.playing_buf==null)
			jry_wb_background_music.break();
<?php } ?>
		this.playing_buf = audio;
	};
	this.onpause = function(audio)
	{
<?php if(constant('jry_wb_background_music_switch')){ ?>			
		if(audio.id=='jry_wb_background_music')
			this.stop_background=true;
		else
		{
<?php } ?>
			this.playing_buf=null;
<?php if(constant('jry_wb_background_music_switch')){ ?>			
			setTimeout(function()
			{
				jry_wb_background_music.oncontrol=true,jry_wb_background_music.continue();
			},500);
		}
<?php } ?>
	};
};
<?php if(false){ ?></script><?php } ?>