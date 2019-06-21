<?php if(false){ ?><script><?php } ?>
var jry_wb_music_alert=new function()
{
	var a=document.createElement('audio');
	a.src=jry_wb_message.jry_wb_data_host+'music/newmessage.mp3';
	var playing=false;
	this.play=()=>
	{
		if(playing)
			return;
		playing=true;
<?php if(JRY_WB_BACKGROUND_MUSIC_SWITCH){ ?>
		var volume=jry_wb_background_music.audio.volume;
<?php } ?>
		a.onended=function()
		{
<?php if(JRY_WB_BACKGROUND_MUSIC_SWITCH){ ?>
			jry_wb_background_music.audio.volume=volume;
<?php } ?>
			playing=false;
		};
<?php if(JRY_WB_BACKGROUND_MUSIC_SWITCH){ ?>
		jry_wb_background_music.audio.volume*=0.2;
<?php } ?>
		a.play();		
	};
};
<?php if(false){ ?></script><?php } ?>