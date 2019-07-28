<?php if(JRY_WB_SOCKET_SWITCH){ ?>
<?php include_once('../jry_wb_configs/jry_wb_config_socket.php'); ?>
<?php if(false){ ?><script><?php } ?>
var jry_wb_socket = new function()
{
	var socket=null;
	this.stop=false;
	var timer1;
	var timer2;
	var listener=[];
	var listener_type=[];
	var error=[];
	var send_buf=[];
	this.status=0;
	if(typeof SharedWorker=='undefined')
	{
		var start=()=>
		{
			if(this.stop||jry_wb_login_user==undefined||jry_wb_login_user.id<=0||jry_wb_login_user.id=='')
				return;
			if(socket!==null)
				socket.close(),socket=null;
			socket=new WebSocket('ws://<?php echo JRY_WB_DOMIN; ?>:<?php echo JRY_WB_SOCKET_PORT; ?>/jry_wb/jry_wb_socket/socket.php');
			socket.onopen=(evt)=>
			{
				onstart();
				this.status=socket.readyState;
			};
			socket.onmessage=(evt)=>
			{
<?php if(JRY_WB_DEBUG_MODE){ ?>			
				console.log( "Received Message: " + evt.data);
<?php } ?>
				callback(JSON.parse(evt.data));			
			};
			socket.onclose=(evt)=>
			{
				this.stop=false;
				this.status=socket.readyState;
				setTimeout(()=>
				{
					jry_wb_beautiful_right_alert.alert('已断开连接，正在重连',1000,'auto','warn');
					start();
					onclose();
				},5000);
			};
		};
	}
	else
	{
		var start=()=>
		{
			if(this.stop||jry_wb_login_user==undefined||jry_wb_login_user.id<=0||jry_wb_login_user.id=='')
				return;			
			var worker=new SharedWorker(jry_wb_message.jry_wb_host+'jry_wb_js/jry_wb_socket_worker.js.php');
			socket={};
			worker.port.onmessage=(data)=>
			{
				data=data.data;
				console.log(data);
				if(data.type==100001)
				{
					this.status=socket.readyState=data.readyState;
					if(socket.readyState==1)
						onstart();
					else
						onclose();
				}
				else if(data.type==100002)
					callback(data.data);
			};
			socket.send=(data)=>
			{
				var data=JSON.parse(data);
				if(data.type==100001)
					worker.port.postMessage({'type':100003,'data':data});			
				else if(data.type==100002)
					worker.port.postMessage({'type':100004,'data':data});			
				else
					worker.port.postMessage({'type':100002,'data':data});
			};
			worker.port.start();
			worker.port.postMessage({'type':100000,'data':jry_wb_login_user});			
			worker.port.postMessage({'type':100003,'data':{'code':true,'type':100001,'data':{'add':listener_type}}});
		};
	}
	var onclose=()=>
	{
		if(typeof this.connect_icon=='undefined')
			jry_wb_add_onload(()=>
			{
				this.connect_icon.classList.remove('jry_wb_icon_shujulianjie','jry_wb_color_ok_font');				
				this.connect_icon.classList.add('jry_wb_icon_duankailianjie','jry_wb_color_warn_font','jry_wb_color_normal_font');
			});
		else
		{
			this.connect_icon.classList.remove('jry_wb_icon_shujulianjie','jry_wb_color_ok_font');
			this.connect_icon.classList.add('jry_wb_icon_duankailianjie','jry_wb_color_warn_font','jry_wb_color_normal_font');
		}
	};
	var onstart=()=>
	{
		this.send({'code':true,'type':100001,'data':{'add':listener_type}});		
		if(typeof this.connect_icon=='undefined')
			jry_wb_add_onload(()=>
			{	
				this.connect_icon.classList.add('jry_wb_icon_shujulianjie','jry_wb_color_ok_font');	
				this.connect_icon.classList.remove('jry_wb_icon_duankailianjie','jry_wb_color_warn_font','jry_wb_color_normal_font');
			});
		else
		{	
			this.connect_icon.classList.add('jry_wb_icon_shujulianjie','jry_wb_color_ok_font');	
			this.connect_icon.classList.remove('jry_wb_icon_duankailianjie','jry_wb_color_warn_font','jry_wb_color_normal_font');
		}
	};	
	var callback=(data)=>
	{
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
				for(var i=0;i<send_buf.length;i++)
					this.send(send_buf.splice(0,1)[0]);
			else if(data.type==100003)
				console.log('Now have Listener ',data.data.listener);		
			else if(data.type==100004 && typeof error[data.data.reason]=='function')
				error[data.data.reason](data.data);
			else if(typeof listener[data.type]=='function')
				listener[data.type](data);	
		}		
	};
	start();
	jry_wb_add_onload(()=>
	{
		if(jry_wb_login_user==undefined||jry_wb_login_user.id<=0||jry_wb_login_user.id=='')
			return;		
		this.connect_icon=document.createElement("p");jry_wb_right_tools.add(this.connect_icon);
		this.connect_icon.classList.add('jry_wb_icon_duankailianjie','jry_wb_icon','jry_wb_color_warn_font','jry_wb_color_normal_font');
		this.connect_icon.style="z-index:9999;margin:0px;right:0px;position:fixed;width:35px;height:35px;font-size:35px";
	});
	this.send=(data,add_buf)=>
	{
		if(add_buf==undefined)
			add_buf=true;
		data=JSON.stringify(data);
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
<?php if(JRY_WB_DEBUG_MODE){ ?>			
		console.log('Send Message: ',data);
<?php } ?>
	};
	this.add_listener=(type,func)=>
	{
<?php if(JRY_WB_DEBUG_MODE){ ?>			
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
<?php if(JRY_WB_DEBUG_MODE){ ?>			
		console.log('Socket delete listener at '+type+':');
<?php } ?>
		this.send({'code':true,'type':100002,'data':{'del':type}});
		listener[type]=null;
	};	
	this.add_error=(reason,func)=>
	{
<?php if(JRY_WB_DEBUG_MODE){ ?>			
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
