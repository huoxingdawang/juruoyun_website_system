<?php
	header("content-type: application/x-javascript");
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_user_extern_message.php");	
?>
<?php if(false){ ?><script><?php } ?>
var showdiv=document.getElementById("show");
function unlock()
{
	jry_wb_ajax_load_data('do_chenge.php?action=unlock&id='+id,function (data){jry_wb_loading_off();data=JSON.parse(data);if(data.login==false){jry_wb_beautiful_right_alert.alert('无法操作，因为'+data.reasion,2000,'auto','error');return;}if(data.data=='OK'){jry_wb_beautiful_right_alert.alert('申请成功，请耐心等待',2000,'auto','ok');return;}if(data.data=='mail'){jry_wb_beautiful_right_alert.alert('Mail Error',2000,'auto','error');return;}jry_wb_beautiful_right_alert.alert('Unknow error',2000,'auto','alert');});
}
login_timer=next_green_timer=null;
function show()
{
	if(window.location.hash=='#show'&&showdiv.innerHTML!='')
		return;
	window.location.hash='show';
	showdiv.innerHTML='';
	var table=document.createElement("table");
	table.border=1;
	table.width="100%";
	showdiv.appendChild(table);
	jry_wb_show_tr_no_input(table,'ID',jry_wb_login_user.id,'',250);	
	var tr=document.createElement("tr");
	table.appendChild(tr);
	var td=document.createElement("td");
	td.width="250";
	var h55=document.createElement("h56");
	td.appendChild(h55);	
	h55.innerHTML='头像';
	tr.appendChild(td);	
	td=null;
	var td=document.createElement("td");tr.appendChild(td);	
	td.width="250";td.style="overflow: hidden;";
	var img=document.createElement("img");td.appendChild(img);
	jry_wb_set_user_head_special(jry_wb_login_user,img);
	img.height=80;
	img.width=80;
	tr.onclick=function()
	{
		var head_alert=new jry_wb_beautiful_alert_function;
		var title=head_alert.frame("换头",document.body.clientWidth*0.50,document.body.clientHeight*0.75,document.body.clientWidth*1/4,document.body.clientHeight*3/32);
		var Confirm = document.createElement("button"); title.appendChild(Confirm);
		Confirm.type="button"; 
		Confirm.innerHTML="关闭"; 
		Confirm.style='float:right;margin-right:20px;';
		Confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_warn");
		Confirm.onclick=function()
		{
			head_alert.close();
		};
		jry_wb_beautiful_scroll(head_alert.msgObj);
		var table=document.createElement("table");head_alert.msgObj.appendChild(table);
		var tr=document.createElement("tr"); table.appendChild(tr);
		var td=document.createElement("td"); tr.appendChild(td);td.classList.add('h56');td.innerHTML='类别';
		var td=document.createElement("td"); tr.appendChild(td);td.classList.add('h56');td.innerHTML='效果';
		var td=document.createElement("td"); tr.appendChild(td);td.classList.add('h56');td.innerHTML='操作';
		var tr=document.createElement("tr"); table.appendChild(tr);
		var td=document.createElement("td"); tr.appendChild(td);td.classList.add('h56');td.innerHTML='默认头像';
		var td=document.createElement("td"); tr.appendChild(td);
		var img2=document.createElement("img");td.appendChild(img2);
		jry_wb_set_user_head_special(jry_wb_login_user,img2);img2.height=80;img2.width=80;
		img2.src=jry_wb_get_user_head({'head':{'type':'default'},'sex':jry_wb_login_user.sex});
		var td=document.createElement("td"); tr.appendChild(td);
		if(jry_wb_login_user.head.type=='default_head_man'||jry_wb_login_user.head.type=='default_head_woman')
			td.innerHTML='正在使用';
		else
		{
			var button=document.createElement("button");td.appendChild(button);
			button.innerHTML="使用"; 
			button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
			button.onclick=function()
			{
				jry_wb_ajax_load_data('do_chenge.php?action=chengehead&type=default',function(data)
				{
					jry_wb_loading_off();
					var data=JSON.parse(data);
					if(data.code)
					{
						jry_wb_login_user.head.type='default';
						jry_wb_update_user(jry_wb_login_user,'head');
						jry_wb_beautiful_alert.alert("换头成功","使用默认的头");
					}
					else
					{
						if(data.reason==100000)
							jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
						else if(data.reason==100001)
							jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
						return ;
					}
					head_alert.close();
				});
			};
		}
		if(jry_wb_gravatar_user_head!=null)
		{
			var tr=document.createElement("tr"); table.appendChild(tr);
			var td=document.createElement("td"); tr.appendChild(td);td.classList.add('h56');td.innerHTML='gravatar';
			var td=document.createElement("td"); tr.appendChild(td);
			var img2=document.createElement("img");td.appendChild(img2);
			jry_wb_set_user_head_special(jry_wb_login_user,img2);img2.height=80;img2.width=80;
			img2.src=jry_wb_gravatar_user_head;
			var td=document.createElement("td"); tr.appendChild(td);
			if(jry_wb_login_user.head.type=='gravatar')
				td.innerHTML='正在使用';
			else
			{
				var button=document.createElement("button");td.appendChild(button);
				button.innerHTML="使用"; 
				button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
				button.onclick=function()
				{
					jry_wb_ajax_load_data('do_chenge.php?action=chengehead&type=gravatar',function(data)
					{
						jry_wb_loading_off();
						var data=JSON.parse(data);
						if(data.code)
						{
							jry_wb_login_user.head.type='gravatar';
							jry_wb_update_user(jry_wb_login_user,'head');
							jry_wb_beautiful_alert.alert("换头成功","使用gravatar的头");
						}
						else
						{
							if(data.reason==100000)
								jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
							else if(data.reason==100001)
								jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
							else if(data.reason==300000)
								jry_wb_beautiful_alert.alert("错误的操作","gravatar头像不存在","window.location.href=''");
							return ;
						}
						head_alert.close();
					});
				};
			}
		}
		<?php if($jry_wb_tp_qq_oauth_config!=NULL){ ?>		
		if(jry_wb_login_user.oauth_qq!=null||jry_wb_login_user.mail.includes('@qq.com'))
		{
			var tr=document.createElement("tr"); table.appendChild(tr);
			var td=document.createElement("td"); tr.appendChild(td);td.classList.add('h56');td.innerHTML='QQ';
			var td=document.createElement("td"); tr.appendChild(td);
			var img2=document.createElement("img");td.appendChild(img2);
			jry_wb_set_user_head_special(jry_wb_login_user,img2);img2.height=80;img2.width=80;
			if(jry_wb_login_user.oauth_qq!=null)
				img2.src=jry_wb_login_user.oauth_qq.figureurl_qq_2;
			else
				img2.src="https://q2.qlogo.cn/headimg_dl?dst_uin="+jry_wb_login_user.mail.split('@')[0]+"&spec=100";
			var td=document.createElement("td"); tr.appendChild(td);
			if(jry_wb_login_user.head.type=='qq')
				td.innerHTML='正在使用';
			else
			{
				var button=document.createElement("button");td.appendChild(button);
				button.innerHTML="使用"; 
				button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
				button.onclick=function()
				{
					jry_wb_ajax_load_data('do_chenge.php?action=chengehead&type=qq',function(data)
					{
						jry_wb_loading_off();
						var data=JSON.parse(data);
						if(data.code)
						{
							jry_wb_login_user.head.type='qq';
							jry_wb_update_user(jry_wb_login_user,'head');
							jry_wb_beautiful_alert.alert("换头成功","使用QQ的头");
						}
						else
						{
							if(data.reason==100000)
								jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
							else if(data.reason==100001)
								jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
							return ;
						}
						head_alert.close();
					});
				};
			}
		}
		<?php } ?>
		<?php if(constant('jry_wb_tp_github_oauth_config_client_id')!=''){ ?>
		if(jry_wb_login_user.oauth_gitee!=null)
		{
			var tr=document.createElement("tr"); table.appendChild(tr);
			var td=document.createElement("td"); tr.appendChild(td);td.classList.add('h56');td.innerHTML='gayhub';
			var td=document.createElement("td"); tr.appendChild(td);
			var img2=document.createElement("img");td.appendChild(img2);
			jry_wb_set_user_head_special(jry_wb_login_user,img2);img2.height=80;img2.width=80;
			img2.src=jry_wb_login_user.oauth_gitee.avatar_url;
			var td=document.createElement("td"); tr.appendChild(td);
			if(jry_wb_login_user.head.type=='github')
				td.innerHTML='正在使用';
			else
			{
				var button=document.createElement("button");td.appendChild(button);
				button.innerHTML="使用"; 
				button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
				button.onclick=function()
				{
					jry_wb_ajax_load_data('do_chenge.php?action=chengehead&type=github',function(data)
					{
						jry_wb_loading_off();
						var data=JSON.parse(data);
						if(data.code)
						{
							jry_wb_login_user.head.type='github';
							jry_wb_update_user(jry_wb_login_user,'head');
							jry_wb_beautiful_alert.alert("换头成功","使用gayhub的头");
						}
						else
						{
							if(data.reason==100000)
								jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
							else if(data.reason==100001)
								jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
							return ;
						}
						head_alert.close();
					});
				};
			}
		}
		<?php } ?>
		<?php if(constant('jry_wb_tp_gitee_oauth_config_client_id')!=''){ ?>
		if(jry_wb_login_user.oauth_gitee!=null!='')
		{
			var tr=document.createElement("tr"); table.appendChild(tr);
			var td=document.createElement("td"); tr.appendChild(td);td.classList.add('h56');td.innerHTML='码云';
			var td=document.createElement("td"); tr.appendChild(td);
			var img2=document.createElement("img");td.appendChild(img2);
			jry_wb_set_user_head_special(jry_wb_login_user,img2);img2.height=80;img2.width=80;
			img2.src=jry_wb_login_user.oauth_gitee.avatar_url;
			var td=document.createElement("td"); tr.appendChild(td);
			if(jry_wb_login_user.head.type=='gitee')
				td.innerHTML='正在使用';
			else
			{
				var button=document.createElement("button");td.appendChild(button);
				button.innerHTML="使用"; 
				button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
				button.onclick=function()
				{
					jry_wb_ajax_load_data('do_chenge.php?action=chengehead&type=gitee',function(data)
					{
						jry_wb_loading_off();
						var data=JSON.parse(data);
						if(data.code)
						{
							jry_wb_login_user.head.type='gitee';
							jry_wb_update_user(jry_wb_login_user,'head');
							jry_wb_beautiful_alert.alert("换头成功","使用码云的头");
						}
						else
						{
							if(data.reason==100000)
								jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
							else if(data.reason==100001)
								jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
							return ;
						}
						head_alert.close();
					});
				};
			}
		}
		<?php } ?>
		<?php if(constant('jry_wb_tp_mi_oauth_config_client_id')!=''){ ?>
		if(jry_wb_login_user.oauth_mi!=null)
		{
			var tr=document.createElement("tr"); table.appendChild(tr);
			var td=document.createElement("td"); tr.appendChild(td);td.classList.add('h56');td.innerHTML='MI';
			var td=document.createElement("td"); tr.appendChild(td);
			var img2=document.createElement("img");td.appendChild(img2);
			jry_wb_set_user_head_special(jry_wb_login_user,img2);img2.height=80;img2.width=80;
			img2.src=jry_wb_login_user.oauth_mi.miliaoIcon_orig;
			var td=document.createElement("td"); tr.appendChild(td);
			if(jry_wb_login_user.head.type=='mi')
				td.innerHTML='正在使用';
			else
			{
				var button=document.createElement("button");td.appendChild(button);
				button.innerHTML="使用"; 
				button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
				button.onclick=function()
				{
					jry_wb_ajax_load_data('do_chenge.php?action=chengehead&type=mi',function(data)
					{
						jry_wb_loading_off();
						var data=JSON.parse(data);
						if(data.code)
						{
							jry_wb_login_user.head.type='mi';
							jry_wb_update_user(jry_wb_login_user,'head');
							jry_wb_beautiful_alert.alert("换头成功","使用小米的头<br>小米智能头<br>年轻人的第一个头");
						}
						else
						{
							if(data.reason==100000)
								jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
							else if(data.reason==100001)
								jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
							return ;
						}
						head_alert.close();
					});
				};
			}
		}		
		<?php } ?>
		var tr=document.createElement("tr"); table.appendChild(tr);
		var td=document.createElement("td"); tr.appendChild(td);td.classList.add('h56');td.innerHTML='外部URL<br>请注意防盗链设置';
		var td=document.createElement("td"); tr.appendChild(td);
		var img2=document.createElement("img");td.appendChild(img2);
		jry_wb_set_user_head_special(jry_wb_login_user,img2);img2.height=80;img2.width=80;
		img2.src='';
		var input=document.createElement("input");td.appendChild(input);
		input.classList.add('h56');
		input.onkeyup=function()
		{
			img2.src=input.value;			
		}
		var __url_onerror=false;
		var td=document.createElement("td"); tr.appendChild(td);
		if(jry_wb_login_user.head.type=='url')
		{
			input.value=img2.src=jry_wb_login_user.head.url;
			td.innerHTML='正在使用';
		}
		else
		{
			var button=document.createElement("button");td.appendChild(button);
			button.innerHTML="使用"; 
			button.style.display='none';
			button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
			button.onclick=function()
			{
				if(__url_onerror==false)
				{
					jry_wb_ajax_load_data('do_chenge.php?action=chengehead&type=url',function(data)
					{
						jry_wb_loading_off();
						var data=JSON.parse(data);
						if(data.code)
						{
							jry_wb_login_user.head.type='url';
							jry_wb_login_user.head.url=input.value;
							jry_wb_update_user(jry_wb_login_user,'head');							
							jry_wb_beautiful_alert.alert("换头成功","使用外部的头");
						}
						else
						{
							if(data.reason==100000)
								jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
							else if(data.reason==100001)
								jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
							else if(data.reason==300003)
								jry_wb_beautiful_alert.alert("换头失败",'外部头像地址不可用');
							return ;
						}
						head_alert.close();
					},[{'name':'url','value':encodeURIComponent(input.value)}]);
				}
				else
					jry_wb_beautiful_alert.alert('换头失败','外部头像地址不可用');
			};
		}	
		img2.onreadystatechange=img2.onload=function()
		{
			if(img2.readystate=="complete"||img2.readystate=="loaded"||img2.complete==true)
			{
				if(jry_wb_login_user.head.type!='url')
					button.style.display='';
				__url_onerror=false;
			}
		}		
		img2.onerror=function()
		{
			if(jry_wb_login_user.head.type!='url')
				button.style.display='none';
			__url_onerror=true;		
		}
		var tr=document.createElement("tr"); table.appendChild(tr);
		var td=document.createElement("td"); tr.appendChild(td);td.classList.add('h56');td.innerHTML='网盘<br>请开启图床模式';
		var td=document.createElement("td"); tr.appendChild(td);
		var img2=document.createElement("img");td.appendChild(img2);
		jry_wb_set_user_head_special(jry_wb_login_user,img2);img2.height=80;img2.width=80;
		img2.src='';
		var span=document.createElement("span");td.appendChild(span);span.classList.add('h56');span.innerHTML='分享ID:'
		var input_share_id=document.createElement("input");td.appendChild(input_share_id);
		input_share_id.classList.add('h56');
		input_share_id.style.width='100px'
		var span=document.createElement("span");td.appendChild(span);span.classList.add('h56');span.innerHTML='文件ID:'
		var input_file_id=document.createElement("input");td.appendChild(input_file_id);
		input_file_id.classList.add('h56');
		input_file_id.style.width='100px'
		input_share_id.onkeyup=input_file_id.onkeyup=function()
		{
			img2.src=jry_wb_get_user_head({'head':{'type':'netdisk','share_id':input_share_id.value,'file_id':input_file_id.value}});
		}
		var __netdisk_onerror=false;
		var td=document.createElement("td"); tr.appendChild(td);
		if(jry_wb_login_user.head.type=='netdisk')
		{
			img2.src=jry_wb_get_user_head(jry_wb_login_user);
			input_share_id.value=jry_wb_login_user.head.share_id;
			input_file_id.value=jry_wb_login_user.head.file_id;
			td.innerHTML='正在使用';
		}
		else
		{
			var button=document.createElement("button");td.appendChild(button);
			button.innerHTML="使用"; 
			button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
			button.style.display='none';
			button.onclick=function()
			{
				if(__netdisk_onerror==false)
				{
					jry_wb_ajax_load_data('do_chenge.php?action=chengehead&type=netdisk',function(data)
					{
						jry_wb_loading_off();
						var data=JSON.parse(data);
						if(data.code)
						{
							jry_wb_login_user.head.type='netdisk';
							jry_wb_login_user.head.file_id=input_file_id.value;
							jry_wb_login_user.head.share_id=input_share_id.value;
							jry_wb_update_user(jry_wb_login_user,'head');							
							jry_wb_beautiful_alert.alert("换头成功","使用网盘的头");
						}
						else
						{
							if(data.reason==100000)
								jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
							else if(data.reason==100001)
								jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
							else if(data.reason==300003)
								jry_wb_beautiful_alert.alert("换头失败",'网盘头像地址不可用');
							return ;
						}
						head_alert.close();
					},[{'name':'share_id','value':input_share_id.value},{'name':'file_id','value':input_file_id.value}]);
				}
				else
					jry_wb_beautiful_alert.alert('换头失败','网盘头像地址不可用');
			};
		}	
		img2.onreadystatechange=img2.onload=function()
		{
			if(img2.readystate=="complete"||img2.readystate=="loaded"||img2.complete==true)
			{
				if(jry_wb_login_user.head.type!='netdisk')
					button.style.display='';
				__netdisk_onerror=false;
			}
		}		
		img2.onerror=function()
		{
			if(jry_wb_login_user.head.type!='netdisk')
				button.style.display='none';
			__netdisk_onerror=true;		
		}		
	};
	jry_wb_show_tr_no_input(table,'绿币',jry_wb_login_user.green_money,'',250);	
	jry_wb_show_tr_no_input(table,'注册日期',jry_wb_login_user.enroldate,'',250);		
	jry_wb_show_tr_no_input(table,'登录日期',jry_wb_login_user.logdate,'',250);		
	var login_time=jry_wb_show_tr_no_input(table,'已登录时间',0,'login','',250).children[0];
	if(login_timer==null)clearInterval(login_timer),login_timer=null;
	login_timer=setInterval(function()
	{
		var ms=jry_wb_compare_time(jry_wb_get_server_time(),jry_wb_login_user.logdate);
		var day=parseInt(ms/(24*60*60*1000));
		var hour=parseInt(ms/(60*60*1000))-day*24;
		var minute=parseInt(ms/(60*1000))-hour*60-day*24*60;
		var s=parseInt(ms/(1000))-minute*60-hour*60*60-day*24*60*60;
			login_time.innerHTML=day+"天"+hour+"时"+minute+"分"+s+"秒";			
	},1000);
	jry_wb_show_tr_no_input(table,'上次绿币奖励时间',jry_wb_login_user.greendate,'',250);
	var next_green_time=jry_wb_show_tr_no_input(table,'距下次获得',0,'next',250).children[0];
	if(next_green_timer==null)clearInterval(next_green_timer),next_green_timer=null;
	next_green_timer=setInterval(function()
	{
		var ms=24*60*60*1000-jry_wb_compare_time(jry_wb_get_server_time(),jry_wb_login_user.greendate);
		if(ms>0)
		{
			var day=0;
			var hour=parseInt(ms/(60*60*1000))-day*24;
			var minute=parseInt(ms/(60*1000))-hour*60-day*24*60;
			var s=parseInt(ms/(1000))-minute*60-hour*60*60-day*24*60*60;
			next_green_time.innerHTML=hour+"时"+minute+"分"+s+"秒";
		}
		else
			next_green_time.innerHTML="时间到，退出重登即可";
	},1000);
	jry_wb_show_tr_no_input(table,'类型',jry_wb_login_user.competencename,'',250);	
	var td=jry_wb_show_tr_no_input(table,'显示状态演示','','',250);
	td.innerHTML='';
	jry_wb_get_and_show_user(td,jry_wb_login_user.id,'auto','',true);
	td.innerHTML+='<h55>点击有惊喜</h55>';
	table=null;
}
function show_ip()
{
	window.location.hash='show_ip';
	showdiv.innerHTML='';
	if(login_timer==null)clearInterval(login_timer),login_timer=null;
	if(next_green_timer==null)clearInterval(next_green_timer),next_green_timer=null;
	var table=document.createElement("table");
	table.border=1;
	table.width="100%";
	showdiv.appendChild(table);	
	var tr=document.createElement("tr");
	table.appendChild(tr);
	var td=document.createElement("td");
	td.width="250";
	var h55=document.createElement("h56");
	td.appendChild(h55);	
	h55.innerHTML='登录信息'; 
	tr.appendChild(td);	
	td=null;
	var td=document.createElement("td");
	td.width="*";
	var h55=document.createElement("h55");
	td.appendChild(h55);	
	tr.appendChild(td);	
	td=null;
	tr=null;	
	
	for(let i=0;i<jry_wb_login_user.login_addr.length;i++)
	{
		let div=document.createElement("div");h55.appendChild(div);
		div.innerHTML=jry_wb_login_user.login_addr[i].data;
		let logout=document.createElement("span");div.appendChild(logout);
		logout.classList.add('jry_wb_icon','h55','jry_wb_icon_logout');
		logout.onclick=function()
		{
			jry_wb_beautiful_alert.check("确定登出?",function()
			{
				jry_wb_ajax_load_data('do_chenge.php?action=logout',function(data)
				{
					data=JSON.parse(data);
					jry_wb_loading_off();
					if(data.code)
					{
						h55.removeChild(div);
						if(jry_wb_login_user.login_addr[i].isthis)
							document.location.href=jry_wb_message.jry_wb_index_page;						
					}
					else
					{
						if(data.reason==100000)
							jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
						else if(data.reason==100001)
							jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
						else
							jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
						return ;
					}
				},[{'name':'login_id','value':jry_wb_login_user.login_addr[i].login_id}]);
			},function(){});
		};
		let trust=document.createElement("span");div.appendChild(trust);
		trust.classList.add('jry_wb_icon','h55');
		if(jry_wb_login_user.login_addr[i].trust||jry_wb_login_user.login_addr[i].isthis)
			trust.classList.add('jry_wb_icon_xinrenshebei');
		if(jry_wb_login_user.login_addr[i].trust)
			trust.classList.add('jry_wb_color_ok');
		trust.onclick=function()
		{
			if(jry_wb_login_user.login_addr[i].trust)
			{
				jry_wb_beautiful_alert.check("确定解除信任?",function()
				{
					jry_wb_ajax_load_data('do_chenge.php?action=untrust',function(data)
					{
						data=JSON.parse(data);
						jry_wb_loading_off();
						if(data.code)
						{
							jry_wb_login_user.login_addr[i].trust=0;
							trust.classList.remove('jry_wb_color_ok');
							if(!jry_wb_login_user.login_addr[i].isthis)
								div.removeChild(trust);
							jry_wb_beautiful_right_alert.alert('解除信任成功',2000,'auto','ok');
						}
						else
						{
							if(data.reason==100000)
								jry_wb_beautiful_right_alert.alert('因为没有登录信任失败',1000,'auto','error');
							else if(data.reason==100001)
								jry_wb_beautiful_right_alert.alert("因为'"+data.extern+"'权限缺失信任失败",1000,'auto','error');
							else
								jry_wb_beautiful_right_alert.alert('信任失败',2000,'auto','error');
							return ;
						}						
					},[{'name':'login_id','value':jry_wb_login_user.login_addr[i].login_id}]);
				},function(){});
			}
			else
			{
				jry_wb_beautiful_alert.check("确定信任?",function()
				{
					jry_wb_ajax_load_data('do_chenge.php?action=trust',function(data)
					{
						data=JSON.parse(data);
						jry_wb_loading_off();
						if(data.code)
						{						
							jry_wb_login_user.login_addr[i].trust=0;
							trust.classList.add('jry_wb_color_ok');
							jry_wb_beautiful_right_alert.alert('信任成功',2000,'auto','ok');
						}
						else
						{
							if(data.reason==100000)
								jry_wb_beautiful_right_alert.alert('因为没有登录信任失败',1000,'auto','error');
							else if(data.reason==100001)
								jry_wb_beautiful_right_alert.alert("因为'"+data.extern+"'权限缺失信任失败",1000,'auto','error');
							else
								jry_wb_beautiful_right_alert.alert('信任失败',2000,'auto','error');
							return ;
						}
					});
				},function(){});				
			}
		};
		var span=document.createElement("span");div.appendChild(span);
		span.classList.add('jry_wb_icon','h55');		
		if(jry_wb_login_user.login_addr[i].isthis)
			span.classList.add('jry_wb_icon_dangqian');
			
		li=null;		
	}
	var tr=document.createElement("tr");table.appendChild(tr);	
	var td=document.createElement("td");tr.appendChild(td);
	td.setAttribute('colspan',2);	
	var button=document.createElement("button");td.appendChild(button);
	button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');
	button.setAttribute('onclick',"window.open('logout.php','_parent');");
	button.innerHTML="登出";
	button=null;	
	var button=document.createElement("button");td.appendChild(button);
	button.classList.add("jry_wb_button","jry_wb_button_size_big","jry_wb_color_warn");
	button.setAttribute('onclick',"window.open('logout.php?action=all','_parent')");
	button.innerHTML="全部登出";
	button=null;
	
	
}
function __addbutton(form,onclick)
{
	var input=document.createElement("input");
	input.name=input.id='submit';
	input.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');
	input.type='submit';
	input.value='提交';
	input.setAttribute('onclick',onclick);
	form.appendChild(input);	
}
function showchenge()
{
	window.location.hash='showchenge';
	showdiv.innerHTML='';
	if(login_timer==null)clearInterval(login_timer),login_timer=null;
	if(next_green_timer==null)clearInterval(next_green_timer),next_green_timer=null;
	var table=document.createElement("table");showdiv.appendChild(table);
	table.border=1;
	table.width="100%";
	
	var namee=jry_wb_show_tr_with_input(table,'昵称','name',jry_wb_login_user.name,'text',function(){},250).children[0];
	var time5=0;
	namee.onfocus=namee.onkeyup=function()
	{
		if(namee.value=='')
		{
			if(((new Date())-time5)>5000)
			{
				time5=new Date();
				jry_wb_beautiful_right_alert.alert("昵称不为空",2000,"auto","error");
			}
			namee.style.border="5px solid #ff0000",namee.style.margin="0px 0px";
		}
		else
			namee.style.border="",namee.style.margin="",time5=0;
	};
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);	
	td.width="250";
	var h55=document.createElement("h56");td.appendChild(h55);
	h55.innerHTML='性别';
	td=null;
	var td=document.createElement("td");tr.appendChild(td);	
	var sexs=[]
	var input=sexs[sexs.length]=document.createElement("input");td.appendChild(input);
	input.name=input.id='sex';input.classList.add('h56');input.type='radio';
	if(jry_wb_login_user.sex==(input.value=1))
		input.setAttribute('checked','');
	var h56=document.createElement("h56");td.appendChild(h56);
	h56.innerHTML="男"
		
	var input=sexs[sexs.length]=document.createElement("input");td.appendChild(input);
	input.name=input.id='sex';input.classList.add('h56');input.type='radio';
	if(jry_wb_login_user.sex==(input.value=0))
		input.setAttribute('checked','');
	var h56=document.createElement("h56");td.appendChild(h56);
	h56.innerHTML="女"

	var input=sexs[sexs.length]=document.createElement("input");td.appendChild(input);
	input.name=input.id='sex';input.classList.add('h56');input.type='radio';
	input.onclick=function()
	{
		jry_wb_beautiful_right_alert.alert("That's good",2000,"auto","ok");
	};
	if(jry_wb_login_user.sex==(input.value=2))
		input.setAttribute('checked','');
	var h56=document.createElement("h56");td.appendChild(h56);
	h56.innerHTML="女装大佬"
	
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);	
	td.width="250";
	var h55=document.createElement("h56");td.appendChild(h55);
	h55.innerHTML='惯用语';
	td=null;
	var td=document.createElement("td");tr.appendChild(td);	
	var language=document.createElement("select");td.appendChild(language);
	language.name=language.id='language';
	language.classList.add('h56');
	language.type='radio';
	language.value=1;
	var languages=Array('zh-CN');
	for(var i=0;i<languages.length;i++)
	{
		var option=document.createElement("option");language.appendChild(option);
		option.value=languages[i];		
		option.innerHTML=languages[i];
		if(jry_wb_login_user.language==languages[i])
			option.setAttribute('selected','selected');
	}
	td=null;
	tr=null;
	
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);	
	td.width="250";
	var h55=document.createElement("h56");td.appendChild(h55);
	h55.innerHTML='配色';
	td=null;
	var td=document.createElement("td");tr.appendChild(td);	
	var style_id=document.createElement("select");td.appendChild(style_id);
	style_id.name=style_id.id='style_id';
	style_id.classList.add('h56');
	style_id.type='radio';
	style_id.value=1;
	for(var i=0;i<style.length;i++)
	{
		var option=document.createElement("option");style_id.appendChild(option);
		option.value=style[i].style_id;
		option.innerHTML=style[i].name;
		if(style[i].style_id==1)
			option.innerHTML+='(默认)';
		if(jry_wb_login_user.style.style_id==style[i].style_id) 
			option.setAttribute('selected','selected');
	}
	var style_href=document.createElement("a");td.appendChild(style_href);
	style_href.classList.add('jry_wb_font_small_size');
	style_href.target='_blank';
	style_id.onchange=function()
	{
		if(jry_wb_login_user.style.style_id==parseInt(style_id.options[style_id.selectedIndex].value))
			style_href.innerHTML=style_href.href='';
		else
		{
			style_href.innerHTML="预览"+style_id.options[style_id.selectedIndex].text;
			style_href.href='<?php echo jry_wb_print_href('jry_wb_style_control','','',true);?>'+'?try='+style_id.options[style_id.selectedIndex].value;
		}
	}
	tr=null;
	
	var tr=document.createElement("tr");
	table.appendChild(tr);
	var td=document.createElement("td");
	td.width="250";
	var h55=document.createElement("h56");
	td.appendChild(h55);	
	h55.innerHTML='签名';
	tr.appendChild(td);	
	td=null;
	var td=document.createElement("td");
	var zhushi=document.createElement("textarea");
	zhushi.name=zhushi.id='zhushi';
	zhushi.classList.add('h56');
	zhushi.style="margin: 3px; height: 500px; width: 49%;float:left;";
	zhushi.innerHTML=jry_wb_login_user.zhushi;
	td.appendChild(zhushi);	
	tr.appendChild(td);	
	var result=document.createElement("div");
	result.style="height: 500px;width:49%;overflow:hidden;position:relative;";
	td.appendChild(result);
	var md=new jry_wb_markdown(result,0,0,(jry_wb_login_user.zhushi),false);
	zhushi.onkeyup=function()
	{
		md.fresh(new Date(),zhushi.value);		
	}
	new jry_wb_beautiful_scroll(result);
	var tr=document.createElement("tr");table.appendChild(tr);	
	var td=document.createElement("td");tr.appendChild(td);
	td.setAttribute('colspan',2);	
	var button=document.createElement("button");td.appendChild(button);
	button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');
	button.type='button';
	button.innerHTML='提交';
	button.onclick=function()
	{
		var sex=0;
		for(var i=0;i<sexs.length;i++)
			if(sexs[i].checked)
				sex=parseInt(sexs[i].value);
		if(namee.value=="")
		{
			jry_wb_beautiful_alert.alert("请填写完整信息","名字为空",function(){namee.focus();namee.style.border="5px solid #ff0000",namee.style.margin="0px 0px";});
			return false;
		}		
		jry_wb_ajax_load_data('do_chenge.php?action=simple',function (data)
		{
			data=JSON.parse(data);
			jry_wb_loading_off();
			if(data.code)
			{
				jry_wb_beautiful_alert.alert('修改成功',data.style.style_id==jry_wb_login_user.style.style_id?'':'刷新页面以更新主题',function(){showchenge()});
				jry_wb_login_user.name=namee.value;
				jry_wb_login_user.sex=sex;
				jry_wb_login_user.language=language.value;
				jry_wb_login_user.style=data.style;
				jry_wb_login_user.zhushi=zhushi.value;
				var all=document.getElementsByName('jry_wb_user_name_'+jry_wb_login_user.id);
				for(var i=0;i<all.length;i++)
					all[i].innerText=namee.value;
			}
			else
			{
				if(data.reason==100000)
					jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
				else if(data.reason==100001)
					jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
				else if(data.reason==100013)
					jry_wb_beautiful_alert.alert("注册失败","昵称为空",function(){namee.focus();namee.style.border="5px solid #ff0000",namee.style.margin="0px 0px";});
				else
					jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
				return ;
			}
		},[{'name':'name','value':namee.value},{'name':'sex','value':sex},{'name':'language','value':language.value},{'name':'style_id','value':style_id.value},{'name':'zhushi','value':zhushi.value}],true);
	};
}
function showtel()
{
	window.location.hash='showtel';
	showdiv.innerHTML='';
	if(login_timer==null)clearInterval(login_timer),login_timer=null;
	if(next_green_timer==null)clearInterval(next_green_timer),next_green_timer=null;
	
	var table=document.createElement("table");showdiv.appendChild(table);
	table.border=1;
	table.width="100%";
	time1=0;
	time2=0;
	var tel=jry_wb_show_tr_with_input(table,'电话','tel',jry_wb_login_user.tel,'text',function(){},250).children[0];
	tel.onfocus=tel.onkeyup=function()
	{
		if(tel.value!=""&&(jry_wb_test_phone_number(tel.value)==false))
		{
			if(((new Date())-time1)>5000)
			{
				time1=new Date();
				jry_wb_beautiful_right_alert.alert("电话错误",2000,"auto","error");
			}	
			tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";
		}
		else
			tel.style.border="",tel.style.margin="",time1=0;
	};	
	var td=jry_wb_show_tr_with_input(table,'验证码','vcode','','text',function(){},250);
	var vcode=td.children[0];
	vcode.onfocus=vcode.onkeyup=function()
	{
		if(vcode.value!=''&&vcode.value.length!=4)
		{
			if(((new Date())-time2)>5000)
			{
				time2=new Date();
				jry_wb_beautiful_right_alert.alert("4位验证码",2000,"auto","error");
			}
			vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";
		}
		else
			vcode.style.border="",vcode.style.margin="",time2=0;
	};		
	var img=document.createElement("img");td.appendChild(img);
	img.id='vcodesrc';
	img.onclick=function ()
	{
		img.src=jry_wb_message.jry_wb_host+'tools/jry_wb_vcode.php?r='+Math.random();
	};
	img.src=jry_wb_message.jry_wb_host+'tools/jry_wb_vcode.php?r='+Math.random();
<?php if(JRY_WB_CHECK_TEL_SWITCH&&JRY_WB_SHORT_MESSAGE_SWITCH!=''){ ?>	
	var td=jry_wb_show_tr_with_input(table,'短信验证码','phonecode','','text',function (){},250);
		var phonecode=td.children[0];
		var input=document.createElement("button");td.appendChild(input);
		input.name=input.id='button';
		input.classList.add('jry_wb_button','jry_wb_button_size_middle','jry_wb_color_ok');
		input.type='button';
		input.innerHTML='获取验证码';
		input.onclick=function()
		{
			if(tel.value==jry_wb_login_user.tel)
			{
				jry_wb_beautiful_alert.alert("并没有修改","开发组穷啊<br>一个5分钱",function()
				{
					document.getElementById("tel").focus();
				});
				return;
			}
			if(tel.value==jry_wb_login_user.tel)
			return jry_wb_beautiful_alert.alert("发送失败","并没有修改",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
			if(!jry_wb_test_phone_number(tel.value))
			return jry_wb_beautiful_alert.alert("发送失败","错误的格式",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
		jry_wb_ajax_load_data('do_chenge.php?action=send_tel',function (data)
		{
			data=JSON.parse(data);
			jry_wb_loading_off();
			if(data.code)	
			jry_wb_beautiful_alert.alert('发送成功',tel.value,function(){phonecode.focus();});
			else
			{
				if(data.reason==100000)
				jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
				else if(data.reason==100001)
				jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
				if(data.reason==100005)
				jry_wb_beautiful_alert.alert("发送失败","请检查验证码大小写",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";});
				else if(data.reason==100002)
				jry_wb_beautiful_alert.alert("发送失败","请检查验证码,点击图片可以换一张哦",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";});
				else if(data.reason==100004)
				jry_wb_beautiful_alert.alert("发送失败","并没有修改",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
				else if(data.reason==100003)
				jry_wb_beautiful_alert.alert("发送过于频繁","开发组穷啊<br>一个5分钱",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
				else if(data.reason==100008)
				jry_wb_beautiful_alert.alert("手机号格式错误","",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
				else if(data.reason==100009)
				jry_wb_beautiful_alert.alert("手机号重复","",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
				else
				jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
				return ;
			}
		},[{'name':'vcode','value':vcode.value},{'name':'tel','value':tel.value}],true);
	};
	<?php } ?>
	document.getElementById("tel").focus();
	var tr=document.createElement("tr");table.appendChild(tr);	
	var td=document.createElement("td");tr.appendChild(td);
	td.setAttribute('colspan',2);	
	var button=document.createElement("button");td.appendChild(button);
	button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');
	button.type='button';
	button.innerHTML='提交';
	button.onclick=function()
	{
		if(tel.value==jry_wb_login_user.tel)
			return jry_wb_beautiful_alert.alert("修改失败","并没有修改",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
		if(!jry_wb_test_phone_number(tel.value))
			return jry_wb_beautiful_alert.alert("修改失败","错误的格式",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
<?php if(JRY_WB_CHECK_TEL_SWITCH&&JRY_WB_SHORT_MESSAGE_SWITCH!=''){ ?>
		if(phonecode.value=='')
			return jry_wb_beautiful_alert.alert("修改失败","手机验证码为空",function(){phonecode.focus();phonecode.style.border="5px solid #ff0000",phonecode.style.margin="0px 0px";});	
<?php } ?>
		jry_wb_ajax_load_data('do_chenge.php?action=tel',function (data)
		{
			data=JSON.parse(data);
			jry_wb_loading_off();
			if(data.code)
			{
				jry_wb_login_user.tel=tel.value;
				jry_wb_beautiful_alert.alert('修改成功',tel.value,function(){showtel()});
			}
			else
			{
				if(data.reason==100000)
				jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
				else if(data.reason==100001)
				jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
				if(data.reason==100005)
				jry_wb_beautiful_alert.alert("修改失败","请检查验证码大小写",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";});
				else if(data.reason==100002)
				jry_wb_beautiful_alert.alert("修改失败","请检查验证码,点击图片可以换一张哦",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";});
				else if(data.reason==100004)
				jry_wb_beautiful_alert.alert("修改失败","并没有修改",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
<?php if(JRY_WB_CHECK_TEL_SWITCH&&JRY_WB_SHORT_MESSAGE_SWITCH!=''){ ?>
					else if(data.reason==100003)
					jry_wb_beautiful_alert.alert("发送过于频繁","开发组穷啊<br>一个5分钱",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
<?php } ?>
					else if(data.reason==100008)
					jry_wb_beautiful_alert.alert("手机号格式错误","",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
					else if(data.reason==100009)
					jry_wb_beautiful_alert.alert("手机号重复","",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
<?php if(JRY_WB_CHECK_TEL_SWITCH&&JRY_WB_SHORT_MESSAGE_SWITCH!=''){ ?>
				else if(data.reason==100010)
					jry_wb_beautiful_alert.alert("修改失败","手机验证码错误",function(){phonecode.focus();phonecode.style.border="5px solid #ff0000",phonecode.style.margin="0px 0px";});	
<?php } ?>
				else
					jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
				return ;
			}
		},[{'name':'vcode','value':vcode.value},{'name':'tel','value':tel.value},<?php if(JRY_WB_CHECK_TEL_SWITCH&&JRY_WB_SHORT_MESSAGE_SWITCH!=''){ ?>{'name':'phonecode','value':phonecode.value},<?php } ?>],true);		
	};
}
function showmail()
{
	window.location.hash='showmail';
	showdiv.innerHTML='';
	if(login_timer==null)clearInterval(login_timer),login_timer=null;
	if(next_green_timer==null)clearInterval(next_green_timer),next_green_timer=null;
	
	var table=document.createElement("table");
	table.border=1;
	table.width="100%";
	showdiv.appendChild(table);	
	
	time1=0;
	time2=0;
	var mail=jry_wb_show_tr_with_input(table,'邮箱','mail',jry_wb_login_user.mail,'text',function(){},250).children[0];
	mail.onfocus=mail.onkeyup=function()
	{
		if(mail.value!=""&&(jry_wb_test_mail(mail.value)==false))
		{
			if(((new Date())-time1)>5000)
			{
				time1=new Date();
				jry_wb_beautiful_right_alert.alert("邮箱错误",2000,"auto","error");
			}	
			mail.style.border="5px solid #ff0000",mail.style.margin="0px 0px";
		}
		else
			mail.style.border="",mail.style.margin="",time1=0;
	};
	mail.onclick();
	var td=jry_wb_show_tr_with_input(table,'验证码','vcode','','text',function (){},250);
	vcode=td.children[0];
	vcode.onfocus=vcode.onkeyup=function()
	{
		if(vcode.value!=''&&vcode.value.length!=4)
		{
			if(((new Date())-time2)>5000)
			{
				time2=new Date();
				jry_wb_beautiful_right_alert.alert("4位验证码",2000,"auto","error");
			}
			vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";
		}
		else
			vcode.style.border="",vcode.style.margin="",time2=0;
	};	
	var img=document.createElement("img");td.appendChild(img);
	img.id='vcodesrc';
	img.onclick=function ()
	{
		img.src=jry_wb_message.jry_wb_host+'tools/jry_wb_vcode.php?r='+Math.random();
	};	
	img.src=jry_wb_message.jry_wb_host+'tools/jry_wb_vcode.php?r='+Math.random();
	var tr=document.createElement("tr");table.appendChild(tr);	
	var td=document.createElement("td");tr.appendChild(td);
	td.setAttribute('colspan',2);	
	var button=document.createElement("button");td.appendChild(button);
	button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');
	button.type='button';
	button.innerHTML='获取验证码';
<?php if(JRY_WB_MAIL_SWITCH!=''&&JRY_WB_MAIL_SWITCH!=''){?>
	button.innerHTML='获取验证码';
<?php }else{ ?>
	button.innerHTML='提交';
<?php } ?>
	button.onclick=function()
	{
		if(mail.value==jry_wb_login_user.mail)
		return jry_wb_beautiful_alert.alert("<?php if(JRY_WB_MAIL_SWITCH!=''){?>发送<?php }else{ ?>修改<?php } ?>失败","并没有修改",function(){mail.focus();mail.style.border="5px solid #ff0000",mail.style.margin="0px 0px";});
		if(!jry_wb_test_mail(mail.value))
		return jry_wb_beautiful_alert.alert("<?php if(JRY_WB_MAIL_SWITCH!=''){?>发送<?php }else{ ?>修改<?php } ?>失败","错误的格式",function(){mail.focus();mail.style.border="5px solid #ff0000",mail.style.margin="0px 0px";});
		jry_wb_ajax_load_data('do_chenge.php?action=<?php if(JRY_WB_MAIL_SWITCH!=''){?>send_mail<?php }else{ ?>mail<?php } ?>',function (data)
		{
			data=JSON.parse(data);
			jry_wb_loading_off();
			if(data.code)	
			jry_wb_beautiful_alert.alert('<?php if(JRY_WB_MAIL_SWITCH!=''){?>发送<?php }else{ ?>修改<?php } ?>成功',mail.value,function()
			{
<?php if(JRY_WB_MAIL_SWITCH!=''&&JRY_WB_MAIL_SWITCH!=''){?>
					if(mail.value.includes("@163.com"))
						window.open("https://mail.163.com/");
					else if(mail.value.includes("@qq.com"))
						window.open("https://mail.qq.com/");
<?php }else{ ?>
					jry_wb_login_user.mail=mail.value;
					jry_wb_gravatar_user_head=data.jry_wb_gravatar_user_head;
<?php } ?>
				});
				else
				{
					if(data.reason==100000)
						jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
					else if(data.reason==100001)
						jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
					if(data.reason==100005)
						jry_wb_beautiful_alert.alert("<?php if(JRY_WB_MAIL_SWITCH!=''){?>发送<?php }else{ ?>修改<?php } ?>失败","请检查验证码大小写",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";});
					else if(data.reason==100002)
						jry_wb_beautiful_alert.alert("<?php if(JRY_WB_MAIL_SWITCH!=''){?>发送<?php }else{ ?>修改<?php } ?>失败","请检查验证码,点击图片可以换一张哦",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";});
					else if(data.reason==100004)
						jry_wb_beautiful_alert.alert("<?php if(JRY_WB_MAIL_SWITCH!=''){?>发送<?php }else{ ?>修改<?php } ?>失败","并没有修改",function(){mail.focus();mail.style.border="5px solid #ff0000",mail.style.margin="0px 0px";});
					else if(data.reason==100014)
						jry_wb_beautiful_alert.alert("<?php if(JRY_WB_MAIL_SWITCH!=''){?>发送<?php }else{ ?>修改<?php } ?>失败","错误的格式",function(){mail.focus();mail.style.border="5px solid #ff0000",mail.style.margin="0px 0px";});
					else if(data.reason==100015)
						jry_wb_beautiful_alert.alert("<?php if(JRY_WB_MAIL_SWITCH!=''){?>发送<?php }else{ ?>修改<?php } ?>失败","别人绑定过了",function(){mail.focus();mail.style.border="5px solid #ff0000",mail.style.margin="0px 0px";});
					else if(data.reason==100016)
						jry_wb_beautiful_alert.alert("<?php if(JRY_WB_MAIL_SWITCH!=''){?>发送<?php }else{ ?>修改<?php } ?>失败","配置错误，请联系开发组");				
					else
						jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
					return ;
				}
			},[{'name':'vcode','value':vcode.value},{'name':'mail','value':mail.value}],true);
		};
	}
function showpas()
{
	window.location.hash='showpas';	
	showdiv.innerHTML='';
	if(login_timer==null)clearInterval(login_timer),login_timer=null;
	if(next_green_timer==null)clearInterval(next_green_timer),next_green_timer=null;
	
	var table=document.createElement("table");showdiv.appendChild(table);	
	table.border=1;
	table.width="100%";
	
	var password_yuan=jry_wb_show_tr_with_input(table,"原始密码","password_yuan",'','password',function(){},250).children[0];
	var password1=jry_wb_show_tr_with_input(table,"新密码","password1",'','password',function(){},250).children[0];
	var password2=jry_wb_show_tr_with_input(table,"再输新密码","password2",'','password',function(){},250).children[0];
	time1=time2=time3=0;
	password_yuan.onfocus=password_yuan.onkeyup=function()
	{
		if(password_yuan.value!=''&&password_yuan.value.length<8)
		{
			if(((new Date())-time3)>5000)
			{
				time3=new Date();
				jry_wb_beautiful_right_alert.alert("密码长度大于8位",2000,"auto","error");
			}
			password_yuan.style.border="5px solid #ff0000",password_yuan.style.margin="0px 0px";
		}
		else
			password_yuan.style.border="",password_yuan.style.margin="",time3=0;
	};	
	password1.onfocus=password1.onkeyup=function()
	{
		if(password1.value!=''&&password1.value.length<8)
		{
			if(((new Date())-time1)>5000)
			{
				time1=new Date();
				jry_wb_beautiful_right_alert.alert("密码长度大于8位",2000,"auto","error");
			}
			password1.style.border="5px solid #ff0000",password1.style.margin="0px 0px";
		}
		else
			password1.style.border="",password1.style.margin="",time1=0;
	};
	password2.onfocus=password2.onkeyup=function()
	{
		if(password2.value!=''&&(password2.value!=document.getElementById('password1').value||password2.value.length<8))
		{
			if(((new Date())-time2)>5000)
			{
				time2=new Date();
				if(password2.value.length<8)
					jry_wb_beautiful_right_alert.alert("密码长度大于8位",2000,"auto","error");
				else
					jry_wb_beautiful_right_alert.alert("两次不一样耶",2000,"auto","error");
			}
			password2.style.border="5px solid #ff0000",password2.style.margin="0px 0px";
		}
		else
			password2.style.border="",password2.style.margin="",time2=0;
	};	
	var tr=document.createElement("tr");table.appendChild(tr);	
	var td=document.createElement("td");tr.appendChild(td);
	td.setAttribute('colspan',2);	
	var button=document.createElement("button");td.appendChild(button);
	button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');
	button.type='button';
	button.innerHTML='提交';
	button.onclick=function()
	{
		if(password_yuan.value=="")
			return jry_wb_beautiful_alert.alert("请填写完整信息","原始密码为空",function(){password_yuan.focus();password_yuan.style.border="5px solid #ff0000",password_yuan.style.margin="0px 0px";});
		else if(password_yuan.value.length<8)
			return jry_wb_beautiful_alert.alert("请填写正确信息","原始密码太短",function(){password_yuan.focus();password_yuan.style.border="5px solid #ff0000",password_yuan.style.margin="0px 0px";});
		else if(password1.value=="")
			return jry_wb_beautiful_alert.alert("请填写完整信息","新密码为空",function(){password1.focus();password1.style.border="5px solid #ff0000",password1.style.margin="0px 0px";});
		else if(password2.value=="")
			return jry_wb_beautiful_alert.alert("请填写完整信息","新密码为空",function(){password2.focus();password2.style.border="5px solid #ff0000",password2.style.margin="0px 0px";});
		else if(password1.value.length<8)
			return jry_wb_beautiful_alert.alert("请填写正确信息","新密码太短",function(){password1.focus();password1.style.border="5px solid #ff0000",password1.style.margin="0px 0px";});
		else if(password1.value!=password2.value)
			return jry_wb_beautiful_alert.alert("请填写正确信息","新密码不同",function(){password2.focus();password2.style.border="5px solid #ff0000",password2.style.margin="0px 0px";});
		jry_wb_ajax_load_data('do_chenge.php?action=pas',function (data)
		{
			data=JSON.parse(data);
			jry_wb_loading_off();
			if(data.code)
			{
				jry_wb_beautiful_alert.alert('修改成功','',function(){document.location.reload()});
			}
			else
			{
				if(data.reason==100000)
					jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
				else if(data.reason==100001)
					jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
				else if(data.reason==100011)
					jry_wb_beautiful_alert.alert("修改失败","密码不同",function(){password2.focus();password2.style.border="5px solid #ff0000",password2.style.margin="0px 0px";});	
				else if(data.reason==100012)
					jry_wb_beautiful_alert.alert("修改失败","密码太短",function(){password1.focus();password1.style.border="5px solid #ff0000",password1.style.margin="0px 0px";});
				else if(data.reason==100006)
					jry_wb_beautiful_alert.alert("修改失败","密码错误",function(){password_yuan.style.border="5px solid #ff0000",password_yuan.style.margin="0px 0px";password_yuan.focus();});								
				else
					jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
				return ;
			}
		},[{'name':'password_yuan','value':password_yuan.value},{'name':'password1','value':password1.value},{'name':'password2','value':password2.value}],true);
	};	
}
function showshow()
{
	window.location.hash='showshow';
	showdiv.innerHTML='';
	if(login_timer==null)clearInterval(login_timer),login_timer=null;
	if(next_green_timer==null)clearInterval(next_green_timer),next_green_timer=null;
	var options=[{id:0,name:'正常'},{id:1,name:'全码'},{id:2,name:'不码'}];
	var table=document.createElement("table");showdiv.appendChild(table);	
	table.border=1;
	table.width="100%";
	
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);	
	td.width="250";
	var h55=document.createElement("h56");td.appendChild(h55);	
	h55.innerHTML='电话';
	td=null;
	var td=document.createElement("td");tr.appendChild(td);	
	var tel_show=document.createElement("select");td.appendChild(tel_show);	
	tel_show.name=tel_show.id='tel_show';tel_show.classList.add('h56');
	for(var i=0,n=options.length;i<n;i++)
	{
		var option=document.createElement('option');tel_show.appendChild(option);	
		option.value=options[i].id;
		if(options[i].id==jry_wb_login_user.tel_show)
			option.setAttribute("selected","selected");
		option.innerHTML=options[i].name;
	}	

	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);	
	td.width="250";
	var h55=document.createElement("h56");td.appendChild(h55);	
	h55.innerHTML='邮箱';
	td=null;
	var td=document.createElement("td");tr.appendChild(td);	
	var mail_show=document.createElement("select");td.appendChild(mail_show);	
	mail_show.name=mail_show.id='mail_show';mail_show.classList.add('h56');
	for(var i=0,n=options.length;i<n;i++)
	{
		var option=document.createElement('option');mail_show.appendChild(option);	
		option.value=options[i].id;
		if(options[i].id==jry_wb_login_user.mail_show)
			option.setAttribute("selected","selected");
		option.innerHTML=options[i].name;
	}
	var options=[{id:1,name:'显示'},{id:0,name:'隐藏'}];
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);	
	td.width="250";
	var h55=document.createElement("h56");td.appendChild(h55);	
	h55.innerHTML='登录信息';
	td=null;
	var td=document.createElement("td");tr.appendChild(td);	
	var ip_show=document.createElement("select");td.appendChild(ip_show);	
	ip_show.name=ip_show.id='ip_show';ip_show.classList.add('h56');
	for(var i=0,n=options.length;i<n;i++)
	{
		var option=document.createElement('option');ip_show.appendChild(option);	
		option.value=options[i].id;
		if(options[i].id==jry_wb_login_user.ip_show)
			option.setAttribute("selected","selected");
		option.innerHTML=options[i].name;
	}
	<?php if(JRY_WB_OAUTH_SWITCH){ ?>
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);	
	td.width="250";
	var h55=document.createElement("h56");td.appendChild(h55);	
	h55.innerHTML='第三方接入信息';
	td=null;
	var td=document.createElement("td");tr.appendChild(td);	
	var oauth_show=document.createElement("select");td.appendChild(oauth_show);	
	oauth_show.name=oauth_show.id='oauth_show';oauth_show.classList.add('h56');
	for(var i=0,n=options.length;i<n;i++)
	{
		var option=document.createElement('option');oauth_show.appendChild(option);	
		option.value=options[i].id;
		if(options[i].id==jry_wb_login_user.oauth_show)
			option.setAttribute("selected","selected");
		option.innerHTML=options[i].name;
	}
	<?php }?>
	var tr=document.createElement("tr");table.appendChild(tr);	
	var td=document.createElement("td");tr.appendChild(td);
	td.setAttribute('colspan',2);	
	var button=document.createElement("button");td.appendChild(button);
	button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');
	button.type='button';
	button.innerHTML='提交';
	button.onclick=function()
	{
		jry_wb_ajax_load_data('do_chenge.php?action=show',function (data)
		{
			data=JSON.parse(data);
			jry_wb_loading_off();
			if(data.code)
			{				
				jry_wb_login_user.tel_show=parseInt(tel_show.value);
				jry_wb_login_user.mail_show=parseInt(mail_show.value);
				jry_wb_login_user.ip_show=parseInt(ip_show.value);
				jry_wb_login_user.oauth_show=parseInt(oauth_show.value);
				jry_wb_beautiful_alert.alert('修改成功','',function(){showshow();});
			}
			else
			{
				if(data.reason==100000)
					jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
				else if(data.reason==100001)
					jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
				else
					jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
				return ;
			}
		},[{'name':'tel_show','value':tel_show.value},{'name':'mail_show','value':mail_show.value},{'name':'ip_show','value':ip_show.value},{'name':'oauth_show','value':<?php if(JRY_WB_OAUTH_SWITCH){ ?>oauth_show.value<?php }else{?>0<?php }?>}],true);
	};
}
function showspecialfact()
{
	window.location.hash='showspecialfact';
	showdiv.innerHTML='';
	if(login_timer==null)clearInterval(login_timer),login_timer=null;
	if(next_green_timer==null)clearInterval(next_green_timer),next_green_timer=null;
	var options=[{id:0,name:'关闭'},{id:1,name:'打开'}];
	var table=document.createElement("table");showdiv.appendChild(table);	
	table.border=1;
	table.width="100%";
	
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);	
	td.width="250";
	var h55=document.createElement("h56");td.appendChild(h55);	
	h55.innerHTML='弹幕';
	td=null;
	var td=document.createElement("td");tr.appendChild(td);	
	var word_special_fact=document.createElement("select");td.appendChild(word_special_fact);	
	word_special_fact.name=word_special_fact.id='word_special_fact';word_special_fact.classList.add('h56');
	for(var i=0,n=options.length;i<n;i++)
	{
		var option=document.createElement('option');word_special_fact.appendChild(option);	
		option.value=options[i].id;
		if(options[i].id==jry_wb_login_user.word_special_fact)
			option.setAttribute("selected","selected");
		option.innerHTML=options[i].name;
	}	

	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);	
	td.width="250";
	var h55=document.createElement("h56");td.appendChild(h55);	
	h55.innerHTML='鼠标跟随';
	td=null;
	var td=document.createElement("td");tr.appendChild(td);	
	var follow_mouth=document.createElement("select");td.appendChild(follow_mouth);	
	follow_mouth.name=follow_mouth.id='follow_mouth';follow_mouth.classList.add('h56');
	for(var i=0,n=options.length;i<n;i++)
	{
		var option=document.createElement('option');follow_mouth.appendChild(option);	
		option.value=options[i].id;
		if(options[i].id==jry_wb_login_user.follow_mouth)
			option.setAttribute("selected","selected");
		option.innerHTML=options[i].name;
	}
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);	
	td.width="250";
	var h55=document.createElement("h56");td.appendChild(h55);	
	h55.innerHTML='头像';
	td=null;
	var direction=[{'value':1,'name':'顺时针'},{'value':0,'name':'逆时针'}];
	var td=document.createElement("td");tr.appendChild(td);
	var head_table=document.createElement("table");td.appendChild(head_table);
	var head_tr1=document.createElement('tr');head_table.appendChild(head_tr1);
	var head_td=document.createElement('td');head_tr1.appendChild(head_td);
	head_td.classList.add('h56');
	head_td.innerHTML='鼠标离开时:';	
	var head_td=document.createElement('td');head_tr1.appendChild(head_td);
	head_td.classList.add('h56');
	head_td.innerHTML='速度:';
	var mouse_out_speed=document.createElement('input');head_td.appendChild(mouse_out_speed);
	mouse_out_speed.classList.add('h56');
	mouse_out_speed.style.width='100px';
	mouse_out_speed.value=jry_wb_login_user.head_special.mouse_out.speed;
	mouse_out_speed.name=mouse_out_speed.id='mouse_out_speed';
	var head_td=document.createElement('td');head_tr1.appendChild(head_td);
	head_td.classList.add('h56');
	head_td.innerHTML='方向:';
	var mouse_out_direction=document.createElement("select");head_td.appendChild(mouse_out_direction);
	mouse_out_direction.classList.add('h56');mouse_out_direction.id=mouse_out_direction.name='mouse_out_direction';
	for(var i=0,n=direction.length;i<n;i++)
	{
		var option=document.createElement('option');mouse_out_direction.appendChild(option);	
		option.value=direction[i].value;
		if(direction[i].value==jry_wb_login_user.head_special.mouse_out.direction)
			option.setAttribute("selected","selected");
		option.innerHTML=direction[i].name;
	}
	var head_td=document.createElement('td');head_tr1.appendChild(head_td);
	head_td.classList.add('h56');
	head_td.innerHTML='次数:(-1为无限)';
	var mouse_out_times=document.createElement('input');head_td.appendChild(mouse_out_times);
	mouse_out_times.classList.add('h56');	
	mouse_out_times.style.width='100px';
	mouse_out_times.value=jry_wb_login_user.head_special.mouse_out.times;
	mouse_out_times.name=mouse_out_times.id='mouse_out_times';
	var head_tr2=document.createElement('tr');head_table.appendChild(head_tr2);
	var head_td=document.createElement('td');head_tr2.appendChild(head_td);
	head_td.classList.add('h56');
	head_td.innerHTML='鼠标放置时:';
	var head_td=document.createElement('td');head_tr2.appendChild(head_td);
	head_td.classList.add('h56');
	head_td.innerHTML='速度:';
	var mouse_on_speed=document.createElement('input');head_td.appendChild(mouse_on_speed);
	mouse_on_speed.classList.add('h56');
	mouse_on_speed.style.width='100px';
	mouse_on_speed.value=jry_wb_login_user.head_special.mouse_on.speed;
	mouse_on_speed.name=mouse_on_speed.id='mouse_on_speed';
	var head_td=document.createElement('td');head_tr2.appendChild(head_td);
	head_td.classList.add('h56');
	head_td.innerHTML='方向:';
	var mouse_on_direction=document.createElement("select");head_td.appendChild(mouse_on_direction);
	mouse_on_direction.classList.add('h56');mouse_on_direction.id=mouse_on_direction.name='mouse_on_direction';
	for(var i=0,n=direction.length;i<n;i++)
	{
		var option=document.createElement('option');mouse_on_direction.appendChild(option);	
		option.value=direction[i].value;
		if(direction[i].value==jry_wb_login_user.head_special.mouse_on.direction)
			option.setAttribute("selected","selected");
		option.innerHTML=direction[i].name;
	}	
	var head_td=document.createElement('td');head_tr2.appendChild(head_td);	
	head_td.classList.add('h56');
	head_td.innerHTML='次数:(-1为无限)';
	var mouse_on_times=document.createElement('input');head_td.appendChild(mouse_on_times);
	mouse_on_times.classList.add('h56');
	mouse_on_times.style.width='100px';
	mouse_on_times.value=jry_wb_login_user.head_special.mouse_on.times;
	mouse_on_times.name=mouse_on_times.id='mouse_on_times';
	var tr=document.createElement("tr");table.appendChild(tr);	
	var td=document.createElement("td");tr.appendChild(td);
	td.setAttribute('colspan',2);
	var button=document.createElement("button");td.appendChild(button);
	button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');
	button.type='button';
	button.innerHTML='提交';
	button.onclick=function()
	{
		jry_wb_ajax_load_data('do_chenge.php?action=specialfact',function (data)
		{
			data=JSON.parse(data);
			jry_wb_loading_off();
			if(data.code)
			{				
				jry_wb_login_user.head_special=data.head_special;
				jry_wb_word_special_fact.switch=jry_wb_login_user.word_special_fact=(word_special_fact.value=='0'?false:true);
				jry_wb_login_user.follow_mouth=(follow_mouth.value=='0'?false:true);
				if(jry_wb_login_user.follow_mouth)
					window.follow_mouth.reinit();
				else
					window.follow_mouth.close();
				for(var all=document.getElementsByTagName('img'),i=0,n=all.length;i<n;i++)
					if(all[i].src==jry_wb_login_user.head)
						jry_wb_set_user_head_special(jry_wb_login_user,all[i]);
				jry_wb_beautiful_alert.alert('修改成功','',function(){showspecialfact();});
			}
			else
			{
				if(data.reason==100000)
					jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
				else if(data.reason==100001)
					jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
				else
					jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
				return ;
			}
		},[{'name':'word_special_fact','value':word_special_fact.value},{'name':'follow_mouth','value':follow_mouth.value},{'name':'mouse_on_speed','value':mouse_on_speed.value},{'name':'mouse_on_direction','value':mouse_on_direction.value},{'name':'mouse_on_times','value':mouse_on_times.value},{'name':'mouse_out_speed','value':mouse_out_speed.value},{'name':'mouse_out_direction','value':mouse_out_direction.value},{'name':'mouse_out_times','value':mouse_out_times.value}],true);
	};	
}
function showcache(loaded)
{
	window.location.hash='showcache';	
	loaded=loaded==null?false:loaded;
	showdiv.innerHTML='';
	if(login_timer==null)clearInterval(login_timer),login_timer=null;
	if(next_green_timer==null)clearInterval(next_green_timer),next_green_timer=null;
	var table=document.createElement("table");showdiv.appendChild(table);
	table.border=1;table.width='100%';
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);
	td.innerHTML='占用率<br><a href="../test/cache.php" target="_blank">测试缓存页面</a>';td.width='400';td.classList.add('h55');
	var td=document.createElement("td");tr.appendChild(td);
	td.width='*';td.setAttribute('colspan',2);
	cache_progress =new jry_wb_progress_bar_round(td,10,100,'#ccc','#1ABC9C',40,'#1ABC9C');
	if(loaded)
		cache_progress.update(jry_wb_cache.size()/(1024*1024*5));
	else
		cache_progress.set(0,jry_wb_cache.size()/(1024*1024*5),25);
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);
	td.width="250";
	td.setAttribute('align','center');
	td.setAttribute('colspan',3);
	var input=document.createElement("button");td.appendChild(input);
	input.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_warn');
	input.type='button';
	input.innerHTML='清空';
	input.onclick=function()
	{
		var old=jry_wb_cache.size()/(1024*1024*5)
		jry_wb_cache.delete_all();
		cache_progress.set(old,jry_wb_cache.size()/(1024*1024*5),25,function(){showcache(true);});
	};
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);
	td.innerHTML='key/size';td.width='250';td.classList.add('h55');
	var td=document.createElement("td");tr.appendChild(td);
	td.innerHTML='value';td.width='*';td.classList.add('h55');
	var td=document.createElement("td");tr.appendChild(td);
	td.innerHTML='action';td.width='200';td.classList.add('h55');
	var width=table.clientWidth-450;	
	for(var i=0;i<localStorage.length;i++)
	{
		var word=localStorage.getItem(localStorage.key(i));
		if(word.length>4096)
		{
			word=word.slice(0,4096);
			word+='...';
		}
		var tr=document.createElement("tr");table.appendChild(tr);
		var td=document.createElement("td");tr.appendChild(td);
		td.setAttribute('valign','top');
		var div=document.createElement("div");td.appendChild(div);
		div.innerHTML=localStorage.key(i)+'<br>'+(localStorage.getItem(localStorage.key(i)).length/1024)+'K';div.style='word-wrap:break-word;';div.style.width='250';div.classList.add('jry_wb_font_size_small');
		var td=document.createElement("td");tr.appendChild(td);
		td.setAttribute('valign','top');
		var div=document.createElement("div");td.appendChild(div);
		div.innerHTML=word;div.style='word-wrap:break-word;';div.style.width=width+'px';div.classList.add('jry_wb_font_size_small');
		td.setAttribute('valign','top');
		var td=document.createElement("td");tr.appendChild(td);
		td.width='200';td.classList.add('h55');
		if(jry_wb_cache.check_if_delete(localStorage.key(i)))
		{	
			var input=document.createElement("button");td.appendChild(input);
			input.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_warn');
			input.type='button';
			input.innerHTML='删除';
			td.setAttribute('valign','top');
			td.setAttribute('onclick','var old=jry_wb_cache.size()/(1024*1024*5);jry_wb_cache.delete("'+localStorage.key(i)+'");cache_progress.set(old,jry_wb_cache.size()/(1024*1024*5),25,function(){showcache(true);});');
		}
		else
			td.innerHTML='系统信息';
	}	
}
<?php if(JRY_WB_BACKGROUND_MUSIC_SWITCH){ ?>
function showmusiclist()
{
	window.location.hash='showmusiclist';	
	showdiv.innerHTML='';
	if(login_timer==null)clearInterval(login_timer),login_timer=null;
	if(next_green_timer==null)clearInterval(next_green_timer),next_green_timer=null;
	var background_music_list=jry_wb_login_user.background_music_list.slice();
	var table=document.createElement("table");showdiv.appendChild(table);
	table.border=1;table.width='100%';
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);
	var select=document.createElement("select");td.appendChild(select);select.classList.add('h56');
	var option=document.createElement("option");select.appendChild(option);option.classList.add('h56');option.innerHTML='QQ音乐';option.value='qq';
	var option=document.createElement("option");select.appendChild(option);option.classList.add('h56');option.innerHTML='网易云音乐';option.value='163';
	var input=document.createElement("input");td.appendChild(input);
	input.classList.add('h56');
	var button=document.createElement("button");td.appendChild(button);
	button.innerHTML="添加";
	button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);
	var tree=new jry_wb_tree(td,'');
	button.onclick=function()
	{
		var buf={'type':select.value,'mid':input.value,'slid':input.value};
		var data=jry_wb_get_songs_by_mid([buf]);
		if(data[0].name!='')
			if(background_music_list.findIndex(function(a){return (a.type==buf.type)&&((a.type=='songlist')?a.slid==buf.slid:a.mid==buf.mid);})==-1)
				tree.add(tree.root,data[0].name+'@'+data[0].type,JSON.stringify(buf)),background_music_list.push(data[0]);
			else
				jry_wb_beautiful_alert.alert("操作失败","因为已经有了");
		else
			jry_wb_beautiful_alert.alert("操作失败","不存在的歌曲");
	}
	for(var i=0,n=background_music_list.length;i<n;i++)
	{
		var buf={'type':background_music_list[i].type,'mid':background_music_list[i].mid,'slid':background_music_list[i].slid};
		var data=jry_wb_get_songs_by_mid([buf]);
		if(background_music_list[i].type=='songlist')
			for(var j=0,nn=data.length,a=tree.add(tree.root,"歌单"+background_music_list[i].slid,JSON.stringify(buf));j<nn;j++)
				tree.add(a,data[j].name+'@'+data[j].type,'',false);
		else
			tree.add(tree.root,data[0].name+'@'+data[0].type,JSON.stringify(buf));
	}
	tree.openall();
	var tr=document.createElement("tr");table.appendChild(tr);	
	var td=document.createElement("td");tr.appendChild(td);
	td.setAttribute('colspan',2);	
	var button=document.createElement("button");td.appendChild(button);
	button.innerHTML="删除选中";
	button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_error');	
	button.style.float='left';
	button.onclick=function()
	{
		var ans=background_music_list.slice();
		for(var data=tree.get_checked(true),i=0,n=data.length;i<n;i++)
		{
			var buf=JSON.parse(data[i]);
			var find=ans.findIndex(function(a){return (a.type==buf.type)&&((a.type=='songlist')?a.slid==buf.slid:a.mid==buf.mid);});
			if(find!=-1)
				ans.splice(find,1);
		}
		jry_wb_ajax_load_data('do_chenge.php?action=setsonglist',function(data)
		{
			data=JSON.parse(data);
			jry_wb_loading_off();
			if(data.code)
				jry_wb_beautiful_alert.alert("操作成功","",function()
				{
					jry_wb_login_user.background_music_list=ans;
					jry_wb_background_music.push_song_list(ans);
					showmusiclist();
				});
			else
			{
				if(data.reason==100000)
					jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
				else if(data.reason==100001)
					jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
				else
					jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
				return ;
			}
		},[{'name':'data','value':JSON.stringify(ans)}]);
	};
	var button=document.createElement("button");td.appendChild(button);
	button.innerHTML="保存";
	button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');	
	button.style.float='left';
	button.style.marginLeft='100px';
	button.onclick=function()
	{
		var ans=background_music_list.slice();
		jry_wb_ajax_load_data('do_chenge.php?action=setsonglist',function(data)
		{
			data=JSON.parse(data);
			jry_wb_loading_off();
			if(data.code)
				jry_wb_beautiful_alert.alert("操作成功","",function()
				{
					jry_wb_login_user.background_music_list=ans;
					jry_wb_background_music.push_song_list(ans);
					showmusiclist();
				});
			else
			{
				if(data.reason==100000)
					jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
				else if(data.reason==100001)
					jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
				else
					jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
				return ;
			}
		},[{'name':'data','value':JSON.stringify(ans)}]);
	};	
}
<?php } ?>
<?php if(JRY_WB_OAUTH_SWITCH){ ?>		
function tp_in()
{
	window.location.hash='tpin';	
	showdiv.innerHTML='';
	if(login_timer==null)clearInterval(login_timer),login_timer=null;
	if(next_green_timer==null)clearInterval(next_green_timer),next_green_timer=null;
	var table=document.createElement("table");showdiv.appendChild(table);
	table.border=1;table.width='100%';
	<?php if($jry_wb_tp_qq_oauth_config!=NULL){ ?>
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);
	td.classList.add('h56');
	td.innerHTML='QQ(oauth2.0)';
	var td=document.createElement("td");tr.appendChild(td);
	td.classList.add('h55');
	if(jry_wb_login_user.oauth_qq==null||jry_wb_login_user.oauth_qq=='')
	{
		td.innerHTML='没有绑定,点击绑定，powered by Tencent';
		td.onclick=function()
		{
			newwindow=window.open("jry_wb_qq_oauth.php","TencentLogin","width=450,height=700,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");	
			var timer=setInterval(function(){
				if(newwindow.closed)
				{
					clearInterval(timer);
					jry_wb_login_user.oauth_qq=JSON.parse(jry_wb_cache.get('oauth_qq'));
					jry_wb_cache.delete('oauth_qq');
					tp_in();
				}
			},500);
		};
	}
	else
	{
		var div=document.createElement("div");td.appendChild(div);
		div.innerHTML=jry_wb_login_user.oauth_qq.nickname;
		var img=document.createElement("img");div.appendChild(img);
		img.src=jry_wb_login_user.oauth_qq.figureurl_qq_2;
		img.height=30;
		img.width=30;
		var span=document.createElement("span");td.appendChild(span);
		span.innerHTML='点击解绑，powered by Tencent';
		span.classList.add('h55');
		span.onclick=function()
		{
			jry_wb_ajax_load_data('do_chenge.php?action=untpin&type=qq',function(data)
			{
				jry_wb_loading_off();
				var data=JSON.parse(data);
				if(data.code)
				{
					jry_wb_login_user.oauth_qq=null;
					jry_wb_login_user.head.type='default';
					jry_wb_update_user(jry_wb_login_user,'head');
					tp_in();
					jry_wb_beautiful_alert.alert("操作成功","");
				}
				else
				{
					if(data.reason==100000)
						jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
					else if(data.reason==100001)
						jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
					else
						jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
					return ;
				}
			});			
		};
	}
	<?php } ?>
	<?php if(constant('jry_wb_tp_github_oauth_config_client_id')!=''){ ?>
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);
	td.classList.add('h56');
	td.innerHTML='gayhub(oauth2.0)';
	var td=document.createElement("td");tr.appendChild(td);
	td.classList.add('h55');	
	if(jry_wb_login_user.oauth_github==null||jry_wb_login_user.oauth_github=='')
	{
		td.innerHTML='没有绑定,点击绑定，powered by github';
		td.onclick=function()
		{
			newwindow=window.open("jry_wb_github_oauth.php","GithubLogin","width=450,height=700,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");	
			var timer=setInterval(function(){
				if(newwindow.closed)
				{
					clearInterval(timer);
					jry_wb_login_user.oauth_github=JSON.parse(jry_wb_cache.get('oauth_github'));
					jry_wb_cache.delete('oauth_github');
					tp_in();
				}
			},1000);
		};
	}
	else
	{
		var div=document.createElement("div");td.appendChild(div);
		div.innerHTML=jry_wb_login_user.oauth_github.name+jry_wb_login_user.oauth_github.login;
		var img=document.createElement("img");div.appendChild(img);
		img.src=jry_wb_login_user.oauth_github.avatar_url;
		img.height=30;
		img.width=30;
		var span=document.createElement("span");td.appendChild(span);
		span.innerHTML='点击解绑，powered by github';
		span.classList.add('h55');
		span.onclick=function()
		{
			jry_wb_ajax_load_data('do_chenge.php?action=untpin&type=github',function(data)
			{
				jry_wb_loading_off();
				var data=JSON.parse(data);
				if(data.code)
				{
					jry_wb_login_user.oauth_github=null;
					jry_wb_login_user.head.type='default';
					jry_wb_update_user(jry_wb_login_user,'head');
					tp_in();
					jry_wb_beautiful_alert.alert("操作成功","");
				}
				else
				{
					if(data.reason==100000)
						jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
					else if(data.reason==100001)
						jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
						else
						jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
						return ;
				}
			});			
		};		
	}	
	<?php } ?>
	<?php if(constant('jry_wb_tp_mi_oauth_config_client_id')!=''){ ?>
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);
	td.classList.add('h56');
	td.innerHTML='MI(oauth2.0)';
	var td=document.createElement("td");tr.appendChild(td);
	td.classList.add('h55');
	if(jry_wb_login_user.oauth_mi==null||jry_wb_login_user.oauth_mi=='')
	{
		td.innerHTML='没有绑定,点击绑定，powered by Xiaomi.inc';
		td.onclick=function()
		{
			newwindow=window.open("jry_wb_mi_oauth.php","miLogin","width=450,height=700,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");	
			var timer=setInterval(function(){
				if(newwindow.closed)
				{
					clearInterval(timer);
					jry_wb_login_user.oauth_mi=JSON.parse(jry_wb_cache.get('oauth_mi'));
					jry_wb_cache.delete('oauth_mi');
					tp_in();
				}
			},500);
		};
	}
	else
	{
		var div=document.createElement("div");td.appendChild(div);
		div.innerHTML=jry_wb_login_user.oauth_mi.miliaoNick;
		var img=document.createElement("img");div.appendChild(img);
		img.src=jry_wb_login_user.oauth_mi.miliaoIcon_orig;
		img.height=30;
		img.width=30;
		var span=document.createElement("span");td.appendChild(span);
		span.innerHTML='点击解绑，powered by Xiaomi.inc';
		span.classList.add('h55');
		span.onclick=function()
		{
			jry_wb_ajax_load_data('do_chenge.php?action=untpin&type=mi',function(data)
			{
				jry_wb_loading_off();
				var data=JSON.parse(data);
				if(data.code)
				{
					jry_wb_login_user.oauth_mi=null;
					jry_wb_login_user.head.type='default';
					jry_wb_update_user(jry_wb_login_user,'head');
					tp_in();
					jry_wb_beautiful_alert.alert("操作成功","");
				}
				else
				{
					if(data.reason==100000)
						jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
					else if(data.reason==100001)
						jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
					else
						jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
					return ;
				}
			});			
		};
	}
	<?php } ?>
	<?php if(constant('jry_wb_tp_gitee_oauth_config_client_id')!=''){ ?>
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);
	td.classList.add('h56');
	td.innerHTML='码云(oauth2.0)';
	var td=document.createElement("td");tr.appendChild(td);
	td.classList.add('h55');	
	if(jry_wb_login_user.oauth_gitee==null||jry_wb_login_user.oauth_gitee=='')
	{
		td.innerHTML='没有绑定,点击绑定，powered by 码云';
		td.onclick=function()
		{
			newwindow=window.open("jry_wb_gitee_oauth.php","GiteeLogin","width=1200,height=700,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");	
			var timer=setInterval(function(){
				if(newwindow.closed)
				{
					clearInterval(timer);
					jry_wb_login_user.oauth_gitee=JSON.parse(jry_wb_cache.get('oauth_gitee').replace(/\n/g, "<br>"));
					jry_wb_cache.delete('oauth_gitee');
					tp_in();
				}
			},1000);
		};
	}
	else
	{
		var div=document.createElement("div");td.appendChild(div);
		div.innerHTML=jry_wb_login_user.oauth_gitee.name+jry_wb_login_user.oauth_gitee.login;
		var img=document.createElement("img");div.appendChild(img);
		img.src=jry_wb_login_user.oauth_gitee.avatar_url;
		img.height=30;
		img.width=30;
		var span=document.createElement("span");td.appendChild(span);
		span.innerHTML='点击解绑，powered by 码云';
		span.classList.add('h55');
		span.onclick=function()
		{
			jry_wb_ajax_load_data('do_chenge.php?action=untpin&type=gitee',function(data)
			{
				jry_wb_loading_off();
				var data=JSON.parse(data);	
				if(data.code)
				{
					jry_wb_login_user.oauth_gitee=null;
					jry_wb_login_user.head.type='default';
					jry_wb_update_user(jry_wb_login_user,'head');
					tp_in();
					jry_wb_beautiful_alert.alert("操作成功","");
				}
				else
				{
					if(data.reason==100000)
						jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
					else if(data.reason==100001)
						jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
					else
						jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
					return ;
				}
			});			
		};
	}
	<?php } ?>
}
<?php } ?>
<?php if($jry_wb_config_user_extern_message!=NULL){ ?>
function showextern()
{
	window.location.hash='extern';
	showdiv.innerHTML='';
	if(login_timer==null)clearInterval(login_timer),login_timer=null;
	if(next_green_timer==null)clearInterval(next_green_timer),next_green_timer=null;	
<?php foreach($jry_wb_config_user_extern_message as $one)if($one['type']!='cutter'){ ?>
	var time_<?php echo $one['key']; ?>=0;
<?php } ?>	
	var table=document.createElement("table");showdiv.appendChild(table);	
	table.border=1;
	table.width="100%";
<?php foreach($jry_wb_config_user_extern_message as $one)if($one['type']!='cutter'){?>
	var tr=document.createElement("tr");table.appendChild(tr);	
	var td=document.createElement("td");tr.appendChild(td);
	td.style.width='250px';
	td.innerHTML='<?php echo $one['name']; ?>';	
	td.classList.add('h56');
	var td=document.createElement("td");tr.appendChild(td);
<?php if($one['type']=='word'||$one['type']=='tel'||$one['type']=='mail'||$one['type']=='china_id'){ ?>
	var <?php  echo $one['key']; ?>=document.createElement("input");td.appendChild(<?php  echo $one['key']; ?>);
	<?php  echo $one['key']; ?>.type='text';
	<?php  echo $one['key']; ?>.id=<?php  echo $one['key']; ?>.name='<?php  echo $one['key']; ?>';
	<?php  echo $one['key']; ?>.classList.add('h56');
	<?php  echo $one['key']; ?>.value=jry_wb_login_user.extern.<?php  echo $one['key']; ?>;
<?php }else if($one['type']=='select'){ ?>
	var <?php  echo $one['key']; ?>=document.createElement("select");td.appendChild(<?php  echo $one['key']; ?>);
	<?php  echo $one['key']; ?>.classList.add('h56');
	var option=document.createElement('option');<?php  echo $one['key']; ?>.appendChild(option);
	option.style.display='none';
<?php foreach($one['select'] as $select){ if(is_array($select)){?>
	var option=document.createElement('option');<?php  echo $one['key']; ?>.appendChild(option);
	option.value='<?php echo $select['value']; ?>';
	option.innerHTML='<?php echo $select['name']; ?>';	
<?php }else{ ?>
	var option=document.createElement('option');<?php  echo $one['key']; ?>.appendChild(option);
	option.value=option.innerHTML='<?php echo $select; ?>';
<?php } ?>
<?php }?>
<?php  echo $one['key']; ?>.value=jry_wb_login_user.extern.<?php  echo $one['key']; ?>;
<?php }else if($one['type']=='check'){ ?>
	var <?php  echo $one['key']; ?>s=[];
	var input=document.createElement('input');td.appendChild(input);
	if(jry_wb_login_user.extern.<?php  echo $one['key']; ?>==(input.value=1))
		input.setAttribute('checked','');
	input.type='radio';
	input.name='<?php  echo $one['key']; ?>';
	input.onclick=function(e){if(e==undefined)e=window.event;if(e.target==this)check_all(e);};
	<?php  echo $one['key']; ?>s[0]=input;
	var h56=document.createElement('h56');td.appendChild(h56);
	h56.innerHTML='是';
	var input=document.createElement('input');td.appendChild(input);
	if(jry_wb_login_user.extern.<?php  echo $one['key']; ?>==(input.value=0))
		input.setAttribute('checked','');
	input.type='radio';
	input.name='<?php  echo $one['key']; ?>';
	input.onclick=function(e){if(e==undefined)e=window.event;if(e.target==this)check_all(e);};
	<?php  echo $one['key']; ?>s[1]=input;
	var h56=document.createElement('h56');td.appendChild(h56);
	h56.innerHTML='否';	
	h56.style.marginLeft='20px';		 
<?php }?>
<?php }?>
<?php foreach($jry_wb_config_user_extern_message as $one)if($one['type']!='cutter'){ ?>
<?php if($one['type']!='check'){ ?>
<?php echo $one['key']; ?>.onfocus=<?php echo $one['key']; ?>.onkeyup=<?php echo $one['key']; ?>.onchenge=<?php echo $one['key']; ?>.onblur=function(e)
	{
		if(e==undefined)e=window.event;
		if(e.target==this)check_all(e);		
<?php if($one['type']=='tel'){ ?>
		if(<?php echo $one['key']; ?>.value!=""&&(jry_wb_test_phone_number(<?php echo $one['key']; ?>.value)==false))
		{
			if(((new Date())-time_<?php echo $one['key']; ?>)>5000)
			{
				time_<?php echo $one['key']; ?>=new Date();
				jry_wb_beautiful_right_alert.alert("<?php echo $one['name']; ?>错误",2000,"auto","error");
			}
			<?php echo $one['key']; ?>.style.border="5px solid #ff0000",<?php echo $one['key']; ?>.style.margin="0px 0px";
			return false;
		}
		else
			<?php echo $one['key']; ?>.style.border="",<?php echo $one['key']; ?>.style.margin="",time_<?php echo $one['key']; ?>=0;
<?php }else if($one['type']=='mail'){ ?>
		if(<?php echo $one['key']; ?>.value!=""&&(jry_wb_test_mail(<?php echo $one['key']; ?>.value)==false))
		{
			if(((new Date())-time_<?php echo $one['key']; ?>)>5000)
			{
				time_<?php echo $one['key']; ?>=new Date();
				jry_wb_beautiful_right_alert.alert("<?php echo $one['name']; ?>错误",2000,"auto","error");
			}	
			<?php echo $one['key']; ?>.style.border="5px solid #ff0000",<?php echo $one['key']; ?>.style.margin="0px 0px";
			return false;
		}
		else
			<?php echo $one['key']; ?>.style.border="",<?php echo $one['key']; ?>.style.margin="",time_<?php echo $one['key']; ?>=0;
<?php }else if($one['type']=='china_id'){ ?>
		if(<?php echo $one['key']; ?>.value!=""&&(jry_wb_test_china_id_card(<?php echo $one['key']; ?>.value)==false))
		{
			if(((new Date())-time_<?php echo $one['key']; ?>)>5000)
			{
				time_<?php echo $one['key']; ?>=new Date();
				jry_wb_beautiful_right_alert.alert("<?php echo $one['name']; ?>错误",2000,"auto","error");
			}	
			<?php echo $one['key']; ?>.style.border="5px solid #ff0000",<?php echo $one['key']; ?>.style.margin="0px 0px";
			return false;
		}
		else
			<?php echo $one['key']; ?>.style.border="",<?php echo $one['key']; ?>.style.margin="",time_<?php echo $one['key']; ?>=0;
<?php } ?>
<?php if($one['connect']!=NULL)
{
	foreach($one['connect'] as $connect)
	{
		if($one['type']=='china_id'&&$connect=='sex')
		{ ?>
			if(<?php echo $one['key']; ?>.value!=""&&(jry_wb_get_sex_by_china_id_card(<?php echo $one['key']; ?>.value)!=jry_wb_login_user.sex))
			{
				jry_wb_beautiful_right_alert.alert("<?php echo $one['name']; ?>与性别不符",2000,"auto","error");
				<?php echo $one['key']; ?>.style.border="5px solid #ff0000",<?php echo $one['key']; ?>.style.margin="0px 0px";
				return false;
			}
			else
				<?php echo $one['key']; ?>.style.border="",<?php echo $one['key']; ?>.style.margin="";
		<?php }else{ ?>
			if(<?php echo $one['key']; ?>.value!=""&&(<?php echo $one['key']; ?>.value==<?php echo ($connect=='namee')?('jry_wb_login_user.name'):((($connect=='sex'||$connect=='tel'||$connect=='mail')?'jry_wb_login_user.':'').$connect.(($connect=='sex'||$connect=='tel'||$connect=='mail'?'':'.value'))); ?>))
			{
				jry_wb_beautiful_right_alert.alert("信息重复",2000,"auto","error");
				<?php echo $one['key']; ?>.style.border="5px solid #ff0000",<?php echo $one['key']; ?>.style.margin="0px 0px";
				return false;
			}
			else
				<?php echo $one['key']; ?>.style.border="",<?php echo $one['key']; ?>.style.margin="",time_<?php echo $one['key']; ?>=0;					
		<?php }
	}
}
?>
<?php if($one['checker_js']!=''){ ?>
		if(eval("<?php echo $one['checker_js']; ?>")==false)
		{
			jry_wb_beautiful_right_alert.alert("信息错误",2000,"auto","error");
			<?php echo $one['key']; ?>.style.border="5px solid #ff0000",<?php echo $one['key']; ?>.style.margin="0px 0px";
			return false;
		}
		else
			<?php echo $one['key']; ?>.style.border="",<?php echo $one['key']; ?>.style.margin="",time_<?php echo $one['key']; ?>=0;		
<?php }?>
		return true;		
	}
<?php }?>
<?php }?>
	function check_all(e)
	{
		if(e==undefined)e=window.event;
<?php foreach($jry_wb_config_user_extern_message as $one)if($one['type']!='cutter'&&$one['type']!='check'){ ?>
		if(e.target!=<?php echo $one['key']; ?>)if(!<?php echo $one['key']; ?>.onkeyup(e))return false;
<?php } ?>
		return true;
	}
	var tr=document.createElement("tr");table.appendChild(tr);	
	var td=document.createElement("td");tr.appendChild(td);
	td.setAttribute('colspan',2);
	var button=document.createElement("button");td.appendChild(button);
	button.innerHTML="提交";
	button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');	
	button.style.float='left';
	button.onclick=function()
	{
<?php foreach($jry_wb_config_user_extern_message as $one){if($one['type']=='check'){ ?>
		for(var i=0,n=<?php echo $one['key']; ?>s.length;i<n;i++)
			if(<?php echo $one['key']; ?>s[i].checked)
				<?php echo $one['key']; ?>=<?php echo $one['key']; ?>s[i].value;
<?php }} ?>
		if(!check_all({'target':button}))
			return jry_wb_beautiful_alert.alert("修改失败","");
		var extern={<?php foreach($jry_wb_config_user_extern_message as $one)if($one['type']!='cutter'){ ?>'<?php echo $one['key']; ?>':<?php echo $one['key']; ?><?php if($one['type']!='check'){ ?>.value<?php } ?>,<?php } ?>};
		if(<?php $i=0; foreach($jry_wb_config_user_extern_message as $one)if($one['type']!='cutter'&&$one['type']!='check'){ ?><?php if($i!=0)echo '&&';$i++; ?>(extern.<?php echo $one['key']; ?>===jry_wb_login_user.extern.<?php echo $one['key']; ?>)<?php } ?>)
			return jry_wb_beautiful_alert.alert("修改失败","并没有修改");
		jry_wb_ajax_load_data('do_chenge.php?action=extern',function (data)
		{
			data=JSON.parse(data);
			jry_wb_loading_off();
			if(data.code)
			{	
				jry_wb_beautiful_alert.alert('修改成功','',function(){jry_wb_login_user.extern=extern;showextern();})
			}
			else
			{
				if(data.reason==100000)
					jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
				else if(data.reason==100001)
					jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");				
				else if(data.reason==100017)
					jry_wb_beautiful_alert.alert("注册失败",data.extern.name+"为空或错误",function(){eval(data.extern.key).focus();eval(data.extern.key).style.border="5px solid #ff0000",eval(data.extern.key).style.margin="0px 0px";});
			}
		},[{'name':'extern','value':encodeURIComponent(JSON.stringify(extern))}],true);
		return true;
	}
}
<?php } ?>
switch(window.location.hash)
{
	case '#show':
		show();
		window.onresize();
		break;
	case '#showchenge':
		showchenge();
		window.onresize();
		break;
	case '#show_ip':
		show_ip();
		window.onresize();
		break;
	case '#showtel':
		showtel();
		window.onresize();
		break;
	case '#showmail':
		showmail();
		window.onresize();
		break;
	case '#showpas':
		showpas();
		window.onresize();
		break;
	case '#showshow':
		showshow();
		window.onresize();
		break;
	case '#showspecialfact':
		showspecialfact();
		window.onresize();
		break;
	case '#showcache':
		showcache();
		window.onresize();
		break;
<?php if(JRY_WB_BACKGROUND_MUSIC_SWITCH){ ?>		
	case '#showmusiclist':
		showmusiclist();
		window.onresize();
		break;
<?php } ?>
<?php if(JRY_WB_OAUTH_SWITCH){ ?>		
	case '#tpin':
		tp_in();
		window.onresize();
		break;			
<?php } ?>
<?php if($jry_wb_config_user_extern_message!=NULL){ ?>
	case '#extern':
		showextern();
		window.onresize();
		break;	
<?php } ?>
		default:
			show();
			window.onresize();
}
<?php if(false){ ?></script><?php } ?>