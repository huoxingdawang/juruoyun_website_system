var showdiv=document.getElementById("show");
function unlock()
{
	jry_wb_ajax_load_data('do_chenge.php?action=unlock&id='+id,function (data){jry_wb_loading_off();data=JSON.parse(data);if(data.login==false){jry_wb_beautiful_right_alert.alert('无法操作，因为'+data.reasion,2000,'auto','error');return;}if(data.data=='OK'){jry_wb_beautiful_right_alert.alert('申请成功，请耐心等待',2000,'auto','ok');return;}if(data.data=='mail'){jry_wb_beautiful_right_alert.alert('Mail Error',2000,'auto','error');return;}jry_wb_beautiful_right_alert.alert('Unknow error',2000,'auto','alert');});
}
stoplogin=false;
function showlogin() 
{
	var intime=jry_wb_login_user.logdate;
	var addre="login";
	var date1 =jry_wb_get_server_time();
	var date2 = new Date(intime.replace(/\-/g, "/"));
	var ms = (date1.getTime() - date2.getTime());
	var day=parseInt(ms/(24*60*60*1000));
	var hour=parseInt(ms/(60*60*1000))-day*24;
	var minute=parseInt(ms/(60*1000))-hour*60-day*24*60;
	var s=parseInt(ms/(1000))-minute*60-hour*60*60-day*24*60*60;
	if(!stopnext)
		document.getElementById(addre).innerHTML=day+"天"+hour+"时"+minute+"分"+s+"秒";
	if(!stoplogin)
		timerid = setTimeout("showlogin()",1000);
	timerRunning = true;
}
stopnext=false;
function shownext() 
{
	var intime=jry_wb_login_user.greendate;
	var addre="next";
	var date1 =jry_wb_get_server_time();
	var date2 = new Date(intime.replace(/\-/g, "/"));
	var ms = (date1.getTime() - date2.getTime());
	ms=9*60*60*1000-ms;
	if(!stopnext)
	{
		if(ms>0)
		{
			var day=0;
			var hour=parseInt(ms/(60*60*1000))-day*24;
			var minute=parseInt(ms/(60*1000))-hour*60-day*24*60;
			var s=parseInt(ms/(1000))-minute*60-hour*60*60-day*24*60*60;
			document.getElementById(addre).innerHTML=hour+"时"+minute+"分"+s+"秒";
			timerRunning = true;
			if(!stopnext)
				timerid = setTimeout("shownext()",1000);
		}
		else
		{
			document.getElementById(addre).innerHTML="时间到，退出重登即可";
		}
	}
}
function show()
{
	window.location.hash='show';
	showdiv.innerHTML='';
	stoplogin=false;
	stopnext=false;
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
	img.onclick=function()
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
		img2.src=jry_wb_default_user_head;
		var td=document.createElement("td"); tr.appendChild(td);
		if(jry_wb_default_user_head==jry_wb_login_user.head)
			td.innerHTML='正在使用';
		else
		{
			var button=document.createElement("button");td.appendChild(button);
			button.innerHTML="使用"; 
			button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
			button.onclick=function()
			{
				for(var all=document.getElementsByTagName('img'),i=0,n=all.length;i<n;i++)if(all[i].src==jry_wb_login_user.head)all[i].src=jry_wb_default_user_head;jry_wb_login_user.head=jry_wb_default_user_head;
				jry_wb_ajax_load_data('do_chenge.php?action=chengehead&type=default',function(data)
				{
					jry_wb_loading_off();
					head_alert.close();
					var data=JSON.parse(data);
					if(data.statue)
						jry_wb_beautiful_alert.alert("操作成功","");
					else
						jry_wb_beautiful_alert.alert("操作失败","因为"+data.reasion);
				});
			};
		}
		if(jry_wb_gravatar_user_head!='')
		{
			var tr=document.createElement("tr"); table.appendChild(tr);
			var td=document.createElement("td"); tr.appendChild(td);td.classList.add('h56');td.innerHTML='gravatar';
			var td=document.createElement("td"); tr.appendChild(td);
			var img2=document.createElement("img");td.appendChild(img2);
			jry_wb_set_user_head_special(jry_wb_login_user,img2);img2.height=80;img2.width=80;
			img2.src=jry_wb_gravatar_user_head;
			var td=document.createElement("td"); tr.appendChild(td);
			if(jry_wb_gravatar_user_head.split('?')[0]==jry_wb_login_user.head.split('?')[0])
				td.innerHTML='正在使用';
			else
			{
				var button=document.createElement("button");td.appendChild(button);
				button.innerHTML="使用"; 
				button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
				button.onclick=function()
				{
					for(var all=document.getElementsByTagName('img'),i=0,n=all.length;i<n;i++)if(all[i].src==jry_wb_login_user.head)all[i].src=jry_wb_gravatar_user_head;jry_wb_login_user.head=jry_wb_gravatar_user_head;
					jry_wb_ajax_load_data('do_chenge.php?action=chengehead&type=gravatar',function(data)
					{
						jry_wb_loading_off();
						head_alert.close();
						var data=JSON.parse(data);
						if(data.statue)
							jry_wb_beautiful_alert.alert("操作成功","");
						else
							jry_wb_beautiful_alert.alert("操作失败","因为"+data.reasion);
					});
				};
			}
		}
		if(jry_wb_qq_user_head!='')
		{
			var tr=document.createElement("tr"); table.appendChild(tr);
			var td=document.createElement("td"); tr.appendChild(td);td.classList.add('h56');td.innerHTML='QQ';
			var td=document.createElement("td"); tr.appendChild(td);
			var img2=document.createElement("img");td.appendChild(img2);
			jry_wb_set_user_head_special(jry_wb_login_user,img2);img2.height=80;img2.width=80;
			img2.src=jry_wb_qq_user_head;
			var td=document.createElement("td"); tr.appendChild(td);
			if(jry_wb_qq_user_head==jry_wb_login_user.head)
				td.innerHTML='正在使用';
			else
			{
				var button=document.createElement("button");td.appendChild(button);
				button.innerHTML="使用"; 
				button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
				button.onclick=function()
				{
					for(var all=document.getElementsByTagName('img'),i=0,n=all.length;i<n;i++)if(all[i].src==jry_wb_login_user.head)all[i].src=jry_wb_qq_user_head;jry_wb_login_user.head=jry_wb_qq_user_head;
					jry_wb_ajax_load_data('do_chenge.php?action=chengehead&type=qq',function(data)
					{
						jry_wb_loading_off();
						head_alert.close();
						var data=JSON.parse(data);
						if(data.statue)
							jry_wb_beautiful_alert.alert("操作成功","");
						else
							jry_wb_beautiful_alert.alert("操作失败","因为"+data.reasion);
					});
				};
			}
		}
		if(jry_wb_github_user_head!='')
		{
			var tr=document.createElement("tr"); table.appendChild(tr);
			var td=document.createElement("td"); tr.appendChild(td);td.classList.add('h56');td.innerHTML='gayhub';
			var td=document.createElement("td"); tr.appendChild(td);
			var img2=document.createElement("img");td.appendChild(img2);
			jry_wb_set_user_head_special(jry_wb_login_user,img2);img2.height=80;img2.width=80;
			img2.src=jry_wb_github_user_head;
			var td=document.createElement("td"); tr.appendChild(td);
			if(jry_wb_github_user_head==jry_wb_login_user.head)
				td.innerHTML='正在使用';
			else
			{
				var button=document.createElement("button");td.appendChild(button);
				button.innerHTML="使用"; 
				button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
				button.onclick=function()
				{
					for(var all=document.getElementsByTagName('img'),i=0,n=all.length;i<n;i++)if(all[i].src==jry_wb_login_user.head)all[i].src=jry_wb_github_user_head;jry_wb_login_user.head=jry_wb_github_user_head;
					jry_wb_ajax_load_data('do_chenge.php?action=chengehead&type=github',function(data)
					{
						jry_wb_loading_off();
						head_alert.close();
						var data=JSON.parse(data);
						if(data.statue)
							jry_wb_beautiful_alert.alert("操作成功","");
						else
							jry_wb_beautiful_alert.alert("操作失败","因为"+data.reasion);
					});
				};
			}
		}
		if(jry_wb_gitee_user_head!='')
		{
			var tr=document.createElement("tr"); table.appendChild(tr);
			var td=document.createElement("td"); tr.appendChild(td);td.classList.add('h56');td.innerHTML='码云';
			var td=document.createElement("td"); tr.appendChild(td);
			var img2=document.createElement("img");td.appendChild(img2);
			jry_wb_set_user_head_special(jry_wb_login_user,img2);img2.height=80;img2.width=80;
			img2.src=jry_wb_gitee_user_head;
			var td=document.createElement("td"); tr.appendChild(td);
			if(jry_wb_gitee_user_head==jry_wb_login_user.head)
				td.innerHTML='正在使用';
			else
			{
				var button=document.createElement("button");td.appendChild(button);
				button.innerHTML="使用"; 
				button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
				button.onclick=function()
				{
					for(var all=document.getElementsByTagName('img'),i=0,n=all.length;i<n;i++)if(all[i].src==jry_wb_login_user.head)all[i].src=jry_wb_gitee_user_head;jry_wb_login_user.head=jry_wb_gitee_user_head;
					jry_wb_ajax_load_data('do_chenge.php?action=chengehead&type=gitee',function(data)
					{
						jry_wb_loading_off();
						head_alert.close();
						var data=JSON.parse(data);
						if(data.statue)
							jry_wb_beautiful_alert.alert("操作成功","");
						else
							jry_wb_beautiful_alert.alert("操作失败","因为"+data.reasion);
					});
				};
			}
		}
		if(jry_wb_mi_user_head!='')
		{
			var tr=document.createElement("tr"); table.appendChild(tr);
			var td=document.createElement("td"); tr.appendChild(td);td.classList.add('h56');td.innerHTML='MI';
			var td=document.createElement("td"); tr.appendChild(td);
			var img2=document.createElement("img");td.appendChild(img2);
			jry_wb_set_user_head_special(jry_wb_login_user,img2);img2.height=80;img2.width=80;
			img2.src=jry_wb_mi_user_head;
			var td=document.createElement("td"); tr.appendChild(td);
			if(jry_wb_mi_user_head==jry_wb_login_user.head)
				td.innerHTML='正在使用';
			else
			{
				var button=document.createElement("button");td.appendChild(button);
				button.innerHTML="使用"; 
				button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
				button.onclick=function()
				{
					for(var all=document.getElementsByTagName('img'),i=0,n=all.length;i<n;i++)if(all[i].src==jry_wb_login_user.head)all[i].src=jry_wb_mi_user_head;jry_wb_login_user.head=jry_wb_mi_user_head;
					jry_wb_ajax_load_data('do_chenge.php?action=chengehead&type=mi',function(data)
					{
						jry_wb_loading_off();
						head_alert.close();
						var data=JSON.parse(data);
						if(data.statue)
							jry_wb_beautiful_alert.alert("操作成功","");
						else
							jry_wb_beautiful_alert.alert("操作失败","因为"+data.reasion);
					});
				};
			}
		}		
	};
	jry_wb_show_tr_no_input(table,'绿币',jry_wb_login_user.green_money,'',250);	
	jry_wb_show_tr_no_input(table,'注册日期',jry_wb_login_user.enroldate,'',250);		
	jry_wb_show_tr_no_input(table,'登录日期',jry_wb_login_user.logdate,'',250);		
	jry_wb_show_tr_no_input(table,'已登录时间',0,'login','',250);
	showlogin();
	jry_wb_show_tr_no_input(table,'上次绿币奖励时间',jry_wb_login_user.greendate,'',250);		
	jry_wb_show_tr_no_input(table,'距下次获得',0,'next',250);
	shownext();
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
	stoplogin=true;
	stopnext=true;
	var table=document.createElement("table");
	table.border=1;
	table.width="100%";
	showdiv.appendChild(table);	
	var tr=document.createElement("tr");
	table.appendChild(tr);
	var td=document.createElement("td");
	td.width="250";
	var h55=document.createElement("h55");
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
				jry_wb_ajax_load_data('do_chenge.php?action=logout',function(){jry_wb_loading_off();h55.removeChild(div);if(jry_wb_login_user.login_addr[i].isthis)document.location.href=jry_wb_message.jry_wb_index_page;},[{'name':'code','value':jry_wb_login_user.login_addr[i].code}]);
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
					jry_wb_ajax_load_data('do_chenge.php?action=untrust',function(){jry_wb_login_user.login_addr[i].trust=0;jry_wb_loading_off();trust.classList.remove('jry_wb_color_ok');},[{'name':'code','value':jry_wb_login_user.login_addr[i].code}]);
				},function(){});
			}
			else
			{
				jry_wb_beautiful_alert.check("确定信任?",function()
				{
					jry_wb_ajax_load_data('do_chenge.php?action=trust',function(){jry_wb_login_user.login_addr[i].trust=0;jry_wb_loading_off();trust.classList.add('jry_wb_color_ok');});
				},function(){});				
			}
		};
		var span=document.createElement("span");div.appendChild(span);
		span.classList.add('jry_wb_icon','h55');		
		if(jry_wb_login_user.login_addr[i].isthis)
			span.classList.add('jry_wb_icon_dangqian');
			
		li=null;		
	}
	var button=document.createElement('button');showdiv.appendChild(button);	
	button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');
	button.setAttribute('onclick',"window.open('logout.php','_parent');");
	button.innerHTML="登出";
	button=null;
	
	var button=document.createElement("button");showdiv.appendChild(button);	
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
	stoplogin=true;
	stopnext=true;
	var form=document.createElement("form");
	form.method='POST';
	form.action="do_chenge.php?action=simple";
	showdiv.appendChild(form);	
	var table=document.createElement("table");
	table.border=1;
	table.width="100%";
	form.appendChild(table);
	jry_wb_show_tr_with_input(table,'姓名','name',jry_wb_login_user.name,'text',function(){},250);
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);	
	td.width="250";
	var h55=document.createElement("h55");td.appendChild(h55);
	h55.innerHTML='性别';
	td=null;
	var td=document.createElement("td");tr.appendChild(td);	

	var input=document.createElement("input");td.appendChild(input);
	input.name=input.id='sex';input.classList.add('h56');input.type='radio';
	if(jry_wb_login_user.sex==(input.value=1))
		input.setAttribute('checked','');
	input=null;
	var h56=document.createElement("h56");td.appendChild(h56);
	h56.innerHTML="男"
	h56=null;
		
	var input=document.createElement("input");td.appendChild(input);
	input.name=input.id='sex';input.classList.add('h56');input.type='radio';
	if(jry_wb_login_user.sex==(input.value=0))
		input.setAttribute('checked','');
	input=null;
	var h56=document.createElement("h56");td.appendChild(h56);
	h56.innerHTML="女"
	h56=null;

	var input=document.createElement("input");td.appendChild(input);
	input.name=input.id='sex';input.classList.add('h56');input.type='radio';
	if(jry_wb_login_user.sex==(input.value=2))
		input.setAttribute('checked','');
	input=null;
	var h56=document.createElement("h56");td.appendChild(h56);
	h56.innerHTML="女装大佬"
	h56=null;
	
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);	
	td.width="250";
	var h55=document.createElement("h55");td.appendChild(h55);
	h55.innerHTML='惯用语';
	td=null;
	var td=document.createElement("td");tr.appendChild(td);	
	var select=document.createElement("select");td.appendChild(select);
	select.name=select.id='language';
	select.classList.add('h56');
	select.type='radio';
	select.value=1;
	var languages=Array('zh-CN');
	for(var i=0;i<languages.length;i++)
	{
		var option=document.createElement("option");select.appendChild(option);
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
	var h55=document.createElement("h55");td.appendChild(h55);
	h55.innerHTML='配色';
	td=null;
	var td=document.createElement("td");tr.appendChild(td);	
	var select=document.createElement("select");td.appendChild(select);
	select.name=select.id='style_id';
	select.classList.add('h56');
	select.type='radio';
	select.value=1;
	for(var i=0;i<style.length;i++)
	{
		var option=document.createElement("option");select.appendChild(option);
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
	select.onchange=function()
	{
		if(jry_wb_login_user.style.style_id==parseInt(select.options[select.selectedIndex].value))
			style_href.innerHTML=style_href.href='';
		else
		{
			style_href.innerHTML="预览"+select.options[select.selectedIndex].text;
			style_href.href=jry_wb_mainpages_chenge_prelook_styles+'?try='+select.options[select.selectedIndex].value;
		}
	}
	tr=null;
	
	var tr=document.createElement("tr");
	table.appendChild(tr);
	var td=document.createElement("td");
	td.width="250";
	var h55=document.createElement("h55");
	td.appendChild(h55);	
	h55.innerHTML='签名';
	tr.appendChild(td);	
	td=null;
	var td=document.createElement("td");
	var input=document.createElement("textarea");
	input.name=input.id='zhushi';
	input.classList.add('h56');
	input.style="margin: 3px; height: 500px; width: 49%;float:left;";
	input.innerHTML=jry_wb_login_user.zhushi;
	td.appendChild(input);	
	tr.appendChild(td);	
	var result=document.createElement("div");
	result.style="height: 500px;width:49%;overflow:hidden;position:relative;";
	td.appendChild(result);
	markdown(result,0,0,(jry_wb_login_user.zhushi),false);
	input.onkeyup=function()
	{
		markdown(result,0,0,(input.value),false);		
	}
	new jry_wb_beautiful_scroll(result);
	__addbutton(form,'return check_chenge()')

}
function check_chenge()
{ 
	var name= document.getElementById("name").value;
	if(name=="")
    {
		jry_wb_beautiful_alert.alert("请填写完整信息","名字为空",'document.getElementById("password1").focus()');
        return false;
	}
    return true;
}
function showtel()
{
	window.location.hash='showtel';
	showdiv.innerHTML='';
	stoplogin=true;
	stopnext=true;
	var form=document.createElement("form");
	form.method='POST';
	form.action="do_chenge.php?action=tel";
	showdiv.appendChild(form);	
	
	var table=document.createElement("table");
	table.border=1;
	table.width="100%";
	form.appendChild(table);
		
	jry_wb_show_tr_with_input(table,'电话','tel',jry_wb_login_user.tel,'text',function(){},250);
	var td=jry_wb_show_tr_with_input(table,'验证码','vcode','','text',function (){document.getElementById('vcodesrc').src='<?php echo jry_wb_print_href("verificationcode",0,"",1);?>?r='+Math.random()},250);
	var img=document.createElement("img");td.appendChild(img);
	img.id='vcodesrc';
	td=null;
	var td=jry_wb_show_tr_with_input(table,'短信验证码','phonecode','','text',function (){},250);

	var input=document.createElement("button");
	input.name=input.id='button';
	input.classList.add('jry_wb_button','jry_wb_button_size_middle','jry_wb_color_ok');
	input.type='button';
	input.innerHTML='获取验证码';
	input.onclick=function()
	{
		if(check_tel())
		{
			i=new Array();
			i.push({name:'vcode',value:document.getElementById("vcode").value});
			i.push({name:'tel',value:document.getElementById("tel").value});
			jry_wb_ajax_load_data('do_chenge.php?action=send_tel',function (data_){jry_wb_beautiful_alert.alert(data_,'',function(){jry_wb_loading_off();});},i,true);
		}
	};
	td.appendChild(input);
	
	
	__addbutton(form,'return check_tel()');
}
function check_tel()
{ 
	var tel= document.getElementById("tel").value;
	var vcode= document.getElementById("vcode").value;
	if(vcode=='')
	{
		jry_wb_beautiful_alert.alert("请填写完整信息","验证码为空",'document.getElementById("vcode").focus()');
		return false;		
	}
	if(tel!=""&&jry_wb_test_phone_number(tel)==false)
	{
		jry_wb_beautiful_alert.alert("请填写正确信息","电话错误",'document.getElementById("tel").focus()');
        return false;
    }
    return true;
}

function showmail()
{
	window.location.hash='showmail';
	showdiv.innerHTML='';
	stoplogin=true;
	stopnext=true;
	var form=document.createElement("form");
	form.method='POST';
	form.action="do_chenge.php?action=mail_send";
	showdiv.appendChild(form);	
	
	var table=document.createElement("table");
	table.border=1;
	table.width="100%";
	form.appendChild(table);	
	
	jry_wb_show_tr_with_input(table,'邮箱','mail',jry_wb_login_user.mail,'text',function(){},250);
	var td=jry_wb_show_tr_with_input(table,'验证码','vcode','','text',function (){document.getElementById('vcodesrc').src='<?php echo jry_wb_print_href("verificationcode",0,"",1);?>?r='+Math.random()},250);
	var img=document.createElement("img");td.appendChild(img);
	img.id='vcodesrc';
	__addbutton(form,'return check_mail()');
}
function check_mail()
{ 
	var mail= document.getElementById("mail").value;
	var vcode= document.getElementById("vcode").value;
	if(vcode=='')
	{
		jry_wb_beautiful_alert.alert("请填写完整信息","验证码为空",'document.getElementById("vcode").focus()');
		return false;		
	}
	if(jry_wb_test_mail(mail)==false)
	{
		jry_wb_beautiful_alert.alert("请填写正确信息","邮箱错误",'document.getElementById("mail").focus()');
        return false;
    }
    return true;
}
function showpas()
{
	window.location.hash='showpas';	
	showdiv.innerHTML='';
	stoplogin=true;
	stopnext=true;
	var form=document.createElement("form");
	form.method='POST';
	form.action="do_chenge.php?action=pas";
	showdiv.appendChild(form);	
	
	var table=document.createElement("table");
	table.border=1;
	table.width="100%";
	form.appendChild(table);	
	
	jry_wb_show_tr_with_input(table,"原始密码","password_yuan",'','password',function(){},250);
	jry_wb_show_tr_with_input(table,"新密码","password1",'','password',function(){},250);
	jry_wb_show_tr_with_input(table,"再输新密码","password2",'','password',function(){},250);
	__addbutton(form,'return check_pas()');
}
function check_pas()
{ 
	var password_yuan= document.getElementById("password_yuan").value;
	var password1= document.getElementById("password1").value;
	var password2= document.getElementById("password2").value;
	if(password_yuan=="")
    {
		jry_wb_beautiful_alert.alert("请填写完整信息","密码为空",'document.getElementById("password_yuan").focus()');
        return false;
    }
    if(password1!=password2)
    {
		jry_wb_beautiful_alert.alert("请填写正确信息","试图修改密码但两次密码不同",'document.getElementById("password2").focus()');
        return false;
    }
	if(password1.length<8&&password1!='')
    {
		jry_wb_beautiful_alert.alert("请填写正确信息","密码太短",function(){
        document.getElementById("password1").focus();});
        return false;
    }
}
function showshow()
{
	window.location.hash='showshow';
	showdiv.innerHTML='';
	stoplogin=true;
	stopnext=true;
	var options=[{id:0,name:'正常'},{id:1,name:'全码'},{id:2,name:'不码'}];
	var form=document.createElement("form");showdiv.appendChild(form);	
	form.method='POST';
	form.action="do_chenge.php?action=show";
	var table=document.createElement("table");form.appendChild(table);	
	table.border=1;
	table.width="100%";
	
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);	
	td.width="250";
	var h55=document.createElement("h56");td.appendChild(h55);	
	h55.innerHTML='电话';
	td=null;
	var td=document.createElement("td");tr.appendChild(td);	
	var select=document.createElement("select");td.appendChild(select);	
	select.name=select.id='tel_show';select.classList.add('h56');
	for(var i=0,n=options.length;i<n;i++)
	{
		var option=document.createElement('option');select.appendChild(option);	
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
	var select=document.createElement("select");td.appendChild(select);	
	select.name=select.id='mail_show';select.classList.add('h56');
	for(var i=0,n=options.length;i<n;i++)
	{
		var option=document.createElement('option');select.appendChild(option);	
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
	var select=document.createElement("select");td.appendChild(select);	
	select.name=select.id='ip_show';select.classList.add('h56');
	for(var i=0,n=options.length;i<n;i++)
	{
		var option=document.createElement('option');select.appendChild(option);	
		option.value=options[i].id;
		if(options[i].id==jry_wb_login_user.ip_show)
			option.setAttribute("selected","selected");
		option.innerHTML=options[i].name;
	}
	__addbutton(form,'');
}
function showspecialfact()
{
	window.location.hash='showspecialfact';
	showdiv.innerHTML='';
	stoplogin=true;
	stopnext=true;
	var options=[{id:0,name:'关闭'},{id:1,name:'打开'}];
	var form=document.createElement("form");showdiv.appendChild(form);	
	form.method='POST'; 
	form.action="do_chenge.php?action=specialfact";
	var table=document.createElement("table");form.appendChild(table);	
	table.border=1;
	
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);	
	td.width="250";
	var h55=document.createElement("h56");td.appendChild(h55);	
	h55.innerHTML='弹幕';
	td=null;
	var td=document.createElement("td");tr.appendChild(td);	
	var select=document.createElement("select");td.appendChild(select);	
	select.name=select.id='word_special_fact';select.classList.add('h56');
	for(var i=0,n=options.length;i<n;i++)
	{
		var option=document.createElement('option');select.appendChild(option);	
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
	var select=document.createElement("select");td.appendChild(select);	
	select.name=select.id='follow_mouth';select.classList.add('h56');
	for(var i=0,n=options.length;i<n;i++)
	{
		var option=document.createElement('option');select.appendChild(option);	
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
	var input=document.createElement('input');head_td.appendChild(input);
	input.classList.add('h56');
	input.style.width='100px';
	input.value=jry_wb_login_user.head_special.mouse_out.speed;
	input.name=input.id='mouse_out_speed';
	var head_td=document.createElement('td');head_tr1.appendChild(head_td);
	head_td.classList.add('h56');
	head_td.innerHTML='方向:';
	var select=document.createElement("select");head_td.appendChild(select);
	select.classList.add('h56');select.id=select.name='mouse_out_direction';
	for(var i=0,n=direction.length;i<n;i++)
	{
		var option=document.createElement('option');select.appendChild(option);	
		option.value=direction[i].value;
		if(direction[i].value==jry_wb_login_user.head_special.mouse_out.direction)
			option.setAttribute("selected","selected");
		option.innerHTML=direction[i].name;
	}
	var head_td=document.createElement('td');head_tr1.appendChild(head_td);
	head_td.classList.add('h56');
	head_td.innerHTML='次数:(-1为无限)';
	var input=document.createElement('input');head_td.appendChild(input);
	input.classList.add('h56');	
	input.style.width='100px';
	input.value=jry_wb_login_user.head_special.mouse_out.times;
	input.name=input.id='mouse_out_times';
	var head_tr2=document.createElement('tr');head_table.appendChild(head_tr2);
	var head_td=document.createElement('td');head_tr2.appendChild(head_td);
	head_td.classList.add('h56');
	head_td.innerHTML='鼠标放置时:';
	var head_td=document.createElement('td');head_tr2.appendChild(head_td);
	head_td.classList.add('h56');
	head_td.innerHTML='速度:';
	var input=document.createElement('input');head_td.appendChild(input);
	input.classList.add('h56');
	input.style.width='100px';
	input.value=jry_wb_login_user.head_special.mouse_on.speed;
	input.name=input.id='mouse_on_speed';
	var head_td=document.createElement('td');head_tr2.appendChild(head_td);
	head_td.classList.add('h56');
	head_td.innerHTML='方向:';
	var select=document.createElement("select");head_td.appendChild(select);
	select.classList.add('h56');select.id=select.name='mouse_on_direction';
	for(var i=0,n=direction.length;i<n;i++)
	{
		var option=document.createElement('option');select.appendChild(option);	
		option.value=direction[i].value;
		if(direction[i].value==jry_wb_login_user.head_special.mouse_on.direction)
			option.setAttribute("selected","selected");
		option.innerHTML=direction[i].name;
	}	
	var head_td=document.createElement('td');head_tr2.appendChild(head_td);	
	head_td.classList.add('h56');
	head_td.innerHTML='次数:(-1为无限)';
	var input=document.createElement('input');head_td.appendChild(input);
	input.classList.add('h56');
	input.style.width='100px';
	input.value=jry_wb_login_user.head_special.mouse_on.times;
	input.name=input.id='mouse_on_times';
	__addbutton(form,'');
}
function showcache(loaded)
{
	window.location.hash='showcache';	
	loaded=loaded==null?false:loaded;
	showdiv.innerHTML='';
	stoplogin=true;
	stopnext=true;
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
function showmusiclist()
{
	window.location.hash='showmusiclist';	
	showdiv.innerHTML='';
	stoplogin=true;
	stopnext=true;
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
		console.log(data);
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
				tree.add(a,data[j].name+'@'+data[j].type);
		else
			tree.add(tree.root,data[0].name+'@'+data[0].type,JSON.stringify(buf));
	}
	tree.openall();
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);
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
			if(data.statue)
				jry_wb_beautiful_alert.alert("操作成功","",function(){window.location.reload();});
			else
				jry_wb_beautiful_alert.alert("操作失败","因为"+data.reasion);
		},[{'name':'data','value':JSON.stringify(ans)}]);
	};
	var div=document.createElement("div");td.appendChild(div);
	div.style.height=1;
	div.style.width=100;
	div.style.float='left';
	var button=document.createElement("button");td.appendChild(button);
	button.innerHTML="保存";
	button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');	
	button.style.float='left';
	button.onclick=function()
	{
		var ans=background_music_list.slice();
		jry_wb_ajax_load_data('do_chenge.php?action=setsonglist',function(data)
		{
			data=JSON.parse(data);
			if(data.statue)
				jry_wb_beautiful_alert.alert("操作成功","",function(){window.location.reload();});
			else
				jry_wb_beautiful_alert.alert("操作失败","因为"+data.reasion);
		},[{'name':'data','value':JSON.stringify(ans)}]);
	};	
}
function tp_in()
{
	window.location.hash='tpin';	
	showdiv.innerHTML='';
	stoplogin=true;
	stopnext=true;
	var table=document.createElement("table");showdiv.appendChild(table);
	table.border=1;table.width='100%';
	if(jry_wb_tp_qq_oauth_config_enable)
	{
		var tr=document.createElement("tr");table.appendChild(tr);
		var td=document.createElement("td");tr.appendChild(td);
		td.classList.add('h55');
		td.innerHTML='QQ(oauth2.0)';
		var td=document.createElement("td");tr.appendChild(td);
		td.classList.add('h55');
		if(jry_wb_login_user.oauth_qq==null||jry_wb_login_user.oauth_qq=='')
		{
			td.innerHTML='没有绑定,点击绑定，powered by Tencent';
			td.onclick=function()
			{
				newwindow=window.open("jry_wb_qq_oauth.php","TencentLogin","width=450,height=320,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");	
				var timer=setInterval(function(){
					if(newwindow.closed)
					{
						clearInterval(timer);
						jry_wb_login_user.oauth_qq=JSON.parse(jry_wb_cache.get('oauth_qq'));
						jry_wb_cache.delete('oauth_qq');
						jry_wb_qq_user_head=jry_wb_login_user.oauth_qq.figureurl_qq_2;
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
					if(data.statue)
					{
						jry_wb_login_user.oauth_qq=null;
						tp_in();
						jry_wb_beautiful_alert.alert("操作成功","");
					}
					else
						jry_wb_beautiful_alert.alert("操作失败","因为"+data.reasion);
				});			
			};
		}
	}
	if(jry_wb_tp_github_oauth_config_enable)
	{
		var tr=document.createElement("tr");table.appendChild(tr);
		var td=document.createElement("td");tr.appendChild(td);
		td.classList.add('h55');
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
						jry_wb_github_user_head=jry_wb_login_user.oauth_github.avatar_url;
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
					if(data.statue)
					{
						jry_wb_login_user.oauth_github=null;
						tp_in();
						jry_wb_beautiful_alert.alert("操作成功","");
					}
					else
						jry_wb_beautiful_alert.alert("操作失败","因为"+data.reasion);
				});			
			};		
		}
	}	
	if(jry_wb_tp_mi_oauth_config_enable)
	{
		var tr=document.createElement("tr");table.appendChild(tr);
		var td=document.createElement("td");tr.appendChild(td);
		td.classList.add('h55');
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
						jry_wb_mi_user_head=jry_wb_login_user.oauth_mi.miliaoIcon_orig;
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
					if(data.statue)
					{
						jry_wb_login_user.oauth_mi=null;
						tp_in();
						jry_wb_beautiful_alert.alert("操作成功","");
					}
					else
						jry_wb_beautiful_alert.alert("操作失败","因为"+data.reasion);
				});			
			};
		}
	}
	if(jry_wb_tp_github_oauth_config_enable)
	{
		var tr=document.createElement("tr");table.appendChild(tr);
		var td=document.createElement("td");tr.appendChild(td);
		td.classList.add('h55');
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
						jry_wb_github_user_head=jry_wb_login_user.oauth_gitee.avatar_url;
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
					if(data.statue)
					{
						jry_wb_login_user.oauth_gitee=null;
						tp_in();
						jry_wb_beautiful_alert.alert("操作成功","");
					}
					else
						jry_wb_beautiful_alert.alert("操作失败","因为"+data.reasion);
				});			
			};		
		}
	}		
}
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
	case '#showmusiclist':
		showmusiclist();
		window.onresize();
		break;
	case '#tpin':
		tp_in();
		window.onresize();
		break;			
	default:
		show();
		window.onresize();
}