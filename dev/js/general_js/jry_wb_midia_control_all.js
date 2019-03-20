var jry_wb_midia_control_all  =  new function()
{
	this.playing_buf = null;
	this.playing = 0;
	var timer1=null;
	var timer2=null;
	var timer3=null;
	jry_wb_js_session.add_listener(1,(data)=>
	{
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
					},(Math.random()*20)+20);
				}
			},Math.random()*20);
		}
	});
	jry_wb_add_onbeforeunload(()=>
	{
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
			this.start();			
		}
	});
	setInterval(()=>
	{
		if(document.visibilityState != 'hidden'&& !jry_wb_background_music.status())
		{
			jry_wb_js_session.send(1,'get');
			this.start();
		}
	},2000);
	this.pause_all=()=>
	{
		if(this.playing_buf!=null)
			this.playing_buf.pause();
		if(this.playing_buf==null)
			jry_wb_background_music.break(),jry_wb_background_music.oncontrol=false;
	};
	this.start=()=>
	{
		if(this.playing_buf==null)
			jry_wb_background_music.oncontrol=true,jry_wb_background_music.continue();	
		else
			this.playing_buf.play();
	};
	this.onplay = function(audio)
	{
		if(this.playing_buf!=null)
			this.playing_buf.pause();
		if(audio==jry_wb_background_music.audio)
			return;
		if(this.playing_buf==null)
			jry_wb_background_music.break();
		this.playing_buf = audio;
	};
	this.onpause = function(audio)
	{
		if(audio==jry_wb_background_music.audio)
			return;
		this.playing_buf=null;
		setTimeout(function()
		{
			jry_wb_background_music.oncontrol=true,jry_wb_background_music.continue();
		},500);
	};
};
