//测试代码大全
//AJAX
//发送信息
jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=send',function(data){jry_wb_loading_off();console.log(JSON.parse(data))},[{'name':'room','value':1},{'name':'message','value':'ajaxsend'}]);
//加入房间
jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=enter_room',function(data){jry_wb_loading_off();console.log(JSON.parse(data))},[{'name':'room','value':1}]);
//推出房间
jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=exit_room',function(data){jry_wb_loading_off();console.log(JSON.parse(data))},[{'name':'room','value':1}]);
//创建房间
jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=add_room',function(data){jry_wb_loading_off();console.log(JSON.parse(data))});
//删除房间
jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=delete_room',function(data){jry_wb_loading_off();console.log(data)},[{'name':'room','value':1}]);
//获取房间
jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=get_rooms',function(data){jry_wb_loading_off();console.log(JSON.parse(data))});
//获取房间消息
jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=get_message',function(data){jry_wb_loading_off();console.log(JSON.parse(data))},[{'name':'room','value':1},{'name':'lasttime','value':'1926-18-17 00:00:00'}]);
jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=get_message',function(data){jry_wb_loading_off();console.log(JSON.parse(data))},[{'name':'room','value':JSON.stringify([1,2])},{'name':'lasttime','value':'1926-18-17 00:00:00'}]);
//获取房间信息
jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=get_room',function(data){jry_wb_loading_off();console.log(JSON.parse(data))},[{'name':'room','value':1}]);
jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=get_room',function(data){jry_wb_loading_off();console.log(JSON.parse(data))},[{'name':'room','value':JSON.stringify([1,2])}]);
jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=get_room',function(data){jry_wb_loading_off();console.log(JSON.parse(data))},[{'name':'room','value':JSON.stringify([1,2])},{'name':'lasttime','value':'1926-18-17 00:00:00'}]);
//重命名房间
jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=rename_room',function(data){jry_wb_loading_off();console.log(JSON.parse(data))},[{'name':'room','value':1},{'name':'to_name','value':'test'}]);
//重设置房间头
jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=reset_room_head',function(data){jry_wb_loading_off();console.log(JSON.parse(data))},[{'name':'room','value':1},{'name':'to_head','value':JSON.stringify({'type':'default'})}]);
//私聊
jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_chat/jry_wb_do_chat.php?action=start_between',function(data){jry_wb_loading_off();console.log(JSON.parse(data))},[{'name':'id','value':45}]);
//socket
//获取监听器
jry_wb_socket.send({'code':true,'type':100003});
//发送信息
jry_wb_socket.send({'code':true,'type':200000,'data':{'room':1,'message':'socketsend'}});
//加入房间
jry_wb_socket.send({'code':true,'type':200001,'data':{'room':1}});
//推出房间
jry_wb_socket.send({'code':true,'type':200002,'data':{'room':1}});
//创建房间
jry_wb_socket.send({'code':true,'type':200003});
//删除房间
jry_wb_socket.send({'code':true,'type':200004,'data':{'room':1}});
//获取房间
jry_wb_socket.send({'code':true,'type':200005});
//获取房间消息
jry_wb_socket.send({'code':true,'type':200006,'data':{'room':1,'lasttime':'1926-08-17 00:00:00'}});
jry_wb_socket.send({'code':true,'type':200006,'data':{'room':[1,2],'lasttime':'1926-08-17 00:00:00'}});
//获取房间信息
jry_wb_socket.send({'code':true,'type':200007,'data':{'room':1}});
jry_wb_socket.send({'code':true,'type':200007,'data':{'room':[1,2]}});
jry_wb_socket.send({'code':true,'type':200007,'data':{'room':[1,2],'lasttime':'1926-08-17 00:00:00'}});
//重命名房间
jry_wb_socket.send({'code':true,'type':200008,'data':{'room':1,'to_name':'1231231231'}});
//重设置房间头
jry_wb_socket.send({'code':true,'type':200009,'data':{'room':1,'to_head':{'type':'default'}}});
//私聊
jry_wb_socket.send({'code':true,'type':200010,'data':1});
//加入监听
jry_wb_socket.add_listener(200000,function(data){console.log('来自'+data.from+'在'+data.data.room+'的新信息'+data.data.message);})
jry_wb_socket.add_listener(200001,function(data){console.log(data.from+'加入聊天室'+data.data.room);})
jry_wb_socket.add_listener(200002,function(data){console.log(data.from+'离开聊天室'+data.data.room);})
jry_wb_socket.add_listener(200004,function(data){console.log(data.from+'删除聊天室'+data.data.room);})
jry_wb_socket.add_listener(200005,function(data){console.log(data.from+'的聊天室',data.data);})
jry_wb_socket.add_listener(200006,function(data){console.log(data.from+'的消息',data.data);})
jry_wb_socket.add_listener(200007,function(data){console.log('房间',data.data);})
jry_wb_socket.add_listener(200008,function(data){console.log(data.from+'重命名'+data.data.room+'房间为'+data.data.name);})
jry_wb_socket.add_listener(200009,function(data){console.log(data.from+'重设'+data.data.room+'房间头为',data.data.head);})
jry_wb_socket.add_error(600000,function(data){console.error(data.reason);})
jry_wb_socket.add_error(600001,function(data){console.error(data.reason);})
jry_wb_socket.add_error(600002,function(data){console.error(data.reason);})
jry_wb_socket.add_error(600003,function(data){console.error(data.reason);})
jry_wb_socket.add_error(600004,function(data){console.error(data.reason);})
jry_wb_socket.add_error(600005,function(data){console.error(data.reason);})






jry_wb_socket.add_error(500000,function(data){console.error(data.reason);})
jry_wb_socket.add_error(500001,function(data){console.error(data.reason);})
jry_wb_socket.add_error(500002,function(data){console.error(data.reason);})
jry_wb_socket.add_error(500003,function(data){console.error(data.reason);})






document.body.removeChild(canvas);
var width=50;
var fontsize=20;
var word='聊天室';
var canvas=document.createElement('canvas');document.body.appendChild(canvas);
canvas.style.height=width;
canvas.style.width=width;
canvas.style.borderRadius=width/2+'px';
canvas.height=width;
canvas.width=width;
canvas.style.position='fixed';canvas.style.top=200;canvas.style.left=0;
var ctx=canvas.getContext("2d");
ctx.fillStyle='#ffffff';
ctx.fillRect(0,0,width,width);
ctx.font=fontsize+'px Arial';
ctx.fillStyle='#00ff00';
ctx.fillText(word.slice(0,2),width/2-(fontsize*word.slice(0,2).length)/2,fontsize*1+(width-fontsize*2)/2);
if(word.length<=4)
	ctx.fillText(word.slice(2,4),width/2-(fontsize*word.slice(2,4).length)/2,fontsize*2+(width-fontsize*2)/2);
else
	ctx.fillText(word.slice(2,3)+'...',width/2-(fontsize*2)/2,fontsize*2+(width-fontsize*2)/2);
	







var worker=new SharedWorker(jry_wb_message.jry_wb_host+'jry_wb_js/jry_wb_socket_worker.js.php');
worker.port.onmessage=function(data)
{
	data=data.data;
	console.log(data);
};
worker.port.start();
worker.port.postMessage({'type':100000,'data':jry_wb_login_user});


<span style="
    font-size: 16px;
    position: absolute;
    top: 0px;
    left: 0px;
    background: #ff000066;
    border-radius: 10px;
    width: 20px;
    height: 20px;
    text-align: center;
    color: white;
">99</span>
