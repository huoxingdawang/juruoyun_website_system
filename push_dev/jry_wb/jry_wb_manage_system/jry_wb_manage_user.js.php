<?php
	header("content-type: application/x-javascript");
	include_once("../jry_wb_tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_user_extern_message.php");	
?>
<?php if(false){ ?><script><?php } ?>
jry_wb_include_css('manage/user');
var jry_wb_manage_user=new function()
{
	this.top_toolbar=document.getElementsByClassName('jry_wb_top_toolbar')[0];	
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
			if(this.all!=undefined)
				this.showall();					
		},function(a,b){return a.type-b.type});		
	jry_wb_sync_data_with_server('manage_user_list',"jry_wb_manage_user_get_information.php?action=list",null,(data)=>
	{
		this.all=data;
		for(var i=0,n=this.all.length;i<n;i++)
			this.reload[this.all[i]]=true;
		if(this.competence!=undefined)
			this.showall();
		return data.max('lasttime','date');
	},function(a,b){return a.id-b.id});
}
jry_wb_manage_user.showall=function()
{
	this.area.innerHTML='';
	var all = document.createElement('div');this.area.appendChild(all);
	all.width='100%';all.height='100%';
	all.classList.add("jry_wb_left_toolbar");
	var list = document.createElement('div');all.appendChild(list);
	list.classList.add("jry_wb_left_toolbar_left");	
	list.style.width='150px';
	list.style.float='left';
	var right_body=document.createElement('div');all.appendChild(right_body);
	right_body.classList.add("jry_wb_left_toolbar_right");	
	right_body.style.float='left';
	all.style.height=list.style.height=right_body.style.height=document.body.clientHeight-((this.top_toolbar==null)?0:this.top_toolbar.clientHeight);
	right_body.style.position=list.style.position='relative';
	right_body.style.width=all.clientWidth-list.clientWidth;
	var one=document.createElement('div');list.appendChild(one);
	one.classList.add('jry_wb_left_toolbar_left_list_default');
	one.innerHTML='重载';
	one.onclick=(event)=>
	{
		jry_wb_indexeddb_delete({'name':'manage_user_list'	,'key':'id'});
		jry_wb_indexeddb_delete({'name':'manage_competence'	,'key':'type'});
		jry_wb_indexeddb_delete({'name':'manage_user'		,'key':'id'});
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
	var one=document.createElement('div');list.appendChild(one);
	one.classList.add('jry_wb_left_toolbar_left_list_default');
	one.innerHTML='预加载';
	one.onclick=()=>{for(var i=0;i<this.all.length;i++)this.all[i].button.onclick(false);};	
	one.oncontextmenu=()=>{for(var i=0;i<this.all.length;i++)this.all[i].button.onclick();return false;};	
	for(let i=0,n=this.all.length;i<n;i++)
	{
		let one=document.createElement('div');list.appendChild(one);
		one.style="text-overflow: ellipsis; overflow:hidden;";
		one.style.width='';
		if(!this.all[i].use)
			one.classList.add("jry_wb_left_toolbar_left_list_default","jry_wb_color_error");
		else
			one.classList.add(('jry_wb_left_toolbar_left_list_'+(i%2+1)));
		one.innerHTML=this.all[i].id+':'+this.all[i].name;
		this.all[i].button=one;
		one.onclick=(event)=>
		{
			let id=this.all[i].id;
			right_body.innerHTML='';
			let ii=i;
			jry_wb_get_user(id,this.reload[id],(user)=>
			{
				this.reload[id]=false;
				if(this.lasthighlight!=null)
					this.lasthighlight.classList.remove('jry_wb_left_toolbar_left_list_active');
				this.lasthighlight=this.all[ii].button;	
				this.all[ii].button.classList.add('jry_wb_left_toolbar_left_list_active'); 
				this.list_scroll.scrollto(0,this.all[ii].button.offsetTop-this.all[0].button.offsetTop-((document.body.clientHeight-((this.top_toolbar==null)?0:this.top_toolbar.clientHeight))/2));					
				if(event===false)
					return;
				right_body.innerHTML='';
				if(ii!=0)
					jry_wb_set_shortcut(jry_wb_keycode_left,(e)=>{this.all[ii-1].button.onclick(e);});
				else
					jry_wb_set_shortcut(jry_wb_keycode_left,(e)=>{});
				if(ii!=this.all.length-1)
					jry_wb_set_shortcut(jry_wb_keycode_right,(e)=>{this.all[ii+1].button.onclick(e);});
				else
					jry_wb_set_shortcut(jry_wb_keycode_right,(e)=>{});
				var one=document.createElement("table");right_body.appendChild(one);one.classList.add('jry_wb_manage_user');
				var tr=document.createElement("tr");one.appendChild(tr);
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('id')		;td.innerHTML='ID';
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('id_v')	;td.innerHTML=user.id;
				var tr=document.createElement("tr");one.appendChild(tr);
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('name')	;td.innerHTML='昵称';
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('name_v')	;td.innerHTML=user.name.toString().replace(/</g,'&lt;').replace(/>/g,'&gt;');	
				var button=document.createElement("button");td.appendChild(button);button.innerHTML="昵称不合法";button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_warn");button.name=user.id;
				button.onclick=(event)=>
				{
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
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('head')	;td.innerHTML='头像';
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('head_v')	;td.style.overflow="hidden";var img=document.createElement("img");td.appendChild(img);jry_wb_set_user_head_special(user,img);/*img.classList.add('');*/
				var tr=document.createElement("tr");one.appendChild(tr);
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('grm')	;td.innerHTML='绿币';
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('grm_v')	;td.innerHTML=user.green_money;	
				var tr=document.createElement("tr");one.appendChild(tr);
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('erd')	;td.innerHTML='注册日期';
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('erd_v')	;td.innerHTML=user.enroldate;	
				var tr=document.createElement("tr");one.appendChild(tr);
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('psw')	;td.innerHTML='密码';
				var td=document.createElement("td");tr.appendChild(td);
				var input	=document.createElement("input");	td.appendChild(input);	input.classList.add('psw_v');input.name=input.id='password';input.type='text';input.value=user.password;
				var button	=document.createElement("button");	td.appendChild(button);	button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");button.innerHTML="md5";
				button.onclick=function(){document.getElementById('password').value=hex_md5(document.getElementById('password').value);};

				var tr=document.createElement("tr");one.appendChild(tr);
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('cptn')	;td.innerHTML='权限组';
				if(jry_wb_login_user.compentence.managecompentence)
				{
					let type_dom=document.createElement("td");tr.appendChild(type_dom);	
					for(var j=0,m=user.type.length;j<m;j++)
					{
						let select=document.createElement("select");type_dom.appendChild(select);	
						select.name=select.id='type';select.classList.add('cptn_v');
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
					add_type_button.classList.add('jry_wb_button','jry_wb_button_size_small','jry_wb_color_normal','jry_wb_icon','jry_wb_icon_new');
					add_type_button.onclick=()=>
					{
						let select=document.createElement("select");add_type_button.parentNode.insertBefore(select,add_type_button);
						select.name=select.id='type';select.classList.add('cptn_v');
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
					var td=document.createElement("td");tr.appendChild(td);td.width='*';td.classList.add('cptn_v');td.innerHTML=user.competencename;
				}
				var tr=document.createElement("tr");one.appendChild(tr);
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('use')		;td.innerHTML='使用权';
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('use_v')		;
				var select=document.createElement("select");td.appendChild(select);select.name=select.id='use';select.classList.add('use_v');
				for(var i=0,a=[{'value':0,'name':"禁止"},{'value':1,'name':"允许"}],n=a.length;i<n;i++)
				{
					var option=document.createElement('option');select.appendChild(option);	
					option.value=a[i].value;
					if(a[i].value==user.use)
						option.setAttribute("selected","selected");
					option.innerHTML=a[i].name;
				}			
				var tr=document.createElement("tr");one.appendChild(tr);
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('sex')	;td.innerHTML='性别';
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('sex_v')	;td.innerHTML=(user.sex==0?'女':(user.sex==1?'男':(user.sex==2?'女装大佬':'咱也不知道，咱也不敢问')));
				var tr=document.createElement("tr");one.appendChild(tr);
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('tel')	;td.innerHTML='电话';
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('tel_v')	;td.innerHTML=user.tel;
				var tr=document.createElement("tr");one.appendChild(tr);
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('mail');	td.innerHTML='邮箱';
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('mail_v');
				if(user.mail=='') 
				{
					var button=document.createElement("button");td.appendChild(button);button.innerHTML="提醒绑邮箱";button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_warn");button.name=user.id;
					button.onclick=(event)=>
					{
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
					td.innerHTML=user.mail;
				if(user.zhushi!=''&&user.zhushi!=null)
				{
					var tr=document.createElement("tr");one.appendChild(tr);
					var td=document.createElement("td");tr.appendChild(td);td.classList.add('zhushi');td.innerHTML='签名';
					var td=document.createElement("td");tr.appendChild(td);td.classList.add('zhushi_v');		
					new jry_wb_markdown(td,user.id,0,(user.zhushi),false);
				}
				var tr=document.createElement("tr");one.appendChild(tr);
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('login');td.innerHTML='登录信息';
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('login_v');
				for(let i = 0,n = user.login_addr.length;i<n;i++)
				{
					let address=document.createElement("div");td.appendChild(address);
					jry_wb_get_ip_address(user.login_addr[i].ip,function(data)
					{
						var span=document.createElement("span");address.appendChild(span);span.classList.add('country')	;span.innerHTML=(data.isp=='内网IP'?'':data.country);
						var span=document.createElement("span");address.appendChild(span);span.classList.add('region')	;span.innerHTML=(data.isp=='内网IP'?'':data.region);
						var span=document.createElement("span");address.appendChild(span);span.classList.add('isp')		;span.innerHTML=data.isp;
						var span=document.createElement("span");address.appendChild(span);span.classList.add('time')	;span.innerHTML=user.login_addr[i].time;
						var span=document.createElement("span");address.appendChild(span);span.classList.add('device')	;span.innerHTML=jry_wb_get_device_from_database(user.login_addr[i].device);
						var span=document.createElement("span");address.appendChild(span);span.classList.add('browser')	;span.innerHTML=jry_wb_get_browser_from_database(user.login_addr[i].browser);
					});
				}
				
				
				
<?php if(JRY_WB_OAUTH_SWITCH){ ?>						
				var tr=document.createElement("tr");one.appendChild(tr);
				var td=document.createElement("td");tr.appendChild(td);td.width="250px";td.classList.add('tpin');td.innerHTML='第三方接入';
				var td=document.createElement("td");tr.appendChild(td);td.width="*"		;td.classList.add('tpin_v');
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
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('extern');td.innerHTML='扩展信息';td.setAttribute('valign','top');
				var td=document.createElement("td");tr.appendChild(td);	
				var table=document.createElement("table");td.appendChild(table);table.classList.add('extern_v');
<?php foreach($JRY_WB_CONFIG_USER_EXTERN_MESSAGE as $one)if($one['type']!='cutter'){?>
				var tr=document.createElement("tr");table.appendChild(tr);	
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('key','<?php  echo $one['key']; ?>');td.innerHTML='<?php echo $one['name']; ?>';	
				var td=document.createElement("td");tr.appendChild(td);td.classList.add('value','<?php  echo $one['key']; ?>_v');
<?php if($one['type']=='word'||$one['type']=='tel'||$one['type']=='mail'||$one['type']=='china_id'){ ?>
				var <?php  echo $one['key']; ?>=document.createElement("input");td.appendChild(<?php  echo $one['key']; ?>);
				<?php  echo $one['key']; ?>.disabled=true;
				<?php  echo $one['key']; ?>.type='text';
				<?php  echo $one['key']; ?>.id=<?php  echo $one['key']; ?>.name='<?php  echo $one['key']; ?>';
				<?php  echo $one['key']; ?>.value=user.extern.<?php  echo $one['key']; ?>;
<?php }else if($one['type']=='select'){ ?>
				var <?php  echo $one['key']; ?>=document.createElement("select");td.appendChild(<?php  echo $one['key']; ?>);
				<?php  echo $one['key']; ?>.disabled=true;
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
				var span=document.createElement('span');td.appendChild(span);span.classList.add('yes');span.innerHTML='是';
				var input=document.createElement('input');td.appendChild(input);
				if(user.extern.<?php  echo $one['key']; ?>==(input.value=0))
					input.setAttribute('checked','');
				input.type='radio';
				input.name='<?php  echo $one['key']; ?>';
				input.disabled=true;
				<?php  echo $one['key']; ?>s[1]=input;
				var span=document.createElement('span');td.appendChild(span);span.classList.add('no');span.innerHTML='否';	
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
					this.reload[id]=true;
					var inputs=right_body.getElementsByTagName('input');
					var out=new Array();
					for(var i=0,n=inputs.length;i<n;i++)
						out.push({'name':inputs[i].name,'value':inputs[i].value});
					var inputs=right_body.getElementsByTagName('select');
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
				new jry_wb_beautiful_scroll(right_body,undefined,true);	
				window.onresize();
			},true);
		};
	}
	this.list_scroll=new jry_wb_beautiful_scroll(list);	
	this.all[0].button.onclick();
	window.onresize();
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