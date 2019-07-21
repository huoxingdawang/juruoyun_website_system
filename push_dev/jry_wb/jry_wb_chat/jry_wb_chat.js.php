<?php
	header("content-type: application/x-javascript");
	include_once("../jry_wb_tools/jry_wb_includes.php");
?>
<?php if(false){ ?><script><?php } ?>
var jry_wb_chat_room=new function()
{
	this.init=function(body,status_dom)
	{
		this.body=body;
	};
	var chenge_name_doms=[];
	var rooms=[];
	var loading_count=0;
	var messages=[];
	var now_show=null;
	var last_show_dom;
	var need_ctrl=true;
	if(jry_wb_cache.get('jry_wb_chat_need_ctrl')==false)
		need_ctrl=false;
<?php if(JRY_WB_SOCKET_SWITCH){ ?>	
	jry_wb_socket.add_listener(200000,(data)=>
	{
<?php if(JRY_WB_DEBUG_MODE){ ?>		
		console.log('来自'+data.from+'在'+data.data.room+'的编号是'+data.data.chat_text_id+'的新信息'+data.data.message);
<?php } ?>
		if(data.from!=jry_wb_login_user.id)
			jry_wb_music_alert.play();
		var buf={'chat_room_id':data.data.room,'chat_text_id':data.data.chat_text_id,'id':data.from,'message':data.data.message,'send_time':data.data.send_time};
		jry_wb_sync_data_with_array('chat_messages',[buf],(mess)=>
		{
			messages=mess.sort(function(a,b){return b.send_time.to_time()-a.send_time.to_time();});
			jry_wb_indexeddb_set_lasttime('chat_messages',messages[0].send_time);
			var one=rooms.find(function(a){return a.chat_room_id==data.data.room});
			if(now_show==data.data.room)
			{
				show_one_chat_message(one.message_box,buf);
				this.message_scroll.scrollto(0,this.message_scroll.get_all_child_height());
			}
			jry_wb_get_user(data.from,false,function(data)
			{
				one.lastsay_dom.innerHTML=data.name+':'+buf.message.slice(0,50);
				var parentNode=one.dom.parentNode;
				if(parentNode.children[1]!=one.dom)
				{
					parentNode.removeChild(one.dom);
					parentNode.insertBefore(one.dom,parentNode.children[1]);
				}
			});			
		});
	});
	jry_wb_socket.add_listener(200001,(data)=>
	{
<?php if(JRY_WB_DEBUG_MODE){ ?>
		console.log(data.from+'加入聊天室'+data.data.room);
<?php } ?>		
		if(data.from!=jry_wb_login_user.id)
		{
			var one=rooms.find(function(a){return a.chat_room_id==data.data.room});
			if(one==undefined)
				return ;
			one.users.push(parseInt(data.from));
			one.lasttime=data.data.lasttime;
			jry_wb_cache.set('chat_rooms',rooms,undefined,data.data.lasttime);		
			jry_wb_get_user(data.from,false,function(d)
			{
				jry_wb_beautiful_right_alert.alert(d.name+'加入聊天室'+one.name);
			});
		}
		else
		{
			jry_wb_beautiful_right_alert.alert('您加入聊天室');
			loading_count+=2;
			jry_wb_socket.send({'code':true,'type':200007,'data':{'room':data.data.room}});	
			setTimeout(function()
			{
				jry_wb_socket.send({'code':true,'type':200006,'data':{'room':data.data.room}});	
			},2000);				
		}
	});
	jry_wb_socket.add_listener(200002,(data)=>
	{
<?php if(JRY_WB_DEBUG_MODE){ ?>
		console.log(data.from+'离开聊天室'+data.data.room);
<?php } ?>		
		if(data.from==jry_wb_login_user.id)
		{
			var one=rooms.find(function(a){return a.chat_room_id==data.data.room});
			one.dom.parentNode.removeChild(one.dom);
			if(now_show==data.data.room)
				this.right.innerHTML='';
			rooms.splice(rooms.indexOf(one),1);
			jry_wb_cache.set('chat_rooms',rooms,undefined,data.data.lasttime);				
			for(var i=0;i<messages.length;i++)
				if(messages[i].chat_room_id==data.data.room)
					messages.splice(i,1),i--;
			jry_wb_cache.set('chat_messages',messages,undefined,data.data.lasttime);
			jry_wb_beautiful_right_alert.alert('您离开聊天室');			
		}
		else
		{
			var one=rooms.find(function(a){return a.chat_room_id==data.data.room});
			one.users.splice(one.users.indexOf(parseInt(data.from)),1);
			one.lasttime=data.data.lasttime;
			jry_wb_cache.set('chat_rooms',rooms,undefined,data.data.lasttime);		
			jry_wb_get_user(data.from,false,function(d)
			{
				jry_wb_beautiful_right_alert.alert(d.name+'离开聊天室'+one.name);
			});
		}
	});
	jry_wb_socket.add_listener(200004,(data)=>
	{
<?php if(JRY_WB_DEBUG_MODE){ ?>
		console.log(data.from+'删除聊天室'+data.data.room);
<?php } ?>		
		var one=rooms.find(function(a){return a.chat_room_id==data.data.room});
		one.dom.parentNode.removeChild(one.dom);
		if(now_show==data.data.room)
			this.right.innerHTML='';
		rooms.splice(rooms.indexOf(one),1);
		jry_wb_cache.set('chat_rooms',rooms,undefined,data.data.lasttime);				
		for(var i=0;i<messages.length;i++)
			if(messages[i].chat_room_id==data.data.room)
				messages.splice(i,1),i--;
		jry_wb_cache.set('chat_messages',messages,undefined,data.data.lasttime);
		jry_wb_beautiful_right_alert.alert('您离开聊天室');	
	});
	jry_wb_socket.add_listener(200005,(data)=>
	{
		if(loading_count>0)
			jry_wb_socket.send({'code':true,'type':200007,'data':{'room':data.data,'lasttime':jry_wb_cache.get_last_time('chat_rooms')}});
	});
	jry_wb_socket.add_listener(200006,(data)=>
	{
		if(loading_count>0)
			loading_count--;
		jry_wb_sync_data_with_array('chat_messages',data.data,(data)=>
		{
			messages=data.sort(function(a,b){return b.send_time.to_time()-a.send_time.to_time();});
			if(messages.length==0)
				jry_wb_indexeddb_set_lasttime('chat_messages','1926-08-17 00:00:00');
			else
				jry_wb_indexeddb_set_lasttime('chat_messages',messages[0].send_time);
	<?php if(JRY_WB_DEBUG_MODE){ ?>		
			console.log('消息',messages);
	<?php } ?>		
			if(loading_count==0)
				this.show_chat_rooms(true);
		});
	});
	jry_wb_socket.add_listener(200007,(data)=>
	{
		if(document.activeElement.id=='serchinput')
		{
			var room=data.data[0];
			if(room!=undefined)
				if(room.users.indexOf(jry_wb_login_user.id)==-1)
					if(typeof serchcallback=='function')
						serchcallback(room);
			return ;
		}
		jry_wb_sync_data_with_array('chat_rooms',data.data,(data)=>
		{
			rooms=data.sort(function(a,b){return b.lasttime.to_time()-a.lasttime.to_time();});
			if(rooms.length==0)
				jry_wb_indexeddb_set_lasttime('chat_rooms','1926-08-17 00:00:00');
			else
				jry_wb_indexeddb_set_lasttime('chat_rooms',rooms[0].lasttime);
	<?php if(JRY_WB_DEBUG_MODE){ ?>		
			console.log('房间',rooms);
	<?php } ?>
			var buf=[];
			for(var i=0;i<rooms.length;i++)
				buf[buf.length]=rooms[i].chat_room_id;
			if(loading_count>0)			
				jry_wb_socket.send({'code':true,'type':200006,'data':{'room':buf,'lasttime':jry_wb_cache.get_last_time('chat_messages')}});
		});
	});
	jry_wb_socket.add_listener(200008,(data)=>
	{
		console.log(data.from+'重命名'+data.data.room+'房间为'+data.data.name);		
		var one=rooms.find(function(a){return a.chat_room_id==data.data.room});
		one.name=data.data.name;
		one.lasttime=data.data.lasttime;
		jry_wb_cache.set('chat_rooms',rooms,undefined,data.data.lasttime);
		if(one.name_dom!=undefined)
			one.name_dom.innerHTML=data.data.name;
		else
			console.warn('nodom');
		if(now_show==data.data.room)
			for(var i=0;i<chenge_name_doms.length;i++)
				chenge_name_doms[i].innerHTML=data.data.name;
		if(one.head.type=='default'&&one.dom!=undefined)
			drawhead(one.dom,one,one.head_dom);
	});
	jry_wb_socket.add_listener(200009,(data)=>
	{
		console.log(data.from+'重设'+data.data.room+'房间头为',data.data.head);
		var one=rooms.find(function(a){return a.chat_room_id==data.data.room});
		one.head=data.data.head;
		one.lasttime=data.data.lasttime;
		jry_wb_cache.set('chat_rooms',rooms,undefined,data.data.lasttime);
		if(one.dom!=undefined)
			drawhead(one.dom,one,one.head_dom);		
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
	var sync_cnt=0;
	this.sync=()=>
	{
		loading_count++;
		var newdata=false;
		if(sync_cnt==0)
			var newdata=true;
<?php if(JRY_WB_SOCKET_SWITCH){ ?>			
		else if(jry_wb_socket.status==1)
			return;
<?php } ?>
		sync_cnt++;
		console.time('chat_sync');
<?php if(JRY_WB_SOCKET_SWITCH){ ?>
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
				(data,newd)=>
				{
					newdata|=newd;
					console.log(newd);
					rooms=data;
<?php if(JRY_WB_DEBUG_MODE){ ?>		
					console.log('房间',rooms);
<?php } ?>
					loading_count--;
					if(loading_count==0&&newdata)
						this.show_chat_rooms(true);
					return (rooms.length==0)?'1926-08-17 00:00:00':rooms[0].lasttime;					
				},function(a,b){return b.lasttime.to_time()-a.lasttime.to_time();});
				loading_count++;
				jry_wb_sync_data_with_server('chat_messages',jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=get_message',[{'name':'room','value':JSON.stringify(data.data)},{'name':'lasttime','value':jry_wb_cache.get_last_time('chat_messages')}],
				(data,newd)=>
				{
					newdata|=newd;			
					console.log(newd);
					messages=data;
<?php if(JRY_WB_DEBUG_MODE){ ?>		
					console.log('信息',messages);
<?php } ?>
					loading_count--;
					if(loading_count==0&&newdata)
						this.show_chat_rooms(true);
					return (messages.length==0)?'1926-08-17 00:00:00':messages[0].send_time;
				},function(a,b){return b.send_time.to_time()-a.send_time.to_time();});			
			});			
<?php if(JRY_WB_SOCKET_SWITCH){ ?>
		}
<?php } ?>
	};
	jry_wb_add_load(()=>{this.sync();});
	this.delete_sync=function()
	{
		jry_wb_cache.delete('chat_rooms');
		jry_wb_cache.delete('chat_messages');
	};
<?php if(JRY_WB_DEBUG_MODE){ ?>			
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
		jry_wb_cache.delete('jry_wb_chat_input_buf');		
		if((typeof message!='string')||message.length<=0)
			return false;
<?php if(JRY_WB_SOCKET_SWITCH){ ?>			
		if(message.length>1500||jry_wb_socket.send({'code':true,'type':200000,'data':{'room':room,'message':message}},false)==false)
<?php } ?>
			jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=send',(data)=>
			{
				jry_wb_loading_off();
				data=JSON.parse(data);
				if(data.code==false)
				{
					return;
				}
				else				
					this.sync();
			},[{'name':'room','value':room},{'name':'message','value':message}]);
	}
	setInterval(()=>{
<?php if(JRY_WB_SOCKET_SWITCH){ ?>		
		if(jry_wb_socket.status!=1)
<?php } ?>
		{
			this.sync();
		}
	},10000);
	function show_one_chat_message(message_box,message,callback)
	{
		let one=document.createElement('div');message_box.appendChild(one);
		one.classList.add('jry_wb_chat_one');	
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
		setTimeout(()=>
		{
			var md=new jry_wb_markdown(msg,message.id,message.send_time,message.message,true);
			one.style.height=user.clientHeight+msg.clientHeight;
			if(typeof callback=='function')
				callback();
		},Math.random()*100+10);
		jry_wb_add_onresize(function(){
			one.style.height=user.clientHeight+msg.clientHeight;
			msg.style.width=one.clientWidth-head.clientWidth-40;
		});
		var timer;
		msg.onmouseover=function()
		{
			timer=setInterval(function()
			{
				one.style.height=user.clientHeight+msg.clientHeight;				
			},100);
		};
		msg.onmouseleave=function()
		{
			clearInterval(timer);
		};
		one.style.height=user.clientHeight+msg.clientHeight;		
	}
	var first=true;
	var drawhead=(father,room,replace)=>
	{
		var head_width=0;
<?php if(JRY_WB_DEBUG_MODE){ ?>		
		console.log('drawhead',room);
<?php } ?>
		if(room.big)
		{
			if(room.head.type=='default')
			{
				var word=room.name;
				var canvas=document.createElement('canvas');
				if(replace!=undefined)
					replace.parentNode.insertBefore(canvas,replace),replace.parentNode.removeChild(replace);
				else
					father.appendChild(canvas);
				room.head_dom=canvas;
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
		}
		else
		{
			var id=room.users[0];
			if(id==jry_wb_login_user.id)
				id=room.users[1];
			var img=document.createElement('img');
			if(replace!=undefined)
				replace.parentNode.insertBefore(img,replace),replace.parentNode.removeChild(replace);
			else
				father.appendChild(img);
			img.classList.add('jry_wb_chat_head');			
			jry_wb_get_user(id,undefined,(data)=>
			{
				jry_wb_set_user_head_special(data,img);
			});
		}			
		return head_width;
	};
	var serchcallback=null;
	this.show_chat_rooms=(stop)=>
	{
		if(stop==true)
			console.timeEnd('chat_sync');
		if(typeof this.left=='undefined')
			return;
		if(typeof this.right=='undefined')
			return;
		if(loading_count>0)
			return;
		this.left.innerHTML='';
		let top=document.createElement('div');this.left.appendChild(top);
		top.classList.add('jry_wb_chat_top');
		top.style.background='#9990';
		top.style.transitionDuration='2s';
		var input=document.createElement('input');top.appendChild(input);
		input.classList.add('input');
		input.style.width=0;
		input.style.transitionDuration='1s';
		input.id='serchinput';
		top.onmouseover=function()
		{
			top.style.background='';
			input.onblur=function(){};
		};
		top.onmouseleave=function()
		{
			if(document.activeElement.id=='serchinput')		
				input.onblur=function()
				{
					top.style.background='#9990',result.innerHTML='',result.style.height=0,input.value='';
				};
			else
				top.style.background='#9990',result.innerHTML='',result.style.height=0,input.value='';
		};
		var timer=null;
		setTimeout(function()
		{
			input.style.transitionDuration='0s';
		},2000);
		var serch=document.createElement('span');top.appendChild(serch);
		serch.classList.add('jry_wb_icon_search','jry_wb_icon','button');
		serch.style.transitionDuration='0s';
		serch.onclick=()=>
		{
			if(timer!=null)
				clearTimeout(timer),timer=null;			
			if(!isNaN(parseInt(input.value)))
			{
				result.innerHTML='';
				result.style.height=0;
<?php if(JRY_WB_SOCKET_SWITCH){ ?>			
				if(jry_wb_socket.send({'code':true,'type':200007,'data':{'room':parseInt(input.value)}},false)==false)
					jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=get_room',(data)=>
					{
						jry_wb_loading_off();
						data=JSON.parse(data);
						if(data.code==false)
						{
							return;
						}
						else
							if(data.data[0]!=undefined)
								serchcallback(data.data[0]);
					},[{'name':'room','value':parseInt(input.value)}]);
<?php } ?>
				if(parseInt(input.value)!=jry_wb_login_user.id)
					jry_wb_get_user(input.value,undefined,(data)=>
					{
						if(data==undefined||data.head==null||data.name=='')
							return;
						let one=document.createElement('div');result.appendChild(one);
						one.classList.add('jry_wb_chat_one');
						var img=document.createElement('img');one.appendChild(img);
						jry_wb_set_user_head_special(data,img);
						img.classList.add('jry_wb_chat_head');
						var name_dom=document.createElement('span');one.appendChild(name_dom);
						name_dom.classList.add('jry_wb_chat_name','jry_wb_word_cut');
						name_dom.innerHTML=data.name;
						name_dom.style.width=Math.max(one.clientWidth-img.clientWidth,20);
						result.style.height=parseInt(result.style.height)+img.clientWidth;
						one.onclick=()=>
						{
							if(rooms.find(function(a){return a.big==false&&(a.users[0]==parseInt(input.value)||a.users[1]==parseInt(input.value))})!=undefined)
								return jry_wb_beautiful_right_alert.alert('您已经和这个人有聊天室了呀！',3000,'auto','ok');
<?php if(JRY_WB_SOCKET_SWITCH){ ?>	
							if(jry_wb_socket.send({'code':true,'type':200010,'data':parseInt(input.value)},false)==false)
<?php } ?>
							jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=start_between',(data)=>
							{
								jry_wb_loading_off();
								data=JSON.parse(data);
								window.location.hash=data.data;
								this.sync();
							},[{'name':'id','value':parseInt(input.value)}]);

						};
					});
			}
			else
				result.innerHTML='',result.style.height=0;
		};
		var add=document.createElement('span');top.appendChild(add);
		if(jry_wb_login_user.compentence.addchatroom)
		{
			add.classList.add('jry_wb_icon_xinjian','jry_wb_icon','button');
			add.style.transitionDuration='0s';		
			add.onclick=()=>
			{
				jry_wb_beautiful_alert.check('您确定新建聊天室吗?',function()
				{
<?php if(JRY_WB_SOCKET_SWITCH){ ?>			
					if(jry_wb_socket.send({'code':true,'type':200003},false)==false)
<?php } ?>
					jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=add_room',(data)=>
					{
						jry_wb_loading_off();
						data=JSON.parse(data);
						if(data.code==false)
						{
							return;
						}
						else				
							this.sync();
					});
				},function(){},'新建','放弃');
			};
		}
		var home=document.createElement('a');top.appendChild(home);
		home.classList.add('jry_wb_icon_home','jry_wb_icon','button');
		home.href=jry_wb_message.jry_wb_host+'jry_wb_mainpages/index.php';
		input.style.width=top.clientWidth-serch.offsetWidth-add.offsetWidth-home.offsetWidth-20;
		jry_wb_add_onresize(function()
		{
			input.style.width=top.clientWidth-serch.offsetWidth-add.offsetWidth-home.offsetWidth-20;
		});		
		var result=document.createElement('div');top.appendChild(result);
		result.classList.add('result');
		input.onkeyup=()=>
		{
<?php if(JRY_WB_SOCKET_SWITCH){ ?>			
			if(timer!=null)
				clearTimeout(timer);
			timer=setTimeout(function()
			{
				if(!isNaN(parseInt(input.value)))
					serch.onclick();
				else
					result.innerHTML='',result.style.height=0;
			},1000);
<?php } ?>
		};
		result.style.height=0;
		serchcallback=(room)=>
		{
			let one=document.createElement('div');result.appendChild(one);
			one.classList.add('jry_wb_chat_one');
			room.dom=one;
			let head_width=drawhead(one,room);
			room.name_dom=document.createElement('span');one.appendChild(room.name_dom);
			room.name_dom.classList.add('jry_wb_chat_name','jry_wb_word_cut');
			room.name_dom.innerHTML=room.name;
			room.name_dom.style.width=Math.max(one.clientWidth-head_width,20);
			room.lastsay_dom=document.createElement('span');one.appendChild(room.lastsay_dom);
			room.lastsay_dom.classList.add('jry_wb_chat_lastsay');
			room.lastsay_dom.style.width=Math.max(one.clientWidth-head_width,20);
			if(first)	
				jry_wb_add_onresize(function()
				{
					if(room.name_dom!=null)
						room.name_dom.style.width=Math.max(one.clientWidth-head_width,20);
					if(room.lastsay_dom!=null)
						room.lastsay_dom.style.width=Math.max(one.clientWidth-head_width,20);				
				});
			one.onclick=()=>
			{
				jry_wb_beautiful_alert.check('您确定加入聊天室'+room.name+'吗',function()
				{
					input.blur();
<?php if(JRY_WB_SOCKET_SWITCH){ ?>			
					if(jry_wb_socket.send({'code':true,'type':200001,'data':{'room':room.chat_room_id}},false)==false)
<?php } ?>
					jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=enter_room',(data)=>
					{
						jry_wb_loading_off();
						data=JSON.parse(data);
						if(data.code==false)
						{
							return;
						}
						else				
							this.sync();
					},[{'name':'room','value':room.chat_room_id}]);
				},function(){});
			};
			result.style.height=parseInt(result.style.height)+one.clientHeight;
		};
		for(let i=0;i<rooms.length;i++)
		{
			let one=document.createElement('div');this.left.appendChild(one);
			one.classList.add('jry_wb_chat_one');
			rooms[i].dom=one;
			let head_width=drawhead(one,rooms[i]);
			rooms[i].name_dom=document.createElement('span');one.appendChild(rooms[i].name_dom);
			rooms[i].name_dom.classList.add('jry_wb_chat_name','jry_wb_word_cut');
			if(rooms[i].big)
				rooms[i].name_dom.innerHTML=rooms[i].name;
			else
			{
				var id=rooms[i].users[0];
				if(id==jry_wb_login_user.id)
					id=rooms[i].users[1];
				jry_wb_get_user(id,undefined,(data)=>
				{
					rooms[i].name_dom.innerHTML=data.name;				
				});
			}
			rooms[i].name_dom.style.width=Math.max(one.clientWidth-head_width,20);
			if(rooms[i].id==jry_wb_login_user.id&&rooms[i].big)
				rooms[i].name_dom.oncontextmenu=(e)=>
				{
					jry_wb_beautiful_alert.prompt('请输入新名字',(value)=>
					{
						if(value=='')
							return;
<?php if(JRY_WB_SOCKET_SWITCH){ ?>			
						if(jry_wb_socket.send({'code':true,'type':200008,'data':{'room':rooms[i].chat_room_id,'to_name':value}},false)==false)
<?php } ?>
						jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=rename_room',(data)=>
						{
							jry_wb_loading_off();
							data=JSON.parse(data);
							if(data.code==false)
							{
								return;
							}
							else				
								this.sync();
						},[{'name':'room','value':1},{'name':'to_name','value':value}]);	
					});
					e.preventDefault();
					return false;
				};			
			rooms[i].lastsay_dom=document.createElement('span');one.appendChild(rooms[i].lastsay_dom);
			rooms[i].lastsay_dom.classList.add('jry_wb_chat_lastsay');
			rooms[i].lastsay_dom.style.width=Math.max(one.clientWidth-head_width,20);
			if(first)	
				jry_wb_add_onresize(function()
				{
					if(rooms[i].name_dom!=null)
						rooms[i].name_dom.style.width=Math.max(one.clientWidth-head_width,20);
					if(rooms[i].lastsay_dom!=null)
						rooms[i].lastsay_dom.style.width=Math.max(one.clientWidth-head_width,20);				
				});
			let last=messages.find(function(a){return a.chat_room_id==rooms[i].chat_room_id});
			if(last!=undefined)
			{
				jry_wb_get_user(last.id,false,function(data)
				{
					rooms[i].lastsay_dom.innerHTML=data.name+':'+last.message.slice(0,50);
				});
			}
			one.onclick=()=>
			{
				window.location.hash=now_show=rooms[i].chat_room_id;
				chenge_name_doms=[];
				this.right.innerHTML='';
				if(last_show_dom!=null)
					last_show_dom.classList.remove('active');
				one.classList.add('active');
				last_show_dom=one;
				var chat_top=document.createElement('div');this.right.appendChild(chat_top);
				chat_top.classList.add('jry_wb_chat_top');
				var chat_name=document.createElement('span');chat_top.appendChild(chat_name);
				chat_name.classList.add('jry_wb_chat_name');
				if(rooms[i].big)
					chat_name.innerHTML=rooms[i].name;
				else
				{
					var id=rooms[i].users[0];
					if(id==jry_wb_login_user.id)
						id=rooms[i].users[1];
					jry_wb_get_user(id,undefined,(data)=>
					{
						chat_name.innerHTML='和'+data.name+'的私聊';				
					});
				}
				chenge_name_doms.push(chat_name);
				var chat_set=document.createElement('span');chat_top.appendChild(chat_set);
				chat_set.classList.add('jry_wb_chat_set','jry_wb_icon','jry_wb_icon_icon_shezhi');
				chat_set.onclick=()=>
				{
					var set_alert=new jry_wb_beautiful_alert_function;
					var title=set_alert.frame("属性",document.body.clientWidth*0.50,document.body.clientHeight*0.75,document.body.clientWidth*1/4,document.body.clientHeight*3/32);
					var confirm = document.createElement("button"); title.appendChild(confirm);
					confirm.type="button"; 
					confirm.innerHTML="关闭"; 
					confirm.style='float:right;margin-right:20px;';
					confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_warn");
					confirm.onclick=function()
					{
						set_alert.close();
					};
					jry_wb_beautiful_scroll(set_alert.msgObj);
					var table = document.createElement("table"); set_alert.msgObj.appendChild(table);
					table.border=1;
					table.cellspacing=0;
					table.width='100%';
					var tr=document.createElement("tr"); table.appendChild(tr);
					var td=document.createElement("td"); tr.appendChild(td);td.innerHTML='聊天室ID';			td.width='150px';	td.classList.add('h56');
					var td=document.createElement("td"); tr.appendChild(td);td.innerHTML=rooms[i].chat_room_id;	td.width='*';		td.classList.add('h56');
					var tr=document.createElement("tr"); table.appendChild(tr);
					var td=document.createElement("td"); tr.appendChild(td);td.innerHTML='聊天室名字';			td.width='150px';	td.classList.add('h56');
					var name=document.createElement("td"); tr.appendChild(name);								name.width='*';		name.classList.add('h56');	
					if(rooms[i].big)
						name.innerHTML=rooms[i].name;
					else
					{
						var id=rooms[i].users[0];
						if(id==jry_wb_login_user.id)
							id=rooms[i].users[1];
						jry_wb_get_user(id,undefined,(data)=>
						{
							name.innerHTML='和'+data.name+'的私聊';				
						});
					}
					chenge_name_doms.push(name);
					if(rooms[i].id==jry_wb_login_user.id&&rooms[i].big)
						name.oncontextmenu=(e)=>
						{
							jry_wb_beautiful_alert.prompt('请输入新名字',(value)=>
							{
								if(value=='')
									return;
<?php if(JRY_WB_SOCKET_SWITCH){ ?>			
								if(jry_wb_socket.send({'code':true,'type':200008,'data':{'room':rooms[i].chat_room_id,'to_name':value}},false)==false)
<?php } ?>
								jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=rename_room',(data)=>
								{
									jry_wb_loading_off();
									data=JSON.parse(data);
									if(data.code==false)
									{
										return;
									}
									else				
										this.sync();
								},[{'name':'room','value':1},{'name':'to_name','value':value}]);	
							});
							e.preventDefault();
							return false;
						};
					var tr=document.createElement("tr"); table.appendChild(tr);
					var td=document.createElement("td"); tr.appendChild(td);td.innerHTML='聊天室大小';					td.width='150px';	td.classList.add('h56');				
					var td=document.createElement("td"); tr.appendChild(td);td.innerHTML=(rooms[i].big)?'群聊':'私聊';	td.width='150px';	td.classList.add('h56');
					var tr=document.createElement("tr"); table.appendChild(tr);
					var td=document.createElement("td"); tr.appendChild(td);td.innerHTML='创建时间';					td.width='150px';	td.classList.add('h56');				
					var td=document.createElement("td"); tr.appendChild(td);td.innerHTML=rooms[i].cream_time;			td.width='150px';	td.classList.add('h56');
					var tr=document.createElement("tr"); table.appendChild(tr);
					var td=document.createElement("td"); tr.appendChild(td);td.innerHTML='最后加入时间';				td.width='150px';	td.classList.add('h56');				
					var td=document.createElement("td"); tr.appendChild(td);td.innerHTML=rooms[i].last_add_time;		td.width='150px';	td.classList.add('h56');
					var tr=document.createElement("tr"); table.appendChild(tr);
					var td=document.createElement("td"); tr.appendChild(td);td.innerHTML='最后发言时间';				td.width='150px';	td.classList.add('h56');				
					var td=document.createElement("td"); tr.appendChild(td);td.innerHTML=rooms[i].last_say_time;		td.width='150px';	td.classList.add('h56');
					var tr=document.createElement("tr"); table.appendChild(tr);
					var td=document.createElement("td"); tr.appendChild(td);td.innerHTML='发言数量';					td.width='150px';	td.classList.add('h56');				
					var td=document.createElement("td"); tr.appendChild(td);td.innerHTML=rooms[i].say_count;			td.width='150px';	td.classList.add('h56');
					var tr=document.createElement("tr"); table.appendChild(tr);
					var td=document.createElement("td"); tr.appendChild(td);td.innerHTML='成员';						td.width='150px';	td.classList.add('h56');				
					var td=document.createElement("td"); tr.appendChild(td);											td.width='150px';	td.classList.add('h56');
					for(var j=0;j<rooms[i].users.length;j++)
						jry_wb_get_and_show_user(td,rooms[i].users[j],'auto','left',true);
				};
				var message_box=rooms[i].message_box=document.createElement('div');this.right.appendChild(message_box);
				console.time('message');
				let cnt=0,all=0;
				for(var j=messages.length-1;j>=0;j--)
					if(messages[j].chat_room_id==rooms[i].chat_room_id)
						all++,show_one_chat_message(message_box,messages[j],()=>
						{
							cnt++;
							if(cnt==all)
								this.message_scroll.scrollto(0,this.message_scroll.get_all_child_height());
						});
				console.timeEnd('message');
				var input_area=document.createElement('div');this.right.appendChild(input_area);
				input_area.classList.add('jry_wb_chat_input_area');
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
				message_box.style.height=this.right.clientHeight-input_area.clientHeight-chat_top.clientHeight;
				input.style.width=message_box.clientWidth-button.clientWidth-20;
				jry_wb_add_onresize(()=>
				{
					message_box.style.height=this.right.clientHeight-input_area.clientHeight-chat_top.clientHeight;
					input.style.width=message_box.clientWidth-button.clientWidth-20;					
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
				this.message_scroll=new jry_wb_beautiful_scroll(message_box,undefined,true);
			};
			if(window.location.hash=='#'+rooms[i].chat_room_id)
				setTimeout(()=>{one.onclick();},200);
		}
		setInterval(()=>
		{
			if(this.right.innerHTML!='')
				if(this.right.children[0]!==null)
					if(this.right.children[0].children.length>1)
						if(this.right.children[0].lastChild.previousElementSibling.offsetTop+this.right.children[0].lastChild.previousElementSibling.clientHeight<this.right.children[0].clientHeight)
						{
							var total=0;
							for(var i=0,n=this.right.children[0].children.length;i<n;i++)
							{
								total+=this.right.children[0].children[i].clientHeight;
								if(total>this.right.children[0].clientHeight)
									break;
							}						
							if(total>this.right.children[0].clientHeight)
								this.message_scroll.scrollto(0,this.message_scroll.get_all_child_height());								
						}
		},100);
		first=false;
	}
};
<?php if(false){ ?></script><?php } ?>