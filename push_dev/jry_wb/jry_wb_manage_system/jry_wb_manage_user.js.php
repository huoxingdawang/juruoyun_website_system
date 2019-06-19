<?php
	header("content-type: application/x-javascript");
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_user_extern_message.php");	
?>
<?php if(false){ ?><script><?php } ?>
var jry_wb_manage_user=new function()
{
}
jry_wb_manage_user.sync=function()
{
	jry_wb_ajax_load_data('jry_wb_manage_competence_get_information.php',(data)=>{
		var buf=JSON.parse(data);
		if(buf!=null)
			if(buf.code==false)
			{
				if(data.reason==100000)
					jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
				else if(data.reason==100001)
					jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
				else
					jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
				return ;
			}
		data=buf;
		data.sort(function (a,b){return a.type-b.type;});
		jry_wb_cache.set('competence',data);
		this.competence=data;
		jry_wb_loading_off();	
	});	
	jry_wb_sync_data_with_server('user_list',"jry_wb_manage_user_get_information.php?action=list",null,function(a){return a.id==this.buf.id},function(data){jry_wb_manage_user.all=data;for(var i=0,n=jry_wb_manage_user.all.length;i<n;i++)jry_wb_manage_user.reload[jry_wb_manage_user.all[i]]=true;jry_wb_manage_user.showall();},function(a,b){return a.id-b.id});
}
jry_wb_manage_user.showall=function()
{
	this.area.innerHTML='';
	var all = document.createElement('one');this.area.appendChild(all);
	all.width='100%';all.height='100%';
	all.classList.add("jry_wb_left_toolbar");
	var tr=document.createElement('tr');all.appendChild(tr);
	tr.width='100%';tr.height='100%';
	tr.classList.add("jry_wb_left_toolbar_left");
	var td = document.createElement('td');tr.appendChild(td);
	td.setAttribute('valign','top');
	td.style.width='150px';
	var list = document.createElement('div');td.appendChild(list);
	list.style.width='150px';
	var one=document.createElement('div');list.appendChild(one);
	one.classList.add('jry_wb_left_toolbar_left_list_default');
	one.innerHTML='重载';
	one.onclick=function(event)
	{
		jry_wb_manage_user.sync();
		for(var i=0,n=jry_wb_manage_user.reload.length;i<n;i++)
			jry_wb_manage_user.reload[i]=true;
	};
	var one=document.createElement('div');list.appendChild(one);
	one.classList.add('jry_wb_left_toolbar_left_list_default');
	one.innerHTML='下载信息';
	one.onclick=function(event)
	{
		window.open(jry_wb_message.jry_wb_host+'jry_wb_manage_system/jry_wb_manage_user_do.php?action=print')
	};
	for(var i=0,n=this.all.length;i<n;i++)
	{
		var one=document.createElement('div');list.appendChild(one);
		one.style="text-overflow: ellipsis; overflow:hidden;";
		one.style.width='';
		if(!this.all[i].use)
			one.classList.add("jry_wb_left_toolbar_left_list_default","jry_wb_color_error");
		else
			one.classList.add(('jry_wb_left_toolbar_left_list_'+(i%2+1)));
		one.innerHTML=this.all[i].id+':'+this.all[i].name;
		jry_wb_get_user(this.all[i].id,jry_wb_manage_user.reload[this.all[i].id],()=>{jry_wb_loading_off();},undefined,true);
		jry_wb_manage_user.reload[this.all[i].id]=false;
		one.onclick=(event)=>
		{
			var id=parseInt(event.target.innerHTML);
			if(this.lasthighlight!=null&&this.lasthighlight.target!=null&&this.lasthighlight.classList!=null)
			{
				this.lasthighlight.target.classList.remove('jry_wb_left_toolbar_left_list_active');
				for(var i=0,n=this.lasthighlight.classList.length;i<n;i++)
					this.lasthighlight.target.classList.add(this.lasthighlight.classList[i]);
			}
			else
			{
				this.lasthighlight={};
			}
			this.lasthighlight.target=event.target;
			this.lasthighlight.classList=[];
			for(var i=0,n=event.target.classList.length;i<n;i++)
			{
				this.lasthighlight.classList.push(event.target.classList[i]);
				event.target.classList.remove(event.target.classList[i]);
			}				
			event.target.classList.add('jry_wb_left_toolbar_left_list_active');
			jry_wb_get_user(id,jry_wb_manage_user.reload[id],
				function()
				{
					jry_wb_manage_user.reload[id]=false;
					var data=jry_wb_cache.get('jry_wb_manage_user_cache');
					var user=data.find(function (a){return a.id==id});
					var show=event.target.parentNode.parentNode.nextElementSibling;
					jry_wb_loading_off();
					show.innerHTML='';
					var one=document.createElement("table");show.appendChild(one);
					one.border=1;
					jry_wb_show_tr_no_input(one,'ID',user.id);

					var button=document.createElement("button");jry_wb_show_tr_no_input(one,'昵称',user.name).appendChild(button);
					button.type="button";button.innerHTML="昵称不合法";button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_warn");button.name=user.id;
					button.onclick=function(event)
					{
						var id=parseInt(event.target.name); 
						jry_wb_manage_user.reload[id]=true;
						jry_wb_ajax_load_data('jry_wb_manage_user_do.php?action=name_not_ok&id='+id,function (data)
						{
							jry_wb_loading_off();
							data=JSON.parse(data);
							if(data.code==false)
							{
								if(data.reason==100000)
									jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
								else if(data.reason==100001)
									jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
								else if(data.reason==300001)
									jry_wb_beautiful_right_alert.alert('没有邮箱',10000,'auto','error');
								else
									jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
								return ;
							}
							jry_wb_beautiful_right_alert.alert('OK',2000,'auto','ok');
						});
					}			
					var tr=document.createElement("tr");one.appendChild(tr);
					var td=document.createElement("td");tr.appendChild(td);	
					td.width="400";
					var h55=document.createElement("h56");td.appendChild(h55);	
					h55.innerHTML='头像';
					td=null;
					var td=document.createElement("td");tr.appendChild(td);	
					td.style="overflow: hidden;"; 
					var img=document.createElement("img");td.appendChild(img);
					jry_wb_set_user_head_special(user,img);
					img.height=80;
					img.width=80;
					td=null;
					tr=null;	
					jry_wb_show_tr_no_input(one,'绿币',user.green_money);	
					jry_wb_show_tr_no_input(one,'注册日期',user.enroldate);	
					var button=document.createElement("button");jry_wb_show_tr_with_input(one,'密码','password',user.password).appendChild(button);
					button.type="button";
					button.innerHTML="md5";
					button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
					button.onclick=function()
					{
						document.getElementById('password').value=hex_md5(document.getElementById('password').value);
					};
					var tr=document.createElement("tr");one.appendChild(tr);
					var td=document.createElement("td");tr.appendChild(td);	
					td.width="400";
					var h55=document.createElement("h56");td.appendChild(h55);	
					h55.innerHTML='权限组';
					td=null;
					var td=document.createElement("td");tr.appendChild(td);	
					var select=document.createElement("select");td.appendChild(select);	
					select.name=select.id='type';select.classList.add('h56');
					for(var i=0,n=jry_wb_manage_user.competence.length;i<n;i++)
					{
						var option=document.createElement('option');select.appendChild(option);	
						option.value=jry_wb_manage_user.competence[i].type;
						if(jry_wb_manage_user.competence[i].type==user.type)
							option.setAttribute("selected","selected");
						for(var j=0,nn=jry_wb_manage_user.competence[i].data.length;j<nn;j++)
							if(jry_wb_manage_user.competence[i].data[j].name=="competencename")
							{
								option.innerHTML=jry_wb_manage_user.competence[i].data[j].value;
								break;
							}
					}
					td.innerHTML+='<h56>('+user.type+')</h56>';
					var tr=document.createElement("tr");one.appendChild(tr);
					var td=document.createElement("td");tr.appendChild(td);	
					td.width="400";			
					var h55=document.createElement("h56");td.appendChild(h55);	
					h55.innerHTML='使用权';
					td=null;
					var td=document.createElement("td");tr.appendChild(td);	
					var select=document.createElement("select");td.appendChild(select);	
					select.name=select.id='use';select.classList.add('h56');
					var a=[{'value':0,'name':"禁止"},{'value':1,'name':"允许"}]
					for(var i=0,n=a.length;i<n;i++)
					{
						var option=document.createElement('option');select.appendChild(option);	
						option.value=a[i].value;
						if(a[i].value==user.use)
							option.setAttribute("selected","selected");
						option.innerHTML=a[i].name;
					}			
					if(user.sex==1)
						jry_wb_show_tr_no_input(one,'性别','男');
					else if(user.sex==0)
						jry_wb_show_tr_no_input(one,'性别','女');
					else if(user.sex==2)
						jry_wb_show_tr_no_input(one,'性别','女装大佬'); 
					else
						jry_wb_show_tr_no_input(one,'性别','???');
					jry_wb_show_tr_no_input(one,'电话',user.tel);
					if(user.mail=='') 
					{
						var button=document.createElement("button");jry_wb_show_tr_no_input(one,'邮箱',user.mail).appendChild(button);
						button.type="button";button.innerHTML="提醒绑邮箱";button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_warn");button.name=user.id;
						button.onclick=function(event)
						{
							var id=parseInt(event.target.name); 
							jry_wb_manage_user.reload[id]=true;
							jry_wb_ajax_load_data('jry_wb_manage_user_do.php?action=bangyouxiang&id='+id,function(data)
							{
								jry_wb_loading_off();
								data=JSON.parse(data);
								if(data.code==false)
								{
									if(data.reason==100000)
										jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
									else if(data.reason==100001)
										jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
									else
										jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
									return ;
								}
								jry_wb_beautiful_right_alert.alert('OK',2000,'auto','ok');
							});
						}
					}
					else
						jry_wb_show_tr_no_input(one,'邮箱',user.mail);
					if(user.zhushi!=''&&user.zhushi!=null)
						jry_wb_markdown(jry_wb_show_tr_no_input(one,'签名',''),user.id,'',user.zhushi);
					
					var tr=document.createElement("tr");one.appendChild(tr);
					var td=document.createElement("td");tr.appendChild(td);	
					td.width="400";
					var h55=document.createElement("h56");td.appendChild(h55);	
					h55.innerHTML='登录IP';
					td=null;
					var td=document.createElement("td");tr.appendChild(td);	
					var h55=document.createElement("h55");td.appendChild(h55);	
					for(var i=0;i<user.login_addr.length;i++)
					{
						var li=document.createElement("li");h55.appendChild(li);	
						li.innerHTML=user.login_addr[i];
					}
<?php if(JRY_WB_OAUTH_SWITCH){ ?>						
					var tr=document.createElement("tr");one.appendChild(tr);
					var td=document.createElement("td");tr.appendChild(td);	
					td.width="400";
					td.classList.add('h56');
					td.innerHTML='第三方接入';
					var td=document.createElement("td");tr.appendChild(td);	
					td.classList.add('h56');
<?php if($JRY_WB_TP_QQ_OAUTH_CONFIG!=NULL){ ?>
					td.innerHTML+='QQ:';
					if(user.oauth_qq==null)
						td.innerHTML+='无<br>';
					else
						td.innerHTML+=user.oauth_qq.nickname+'<img width="40px" src="'+user.oauth_qq.figureurl_qq_2+'"><br>';
<?php } ?>
<?php if(JRY_WB_TP_MI_OAUTH_CLIENT_ID!=''){ ?>				
					td.innerHTML+='MI:';
					if(user.oauth_mi==null)
						td.innerHTML+='无<br>';
					else
						td.innerHTML+=user.oauth_mi.miliaoNick+'<img width="40px" src="'+user.oauth_mi.miliaoIcon_orig+'"><br>';
<?php } ?>
<?php if(JRY_WB_TP_GITHUB_OAUTH_CLIENT_ID!=''){ ?>			
					td.innerHTML+='gayhub:';
					if(user.oauth_github==null)
						td.innerHTML+='无<br>';
					else
						td.innerHTML+=user.oauth_github.name+','+user.oauth_github.login+'<img width="40px" src="'+user.oauth_github.avatar_url+'"><br>';						
<?php } ?>
<?php if(JRY_WB_TP_GITEE_OAUTH_CLIENT_ID!=''){ ?>				
					td.innerHTML+='码云:';
					if(user.oauth_gitee==null)
						td.innerHTML+='无<br>';
					else
						td.innerHTML+=user.oauth_gitee.name+','+user.oauth_gitee.login+'<img width="40px" src="'+user.oauth_gitee.avatar_url+'"><br>';
<?php } ?>					
<?php } ?>
<?php if($JRY_WB_CONFIG_USER_EXTERN_MESSAGE!=NULL){ ?>
					if(user.extern==null)user.extern={};
					var tr=document.createElement("tr");one.appendChild(tr);
					var td=document.createElement("td");tr.appendChild(td);	
					td.width="400";
					td.classList.add('h56');
					td.innerHTML='扩展信息';
					var td=document.createElement("td");tr.appendChild(td);	
					var table=document.createElement("table");td.appendChild(table);
					table.border=1;
					table.width="100%";					
<?php foreach($JRY_WB_CONFIG_USER_EXTERN_MESSAGE as $one)if($one['type']!='cutter'){?>
					var tr=document.createElement("tr");table.appendChild(tr);	
					var td=document.createElement("td");tr.appendChild(td);
					td.style.width='250px';
					td.innerHTML='<?php echo $one['name']; ?>';	
					td.classList.add('h56');
					var td=document.createElement("td");tr.appendChild(td);
<?php if($one['type']=='word'||$one['type']=='tel'||$one['type']=='mail'||$one['type']=='china_id'){ ?>
					var <?php  echo $one['key']; ?>=document.createElement("input");td.appendChild(<?php  echo $one['key']; ?>);
					<?php  echo $one['key']; ?>.disabled=true;
					<?php  echo $one['key']; ?>.type='text';
					<?php  echo $one['key']; ?>.id=<?php  echo $one['key']; ?>.name='<?php  echo $one['key']; ?>';
					<?php  echo $one['key']; ?>.classList.add('h56');
					<?php  echo $one['key']; ?>.value=user.extern.<?php  echo $one['key']; ?>;
<?php }else if($one['type']=='select'){ ?>
					var <?php  echo $one['key']; ?>=document.createElement("select");td.appendChild(<?php  echo $one['key']; ?>);
					<?php  echo $one['key']; ?>.disabled=true;
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
					<?php  echo $one['key']; ?>.value=user.extern.<?php  echo $one['key']; ?>;
<?php }else if($one['type']=='check'){ ?>
					var <?php  echo $one['key']; ?>s=[];
					var input=document.createElement('input');td.appendChild(input);
					if(user.extern.<?php  echo $one['key']; ?>==(input.value=1))
						input.setAttribute('checked','');
					input.type='radio';
					input.name='<?php  echo $one['key']; ?>';
					input.disabled=true;
					<?php  echo $one['key']; ?>s[0]=input;
					var h56=document.createElement('h56');td.appendChild(h56);
					h56.innerHTML='是';
					var input=document.createElement('input');td.appendChild(input);
					if(user.extern.<?php  echo $one['key']; ?>==(input.value=0))
						input.setAttribute('checked','');
					input.type='radio';
					input.name='<?php  echo $one['key']; ?>';
					input.disabled=true;
					<?php  echo $one['key']; ?>s[1]=input;
					var h56=document.createElement('h56');td.appendChild(h56);
					h56.innerHTML='否';	
					h56.style.marginLeft='20px';		 
<?php }?>
<?php }?>
<?php } ?>
					var tr=document.createElement("tr");one.appendChild(tr);
					var td=document.createElement("td");tr.appendChild(td);	
					td.setAttribute("colspan","2");td.setAttribute("align","center");
					var button=document.createElement("button");td.appendChild(button);
					button.type="button";button.innerHTML="修改";button.className="jry_wb_button jry_wb_button_size_big jry_wb_color_ok";button.name=user.id;
					button.onclick=function(event)
					{
						var id=parseInt(event.target.name); 
						jry_wb_manage_user.reload[id]=true;
						var inputs=event.target.parentNode.parentNode.parentNode.getElementsByTagName('input')
						var out=new Array();
						for(var i=0,n=inputs.length;i<n;i++)
							out.push({'name':inputs[i].name,'value':inputs[i].value});
						var inputs=event.target.parentNode.parentNode.parentNode.getElementsByTagName('select')
						for(var i=0,n=inputs.length;i<n;i++)
							out.push({'name':inputs[i].name,'value':inputs[i].value});
						jry_wb_ajax_load_data('jry_wb_manage_user_do.php?id='+id,function (data)
						{
							jry_wb_loading_off();
							data=JSON.parse(data);
							if(data.code==false)
							{
								if(data.reason==100000)
									jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
								else if(data.reason==100001)
									jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
								else if(data.reason==300001)
									jry_wb_beautiful_right_alert.alert('没有邮箱',10000,'auto','error');
								else
									jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
								return ;
							}
							jry_wb_beautiful_right_alert.alert('OK',2000,'auto','ok');
						},out);
					}
					one=null;	
					window.onresize();
					window.scrollTo(window.scrollX,0);
				},undefined,true);
		}
		window.onresize();
	}
	var one = document.createElement('td');tr.appendChild(one);
	one.width='*';one.setAttribute('valign','top');	
}
function jry_wb_manage_user_init(area,mode)
{
	jry_wb_manage_user.area=area;
	jry_wb_manage_user.reload=new Array();
	jry_wb_manage_user.sync();
}
function jry_wb_manage_user_run(area)
{
	jry_wb_manage_user.area=area;
	jry_wb_manage_user.sync();
}
<?php if(false){ ?></script><?php } ?>