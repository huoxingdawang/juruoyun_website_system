<?php
	header("content-type: application/x-javascript");
	include_once("../jry_wb_tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_user_extern_message.php");	
?>
<?php if(false){ ?><script><?php } ?>
var jry_wb_manage_user=new function()
{
}
jry_wb_manage_user.sync=function()
{
	if(jry_wb_login_user.compentence.managecompentence)
		jry_wb_sync_data_with_server('manage_competence',"jry_wb_manage_competence_get_information.php",null,(data)=>
		{
			this.competence=data;
			for(var i=0;i<this.competence.length;i++)
				for(var k=0,nn=this.competence[i].data.length;k<nn;k++)
					if(this.competence[i].data[k].name=="competencename")
					{
						this.competence[i].name=this.competence[i].data[k].value;
						break;
					}			
		},function(a,b){return a.type-b.type});		
	jry_wb_sync_data_with_server('manage_user_list',"jry_wb_manage_user_get_information.php?action=list",null,(data)=>
	{
		this.all=data;
		for(var i=0,n=this.all.length;i<n;i++)
			this.reload[this.all[i]]=true;
		this.showall();
		return data.max('lasttime','date');
	},function(a,b){return a.id-b.id});
}
jry_wb_manage_user.showall=function()
{
	this.area.innerHTML='';
	var all = document.createElement('table');this.area.appendChild(all);
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
	one.onclick=(event)=>
	{
		this.sync();
		for(var i=0,n=this.all.length;i<n;i++)
			this.reload[i]=true;
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
		one.onclick=(event)=>
		{
			var id=parseInt(event.target.innerHTML);
			if(this.lasthighlight!=null)
				this.lasthighlight.classList.remove('jry_wb_left_toolbar_left_list_active');
			this.lasthighlight=event.target;	
			event.target.classList.add('jry_wb_left_toolbar_left_list_active'); 
			jry_wb_get_user(id,this.reload[id],(user)=>
			{
				this.reload[id]=false;
				var show=event.target.parentNode.parentNode.nextElementSibling;
				jry_wb_loading_off();
				show.innerHTML='';
				var one=document.createElement("table");show.appendChild(one);
				one.border=1;
				one.width='100%';
				jry_wb_show_tr_no_input(one,'ID',user.id);

				var button=document.createElement("button");jry_wb_show_tr_no_input(one,'昵称',user.name).appendChild(button);
				button.type="button";button.innerHTML="昵称不合法";button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_warn");button.name=user.id;
				button.onclick=(event)=>
				{
					var id=parseInt(event.target.name); 
					this.reload[id]=true;
					jry_wb_ajax_load_data('jry_wb_manage_user_do.php?action=name_not_ok&id='+id,(data)=>
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
				td.classList.add('h56');
				td.innerHTML='权限组';
				delete td;
				if(jry_wb_login_user.compentence.managecompentence)
				{
					let type_dom=document.createElement("td");tr.appendChild(type_dom);	
					for(var j=0,m=user.type.length;j<m;j++)
					{
						let select=document.createElement("select");type_dom.appendChild(select);	
						select.name=select.id='type';select.classList.add('h56');
						for(var i=0,n=this.competence.length;i<n;i++)
						{
							var option=document.createElement('option');select.appendChild(option);	
							option.value=this.competence[i].type;
							if(this.competence[i].type==user.type[j])
								option.setAttribute("selected","selected");
							option.innerHTML=this.competence[i].name;
						}
						select.oncontextmenu=()=>
						{
							jry_wb_beautiful_alert.check('确定删除标签"'+this.competence.find(function(a){return a.type==select.value}).name+'"吗?',()=>
							{
								select.parentNode.removeChild(select);
							},function(){});
							return false;
						}						
					}
					let add_type_button=document.createElement("button");type_dom.appendChild(add_type_button);
					add_type_button.classList.add('jry_wb_button','jry_wb_button_size_small','jry_wb_color_normal','jry_wb_icon','jry_wb_icon_xinjian');
					add_type_button.onclick=()=>
					{
						let select=document.createElement("select");add_type_button.parentNode.insertBefore(select,add_type_button);
						select.name=select.id='type';select.classList.add('h56');
						for(var i=0,n=this.competence.length;i<n;i++)
						{
							var option=document.createElement('option');select.appendChild(option);	
							option.value=this.competence[i].type;
							if(this.competence[i].type==user.type[j])
								option.setAttribute("selected","selected");
							option.innerHTML=this.competence[i].name;
						}
						select.oncontextmenu=()=>
						{
							jry_wb_beautiful_alert.check('确定删除标签"'+this.competence.find(function(a){return a.type==select.value}).name+'"吗?',()=>
							{
								select.parentNode.removeChild(select);
							},function(){});
							return false;
						}	
						
					};					
				}
				else
				{
					var td=document.createElement("td");tr.appendChild(td);	
					td.width="400";
					td.classList.add('h56');
					td.innerHTML=user.competencename;
					delete td;
				}
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
					button.onclick=(event)=>
					{
						var id=parseInt(event.target.name); 
						this.reload[id]=true;
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
				for(let i = 0,n = user.login_addr.length;i<n;i++)
				{
					let address=document.createElement("div");h55.appendChild(address);
					jry_wb_get_ip_address(user.login_addr[i].ip,function(data)
					{
						if(data.isp=='内网IP')
							address.innerHTML='内网IP';
						else	
							address.innerHTML=data.country+data.region+data.city+data.isp;
						address.innerHTML+='|'+user.login_addr[i].time+'|'+jry_wb_get_device_from_database(user.login_addr[i].device)+'|'+jry_wb_get_browser_from_database(user.login_addr[i].browser);
					});
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
				if(user.oauth.qq.message==null)
					td.innerHTML+='无<br>';
				else
					td.innerHTML+=user.oauth.qq.message.nickname+'<img width="40px" src="'+user.oauth.qq.message.figureurl_qq_2+'"><br>';
<?php } ?>
<?php if(JRY_WB_TP_MI_OAUTH_CLIENT_ID!=''){ ?>				
				td.innerHTML+='MI:';
				if(user.oauth.mi.message==null)
					td.innerHTML+='无<br>';
				else
					td.innerHTML+=user.oauth.mi.message.miliaoNick+'<img width="40px" src="'+user.oauth.mi.message.miliaoIcon_orig+'"><br>';
<?php } ?>
<?php if(JRY_WB_TP_GITHUB_OAUTH_CLIENT_ID!=''){ ?>			
				td.innerHTML+='gayhub:';
				if(user.oauth.github.message==null)
					td.innerHTML+='无<br>';
				else
					td.innerHTML+=user.oauth.github.message.name+','+user.oauth.github.message.login+'<img width="40px" src="'+user.oauth.github.message.avatar_url+'"><br>';						
<?php } ?>
<?php if(JRY_WB_TP_GITEE_OAUTH_CLIENT_ID!=''){ ?>				
				td.innerHTML+='码云:';
				if(user.oauth.gitee.message==null)
					td.innerHTML+='无<br>';
				else
					td.innerHTML+=user.oauth.gitee.message.name+','+user.oauth.gitee.message.login+'<img width="40px" src="'+user.oauth.gitee.message.avatar_url+'"><br>';
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
				button.onclick=(event)=>
				{
					var id=parseInt(event.target.name); 
					this.reload[id]=true;
					var inputs=event.target.parentNode.parentNode.parentNode.getElementsByTagName('input');
					var out=new Array();
					for(var i=0,n=inputs.length;i<n;i++)
						out.push({'name':inputs[i].name,'value':inputs[i].value});
					var inputs=event.target.parentNode.parentNode.parentNode.getElementsByTagName('select');
					var type_buf=[];
					for(var i=0,n=inputs.length;i<n;i++)
						if(inputs[i].name=='type')
							type_buf.push(parseInt(inputs[i].value));
						else
							out.push({'name':inputs[i].name,'value':inputs[i].value});
					out.push({'name':'type','value':JSON.stringify(type_buf.unique())});
					console.log(out);
					jry_wb_ajax_load_data('jry_wb_manage_user_do.php?id='+id,function (data)
					{
						jry_wb_loading_off();
						data=JSON.parse(data);
						if(data.code==false)
						{
							if(data.reason==100000)
								jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
							else if(data.reason==100001)
								jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=jry_wb_message.jry_wb_host+'jry_wb_mainpages/index.php'");
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