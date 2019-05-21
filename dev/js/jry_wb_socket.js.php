<?php if(constant('jry_wb_socket_switch')){ ?>
<?php include_once('../jry_wb_configs/jry_wb_config_socket.php'); ?>
<?php if(false){ ?><script><?php } ?>
var jry_wb_socket = new function()
{
	var socket=null;
	this.stop=false;
	this.ok=false;
	this.bridge=false;
	var timer1;
	var timer2;
	var listener=[];
	var listener_type=[];
	var error=[];
	var send_buf=[];
	var start=()=>
	{
		this.ok=false;
		if(this.stop||jry_wb_login_user==undefined||jry_wb_login_user.id<=0||jry_wb_login_user.id=='')
			return;
		var timer=setTimeout(()=>
		{
			console.time("socket");
			if(socket!==null)
				socket.close(),socket==null;
			socket=new WebSocket('ws://<?php echo constant('jry_wb_domin'); ?>:<?php echo constant('jry_wb_socket_port'); ?>/jry_wb/jry_wb_socket/socket.php');
			socket.onopen=(evt)=>
			{
				if(this.bridge)
					return socket.close(),socket==null;
				this.connect_icon.classList.remove('jry_wb_icon_duankailianjie','jry_wb_color_warn_font','jry_wb_color_normal_font');
				this.connect_icon.classList.add('jry_wb_icon_shujulianjie','jry_wb_color_ok_font');
				this.bridge=false;
				jry_wb_js_session.add_listener(2,(data)=>
				{
					if(data=='check')
						jry_wb_js_session.send(2,'have');
				});	
				jry_wb_js_session.add_listener(4,(data)=>
				{
					socket.send(data);
				});
				this.send({'code':true,'type':100001,'data':{'add':listener_type}});
			};
			socket.onmessage=(evt)=>
			{
				if(this.bridge)
					return socket.close(),socket==null;				
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
					{
						this.ok=true;
						for(var i=0;i<send_buf.length;i++)
							this.send(send_buf.splice(0,1)[0]);
					}
					else
					{
						jry_wb_js_session.send(3,data);
						callback(data);						
					}
				}			
			};
			socket.onclose=(evt)=>
			{
				console.timeEnd("socket");
				if(!this.bridge)
				{
					this.connect_icon.classList.add('jry_wb_icon_duankailianjie','jry_wb_color_warn_font','jry_wb_color_normal_font');
					this.connect_icon.classList.remove('jry_wb_icon_shujulianjie','jry_wb_color_ok_font');
					this.stop=false;
					this.ok=false;
					setTimeout(()=>
					{
						jry_wb_beautiful_right_alert.alert('已断开连接，正在重连',1000,'auto','warn');
						start();
					},5000);
				}
				else
				{
					this.connect_icon.classList.remove('jry_wb_icon_duankailianjie','jry_wb_color_warn_font','jry_wb_color_normal_font');
					this.connect_icon.classList.add('jry_wb_icon_shujulianjie','jry_wb_color_normal_font');
				}
			};
		},1000);
		jry_wb_js_session.add_listener(2,(data)=>
		{
			if(data=='have')
			{
				clearTimeout(timer);
				if(socket!==null)
					socket.close(),socket==null;
				this.ok=true;
				this.bridge=true;
				this.connect_icon.classList.remove('jry_wb_icon_duankailianjie','jry_wb_color_warn_font','jry_wb_color_normal_font');
				this.connect_icon.classList.add('jry_wb_icon_shujulianjie','jry_wb_color_normal_font');
				jry_wb_js_session.add_listener(3,(data)=>
				{
					callback(data);
				});
			}
			else if(data=='lose')
			{
				this.bridge=false;
				if(timer2!=null)
					clearTimeout(timer2);
				timer1=setTimeout(()=>
				{
					timer1=null;
					jry_wb_js_session.send(2,'try');
					start();
				},Math.random()*1000);				
			}
			else if(data=='try')
			{
				if(timer1!=null)
					clearTimeout(timer1);
				timer2=setTimeout(()=>
				{
					timer2=null;
					start();
				},Math.random()*1000+2000);
			}
		});			
		jry_wb_js_session.send(2,'check');
	};
	jry_wb_add_onbeforeunload(()=>
	{
		if(!this.bridge)
			jry_wb_js_session.send(2,'lose');
	});
	var callback=(data)=>
	{
		if(data.type==100003)
			console.log('Now have Listener ',data.data.listener);		
		else if(data.type==100004 && typeof error[data.data.reason]=='function')
			error[data.data.reason](data.data);
		else if(typeof listener[data.type]=='function')
			listener[data.type](data);	
	};
	jry_wb_add_load(()=>
	{
		if(jry_wb_login_user==undefined||jry_wb_login_user.id<=0||jry_wb_login_user.id=='')
			return;		
		this.connect_icon=document.createElement("p");jry_wb_right_tools.add(this.connect_icon);
		this.connect_icon.classList.add('jry_wb_icon_duankailianjie','jry_wb_icon','jry_wb_color_warn_font','jry_wb_color_normal_font');
		this.connect_icon.style="z-index:9999;margin:0px;right:0px;position:fixed;width:35px;height:35px;font-size:35px";
		start();
	});
	this.send=(data,add_buf)=>
	{
		if(add_buf==undefined)
			add_buf=true;
		data=JSON.stringify(data);
		if(this.bridge)
		{
			jry_wb_js_session.send(4,data);
			return true;
		}
		else
		{
			if(socket!=null&&socket.readyState==1)
			{
				socket.send(data);
				return true;
			}
			else
			{
				if(add_buf)
				{
					data=JSON.parse(data);
					if(data.type!==100001)
						send_buf.push(data);
					return true;
				}
				return false;
			}
		}
<?php if(constant('jry_wb_debug_mode')){ ?>			
		console.log('Send Message: ',data);
<?php } ?>
	};
	this.add_listener=(type,func)=>
	{
<?php if(constant('jry_wb_debug_mode')){ ?>			
		console.log('Socket add listener at '+type+':',func);
<?php } ?>
		type=parseInt(type);
		listener[type]=func;
		listener_type[listener_type.length]=type;
		this.send({'code':true,'type':100001,'data':{'add':type}});
	};
	this.delete_listener=(type)=>
	{
		type=parseInt(type);
<?php if(constant('jry_wb_debug_mode')){ ?>			
		console.log('Socket delete listener at '+type+':');
<?php } ?>
		listener[type]=null;
	};	
	this.add_error=(reason,func)=>
	{
<?php if(constant('jry_wb_debug_mode')){ ?>			
		console.log('Socket add error at '+reason+':',func);
<?php } ?>
		error[reason]=func;
	};
	this.add_error(500000,function(data){console.error(data.reason);});
	this.add_error(500001,function(data){console.error(data.reason);});
	this.add_error(500002,function(data){console.error(data.reason);});
	this.add_error(500003,function(data){console.error(data.reason);});	
};
<?php if(false){ ?></script><?php } ?>
<?php } ?>
