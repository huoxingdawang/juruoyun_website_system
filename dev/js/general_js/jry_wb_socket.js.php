<?php if(constant('jry_wb_socket_switch')){ ?>
<?php include_once('../jry_wb_configs/jry_wb_config_socket.php'); ?>
<?php if(false){ ?><script><?php } ?>
var jry_wb_socket = new function()
{
	var socket;
	this.stop=false;
	this.ok=false;
	var start=()=>
	{
		this.ok=false;
		if(this.stop||jry_wb_login_user==undefined||jry_wb_login_user.id<=0||jry_wb_login_user.id=='')
			return;
		console.time("socket");
		socket=new WebSocket('ws://<?php echo constant('jry_wb_domin'); ?>:<?php echo constant('jry_wb_socket_port'); ?>/jry_wb/jry_wb_socket/socket.php');
		socket.onopen=(evt)=>
		{
			this.connect_icon.classList.remove('jry_wb_icon_duankailianjie','jry_wb_color_warn_font');
			this.connect_icon.classList.add('jry_wb_icon_shujulianjie','jry_wb_color_ok_font');
		};
		socket.onmessage=(evt)=>
		{
<?php if(constant('jry_wb_debug_mode')){ ?>			
			console.log( "Received Message: " + evt.data);
<?php } ?>
			var data=JSON.parse(evt.data);
			if(data.code==false)
			{
				if(data.reason==100000)
					jry_wb_login_user.id=-1;
				else if(data.reason==500000)
					this.stop=true;
			}
			else
			{
				if(data.type==100000)
					this.ok=true;
				else if(data.type==200000)
				{
					console.log('来自'+data.from+'的新信息'+data.data);
				}
			}		
		};
		socket.onclose=(evt)=>
		{
			this.connect_icon.classList.add('jry_wb_icon_duankailianjie','jry_wb_color_warn_font');
			this.connect_icon.classList.remove('jry_wb_icon_shujulianjie','jry_wb_color_ok_font');
			console.timeEnd("socket");
			this.stop=false;
			setTimeout(()=>
			{
				jry_wb_beautiful_right_alert.alert('已断开连接，正在重连',1000,'auto','warn');
				start();
			},5000);
		};
	};
	jry_wb_add_load(()=>
	{
		if(jry_wb_login_user==undefined||jry_wb_login_user.id<=0||jry_wb_login_user.id=='')
			return;		
		this.connect_icon=document.createElement("p");jry_wb_right_tools.add(this.connect_icon);
		this.connect_icon.classList.add('jry_wb_icon_duankailianjie','jry_wb_icon','jry_wb_color_warn_font');
		this.connect_icon.style="z-index:9999;margin:0px;right:0px;position:fixed;width:35px;height:35px;font-size:35px";
		start();
	});
	this.send=(data)=>
	{
		data=JSON.stringify(data);
		socket.send(data);
<?php if(constant('jry_wb_debug_mode')){ ?>			
		console.log('Send Message: '+data);
<?php } ?>
	}
};
<?php if(false){ ?></script><?php } ?>
<?php } ?>
