<?php
	header("content-type: application/x-javascript");
	include_once("../tools/jry_wb_includes.php");
?>
<?php if(false){ ?><script><?php } ?>
//jry_wb_include_once_css('jry_wb_chat.css');
var jry_wb_chat_room=new function()
{
	this.init=function(body,status_dom)
	{
		this.body=body;
	};
	var rooms=[];
	var loading_count=0;
	var messages=[];
	var now_show=null;
	var need_ctrl=true;
	if(jry_wb_cache.get('jry_wb_chat_need_ctrl')==false)
		need_ctrl=false;
<?php if(constant('jry_wb_socket_switch')){ ?>	
	jry_wb_socket.add_listener(200000,(data)=>
	{
		console.log('来自'+data.from+'在'+data.data.room+'的编号是'+data.data.chat_text_id+'的新信息'+data.data.message);
		var buf={'chat_room_id':data.data.room,'chat_text_id':data.data.chat_text_id,'id':data.from,'message':data.data.message,'send_time':data.data.send_time};
		messages=jry_wb_sync_data_with_array('chat_messages',[buf],function(a){return a.chat_text_id==this.buf.chat_text_id},function(a,b){return b.send_time.to_time()-a.send_time.to_time();});
		jry_wb_cache.set_last_time('chat_messages',messages[0].send_time);
		var one=rooms.find(function(a){return a.chat_room_id==data.data.room});
		if(now_show==data.data.room)
		{
			show_one_chat_message(one.message_box,buf);
			this.message_scroll.scrollto(this.message_scroll.get_all_child_height());
		}
		jry_wb_get_user(data.from,false,function(data)
		{
			one.lastsay_dom.innerHTML=data.name+':'+buf.message;
			var parentNode=one.dom.parentNode;
			if(parentNode.children[0]!=one.dom)
			{
				parentNode.removeChild(one.dom);
				parentNode.insertBefore(one.dom,parentNode.children[0]);
			}
		});		
	});
	jry_wb_socket.add_listener(200001,(data)=>
	{
		console.log(data.from+'加入聊天室'+data.data.room);});
	jry_wb_socket.add_listener(200002,(data)=>
	{
		console.log(data.from+'离开聊天室'+data.data.room);
	});
	jry_wb_socket.add_listener(200004,(data)=>
	{
		console.log(data.from+'删除聊天室'+data.data.room);
	});
	jry_wb_socket.add_listener(200005,(data)=>
	{
		loading_count--;
		loading_count++;
		jry_wb_socket.send({'code':true,'type':200007,'data':{'room':data.data,'lasttime':jry_wb_cache.get_last_time('chat_rooms')}});
		if(loading_count==0)
			this.show_chat_rooms();		
	});
	jry_wb_socket.add_listener(200006,(data)=>
	{
		loading_count--;
		messages=jry_wb_sync_data_with_array('chat_messages',data.data,function(a){return a.chat_text_id==this.buf.chat_text_id},function(a,b){return b.send_time.to_time()-a.send_time.to_time();});
		if(messages.length==0)
			jry_wb_cache.set_last_time('chat_messages','1926-08-17 00:00:00');
		else
			jry_wb_cache.set_last_time('chat_messages',messages[0].send_time);
<?php if(constant('jry_wb_debug_mode')){ ?>		
		console.log('消息',messages);
<?php } ?>		
		if(loading_count==0)
			this.show_chat_rooms();
	});
	jry_wb_socket.add_listener(200007,(data)=>
	{
		loading_count--;
		rooms=jry_wb_sync_data_with_array('chat_rooms',data.data,function(a){return a.chat_room_id==this.buf.chat_room_id},function(a,b){return b.lasttime.to_time()-a.lasttime.to_time();});
		if(rooms.length==0)
			jry_wb_cache.set_last_time('chat_rooms','1926-08-17 00:00:00');
		else
			jry_wb_cache.set_last_time('chat_rooms',rooms[0].lasttime);
<?php if(constant('jry_wb_debug_mode')){ ?>		
		console.log('房间',rooms);
<?php } ?>
		var buf=[];
		for(var i=0;i<rooms.length;i++)
			buf[buf.length]=rooms[i].chat_room_id;
		loading_count++;
		jry_wb_socket.send({'code':true,'type':200006,'data':{'room':buf,'lasttime':jry_wb_cache.get_last_time('chat_messages')}});
		if(loading_count==0)
			this.show_chat_rooms();
	});
	jry_wb_socket.add_listener(200008,(data)=>
	{
		console.log(data.from+'重命名'+data.data.room+'房间为'+data.data.name);
	});
	jry_wb_socket.add_listener(200009,(data)=>
	{
		console.log(data.from+'重设'+data.data.room+'房间头为',data.data.head);
	});
	jry_wb_socket.add_error(600000,(data)=>
	{
		console.error(data.reason);
	});
	jry_wb_socket.add_error(600001,(data)=>
	{
		console.error(data.reason);
	});
	jry_wb_socket.add_error(600002,(data)=>
	{
		console.error(data.reason);
	});
	jry_wb_socket.add_error(600003,(data)=>
	{
		console.error(data.reason);
	});
	jry_wb_socket.add_error(600004,(data)=>
	{
		console.error(data.reason);
	});
	jry_wb_socket.add_error(600005,(data)=>
	{
		console.error(data.reason);
	});
<?php } ?>
	this.sync=()=>
	{
		loading_count++;
<?php if(constant('jry_wb_socket_switch')){ ?>
		if(jry_wb_socket.send({'code':true,'type':200005},false)==false)
		{
<?php } ?>
			jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=get_rooms',(data)=>
			{
				jry_wb_loading_off();
				loading_count--;
				data=JSON.parse(data);
				if(data.code==false)
				{
					return;
				}
				loading_count++;
				jry_wb_sync_data_with_server('chat_rooms',jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=get_room',[{'name':'room','value':JSON.stringify(data.data)},{'name':'lasttime','value':jry_wb_cache.get_last_time('chat_rooms')}],
				function(a){return a.chat_room_id==this.buf.chat_room_id},
				(data)=>
				{
					rooms=data;
					if(rooms.length==0)
						jry_wb_cache.set_last_time('chat_rooms','1926-08-17 00:00:00');
					else
						jry_wb_cache.set_last_time('chat_rooms',rooms[0].lasttime);	
<?php if(constant('jry_wb_debug_mode')){ ?>		
					console.log('房间',rooms);
<?php } ?>
					loading_count--;
					if(loading_count==0)
						this.show_chat_rooms();
				},function(a,b){return b.lasttime.to_time()-a.lasttime.to_time();});
				loading_count++;
				jry_wb_sync_data_with_server('chat_messages',jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=get_message',[{'name':'room','value':JSON.stringify(data.data)},{'name':'lasttime','value':jry_wb_cache.get_last_time('chat_messages')}],
				function(a){return a.chat_text_id==this.buf.chat_text_id},
				(data)=>
				{
					messages=data;
					if(messages.length==0)
						jry_wb_cache.set_last_time('chat_messages','1926-08-17 00:00:00');
					else
						jry_wb_cache.set_last_time('chat_messages',messages[0].send_time);	
<?php if(constant('jry_wb_debug_mode')){ ?>		
					console.log('信息',messages);
<?php } ?>
					loading_count--;
					if(loading_count==0)
						this.show_chat_rooms();
				},function(a,b){return b.send_time.to_time()-a.send_time.to_time();});			
			});			
<?php if(constant('jry_wb_socket_switch')){ ?>
		}
<?php } ?>
	};
	jry_wb_add_load(()=>{this.sync();});
	this.delete_sync=function()
	{
		jry_wb_cache.delete('chat_rooms');
		jry_wb_cache.delete('chat_messages');
	};
<?php if(constant('jry_wb_debug_mode')){ ?>			
	this.show_sync=function()
	{
		console.log('chat_rooms',jry_wb_cache.get('chat_rooms'));
		console.log('chat_messages',jry_wb_cache.get('chat_messages'));
	};
<?php } ?>
	this.show_main_button=document.createElement('p');jry_wb_right_tools.add(this.show_main_button);
	this.show_main_button.classList.add('jry_wb_icon_liaotian','jry_wb_icon');
	this.show_main_button.style.fontSize='35px';
	this.show_main_button.style.right='0px';
	this.show_main_button.onclick=()=>
	{
		if(this.body==null)
		{
			this.body=document.createElement('div');document.body.appendChild(this.body);
		}
		this.body.classList.add('jry_wb_chat_body');
		if(this.body.innerHTML=='')
		{
			this.body.display='';
			this.left=document.createElement('div');this.body.appendChild(this.left);
			this.left.classList.add('jry_wb_chat_left');
			this.right=document.createElement('div');this.body.appendChild(this.right);
			this.right.classList.add('jry_wb_chat_right');
			this.show_chat_rooms();
		}
		else
		{
			this.body.innerHTML='';
			this.body.display='none';
		}
	};
	this.send=(room,message)=>
	{
		if(room==undefined||room=='')
			return false;
		if((typeof message!='string')||message.length<=0)
			return false;
<?php if(constant('jry_wb_socket_switch')){ ?>			
		if(jry_wb_socket.send({'code':true,'type':200000,'data':{'room':room,'message':message}},false)==false)
<?php } ?>
			jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=send',(data)=>
			{
				jry_wb_loading_off();
				this.sync();
			},[{'name':'room','value':room},{'name':'message','value':encodeURIComponent(message)}]);
	}
	setInterval(()=>{
		if(jry_wb_socket.status!=1)
		{
			this.sync();
		}
	},10000);
	function show_one_chat_message(message_box,message)
	{
		let one=document.createElement('div');message_box.appendChild(one);
		one.classList.add('jry_wb_cheat_one');	
		let user=document.createElement('div');one.appendChild(user);
		user.classList.add('user');
		user.onclick=function()
		{
			jry_wb_get_and_show_user_full(message.id,document.body.clientWidth*0.75,document.body.clientHeight*0.75);
		};
		let head=document.createElement('img');user.appendChild(head);
		head.classList.add('head');
		let name=document.createElement('span');user.appendChild(name);							
		name.classList.add('name','jry_wb_word_cut');
		let time=document.createElement('span');user.appendChild(time);
		time.classList.add('time');
		time.innerHTML=message.send_time;
		jry_wb_get_user(message.id,false,function(data)
		{
			name.innerHTML=data.name;
			jry_wb_set_user_head_special(data,head);
		});
		let msg=document.createElement('span');one.appendChild(msg);
		msg.classList.add('message');
		msg.style.width=one.clientWidth-head.clientWidth-40;
		var md=new markdown(msg,message.id,message.send_time,message.message,true);
		msg.children[0].style=one.clientWidth-head.clientWidth-40;
		jry_wb_add_onresize(function(){
			one.style.height=user.clientHeight+msg.clientHeight;
		});
		one.style.height=user.clientHeight+msg.clientHeight;		
	}
	this.show_chat_rooms=()=>
	{
		if(typeof this.left=='undefined')
			return;
		if(typeof this.right=='undefined')
			return;
		this.left.innerHTML='';
		for(let i=0;i<rooms.length;i++)
		{
			var one=document.createElement('div');this.left.appendChild(one);
			one.classList.add('jry_wb_chat_one');
			rooms[i].dom=one;
			var head_width=0;
			if(rooms[i].head.type=='default')
			{
				var word=rooms[i].name;
				var canvas=document.createElement('canvas');one.appendChild(canvas);
				rooms[i].head_dom=canvas;
				canvas.classList.add('jry_wb_chat_head');
				var width=head_width=canvas.clientWidth;
				var fontsize=parseInt(window.getComputedStyle(canvas).fontSize);
				canvas.style.height=width;
				canvas.style.width=width;
				canvas.style.borderRadius=width/2+'px';
				canvas.height=width;
				canvas.width=width;
				var ctx=canvas.getContext("2d");
				ctx.fillStyle='#ffffff';
				ctx.fillRect(0,0,width,width);
				ctx.font=fontsize+'px Consolas';
				ctx.fillStyle='#00ff00';
				var line1='',line2='',j=0,kuan1=0,kuan2=0;
				for(;kuan1<=width-fontsize&&j<word.length;line1+=word[j],j++)
					if((/.*[\u4e00-\u9fa5]+.*/.test(word[j])))
						kuan1+=fontsize;
					else
						kuan1+=fontsize/2;
				for(;kuan2<=width-fontsize&&j<word.length;line2+=word[j],j++)
					if((/.*[\u4e00-\u9fa5]+.*/.test(word[j])))
						kuan2+=fontsize;
					else
						kuan2+=fontsize/2;
				if(j!=word.length)
					line2=line2.slice(0,1)+'...';
				ctx.fillText(line1,width/2-(kuan1)/2,fontsize*1+(width-fontsize*2)/2);
				ctx.fillText(line2,width/2-(kuan2)/2,fontsize*2+(width-fontsize*2)/2);
			}
			rooms[i].name_dom=document.createElement('span');one.appendChild(rooms[i].name_dom);
			rooms[i].name_dom.classList.add('jry_wb_chat_name','jry_wb_word_cut');
			rooms[i].name_dom.innerHTML=rooms[i].name;
			rooms[i].name_dom.style.width=one.clientWidth-head_width;
			rooms[i].lastsay_dom=document.createElement('span');one.appendChild(rooms[i].lastsay_dom);
			rooms[i].lastsay_dom.classList.add('jry_wb_chat_lastsay','jry_wb_word_cut');
			rooms[i].lastsay_dom.style.width=one.clientWidth-head_width;
			jry_wb_add_onresize(function(){
				rooms[i].name_dom.style.width=one.clientWidth-head_width;
				rooms[i].lastsay_dom.style.width=one.clientWidth-head_width;				
			});
			var last=messages.find(function(a){return a.chat_room_id==rooms[i].chat_room_id});
			if(last!=undefined)
			{
				jry_wb_get_user(last.id,false,function(data)
				{
					rooms[i].lastsay_dom.innerHTML=data.name+':'+last.message;
				});
			}
			one.onclick=()=>
			{
				window.location.hash=now_show=rooms[i].chat_room_id;
				this.right.innerHTML='';
				var message_box=rooms[i].message_box=document.createElement('div');this.right.appendChild(message_box);
				for(var j=messages.length-1;j>=0;j--)
					if(messages[j].chat_room_id==rooms[i].chat_room_id)
						show_one_chat_message(message_box,messages[j]);
				var input_area=document.createElement('div');this.right.appendChild(input_area);
				input_area.classList.add('jry_wb_cheat_input_area');
				var input=document.createElement('textarea');input_area.appendChild(input);
				input.classList.add('input');
				var button=document.createElement('span');input_area.appendChild(button);
				button.classList.add('button','jry_wb_icon_icon_fabu','jry_wb_icon');
				button.onclick=()=>
				{
					if(this.send(rooms[i].chat_room_id,input.value)==false)
						jry_wb_beautiful_right_alert.alert('消息不能为空',2000,'auto','error');
					else
						input.value='';
				};
				message_box.style.height=this.right.clientHeight-input_area.clientHeight;
				input.style.width=message_box.clientWidth-button.clientWidth;
				jry_wb_add_onresize(()=>
				{
					message_box.style.height=this.right.clientHeight-input_area.clientHeight;
					input.style.width=message_box.clientWidth-button.clientWidth;					
				});
				input.focus();
				var checkbox=document.createElement('input');input_area.appendChild(checkbox);
				checkbox.type='checkbox';
				var word=document.createElement('span');input_area.appendChild(word);
				word.innerHTML='ctrl+enter发送';
				word.classList.add('word');
				input.value=jry_wb_cache.get('jry_wb_chat_input_buf');
				if(need_ctrl)
					checkbox.setAttribute('checked','checked');
				else
					word.innerHTML='enter发送';					
				checkbox.onclick=function()
				{
					need_ctrl=checkbox.checked;
					jry_wb_cache.set('jry_wb_chat_need_ctrl',need_ctrl);
					if(need_ctrl)
						word.innerHTML='ctrl+enter发送';
					else
						word.innerHTML='enter发送';						
				}
				input.onkeyup=(e)=>
				{
					if (!e) 
						e=window.event;
					var keycode=(e.keyCode||e.which);
					if(keycode==jry_wb_keycode_enter&&((need_ctrl&&e.ctrlKey)||(!need_ctrl&&!e.ctrlKey)))
						if(this.send(rooms[i].chat_room_id,input.value)==false)
							jry_wb_beautiful_right_alert.alert('消息不能为空',2000,'auto','error');
						else
							input.value='';
					if(keycode==jry_wb_keycode_enter&&(!need_ctrl&&e.ctrlKey))
						input.value+='\n';
					
					jry_wb_cache.set('jry_wb_chat_input_buf',input.value);
				};
				this.message_scroll=new jry_wb_beautiful_scroll(message_box);
				this.message_scroll.scrollto(this.message_scroll.get_all_child_height());
			};
			if(window.location.hash=='#'+rooms[i].chat_room_id)
				one.onclick();
		}
	}
};
<?php if(false){ ?></script><?php } ?>