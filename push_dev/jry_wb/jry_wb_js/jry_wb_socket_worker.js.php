<?php
	header("content-type: application/x-javascript");
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/JRY_WB_CONFIG_USER_EXTERN_MESSAGE.php");	
?>
<?php if(JRY_WB_SOCKET_SWITCH){ ?>
<?php include_once('../jry_wb_configs/jry_wb_config_socket.php'); ?>
<?php if(false){ ?><script><?php } ?>
/*
100000:更新用户信息
100001:socket状态变化(readyState转发)
100002:数据包
100003:监听器添加
100004:监听器删除
100005:发送失败
*/
var pool=[];
onconnect=function(e)
{
	let key=Math.random();
	pool.push({'port':e.ports[0],'listener':[],'key':key});
	e.ports[0].postMessage({'type':100001,'readyState':readyState,'data':'open'});
	e.ports[0].onmessage=function(e)
	{
		var one=pool.find(function(a){return a.key==key});
		if(e.data.type==100000)
		{
			jry_wb_login_user=e.data.data;
			if(jry_wb_login_user==undefined||jry_wb_login_user.id<=0||jry_wb_login_user.id=='')
				jry_wb_socket.close();
		}
		else if(e.data.type==100002)
		{
			if(!jry_wb_socket.send(e.data.data,e.data.add_buf))
				one.port.postMessage({'type':100005,'data':e.data.data});
		}
		else if(e.data.type==100003)
		{
			if(typeof e.data.data.data.add=='number')
			{
				var b=e.data.data.data.add;
				if(one.listener.find(function(a){return a==b})==undefined)
					one.listener.push(b),jry_wb_socket.add_listener(b);
			}
			else
			{
				for(var i=0;i<e.data.data.data.add.length;i++)
				{
					b=e.data.data.data.add[i];
					if(one.listener.find(function(a){return a==b})==undefined)
						one.listener.push(b),jry_wb_socket.add_listener(b);			
				}				
			}
		}
		else if(e.data.type==100004)
		{
			if(typeof e.data.data.data.add=='number')
			{
				b=e.data.data.data.add;
				if((index=one.listener.indexOf(b))!=-1)
					one.listener.splice(index,1),jry_wb_socket.delete_listener(b);
			}
			else
			{
				for(var i=0;i<e.data.data.data.del.length;i++)
				{
					b=e.data.data.data.del[i];
					if((index=one.listener.indexOf(b))!=-1)
						one.listener.splice(index,1),jry_wb_socket.delete_listener(b);	
				}
			}
				
		}
		
		
	};
	e.ports[0].onmessage.onerror=function()
	{
		console.log(error);
	};
};
function send_to_port(index,data)
{
	console.log(index,data);
	pool[index].port.postMessage(data);	
}
function send_to_all(data)
{
	for(var i=0;i<pool.length;i++)
		send_to_port(i,data);
}
function send_to_listener(listener,data)
{
	for(var i=0;i<pool.length;i++)
		if(pool[i].listener.find(function(a){return a==listener})!==undefined)
			send_to_port(i,data);
}
var jry_wb_login_user=undefined;
var readyState=0;
var jry_wb_socket = new function()
{
	var socket=null;
	var send_buf=[];
	var start_timer;
	var listener_type=[];
	var listener=new Map();
	var start=()=>
	{
		if(this.stop||jry_wb_login_user==undefined||jry_wb_login_user.id<=0||jry_wb_login_user.id=='')
			return;
		console.time("socket");
		if(socket!==null)
			if(socket.readyState!=3)
				return ;
		socket=new WebSocket('ws://<?php echo JRY_WB_DOMIN; ?>:<?php echo JRY_WB_SOCKET_PORT; ?>/jry_wb/jry_wb_socket/socket.php');
		socket.onopen=(evt)=>
		{
			this.send({'code':true,'type':100001,'data':{'add':listener_type}});
			if(start_timer!=null)
				clearInterval(start_timer);
			send_to_all({'type':100001,'readyState':readyState=socket.readyState,'data':'open'});
		};
		socket.onmessage=(evt)=>
		{
			<?php if(JRY_WB_DEBUG_MODE){ ?>console.log( "Received Message: " + evt.data);<?php } ?>
			var data=JSON.parse(evt.data);
			if(data.code)
				send_to_listener(data.type,{'type':100002,'data':data});
			else
				send_to_all({'type':100002,'data':data});
		};
		socket.onclose=(evt)=>
		{
			console.timeEnd("socket");
			send_to_all({'type':100001,'readyState':readyState=socket.readyState,'data':'close'});
			setTimeout(()=>
			{
				start();
			},2000);
		};
	};
	this.close=()=>
	{
		socket.close();
	};
	start_timer=setInterval(()=>
	{
		start();
	},2000);
	start();
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
		<?php if(JRY_WB_DEBUG_MODE){ ?>console.log('Send Message: ',data);<?php } ?>
	};
	this.add_listener=(type)=>
	{
		type=parseInt(type);
		<?php if(JRY_WB_DEBUG_MODE){ ?>console.log('Socket add listener at '+type+':');<?php } ?>
		if(listener_type.find(function(a){return a==type})==undefined)
		{
			this.send({'code':true,'type':100001,'data':{'add':type}});
			listener_type.push(type);
			listener[type]=1;
		}
			listener[type]++;
	};
	this.delete_listener=(type)=>
	{
		type=parseInt(type);
		if((index=listener_type.indexOf(type))!=-1)
			listener_type.splice(index,1);
		listener[type]--;
		if(listener[type]==0)
			this.send({'code':true,'type':100002,'data':{'del':type}});
		<?php if(JRY_WB_DEBUG_MODE){ ?>console.log('Socket delete listener at '+type+':');<?php } ?>
	};	
};
<?php if(false){ ?></script><?php } ?>
<?php } ?>
