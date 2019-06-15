var jry_nd_group=[];
var jry_nd_area=[];
var jry_nd_file_list=[];
var jry_nd_load_count=3;
var jry_nd_upload_count=0;
var jry_nd_upload_list=[];
var jry_nd_selected_list=[];
jry_wb_right_meau=null;
jry_wb_include_once_script('jry_wb_nd_tools.js');
jry_wb_include_once_script('jry_wb_nd_file.js');
jry_wb_include_once_script('jry_wb_nd_upload_file.js.php');
jry_wb_include_once_script('jry_wb_nd_area.js');
jry_wb_include_once_script('jry_wb_nd_alert_list.js');
jry_wb_include_once_script('jry_wb_nd_fresh_file_list.js');
jry_wb_include_once_script('jry_wb_nd_fresh_share_list.js');
jry_wb_include_once_script('jry_wb_nd_fresh.js');
jry_wb_include_once_script('jry_wb_nd_show_files.js');
jry_wb_include_once_script(jry_wb_message.jry_wb_host+'jry_wb_tp_sdk/aly/OSS/js/aliyun-oss-sdk-6.1.0.min.js');
jry_wb_include_once_css('jry_wb_nd.css');
jry_wb_include_once_css('jry_wb_nd_file.css');
jry_wb_add_load(function()
{
	var body=document.getElementById('body');
	if(jry_wb_netdisk_first_time_use)
	{
		
	}
	jry_wb_nd_fresh(false);
	left_body=document.createElement('div');body.appendChild(left_body);			/*创建网盘左侧*/
	left_body.classList.add('jry_wb_netdisk_left_body');							/*设置左侧属性*/
	right_body=document.createElement('div');body.appendChild(right_body);			/*创建网盘右侧*/
	right_body.classList.add('jry_wb_netdisk_right_body');							/*设置右侧属性*/	
	jry_wb_add_onresize(function()													/*自动宽度调整*/
	{
		right_body.style.width=document.documentElement.clientWidth-left_body.clientWidth-2;
	});	
	var back=document.createElement('a');left_body.appendChild(back);				/*返回首页*/
	back.href=jry_wb_message.jry_wb_index_page;
	back.classList.add('jry_wb_netdisk_back','jry_wb_icon');						/*返回首页属性*/
	back.innerHTML="返回"+jry_wb_message.jry_wb_name;								/*返回首页文字*/
	var head=document.createElement('div');left_body.appendChild(head);				/*头像*/
	head.classList.add('jry_wb_netdisk_head');										/*头像属性*/
	if(!jry_nd_share_mode_flag)
		jry_wb_set_user_head_special(jry_wb_login_user,head);						/*头像特效*/
	else
		head.style.backgroundImage='url('+jry_wb_message.jry_wb_logo+')',head.style.backgroundRepeat='no-repeat',head.style.backgroundSize="100% 100%";
	var name=document.createElement('div');left_body.appendChild(name);				/*用户名*/
	name.classList.add('jry_wb_netdisk_name');										/*用户名属性*/
	name.style.width=left_body.clientWidth;											/*用户名宽度*/
	var name_word=document.createElement('a');name.appendChild(name_word);			/*用户名文字*/
	if(!jry_nd_share_mode_flag)
	{
		name_word.innerHTML=jry_wb_login_user.name;									/*用户名文字内容*/
		name_word.style.background="#"+jry_wb_login_user.color;						/*权限组标记*/
		name_word.href=jry_wb_message.jry_wb_chenge_page;
	}
	else
	{	
		name_word.innerHTML="欢迎访问"+jry_wb_message.jry_wb_name;					/*用户名文字内容*/
		name_word.href=jry_wb_message.jry_wb_index_page;
	}
	if(!jry_nd_share_mode_flag)
		progress=new jry_wb_progress_bar(left_body,left_body.clientWidth/2,jry_wb_login_user.nd_ei.size_used/jry_wb_login_user.nd_ei.size_total,jry_wb_get_size(jry_wb_login_user.nd_ei.size_used)+'/'+jry_wb_get_size(jry_wb_login_user.nd_ei.size_total),null,null,"jry_wb_netdisk_progress","ok",false);/*容量占用*/
	var hr=document.createElement('div');left_body.appendChild(hr);					/*分割线*/
	hr.classList.add('jry_wb_netdisk_hr');											/*分割线属性*/
	var button_bar=document.createElement('div');left_body.appendChild(button_bar);	/*按钮栏*/
	button_bar.classList.add('jry_wb_netdisk_button_bar');							/*按钮栏属性*/
	detail_mesage_button=document.createElement('button');button_bar.appendChild(detail_mesage_button);
	detail_mesage_button.classList.add('jry_wb_icon','jry_wb_icon_icon_zhanghao');
	if(!jry_nd_share_mode_flag)
	{	
		var upload_mesage_button=document.createElement('button');button_bar.appendChild(upload_mesage_button);
		upload_mesage_button.classList.add('jry_wb_icon','jry_wb_icon_shangchuanjilu');
	}
	var error_mesage_button=document.createElement('button');button_bar.appendChild(error_mesage_button);
	error_mesage_button.classList.add('jry_wb_icon','jry_wb_icon_cuowuxinxi');
	if(!jry_nd_share_mode_flag)
	{		
		green_money_button=document.createElement('button');button_bar.appendChild(green_money_button);
		green_money_button.classList.add('jry_wb_icon','jry_wb_icon_jinbiduihuan');
		select_mesage_button=document.createElement('button');button_bar.appendChild(select_mesage_button);
		select_mesage_button.classList.add('jry_wb_icon','jry_wb_icon_xuanzhongwenjian');
		select_mesage_button.style.display='none';
	}
	var message=document.createElement('div');left_body.appendChild(message);		/*信息栏*/
	message.classList.add('jry_wb_netdisk_message');								/*信息栏属性*/
	message.style.height=Math.max(document.body.clientHeight-message.offsetTop,window.getComputedStyle(message,null).maxHeight);
	var left_body_scroll=new jry_wb_beautiful_scroll(left_body,true);
	jry_wb_add_onresize(function()
	{
		left_body_scroll.scrollto(0,0);
		message.style.height=Math.max(document.body.clientHeight-message.offsetTop,window.getComputedStyle(message,null).maxHeight);
	});
	var upload_refresh_timer=null;
	if(jry_nd_share_mode_flag)
	{
		detail_mesage_button.onclick=function()
		{
			if(upload_refresh_timer!=null)
			{
				clearInterval(upload_refresh_timer);
				upload_refresh_timer=null;
			}
			message.innerHTML='';
			var div=document.createElement('div');message.appendChild(div);
			div.classList.add('h56');
			div.innerHTML='来自';
			jry_wb_get_and_show_user(div,jry_nd_share_mode_id,null,null,true);
			div.innerHTML+='的'+jry_wb_message.jry_wb_name+'网盘分享';
		}			
	}
	error_mesage_button.onclick=function()
	{
		if(upload_refresh_timer!=null)
		{
			clearInterval(upload_refresh_timer);
			upload_refresh_timer=null;
		}
		message.innerHTML='';		
		var div=document.createElement('div');message.appendChild(div);	div.innerHTML='错误对照表';div.style.textAlign='center';
		var table=document.createElement('table');message.appendChild(table);
		table.setAttribute('border',1);table.setAttribute('cellspacing',0);table.setAttribute('cellpadding',0);
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='200000';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='错误的存储区';
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='200001';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='不允许的文件类型';
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='200002';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='用户空间不足';
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='200003';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='当前存储区域不足';
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='200004';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='分片上传数据发送错误';
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='200005';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='文件重复';
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='200006';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='父目录不存在';
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='200007';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='文件尺寸相差过大';
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='200008';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='文件不存在或已删除';
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='200009';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='慢速下载过大文件';
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='200010';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='加速器错误';
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='220000';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='OSSSDK未知错误';	
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='220001';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='连接错误';
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='220002';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='不存在的文件';	
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='220003';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='STS签名错误';
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='230000';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='缓存更新模式错误';	
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='230001';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='使用过的尺寸为负数';		
		var tr=document.createElement('tr');table.appendChild(tr);
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='230000';
		var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='不存在的分享';		
	};
	error_mesage_button.onclick();
	if(!jry_nd_share_mode_flag)
	{
		detail_mesage_button.onclick=function()
		{
			if(upload_refresh_timer!=null)
			{
				clearInterval(upload_refresh_timer);
				upload_refresh_timer=null;
			}
			message.innerHTML='';
			var table=document.createElement('table');message.appendChild(table);
			table.setAttribute('border',1);table.setAttribute('cellspacing',0);table.setAttribute('cellpadding',0);
			var tr=document.createElement('tr');table.appendChild(tr);
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='权限组ID';
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=jry_wb_login_user.nd_ei.group_id;		
			var tr=document.createElement('tr');table.appendChild(tr);
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='权限组名称';
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=jry_wb_login_user.nd_ei.group_name;
			var tr=document.createElement('tr');table.appendChild(tr);
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='全部空间';
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=jry_wb_get_size(jry_wb_login_user.nd_ei.size_total);
			var tr=document.createElement('tr');table.appendChild(tr);
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='已用空间';
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=jry_wb_get_size(jry_wb_login_user.nd_ei.size_used);
			var tr=document.createElement('tr');table.appendChild(tr);
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='可以上传的类型';
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=jry_wb_login_user.nd_ei.allow_type==-1?'全部':jry_wb_login_user.nd_ei.allow_type.toString();		
			var tr=document.createElement('tr');table.appendChild(tr);
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='剩余高速下载流量';
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=jry_wb_get_size(jry_wb_login_user.nd_ei.fast_size);		
		};
		upload_mesage_button.onclick=function()
		{
			if(upload_refresh_timer!=null)
				return ;
			message.innerHTML='';
			if(jry_nd_upload_list.length==0)
			{			
				var div=document.createElement('div');message.appendChild(div);
				div.classList.add('jry_wb_icon_baoguo-kongde','empty','jry_wb_icon');
				var div=document.createElement('div');message.appendChild(div);
				div.classList.add('empty_text');
				div.innerHTML='没有记录';
				return ;
			}
			var table=document.createElement('table');message.appendChild(table);
			table.setAttribute('border',1);table.setAttribute('cellspacing',0);table.setAttribute('cellpadding',0);
			table.style.width=message.clientWidth;
			var tr=document.createElement('tr');table.appendChild(tr);
			var td1=document.createElement('td');tr.appendChild(td1);td1.innerHTML='速度';
			td1.classList.add('jry_wb_word_cut');
			td1.style.width=message.clientWidth*0.25;
			var speed=document.createElement('td');tr.appendChild(speed);	
			var tr=document.createElement('tr');table.appendChild(tr);
			var td1=document.createElement('td');tr.appendChild(td1);td1.innerHTML='总进度';
			td1.classList.add('jry_wb_word_cut');
			td1.style.width=message.clientWidth*0.25;
			var td=document.createElement('td');tr.appendChild(td);		
			progress_total={'progress':new jry_wb_progress_bar(td,message.clientWidth*0.65,0,'0B/0B',null,null,"progress","ok",false),'td1':td1};		
			var progress_list=[];
			for(var i=0,n=jry_nd_upload_list.length;i<n;i++)
			{
				var tr=document.createElement('tr');table.appendChild(tr);
				var td1=document.createElement('td');tr.appendChild(td1);td1.innerHTML=jry_nd_upload_list[i].name;
				td1.classList.add('jry_wb_word_cut');
				td1.style.width=message.clientWidth*0.25;
				var td=document.createElement('td');tr.appendChild(td);	
				progress_list[i]={'progress':new jry_wb_progress_bar(td,message.clientWidth*0.65,jry_nd_upload_list[i].loaded/jry_nd_upload_list[i].total,jry_wb_get_size(jry_nd_upload_list[i].loaded)+'/'+jry_wb_get_size(jry_nd_upload_list[i].total),null,null,"progress","ok",false),'td1':td1};
			}
			var last=0;
			var cnttt=[];
			upload_refresh_timer=setInterval(function()
			{
				var cnt=0;
				var loaded=0;
				var total=0;
				for(var i=0,n=jry_nd_upload_list.length;i<n;i++)
				{
					loaded+=jry_nd_upload_list[i].loaded;
					total+=jry_nd_upload_list[i].total;
					if(jry_nd_upload_list[i].loaded/jry_nd_upload_list[i].total>=1)
					{
						cnt++;
						progress_list[i].progress.update(jry_nd_upload_list[i].loaded/jry_nd_upload_list[i].total,jry_wb_get_size(jry_nd_upload_list[i].loaded)+'/'+jry_wb_get_size(jry_nd_upload_list[i].total));
						progress_list[i].td1.classList.add('jry_wb_icon','jry_wb_icon_duigoux');
					}
					else if(jry_nd_upload_list[i].stopupload)
					{
						cnt++;
						progress_list[i].progress.update(jry_nd_upload_list[i].loaded/jry_nd_upload_list[i].total,jry_wb_get_size(jry_nd_upload_list[i].loaded)+'/'+jry_wb_get_size(jry_nd_upload_list[i].total));
						progress_list[i].td1.innerHTML=jry_nd_upload_list[i].fail_reason+';'+progress_list[i].td1.innerHTML;
						progress_list[i].td1.classList.add('jry_wb_icon','jry_wb_icon_guanbi1');
					}
					else
					{
						progress_list[i].progress.update(jry_nd_upload_list[i].loaded/jry_nd_upload_list[i].total,jry_wb_get_size(jry_nd_upload_list[i].loaded)+'/'+jry_wb_get_size(jry_nd_upload_list[i].total));
					}
				}
				if(last==0)
					last=loaded;
				cnttt[cnttt.length]=loaded-last;
				last=loaded;
				var here=0;
				if(cnttt.length>100)
					cnttt.splice(0,1);
				for(var i=0;i<cnttt.length;i++)
					here+=cnttt[i];
				progress_total.progress.update(loaded/total,jry_wb_get_size(loaded)+'/'+jry_wb_get_size(total));
				speed.innerHTML=jry_wb_get_size((here)*10/cnttt.length)+'/s'+';还要'+parseInt((total-loaded)/((here)*10/cnttt.length))+'s';
				if(cnt==jry_nd_upload_list.length)
				{
					progress_total.td1.classList.add('jry_wb_icon','jry_wb_icon_duigoux');
					clearInterval(upload_refresh_timer);
					upload_refresh_timer=null;
				}
			}
			,500);
		};
		select_mesage_button.onclick=function()
		{
			if(upload_refresh_timer!=null)
			{
				clearInterval(upload_refresh_timer);
				upload_refresh_timer=null;
			}
			message.innerHTML='';			
			var table=document.createElement('table');message.appendChild(table);
			table.setAttribute('border',1);table.setAttribute('cellspacing',0);table.setAttribute('cellpadding',0);
			table.style.width=message.clientWidth;
			table.name='select_mesage_button_member';
			for(let i=0,n=jry_nd_selected_list.length;i<n;i++)
			{
				var tr=document.createElement('tr');table.appendChild(tr);
				tr.name='select_mesage_button_member';
				var td1=document.createElement('td');tr.appendChild(td1);
				td1.innerHTML=jry_nd_selected_list[i].file.name+(jry_nd_selected_list[i].file.isdir?'':('.'+jry_nd_selected_list[i].file.type));
				td1.name='select_mesage_button_member';
				td1.classList.add('jry_wb_word_cut');
				td1.style.width=message.clientWidth-4+'px';
				td1.onclick=function()
				{
					jry_nd_selected_list[i].body.classList.remove('jry_wb_netdisk_file_active');
					jry_nd_selected_list.splice(i,1);
					if(jry_nd_selected_list.length==0)
						select_delete();
					else
						select_mesage_button.onclick();
				};
			}			
		};
		green_money_button.onclick=function()
		{
			if(upload_refresh_timer!=null)
			{
				clearInterval(upload_refresh_timer);
				upload_refresh_timer=null;
			}
			message.innerHTML='';
			var table=document.createElement('table');message.appendChild(table);
			table.setAttribute('border',1);table.setAttribute('cellspacing',0);table.setAttribute('cellpadding',0);
			table.style.width=message.clientWidth;
			var tr=document.createElement('tr');table.appendChild(tr);
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='您的绿币';
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=jry_wb_login_user.green_money.toFixed(3);
			var tr=document.createElement('tr');table.appendChild(tr);
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='今日价格';
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='高速流量:'+jry_wb_get_size(jry_nd_price_fast_size)+'/绿币<br>'+'空间:'+jry_wb_get_size(jry_nd_price_size)+'/绿币/月';			
			var tr=document.createElement('tr');table.appendChild(tr);
			var td1=document.createElement('td');tr.appendChild(td1);			td1.innerHTML='买流量';
			var td=document.createElement('td');tr.appendChild(td);
			var fast_size=document.createElement('input');td.appendChild(fast_size);fast_size.style.fontSize='16px';fast_size.style.width=message.clientWidth-td1.clientWidth-50;
			fast_size.value=0;
			var span=document.createElement('span');td.appendChild(span);span.innerHTML='KB<br>';
			var fast_size_span=document.createElement('span');td.appendChild(fast_size_span);
			fast_size_span.innerHTML='共'+jry_wb_get_size(fast_size.value)+'<br>耗费'+(fast_size.value/jry_nd_price_fast_size).toFixed(4)+'个绿币';
			fast_size_value=0;
			fast_size.onkeyup=function()
			{
				if(fast_size.value=='')
					fast_size.value=0;
				fast_size.value=fast_size_value=Math.ceil(fast_size.value);
				if(isNaN(fast_size_value)||fast_size_value==undefined)
					fast_size.value=0;
				if(fast_size_value/jry_nd_price_fast_size>jry_wb_login_user.green_money)
					fast_size_span.style.color='#ff0000';
				else
					fast_size_span.style.color='#000000';				
				fast_size_span.innerHTML='共'+jry_wb_get_size(fast_size_value)+'<br>耗费'+(fast_size_value/jry_nd_price_fast_size).toFixed(4)+'个绿币';
			};
			td.appendChild(document.createElement('br'));
			var button=document.createElement('button');td.appendChild(button);button.innerHTML='购买';button.classList.add('jry_wb_button','jry_wb_button_size_small','jry_wb_color_ok');
			button.onclick=function()
			{
				if(fast_size_value==0)
					return jry_wb_beautiful_right_alert.alert("无效操作",3000,"auto","error");
				if(fast_size_value/jry_nd_price_fast_size>jry_wb_login_user.green_money)
					return jry_wb_beautiful_right_alert.alert("余额不足",3000,"auto","error");
				jry_wb_ajax_load_data('jry_nd_do_file.php?action=add_fast_size&size='+fast_size_value,function(data)
				{
					jry_wb_loading_off();
					data=JSON.parse(data);
					if(!data.code)
					{
						if(data.reason==100000)			jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
						else if(data.reason==100001)	jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
						else if(data.reason==300002)	jry_wb_beautiful_right_alert.alert("余额不足",3000,"auto","error");
						green_money_button.onclick();									
						return;
					}
					jry_wb_login_user.nd_ei.fast_size=data.fast_size;
					jry_wb_login_user.green_money=data.green_money;
					jry_wb_beautiful_right_alert.alert("购买"+jry_wb_get_size(fast_size_value)+"高速流量成功",3000,"auto","ok");
					green_money_button.onclick();		
				});
			};
			var tr=document.createElement('tr');table.appendChild(tr);
			var td1=document.createElement('td');tr.appendChild(td1);			td1.innerHTML='买空间';
			var td=document.createElement('td');tr.appendChild(td);
			var size_total=document.createElement('input');td.appendChild(size_total);size_total.style.fontSize='16px';size_total.style.width=message.clientWidth-td1.clientWidth-50;
			size_total.value=0;
			var span=document.createElement('span');td.appendChild(span);span.innerHTML='KB<br>';
			var month=document.createElement('input');td.appendChild(month);month.style.fontSize='16px';month.style.width=message.clientWidth-td1.clientWidth-50;
			month.value=1;
			var span=document.createElement('span');td.appendChild(span);span.innerHTML='月<br>';
			var size_total_span=document.createElement('span');td.appendChild(size_total_span);
			size_total_month=size_total_value=1;
			size_total_span.innerHTML='共'+jry_wb_get_day(size_total_month*60*60*24*30,false)+jry_wb_get_size(size_total_value)+'存储包<br>耗费'+(size_total_value/jry_nd_price_size*size_total_month).toFixed(4)+'个绿币';
			size_total.onkeyup=function()
			{
				if(size_total.value=='')
					size_total.value=0;
				size_total.value=size_total_value=Math.ceil(size_total.value);
				if(isNaN(size_total_value)||size_total_value==undefined)
					size_total.value=0;	
				if(size_total_value/jry_nd_price_size*size_total_month>jry_wb_login_user.green_money)
					size_total_span.style.color='#ff0000';
				else
					size_total_span.style.color='#000000';
				size_total_span.innerHTML='共'+jry_wb_get_day(size_total_month*60*60*24*30,false)+jry_wb_get_size(size_total_value)+'存储包<br>耗费'+(size_total_value/jry_nd_price_size*size_total_month).toFixed(4)+'个绿币';
			};
			month.onkeyup=function()
			{
				if(month.value=='')
					month.value=0;
				month.value=size_total_month=Math.ceil(month.value);
				if(isNaN(size_total_month)||size_total_month==undefined)
					size_total.value=0;	
				if(size_total_value/jry_nd_price_size*size_total_month>jry_wb_login_user.green_money)
					size_total_span.style.color='#ff0000';
				else
					size_total_span.style.color='#000000';				
				size_total_span.innerHTML='共'+jry_wb_get_day(size_total_month*60*60*24*30,false)+jry_wb_get_size(size_total_value)+'存储包<br>耗费'+(size_total_value/jry_nd_price_size*size_total_month).toFixed(4)+'个绿币';
			}
			td.appendChild(document.createElement('br'));
			var button=document.createElement('button');td.appendChild(button);button.innerHTML='购买';button.classList.add('jry_wb_button','jry_wb_button_size_small','jry_wb_color_ok');
			button.onclick=function()
			{
				if(size_total_value==0||size_total_month==0)
					return jry_wb_beautiful_right_alert.alert("无效操作",3000,"auto","error");
				if(size_total_value/jry_nd_price_size*size_total_month>jry_wb_login_user.green_money)
					return jry_wb_beautiful_right_alert.alert("余额不足",3000,"auto","error");
				jry_wb_ajax_load_data('jry_nd_do_file.php?action=add_size&size='+size_total_value+'&time='+size_total_month,function(data)
				{
					jry_wb_loading_off();
					data=JSON.parse(data);
					if(!data.code)
					{
						if(data.reason==100000)			jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
						else if(data.reason==100001)	jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
						else if(data.reason==300002)	jry_wb_beautiful_right_alert.alert("余额不足",3000,"auto","error");
						green_money_button.onclick();
						return;
					}
					jry_wb_login_user.nd_ei.size_total=data.size_total;
					jry_wb_login_user.green_money=data.green_money;
					progress.update(jry_wb_login_user.nd_ei.size_used/jry_wb_login_user.nd_ei.size_total,jry_wb_get_size(jry_wb_login_user.nd_ei.size_used)+'/'+jry_wb_get_size(jry_wb_login_user.nd_ei.size_total));					
					jry_wb_beautiful_right_alert.alert("购买"+jry_wb_get_day(size_total_month*60*60*24*30,false)+jry_wb_get_size(size_total_value)+"存储空间成功",3000,"auto","ok");
					green_money_button.onclick();
				});
			};			
		};
		detail_mesage_button.onclick();
	}
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
		if(!jry_nd_share_mode_flag)/*非分享模式*/
		{
			var label=document.createElement("label");mid_lan.appendChild(label);
			label.classList.add("mid_lan_button","jry_wb_icon","upload","jry_wb_icon_yunduanshangchuan");
			var input=document.createElement("input");mid_lan.appendChild(input);
			label.setAttribute("for",(input.id="jry_wb_uploader_"+Math.random()))
			input.multiple="multiple";
			input.type="file";
			input.style.display="none";
			input.onchange=function()
			{
				if(input.files.length==0)
					return ;
				var dir=dir_input.value;
				var father=dir=='/'?{'file_id':0}:jry_nd_file_list.find(function(a){return a.dir+a.name+'/'==dir});
				if(father==undefined)
				{
					input.value='';
					upload_check.close();
					jry_wb_beautiful_alert.alert("上传失败","不存在的目录")
					return;
				}
				father=father.file_id;
				var total_size=0;
				for(var i=0,n=input.files.length;i<n;i++)
					total_size+=Math.ceil(input.files[i].size/1024);
				var rest=jry_wb_login_user.nd_ei.size_total-jry_wb_login_user.nd_ei.size_used-total_size;
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
					jry_nd_upload_count+=input.files.length;
					for(var i=0,n=input.files.length;i<n;i++)
						jry_nd_upload_list.push(new jry_wb_netdisk_upload_file(input.files[i],father,function(data)
						{
							jry_nd_upload_count--;
							jry_nd_upload_count=Math.max(0,jry_nd_upload_count);
							jry_wb_login_user.nd_ei.lasttime=data.lasttime;
							if(jry_nd_upload_count==0)
								jry_wb_nd_fresh_file_list();
							jry_wb_login_user.nd_ei.size_total=data.size_total;
							jry_wb_login_user.nd_ei.size_used=data.size_used;
							progress.update(data.size_used/data.size_total,jry_wb_get_size(data.size_used)+'/'+jry_wb_get_size(data.size_total));					
						},function()
						{
							jry_nd_upload_count--;
							jry_nd_upload_count=Math.max(0,jry_nd_upload_count);
						}));
					upload_check.close();
					upload_mesage_button.onclick();
					input.value='';
				};
				jry_wb_beautiful_scroll(upload_check.msgObj);
				var div = document.createElement("div"); upload_check.msgObj.appendChild(div);
				div.style="width:100%;margin:0;padding:0;overflow:hidden;";
				div.align='center';
				div.innerHTML="即将上传共计"+input.files.length+"个文件到"+dir+',预计消耗'+jry_wb_get_size(total_size)+'空间,剩余'+jry_wb_get_size(rest)+'空间<br>';
				div.innerHTML+="详细如下:<br>";
				var table=document.createElement('table');div.appendChild(table);
				var tr=document.createElement('tr');table.appendChild(tr);
				var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='文件名';	
				var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='大小/KB';	
				for(var i=0,n=input.files.length;i<n;i++)
				{
					var tr=document.createElement('tr');table.appendChild(tr);
					var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=input.files[i].name;	
					var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=jry_wb_get_size(input.files[i].size/1024);	
				}
			}
			var fresh=document.createElement("div");mid_lan.appendChild(fresh);
			fresh.classList.add("mid_lan_button","jry_wb_icon","fresh","jry_wb_icon_yunduanshuaxin");
			fresh.onclick=function()
			{
				jry_wb_nd_fresh(2);
			};
			fresh.oncontextmenu=function()
			{
				jry_wb_beautiful_alert.check('强制同步？',function()
				{
					jry_wb_nd_fresh(true);
				},function()
				{
					
				},'同步','放弃');
				return false;
			};
			jry_wb_add_oncontextmenu(fresh);			
			var new_dir=document.createElement("div");mid_lan.appendChild(new_dir);
			new_dir.classList.add("mid_lan_button","jry_wb_icon","new_dir","jry_wb_icon_xinjianwenjianjia");
			new_dir.onclick=function()
			{
				jry_wb_ajax_load_data(jry_wb_netdisk_do_file+'?action=new_dir',(data)=>
				{
					jry_wb_loading_off();
					data=JSON.parse(data);
					if(!data.code)
					{
						if(data.reason==100000)			jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
						else if(data.reason==100001)	jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
						else if(data.reason==200006)	jry_wb_beautiful_right_alert.alert("父目录不存在",3000,"auto","error");
						else if(data.reason==200005)	jry_wb_beautiful_right_alert.alert("文件名重复",3000,"auto","error");						
						return;
					}
					jry_wb_login_user.nd_ei.lasttime=data.lasttime;
					jry_wb_nd_fresh_file_list();
				},[{'name':'father','value':father=dir_input.value=='/'?0:jry_nd_file_list.find(function(a){return a.dir+a.name+'/'==dir_input.value}).file_id}]);
			};	
		}
		move_file_button=document.createElement("div");mid_lan.appendChild(move_file_button);
		if(!jry_nd_share_mode_flag)
			move_file_button.style.display='none';
		move_file_button.classList.add("mid_lan_button","jry_wb_icon","move","jry_wb_icon_yidongwenjian");
		move_file_button.onclick=function()
		{
			select_mesage_lock=true;
			var list=[];
			for(var i=0,n=jry_nd_selected_list.length;i<n;i++)
				list.push(jry_nd_selected_list[i].file.file_id);
			jry_wb_nd_alert_list(function(checked)
			{
				jry_wb_ajax_load_data(jry_wb_netdisk_do_file+'?action=move',(data)=>
				{
					jry_wb_loading_off();
					data=JSON.parse(data);
					if(!data.code)
					{
						if(data.reason==100000)			jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
						else if(data.reason==100001)	jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
						else if(data.reason==200006)	jry_wb_beautiful_right_alert.alert("父目录不存在",3000,"auto","error");
						return;
					}
					jry_wb_login_user.nd_ei.lasttime=data.lasttime;
					jry_wb_nd_fresh_file_list();			
				},[{'name':'file_id','value':JSON.stringify(list)},{'name':'to','value':checked[0].file_id}]);				
			});
		};
		if(!jry_nd_share_mode_flag)
		{
			delete_button=document.createElement("div");mid_lan.appendChild(delete_button);
			delete_button.style.display='none';
			delete_button.classList.add("mid_lan_button","jry_wb_icon","delete","jry_wb_icon_trash");
			delete_button.onclick=function()
			{
				var delete_check=  new jry_wb_beautiful_alert_function;
				var title=delete_check.frame("确认删除",document.body.clientWidth*0.75,document.body.clientHeight*0.75,document.body.clientWidth*3/32,document.body.clientHeight*3/32);
				var Confirm = document.createElement("button"); title.appendChild(Confirm);
				Confirm.type="button"; 
				Confirm.innerHTML="取消"; 
				Confirm.style='float:right;margin-right:20px;';
				Confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_warn");
				Confirm.onclick=function()
				{
					delete_check.close();
				};
				var Confirm = document.createElement("button"); title.appendChild(Confirm);
				Confirm.type="button"; 
				Confirm.innerHTML="删除"; 
				Confirm.style='float:right;margin-right:20px;';
				Confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
				Confirm.onclick=function()
				{
					var list=[];
					for(var i=0,n=jry_nd_selected_list.length;i<n;i++)
						list.push(jry_nd_selected_list[i].file.file_id);
					jry_wb_ajax_load_data(jry_wb_netdisk_do_file+'?action=delete',(data)=>
					{
						jry_wb_loading_off();
						data=JSON.parse(data);
						if(!data.code)
						{
							if(data.reason==100000)			jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
							else if(data.reason==100001)	jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
							else if(data.reason==200006)	jry_wb_beautiful_right_alert.alert("文件不存在或已删除",3000,"auto","error");
							return;
						}
						jry_wb_login_user.nd_ei.lasttime=data.lasttime;
						jry_wb_nd_fresh_file_list();							
						jry_wb_login_user.nd_ei.size_total=data.size_total;
						jry_wb_login_user.nd_ei.size_used=data.size_used;
						progress.update(data.size_used/data.size_total,jry_wb_get_size(data.size_used)+'/'+jry_wb_get_size(data.size_total));
						progress.update(data.size_used/data.size_total,jry_wb_get_size(data.size_used)+'/'+jry_wb_get_size(data.size_total));
					},[{'name':'file_id','value':JSON.stringify(list)}]);					
					delete_check.close();
					select_delete();
				};
				jry_wb_beautiful_scroll(delete_check.msgObj);
				var div = document.createElement("div"); delete_check.msgObj.appendChild(div);
				div.style="width:100%;margin:0;padding:0;overflow:hidden;";
				div.align='center';
				div.innerHTML="即将删除共计"+jry_nd_selected_list.length+"个文件<br>";
				div.innerHTML+="详细如下:<br>";
				var table=document.createElement('table');div.appendChild(table);
				var tr=document.createElement('tr');table.appendChild(tr);
				var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='文件名';	
				var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='大小/KB';	
				for(var i=0,n=jry_nd_selected_list.length;i<n;i++)
				{
					var tr=document.createElement('tr');table.appendChild(tr);
					var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=jry_nd_selected_list[i].file.name;	
					var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=jry_wb_get_size(jry_nd_selected_list[i].file.size/1024);	
				}				
			};		
		}
	};
	top_lan_button2=document.createElement('div');top_lan.appendChild(top_lan_button2);
	top_lan_button2.classList.add('top_lan_button');
	top_lan_button2.innerHTML="其他操作";
	top_lan_button2.onclick=function()
	{
		mid_lan.innerHTML='';
		var sort=document.createElement("div");mid_lan.appendChild(sort);
		sort.classList.add("mid_lan_button","jry_wb_icon","sort","jry_wb_icon_paixu");
		sort.onclick=function()
		{
			jry_wb_cache.set('jry_nd_sort',(jry_wb_cache.get('jry_nd_sort')+1)%4);
			var type=jry_wb_cache.get('jry_nd_sort');
			if(type==0)
				sort.classList.add("jry_wb_icon_paixu"),sort.classList.remove("jry_wb_icon_anmingchengpaixu","jry_wb_icon_paixu1","jry_wb_icon_shijian"),jry_wb_beautiful_right_alert.alert("按ID排序",2000,"auto",'ok');
			else if(type==1)
				sort.classList.add("jry_wb_icon_shijian"),sort.classList.remove("jry_wb_icon_anmingchengpaixu","jry_wb_icon_paixu1","jry_wb_icon_paixu"),jry_wb_beautiful_right_alert.alert("按时间排序",2000,"auto",'ok');
			else if(type==2)
				sort.classList.add("jry_wb_icon_anmingchengpaixu"),sort.classList.remove("jry_wb_icon_paixu","jry_wb_icon_paixu1","jry_wb_icon_shijian"),jry_wb_beautiful_right_alert.alert("按名称排序",2000,"auto",'ok');
			else if(type==3)
				sort.classList.add("jry_wb_icon_paixu1"),sort.classList.remove("jry_wb_icon_paixu","jry_wb_icon_anmingchengpaixu","jry_wb_icon_shijian"),jry_wb_beautiful_right_alert.alert("按类型排序",2000,"auto",'ok');
			jry_wb_nd_show_files_by_dir(dir_input.value);			
		};
		var type=jry_wb_cache.get('jry_nd_sort');
		if(type==0)
			sort.classList.add("jry_wb_icon_paixu"),sort.classList.remove("jry_wb_icon_anmingchengpaixu","jry_wb_icon_paixu1","jry_wb_icon_shijian"),jry_wb_beautiful_right_alert.alert("按ID排序",2000,"auto",'ok');
		else if(type==1)
			sort.classList.add("jry_wb_icon_shijian"),sort.classList.remove("jry_wb_icon_anmingchengpaixu","jry_wb_icon_paixu1","jry_wb_icon_paixu"),jry_wb_beautiful_right_alert.alert("按时间排序",2000,"auto",'ok');
		else if(type==2)
			sort.classList.add("jry_wb_icon_anmingchengpaixu"),sort.classList.remove("jry_wb_icon_paixu","jry_wb_icon_paixu1","jry_wb_icon_shijian"),jry_wb_beautiful_right_alert.alert("按名称排序",2000,"auto",'ok');
		else if(type==3)
			sort.classList.add("jry_wb_icon_paixu1"),sort.classList.remove("jry_wb_icon_paixu","jry_wb_icon_anmingchengpaixu","jry_wb_icon_shijian"),jry_wb_beautiful_right_alert.alert("按类型排序",2000,"auto",'ok');
	}
	top_lan_button1.onclick();

	shangyige=document.createElement('div');tool_list.appendChild(shangyige);
	shangyige.classList.add('shangyige','jry_wb_icon_xiangzuo','jry_wb_icon');
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
	shangyiji.classList.add('shangyiji','jry_wb_icon_xuanzeqishouqi','jry_wb_icon');
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
	document_list.ondragenter=function(e)
	{
		e.preventDefault();
		if(typeof follow_mouth!='undefined')
			follow_mouth.close();
	};
	document_list.ondragover=function(e)
	{
		e.preventDefault();
	};
	document_list.ondragleave=function(e)
	{
		e.preventDefault();
		if(typeof follow_mouth!='undefined')
			follow_mouth.reinit();		
	};
	document_list.ondrop=function(e)
	{
		e.preventDefault();
		var file_list=[];
		var loading_count=0;
		function callback(file_list)
		{
			console.log(file_list);
			window.file_list=file_list;
			var dir=dir_input.value;
			var father=dir=='/'?{'file_id':0}:jry_nd_file_list.find(function(a){return a.dir+a.name+'/'==dir});
			if(father==undefined)
			{
				input.value='';
				upload_check.close();
				jry_wb_beautiful_alert.alert("上传失败","不存在的目录")
				return;
			}
			father=father.file_id;
			if(file_list.length==0)
				return ;
			var total_size=0;
			for(var i=0,n=file_list.length;i<n;i++)
				if(file_list[i].isFile)
					total_size+=Math.ceil(file_list[i].file.length/1024);
			var rest=jry_wb_login_user.nd_ei.size_total-jry_wb_login_user.nd_ei.size_used-total_size;
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
				upload_check.close();
			};
			var Confirm = document.createElement("button"); title.appendChild(Confirm);
			Confirm.type="button"; 
			Confirm.innerHTML="开始上传"; 
			Confirm.style='float:right;margin-right:20px;';
			Confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
			Confirm.onclick=function()
			{
				jry_nd_upload_count+=file_list.length;
				for(var i=0,n=file_list.length;i<n;i++)
					jry_nd_upload_list.push(new jry_wb_netdisk_upload_file(file_list[i],father,function(data)
					{
						jry_nd_upload_count--;
						jry_nd_upload_count=Math.max(0,jry_nd_upload_count);
						jry_wb_login_user.nd_ei.lasttime=data.lasttime;
						if(jry_nd_upload_count==0)
							jry_wb_nd_fresh_file_list();
						jry_wb_login_user.nd_ei.size_total=data.size_total;
						jry_wb_login_user.nd_ei.size_used=data.size_used;
						progress.update(data.size_used/data.size_total,jry_wb_get_size(data.size_used)+'/'+jry_wb_get_size(data.size_total));					
					},function()
					{
						jry_nd_upload_count--;
						jry_nd_upload_count=Math.max(0,jry_nd_upload_count);
					}));
				upload_check.close();
				upload_mesage_button.onclick();
			};
			jry_wb_beautiful_scroll(upload_check.msgObj);
			var div = document.createElement("div"); upload_check.msgObj.appendChild(div);
			div.style="width:100%;margin:0;padding:0;overflow:hidden;";
			div.align='center';
			div.innerHTML="即将上传共计"+file_list.length+"个文件到"+dir+',预计消耗'+jry_wb_get_size(total_size)+'空间,剩余'+jry_wb_get_size(rest)+'空间<br>';
			div.innerHTML+="详细如下:<br>";
			var table=document.createElement('table');div.appendChild(table);
			var tr=document.createElement('tr');table.appendChild(tr);
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='文件名';	
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='大小/KB';	
			var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='位置';	
			for(var i=0,n=file_list.length;i<n;i++)
			{
				var buf=file_list[i].fullPath.substr(1);
				if(file_list[i].isFile)
				{
					var tr=document.createElement('tr');table.appendChild(tr);
					var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=file_list[i].name;	
					var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=jry_wb_get_size(file_list[i].file.length/1024);
					var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=dir+buf.substring(0,buf.lastIndexOf('/'));	
				}
				else if(file_list[i].isDirectory)
				{
					var tr=document.createElement('tr');table.appendChild(tr);
					var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='<span style="color:red;">新建文件夹</span>'+file_list[i].name;	
					var td=document.createElement('td');tr.appendChild(td);			td.innerHTML='0B';	
					var td=document.createElement('td');tr.appendChild(td);			td.innerHTML=dir+buf.substring(0,buf.lastIndexOf('/'));						
				}
			}
		}
		for (var i=0;i<e.dataTransfer.items.length;i++)
		{
			function read_dir(entry)
			{
				loading_count++;
				if(entry.fullPath.substr(1).includes('/')==false)
				{
					var dir=dir_input.value+entry.fullPath.substr(1);
					if(jry_nd_file_list.find(function(a){return a.dir+a.name==dir})!=undefined)
					{
						jry_wb_beautiful_alert.alert("上传失败","文件名重复")
						return false;
					}
				}
				if(entry.isFile)
				{
					entry.file(function(file)
					{
						var fileReader = new FileReader();
						fileReader.readAsBinaryString(file);
						fileReader.addEventListener('load', function()
						{
							loading_count--;
							var j=file_list.length;
							file_list[j]=entry;
							file_list[j].file=this.result;
							file_list[j].file.size=file_list[j].file.length;
							if(loading_count==0)
								callback(file_list);
						});
					});
				}
				else if(entry.isDirectory)
				{
					file_list[file_list.length]=entry;
					var reader=entry.createReader();
					reader.readEntries(function(a)
					{
						for(var i=0;i<a.length;i++)
							if(read_dir(a[i])==false)
								return false;
						loading_count--;
						if(loading_count==0)
							callback(file_list);						
					});
				}
				return true;
			}
			read_dir(e.dataTransfer.items[i].webkitGetAsEntry());
		}
		return false;
	};
	if(jry_nd_load_count==0)
		jry_wb_nd_show_files_by_dir(decodeURI(document.location.hash)!=''?(decodeURI(document.location.hash).substr(1)):'/');
	jry_wb_add_onresize(function()
	{
		document_list.style.height=document.body.clientHeight-document_list.offsetTop;
	});
});
select_mesage_lock=false;
jry_wb_add_onclick(function(event)
{
	event=event||window.event;
	if(jry_wb_right_meau!=null)
	{
		if(((new Date()-jry_wb_right_meau.lasttime)/1000)<1)
			return;
		document.body.removeChild(jry_wb_right_meau);
		jry_wb_right_meau=null;
	}
	if(jry_nd_selected_list.length!=0&&event.target!=select_mesage_button&&event.target!=move_file_button&&event.target!=delete_button&&(jry_wb_test_is_pc()&&(!event.ctrlKey)||event.target.name!='jry_nd_file_memeber')&&event.target.name!='select_mesage_button_member'&&!select_mesage_lock)
		select_delete();
});
var dir_stack=[];
function jry_wb_nd_show_files_by_dir(dir)
{
	dir=dir==undefined?'/':dir;
	dir=dir==''?'/':dir;
	jry_wb_nd_show_files(function(file){return file.dir==dir});
	if(dir!='/'&&jry_nd_file_list.find(function(a){return ((a.dir+a.name+'/')==dir);})==undefined)
		return jry_wb_beautiful_alert.alert('不存在的目录','',function(){dir_input.blur();if(dir_stack.length!=0)jry_wb_nd_show_files_by_dir(dir_input.value=document.location.hash=dir_stack[dir_stack.length-1]);else jry_wb_nd_show_files_by_dir(dir_input.value=document.location.hash='/');});
	dir_input.value=document.location.hash=dir;
	dir_stack.push(dir);
}
function select_delete()
{
	for(var i=0,n=jry_nd_selected_list.length;i<n;i++)
		jry_nd_selected_list[i].body.classList.remove('jry_wb_netdisk_file_active');
	jry_nd_selected_list=[];
	move_file_button.style.display='none';
	select_mesage_button.style.display='none';
	delete_button.style.display='none';		
	detail_mesage_button.onclick();	
}
function select_call_back(body,file)
{
	if(!jry_nd_share_mode_flag)
	{
		if(jry_nd_selected_list.find(function(a){return a.file.file_id==file.file_id})!=null)
		{
			body.classList.remove('jry_wb_netdisk_file_active');
			jry_nd_selected_list.splice(jry_nd_selected_list.indexOf(jry_nd_selected_list.find(function(a){return a.file.file_id==file.file_id})),1);
			select_mesage_button.onclick();
			if(jry_nd_selected_list.length==0)
				select_delete();
			return ;
		}
		body.classList.add('jry_wb_netdisk_file_active');
		jry_nd_selected_list.push({'body':body,'file':file});
		move_file_button.style.display='';
		select_mesage_button.style.display='';
		delete_button.style.display='';
		select_mesage_button.onclick();
	}
}
