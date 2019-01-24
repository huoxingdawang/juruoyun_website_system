var jry_wb_midia_control_all  =  new function()
{
	this.playing_buf = null;
	this.playing = 0;
	this.onplay = function(audio)
	{
		if(this.playing!=0)
			this.playing_buf.pause();
		if(audio==jry_wb_background_music.audio)
			return;
		if(this.playing==0)
			jry_wb_background_music.break();
		this.playing++;
		this.playing_buf = audio;
	};
	this.onpause = function(audio)
	{
		if(audio==jry_wb_background_music.audio)
			return;
		this.playing--;
		if(this.playing==0)
			setTimeout(function(){jry_wb_background_music.continue();},500);
		/*this.playing_buf = null;*/
	};
};
