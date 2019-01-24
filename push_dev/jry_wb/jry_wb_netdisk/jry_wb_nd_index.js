var jry_nd_group=[];
var jry_nd_area=[];
var jry_nd_file_list=[];
var jry_nd_load_count=3;
function jry_wb_nd_fresh(qiangzhi)
{
	jry_nd_load_count=3;
	if(jry_wb_compare_time(jry_wb_cache.get_last_time('nd_group').split(/ /)[0],jry_nd_fast_save_message.group)<0||qiangzhi)
		jry_wb_sync_data_with_server('nd_group',jry_wb_message.jry_wb_host+'jry_wb_netdisk/jry_nd_get_information.php?action=group&lasttime='+jry_wb_cache.get_last_time('nd_group',qiangzhi),null,function(a)
		{
			return this.buf.jry_nd_group_id==a.jry_nd_group_id;
		},function(data)
		{
			jry_nd_group=data;
			jry_nd_load_count--;
			if(jry_nd_load_count==0)
				jry_wb_nd_show_files_by_dir(document.location.hash!=''?document.location.hash.split('#')[1]:'/');
		});
	else
		jry_nd_load_count--,jry_nd_group=jry_wb_cache.get('nd_group');
	if(jry_wb_compare_time(jry_wb_cache.get_last_time('nd_area').split(/ /)[0],jry_nd_fast_save_message.area)<0||qiangzhi)
		jry_wb_sync_data_with_server('nd_area',jry_wb_message.jry_wb_host+'jry_wb_netdisk/jry_nd_get_information.php?action=area&lasttime='+jry_wb_cache.get_last_time('nd_area',qiangzhi),null,function(a)
		{
			return this.buf.area_id==a.area_id;
		},function(data)
		{
			jry_nd_area=data;
			jry_nd_load_count--;
			if(jry_nd_load_count==0)
				jry_wb_nd_show_files_by_dir(document.location.hash!=''?document.location.hash.split('#')[1]:'/');
		});
	else
		jry_nd_load_count--,jry_nd_area=jry_wb_cache.get('nd_area');
	if(jry_wb_compare_time(jry_wb_cache.get_last_time('nd_file_list').split(/ /)[0],jry_wb_login_user.jry_wb_nd_extern_information.lasttime)<0||qiangzhi)
		jry_wb_sync_data_with_server('nd_file_list',jry_wb_message.jry_wb_host+'jry_wb_netdisk/jry_nd_get_information.php?action=file_list&lasttime='+jry_wb_cache.get_last_time('nd_file_list',qiangzhi),null,function(a)
		{
			return this.buf.file_id==a.file_id;
		},function(data)
		{
			jry_nd_file_list=data;
			jry_nd_load_count--;
			if(jry_nd_load_count==0)
				jry_wb_nd_show_files_by_dir(document.location.hash!=''?document.location.hash.split('#')[1]:'/');
		});
	else
		jry_nd_load_count--,jry_nd_file_list=jry_wb_cache.get('nd_file_list');
}
jry_wb_add_load(function()
{
	/*uploader=new jry_wb_netdisk_uploader(document.getElementById('uploader'));*/
	document.getElementsByClassName('jry_wb_top_toolbar')[0].style.display='none';
	document.getElementById('buttom_message').style.display='none';
	var body=document.getElementById('body');
	if(jry_wb_netdisk_first_time_use)
	{
		
	}
	jry_wb_nd_fresh(false);
	left_body=document.createElement('div');body.appendChild(left_body);			/*创建网盘左侧*/
	left_body.classList.add('jry_wb_netdisk_left_body');							/*设置左侧属性*/
	var left_body_scroll=new jry_wb_beautiful_scroll(left_body);
	right_body=document.createElement('div');body.appendChild(right_body);			/*创建网盘右侧*/
	right_body.classList.add('jry_wb_netdisk_right_body');							/*设置右侧属性*/	
	jry_wb_add_onresize(function()													/*自动宽度调整*/
	{
		right_body.style.width=document.documentElement.clientWidth-left_body.clientWidth;
	});	
	var back=document.createElement('a');left_body.appendChild(back);				/*返回首页*/
	back.href=jry_wb_message.jry_wb_index_page;
	back.classList.add('jry_wb_netdisk_back','iconfont');							/*返回首页属性*/
	back.innerHTML="返回"+jry_wb_message.jry_wb_name;								/*返回首页文字*/
	var head=document.createElement('div');left_body.appendChild(head);				/*头像*/
	head.classList.add('jry_wb_netdisk_head');										/*头像属性*/
	jry_wb_set_user_head_special(jry_wb_login_user,head);							/*头像特效*/
	head.src=jry_wb_login_user.head;												/*头像地址*/
	var name=document.createElement('div');left_body.appendChild(name);				/*用户名*/
	name.classList.add('jry_wb_netdisk_name');										/*用户名属性*/
	name.style.width=left_body.clientWidth;											/*用户名宽度*/
	var name_word=document.createElement('a');name.appendChild(name_word);			/*用户名文字*/
	name_word.innerHTML=jry_wb_login_user.name;										/*用户名文字内容*/
	name_word.style.background="#"+jry_wb_login_user.color;							/*权限组标记*/
	name_word.href=jry_wb_message.jry_wb_chenge_page;
	var progress=new jry_wb_progress_bar(left_body,left_body.clientWidth/2,jry_wb_login_user.jry_wb_nd_extern_information.jry_nd_size_used/jry_wb_login_user.jry_wb_nd_extern_information.jry_nd_size_total,jry_wb_login_user.jry_wb_nd_extern_information.jry_nd_size_used/1024+'MB/'+jry_wb_login_user.jry_wb_nd_extern_information.jry_nd_size_total/1024+'MB',null,null,"jry_wb_netdisk_progress","ok",false);/*容量占用*/
	var hr=document.createElement('div');left_body.appendChild(hr);					/*分割线*/
	hr.classList.add('jry_wb_netdisk_hr');											/*分割线属性*/
	var button_bar=document.createElement('div');left_body.appendChild(button_bar);	/*按钮栏*/
	button_bar.classList.add('jry_wb_netdisk_button_bar');							/*按钮栏属性*/
	var detail_mesage_button=document.createElement('button');button_bar.appendChild(detail_mesage_button);
	detail_mesage_button.classList.add('iconfont','icon-icon_zhanghao');
	var upload_mesage_button=document.createElement('button');button_bar.appendChild(upload_mesage_button);
	upload_mesage_button.classList.add('iconfont','icon-shangchuanjilu');
	var message=document.createElement('div');left_body.appendChild(message);		/*信息栏*/
	message.classList.add('jry_wb_netdisk_message');								/*信息栏属性*/
	message.style.height=Math.max(document.body.clientHeight-message.offsetTop,window.getComputedStyle(message,null).maxHeight);
	jry_wb_add_onresize(function()
	{
		left_body_scroll.scrollto(0);
		message.style.height=Math.max(document.body.clientHeight-message.offsetTop,window.getComputedStyle(message,null).maxHeight);
	});
	detail_mesage_button.onclick=function()
	{
		message.innerHTML='';
		var table=document.createElement('table');message.appendChild(table);
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='权限组ID';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=jry_wb_login_user.jry_wb_nd_extern_information.jry_nd_group_id;		
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='权限组名称';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=jry_wb_login_user.jry_wb_nd_extern_information.jry_nd_group_name;
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='全部空间';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=jry_wb_login_user.jry_wb_nd_extern_information.jry_nd_size_total/1024+'MB';
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='已用空间';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=jry_wb_login_user.jry_wb_nd_extern_information.jry_nd_size_used/1024+'MB';
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='可以上传的类型';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=jry_wb_login_user.jry_wb_nd_extern_information.jry_nd_allow_type==-1?'全部':jry_wb_login_user.jry_wb_nd_extern_information.jry_nd_allow_type.toString();		
	};
	upload_mesage_button.onclick=function()
	{
		message.innerHTML='';
		if(upload_queue.length==0)
		{			
			var div=document.createElement('div');message.appendChild(div);
			div.classList.add('icon-baoguo-kongde','empty','iconfont');
			var div=document.createElement('div');message.appendChild(div);
			div.classList.add('empty_text');
			div.innerHTML='没有记录';
			return ;
		}
		var table=document.createElement('table');message.appendChild(table);
		
	}
	detail_mesage_button.onclick();
	
	tool_list=document.createElement('div');right_body.appendChild(tool_list);
	tool_list.classList.add('jry_wb_netdisk_tool');
	top_lan=document.createElement('div');tool_list.appendChild(top_lan);
	top_lan.classList.add('top_lan');
	mid_lan=document.createElement('div');tool_list.appendChild(mid_lan);
	mid_lan.classList.add('mid_lan');
	top_lan_button1=document.createElement('div');top_lan.appendChild(top_lan_button1);
	top_lan_button1.classList.add('top_lan_button');
	top_lan_button1.innerHTML="主要操作";
	top_lan_button1.onclick=function()
	{
		mid_lan.innerHTML="";
		var label=document.createElement("label");mid_lan.appendChild(label);
		label.classList.add("mid_lan_button","iconfont","upload","icon-yunduanshangchuan");
		var input=document.createElement("input");mid_lan.appendChild(input);
		label.setAttribute("for",(input.id="jry_wb_uploader_"+Math.random()))
		input.multiple="multiple";
		input.type="file";
		input.style.display="none";
		input.onchange=function()
		{
			console.log(input.files);
			if(input.files.length==0)
				return ;
			var total_size=0;
			for(var i=0,n=input.files.length;i<n;i++)
				total_size+=Math.ceil(input.files[i].size/1024);
			var rest=jry_wb_login_user.jry_wb_nd_extern_information.jry_nd_size_total-jry_wb_login_user.jry_wb_nd_extern_information.jry_nd_size_used-total_size;
			if(rest<0)
			{
				jry_wb_beautiful_alert.alert("空间不够","请联系开发组");
				return;
			}
			var upload_check=  new jry_wb_beautiful_alert_function;
			var title=upload_check.frame("确认上传",document.body.clientWidth*0.75,document.body.clientHeight*0.75,document.body.clientWidth*3/32,document.body.clientHeight*3/32);
			var Confirm = document.createElement("button"); title.appendChild(Confirm);
			Confirm.type="button"; 
			Confirm.innerHTML="取消"; 
			Confirm.style='float:right;margin-right:20px;';
			Confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_warn");
			Confirm.onclick=function()
			{
				input.value='';
				upload_check.close();
			};
			var Confirm = document.createElement("button"); title.appendChild(Confirm);
			Confirm.type="button"; 
			Confirm.innerHTML="开始上传"; 
			Confirm.style='float:right;margin-right:20px;';
			Confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
			Confirm.onclick=function()
			{				
				for(var i=0,n=input.files.length;i<n;i++)
				{
					
				}
				
				upload_check.close();
				input.value='';
			};
			jry_wb_beautiful_scroll(upload_check.msgObj);
			var div = document.createElement("div"); upload_check.msgObj.appendChild(div);
			div.style="width:100%;margin:0;padding:0;overflow:hidden;";
			div.align='center';
			div.innerHTML="即将上传共计"+input.files.length+"个文件到"+dir_input.value+',预计消耗'+total_size/1024+'MB空间,剩余'+(rest)/1024+'MB空间<br>';
			div.innerHTML+="详细如下:<br>";
			var table=document.createElement('table');div.appendChild(table);
			var tr=document.createElement('tr');table.appendChild(tr);
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='文件名';	
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='大小/KB';	
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='修改日期';
			for(var i=0,n=input.files.length;i<n;i++)
			{
				var tr=document.createElement('tr');table.appendChild(tr);
				var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=input.files[i].name;	
				var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=Math.ceil(input.files[i].size/1024);	
				var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=input.files[i].lastModifiedDate;				
			}
		}
		var fresh=document.createElement("div");mid_lan.appendChild(fresh);
		fresh.classList.add("mid_lan_button","iconfont","fresh","icon-yunduanshuaxin");
		fresh.onclick=function()
		{
			jry_wb_nd_fresh(true);
		};
	};
	top_lan_button1.onclick();

	shangyige=document.createElement('div');tool_list.appendChild(shangyige);
	shangyige.classList.add('shangyige','icon-xiangzuo','iconfont');
	shangyige.onclick=function()
	{
		dir_stack.pop();
		var a=dir_stack.pop();
		if(a!=undefined)
			jry_wb_nd_show_files_by_dir(a);
		else
			jry_wb_beautiful_alert.alert("没有上一个了","嘤嘤嘤");
	};
	shangyiji=document.createElement('div');tool_list.appendChild(shangyiji);
	shangyiji.classList.add('shangyiji','icon-xuanzeqishouqi','iconfont');
	shangyiji.onclick=function()
	{
		var data=dir_input.value.split('/');
		var dir='/';
		for(var i=1,n=data.length-2;i<n;i++)
			dir+=data[i]+'/';
		if(dir!=dir_input.value)
			jry_wb_nd_show_files_by_dir(dir);
		else
			jry_wb_beautiful_alert.alert("没有上一级了","嘤嘤嘤");
		jry_wb_nd_show_files_by_dir(dir);
	};
	
	dir_input=document.createElement('input');tool_list.appendChild(dir_input);
	dir_input.classList.add('dir_input');
	serch_input=document.createElement('input');tool_list.appendChild(serch_input);
	serch_input.classList.add('serch_input');
	dir_input.onkeyup=function(e)
	{
		var keycode=(e.keyCode||e.which);
		if(keycode==jry_wb_keycode_enter)
		{
			jry_wb_nd_show_files_by_dir(dir_input.value);
			serch_input.value='';
		}
	}
	dir_input.onclick=function()
	{
		if(dir_input.value=='')
			dir_input.value=dir_stack[dir_stack.length-1];
		jry_wb_nd_show_files_by_dir(dir_input.value);
	}
	
	serch_input.onkeyup=function(e)
	{
		var keycode=(e.keyCode||e.which);
		var word=serch_input.value;
		if(keycode==jry_wb_keycode_enter)
		{
			jry_wb_nd_show_files(function(file)
			{
				return (file.name.includes(word)||file.type.includes(word));
			});
			serch_input.blur();
			dir_input.value='';
		}
	}		
	dir_input.style.width=tool_list.clientWidth-serch_input.clientWidth;	
	jry_wb_add_onresize(function()
	{
		dir_input.style.width=tool_list.clientWidth-serch_input.clientWidth-shangyige.clientWidth-shangyiji.clientWidth;
	});
	
	document_list=document.createElement('div');right_body.appendChild(document_list);
	document_list.classList.add('jry_wb_netdisk_document');
	var right_body_scroll=new jry_wb_beautiful_scroll(document_list);
	if(jry_nd_load_count==0)
		jry_wb_nd_show_files_by_dir(document.location.hash!=''?document.location.hash.split('#')[1]:'/');
	jry_wb_add_onresize(function()
	{
		document_list.style.height=document.body.clientHeight-document_list.offsetTop;
	});	
});
function jry_wb_nd_show_files(checker)
{
	var flag=false;
	document_list.innerHTML='';
	for(var i=0,n=jry_nd_file_list.length;i<n;i++)
	{
		if(checker(jry_nd_file_list[i]))
		{
			flag=true;
			var one=document.createElement('div');document_list.appendChild(one);
			one.classList.add('jry_wb_netdisk_file');
			var button=document.createElement('div');one.appendChild(button);
			button.classList.add('jry_wb_netdisk_file_type','iconfont',jry_wb_nd_get_class_by_type(jry_nd_file_list[i].type)[0],jry_wb_nd_get_class_by_type(jry_nd_file_list[i].type)[1]);
			button.name=JSON.stringify({'name':jry_nd_file_list[i].name,'type':jry_nd_file_list[i].type,'dir':jry_nd_file_list[i].dir});
			button.onclick=function()
			{
				data=JSON.parse(this.name);
				if(data.type==='dir')
					jry_wb_nd_show_files_by_dir(data.dir+data.name+'/');
				
			};
			var name=document.createElement("div");one.appendChild(name);
			name.innerHTML=(jry_nd_file_list[i].name+(jry_nd_file_list[i].type=='dir'?'':('.'+jry_nd_file_list[i].type)));
			name.classList.add('jry_wb_netdisk_file_name','jry_wb_word_cut');
		}
	}
	if(!flag)
	{
		jry_wb_beautiful_alert.alert("没有文件或不存在的目录","");
	}
	return flag;
}
var dir_stack=[];
var upload_queue=[];
function jry_wb_nd_show_files_by_dir(dir)
{
	dir=dir==undefined?'/':dir;
	dir=dir==''?'/':dir;
	dir_input.value=document.location.hash=dir;
	jry_wb_nd_show_files(function(file){return file.dir==dir});
	dir_stack.push(dir);
}
