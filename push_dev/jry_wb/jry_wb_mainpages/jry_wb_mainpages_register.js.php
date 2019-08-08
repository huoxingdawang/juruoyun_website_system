<?php
	header("content-type: application/x-javascript");
	include_once("../jry_wb_tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_user_extern_message.php");	
?>
<?php if(false){ ?><script><?php } ?>
function jry_wb_mainpages_register(area)
{
	jry_wb_include_css('mainpages/register');
	area.innerHTML=area.style=area.className='';
	var <?php foreach($JRY_WB_CONFIG_USER_EXTERN_MESSAGE as $one)if($one['type']!='cutter'){ ?>time_<?php echo $one['key']; ?>=0,<?php } ?>time1=0,time2=0,time3=0,time4=0,time5=0,time6=0;	
	var body		=document.createElement("table")	;area	.appendChild(body)		;body		.classList.add('jry_wb_mainpages_register');body.setAttribute('align','center');
<?php foreach($JRY_WB_CONFIG_USER_EXTERN_MESSAGE as $one)if($one['type']=='cutter'&&$one['before']===true){?>
	var tr			=document.createElement("tr")		;body	.appendChild(tr)		;
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('cutter')		;td.setAttribute('colspan',2);td.innerHTML='<?php echo $one['name']; ?>';
<?php }?>	
	var tr			=document.createElement("tr")		;body	.appendChild(tr)		;
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('name')			;td.innerHTML='昵称';
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('name_v')		;
	var name		=document.createElement("input")	;td		.appendChild(name)		;name		.type='text'					;
	name.onfocus=name.onkeyup=function(e)
	{
		if(e==undefined)e=window.event;
		if(e.target==this)check_all(e);
		if(e.target==tijiao_button&&this.value=='')return jry_wb_beautiful_alert.alert("请填写完整信息","昵称为空",function(){name.focus();name.classList.add('error');}),false;
		if(name.value=='')
		{
			if(((new Date())-time5)>5000)
			{
				time5=new Date();
				jry_wb_beautiful_right_alert.alert("昵称不为空",2000,"auto","error");
			}
			name.classList.add('error');
			return false;
		}
		else
			name.classList.remove('error'),time5=0;
		return true;
	};
	var tr			=document.createElement("tr")		;body	.appendChild(tr)		;
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('password1')		;td.innerHTML='密码';
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('password1_v')	;
	var password1	=document.createElement("input")	;td		.appendChild(password1)	;password1	.type='password'				;
	password1.onfocus=password1.onkeyup=function(e)
	{
		if(e==undefined)e=window.event;
		if(e.target==this)check_all(e);
		if(e.target==tijiao_button&&this.value=='')return jry_wb_beautiful_alert.alert("请填写完整信息","密码为空",function(){password1.focus();password1.classList.add('error');}),false;
		if(password1.value!=''&&password1.value.length<8)
		{
			if(((new Date())-time1)>5000)
			{
				time1=new Date();
				jry_wb_beautiful_right_alert.alert("密码长度大于8位",2000,"auto","error");
			}
			password1.classList.add('error');
			return false;
		}
		else
			password1.classList.remove('error'),time1=0;
		return true;
	};
	var tr			=document.createElement("tr")		;body	.appendChild(tr)		;
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('password2')		;td.innerHTML='再输密码';
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('password2_v')	;
	var password2	=document.createElement("input")	;td		.appendChild(password2)	;password2	.type='password'				;
	password2.onfocus=password2.onkeyup=function(e)
	{
		if(e==undefined)e=window.event;
		if(e.target==this)check_all(e);
		if(e.target==tijiao_button&&this.value=='')return jry_wb_beautiful_alert.alert("请填写完整信息","密码为空",function(){password2.focus();password2.classList.remove('error')}),false;
		if(password2.value!=''&&(password2.value!=password1.value||password2.value.length<8))
		{
			if(((new Date())-time2)>5000)
			{
				time2=new Date();
				if(password2.value.length<8)
					jry_wb_beautiful_right_alert.alert("密码长度大于8位",2000,"auto","error");
				else
					jry_wb_beautiful_right_alert.alert("两次不一样耶",2000,"auto","error");
			}
			password2.classList.add('error')
			return false;
		}
		else
			password2.classList.remove('error'),time2=0;
		return true;
	};
	var tr			=document.createElement("tr")		;body	.appendChild(tr)		;
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('sex')			;td.innerHTML='性别';
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('sex_v')			;
	var sexs		=[];
	var sex			=0;
	var input		=document.createElement("input")	;td		.appendChild(input)		;input		.type='radio'					;sexs.push(input)	;input.name='sex';input.value=1;input.onclick=function(){sex=1;save();};
	var span		=document.createElement("span")		;td		.appendChild(span)		;span		.classList.add('man')			;span.innerHTML="男";
	var input		=document.createElement("input")	;td		.appendChild(input)		;input		.type='radio'					;sexs.push(input)	;input.name='sex';input.value=0;input.onclick=function(){sex=0;save();};
	var span		=document.createElement("span")		;td		.appendChild(span)		;span		.classList.add('woman')			;span.innerHTML="女";
	var input		=document.createElement("input")	;td		.appendChild(input)		;input		.type='radio'					;sexs.push(input)	;input.name='sex';input.value=2;input.onclick=function(){sex=2;save();jry_wb_beautiful_right_alert.alert("That's g♂♂d",2000,"auto","ok");};input.setAttribute('checked','');
	var span		=document.createElement("span")		;td		.appendChild(span)		;span		.classList.add('nzdl')			;span.innerHTML="女装大佬";
<?php if(JRY_WB_CHECK_TEL_SWITCH){ ?>
	var tr			=document.createElement("tr")		;body	.appendChild(tr)		;
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('tel')			;td.innerHTML='电话';
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('tel_v')			;
	var tel			=document.createElement("input")	;td		.appendChild(tel)		;tel		.type='text'					;
	tel.onfocus=tel.onkeyup=function(e)
	{
		if(e==undefined)e=window.event;
		if(e.target==this)check_all(e);
		if(e.target==tijiao_button&&this.value=='')return jry_wb_beautiful_alert.alert("请填写完整信息","电话为空",function(){tel.focus();tel.classList.add('error');}),false;
		if(tel.value!=""&&(jry_wb_test_phone_number(tel.value)==false))
		{
			if(((new Date())-time3)>5000)
			{
				time3=new Date();
				jry_wb_beautiful_right_alert.alert("电话错误",2000,"auto","error");
			}	
			tel.classList.add('error');
			return false;
		}
		else
		{
			tel.classList.remove('error');
			<?php if(JRY_WB_CHECK_TEL_SWITCH&&JRY_WB_SHORT_MESSAGE_SWITCH!=''){ ?>tcodetr.style.display='';<?php } ?>
		}
		return true;
	};
<?php } ?>
<?php if(JRY_WB_CHECK_MAIL_SWITCH){ ?>
	var tr			=document.createElement("tr")		;body	.appendChild(tr)		;
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('mail')			;td.innerHTML='邮箱';
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('mail_v')		;
	var mail		=document.createElement("input")	;td		.appendChild(mail)		;mail		.type='text'					;
	mail.onfocus=mail.onkeyup=function(e)
	{
		if(e==undefined)e=window.event;
		if(e.target==this)check_all(e);
		if(e.target==tijiao_button&&this.value=='')return jry_wb_beautiful_alert.alert("请填写完整信息","邮箱为空",function(){mail.focus();mail.classList.remove('error');}),false;
		if(mail.value!=""&&(jry_wb_test_mail(mail.value)==false))
		{
			if(((new Date())-time6)>5000)
			{
				time6=new Date();
				jry_wb_beautiful_right_alert.alert("邮箱错误",2000,"auto","error");
			}	
			mail.classList.add('error');
		}
		else
			mail.classList.remove('error');
		return true;
	};	
<?php } ?>
<?php foreach($JRY_WB_CONFIG_USER_EXTERN_MESSAGE as $one)if($one['type']=='cutter'){if($one['before']!==true){ ?>
	var tr			=document.createElement("tr")		;body	.appendChild(tr)		;
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('cutter')		;td.setAttribute('colspan',2);td.innerHTML='<?php echo $one['name']; ?>';
<?php }}else{ ?>
	var tr			=document.createElement("tr")		;body	.appendChild(tr)		;
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('extern','<?php  echo $one['key']; ?>')		;td.innerHTML='<?php echo $one['name']; ?>';			
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('extern_v','<?php  echo $one['key']; ?>_v')	;	
<?php if($one['type']=='word'||$one['type']=='tel'||$one['type']=='mail'||$one['type']=='china_id'){ ?>
	var <?php  echo $one['key']; ?>=document.createElement("input")	;td.appendChild(<?php  echo $one['key']; ?>);<?php  echo $one['key']; ?>.type='text';
<?php }else if($one['type']=='select'){ ?>
	var <?php  echo $one['key']; ?>=document.createElement("select");td.appendChild(<?php  echo $one['key']; ?>);
	var option=document.createElement("option");<?php  echo $one['key']; ?>.appendChild(option);option.style.display='none';
<?php foreach($one['select'] as $select)if(is_array($select)){?>
	var option=document.createElement("option");<?php  echo $one['key']; ?>.appendChild(option);option.value="<?php echo $select['value']; ?>";option.innerHTML="<?php echo $select['name']; ?>";
<?php }else{ ?>
	var option=document.createElement("option");<?php  echo $one['key']; ?>.appendChild(option);option.value=option.innerHTML="<?php echo $select; ?>";
<?php }}else if($one['type']=='check'){ ?>
	var <?php echo $one['key']; ?>s=[]
	var input		=document.createElement("input")	;td		.appendChild(input)		;input		.type='radio'					;<?php echo $one['key']; ?>s.push(input)	;input.name='<?php echo $one['key']; ?>';input.value=1;input.onclick=function(){save();};
	var span		=document.createElement("span")		;td		.appendChild(span)		;span		.classList.add('man')			;span.innerHTML="是";
	var input		=document.createElement("input")	;td		.appendChild(input)		;input		.type='radio'					;<?php echo $one['key']; ?>s.push(input)	;input.name='<?php echo $one['key']; ?>';input.value=0;input.onclick=function(){save();};
	var span		=document.createElement("span")		;td		.appendChild(span)		;span		.classList.add('woman')			;span.innerHTML="否";				
<?php }}?>
<?php foreach($JRY_WB_CONFIG_USER_EXTERN_MESSAGE as $one)if($one['type']!='cutter'&&$one['type']!='check'){ ?>
	<?php echo $one['key']; ?>.onfocus=<?php echo $one['key']; ?>.onkeyup=<?php echo $one['key']; ?>.onchenge=<?php echo $one['key']; ?>.onblur=function(e)
	{
		if(e==undefined)e=window.event;
		if(e.target==this)check_all(e);
		if(e.target==tijiao_button&&this.value=='')return jry_wb_beautiful_alert.alert("请填写完整信息","<?php echo $one['name']; ?>为空",function(){<?php echo $one['key']; ?>.focus();<?php echo $one['key']; ?>.classList.add('error');}),false;
<?php if($one['type']=='tel'){ ?>
		if(<?php echo $one['key']; ?>.value!=""&&(jry_wb_test_phone_number(<?php echo $one['key']; ?>.value)==false))
		{
			if(((new Date())-time_<?php echo $one['key']; ?>)>5000)
			{
				time_<?php echo $one['key']; ?>=new Date();
				jry_wb_beautiful_right_alert.alert("<?php echo $one['name']; ?>错误",2000,"auto","error");
			}
			<?php echo $one['key']; ?>.classList.add('error');
			return false;
		}
		else
			<?php echo $one['key']; ?>.classList.remove('error'),time_<?php echo $one['key']; ?>=0;
<?php }else if($one['type']=='mail'){ ?>
		if(<?php echo $one['key']; ?>.value!=""&&(jry_wb_test_mail(<?php echo $one['key']; ?>.value)==false))
		{
			if(((new Date())-time_<?php echo $one['key']; ?>)>5000)
			{
				time_<?php echo $one['key']; ?>=new Date();
				jry_wb_beautiful_right_alert.alert("<?php echo $one['name']; ?>错误",2000,"auto","error");
			}	
			<?php echo $one['key']; ?>.classList.add('error');
			return false;
		}
		else
			<?php echo $one['key']; ?>.classList.remove('error'),time_<?php echo $one['key']; ?>=0;
<?php }else if($one['type']=='china_id'){ ?>
		if(<?php echo $one['key']; ?>.value!=""&&(jry_wb_test_china_id_card(<?php echo $one['key']; ?>.value)==false))
		{
			if(((new Date())-time_<?php echo $one['key']; ?>)>5000)
			{
				time_<?php echo $one['key']; ?>=new Date();
				jry_wb_beautiful_right_alert.alert("<?php echo $one['name']; ?>错误",2000,"auto","error");
			}	
			<?php echo $one['key']; ?>.classList.add('error');
			return false;
		}
		else
			<?php echo $one['key']; ?>.classList.remove('error'),time_<?php echo $one['key']; ?>=0;
<?php } ?>
<?php if($one['connect']!=NULL)
	{
		foreach($one['connect'] as $connect)
		{
			if($one['type']=='china_id'&&$connect=='sex')
			{ ?>	
		if(<?php echo $one['key']; ?>.value!=""&&(jry_wb_get_sex_by_china_id_card(<?php echo $one['key']; ?>.value)!=sex))
		{
			jry_wb_beautiful_right_alert.alert("<?php echo $one['name']; ?>与性别不符",2000,"auto","error");
			<?php echo $one['key']; ?>.classList.add('error');
			return false;
		}
		else
			<?php echo $one['key']; ?>.classList.remove('error'),time_<?php echo $one['key']; ?>=0;
			<?php }else{ ?>
		if(<?php echo $one['key']; ?>.value!=""&&(<?php echo $one['key']; ?>.value==<?php echo $connect.($connect=='sex'?'':'.value'); ?>))
		{
			jry_wb_beautiful_right_alert.alert("信息重复",2000,"auto","error");
			<?php echo $one['key']; ?>.classList.add('error');
			return false;
		}
		else
			<?php echo $one['key']; ?>.classList.remove('error'),time_<?php echo $one['key']; ?>=0;
			<?php }
		}
	}?>	
<?php if($one['checker_js']!=''){ ?>	
	if(eval("<?php echo $one['checker_js']; ?>")==false)
	{
		jry_wb_beautiful_right_alert.alert("信息错误",2000,"auto","error");
					<?php echo $one['key']; ?>.classList.add('error');
		return false;
	}
	else
		<?php echo $one['key']; ?>.classList.remove('error'),time_<?php echo $one['key']; ?>=0;
<?php } ?>
	return true;	
	};
<?php }?>		
	var tr			=document.createElement('tr')		;body	.appendChild(tr)		;
	var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('invitecode')	;td.innerHTML='邀请码';
	var span		=document.createElement('span')		;td		.appendChild(span)		;											;span.innerHTML='(没有留空)';
	var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('invitecode_v')	;
	var invitecode	=document.createElement('input')	;td		.appendChild(invitecode);invitecode	.type='text'					;
	invitecode.onfocus=invitecode.onkeyup=function(e){save();return true;};	
	var tr			=document.createElement('tr')		;body	.appendChild(tr)		;
	var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('vcode')			;td.innerHTML='验证码';
	var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('vcode_v')		;
	var vcode		=document.createElement('input')	;td		.appendChild(vcode)		;vcode		.type='text'					;
	var img			=document.createElement('img')		;td		.appendChild(img)		;img.onclick=function(){img.src=jry_wb_message.jry_wb_host+'jry_wb_tools/jry_wb_vcode.php?r='+Math.random()};img.onclick();
	vcode.onfocus=vcode.onkeyup=function(e)
	{
		if(e==undefined)e=window.event;
		if(e.target==this)check_all(e);
		if(e.target==tijiao_button&&this.value=='')return jry_wb_beautiful_alert.alert("请填写完整信息","验证码为空",function(){vcode.focus();vcode.classList.add('error');}),false;
		if(vcode.value!=''&&vcode.value.length!=4)
		{
			if(((new Date())-time4)>5000)
			{
				time4=new Date();
				jry_wb_beautiful_right_alert.alert("4位验证码",2000,"auto","error");
				
			}
			vcode.classList.add('error');
			return false;
		}
		else
			vcode.style.border="",vcode.style.margin="",time4=0;
		return true;
	};
<?php if(JRY_WB_CHECK_TEL_SWITCH&&JRY_WB_SHORT_MESSAGE_SWITCH!=''){ ?>
	var tcodetr		=document.createElement('tr')		;body	.appendChild(tcodetr)	;tcodetr	.style.display='none';
	var td			=document.createElement('td')		;tcodetr.appendChild(td)		;td			.classList.add('telcode')		;td.innerHTML='电话验证码';
	var td			=document.createElement('td')		;tcodetr.appendChild(td)		;td			.classList.add('telcode_v')	;
	var telcode		=document.createElement('input')	;td		.appendChild(telcode)	;telcode	.type='text'					;
	var button		=document.createElement('button')	;td		.appendChild(button)	;button		.classList.add('jry_wb_button','jry_wb_button_size_middle','jry_wb_color_ok');button.innerHTML='获取验证码';
	button.onclick=function()
	{
		if(vcode.value=='')
		{
			jry_wb_beautiful_alert.alert("请填写完整信息","验证码为空",function(){vcode.focus();vcode.classList.add('error');});
			return false;		
		}
		if(tel.value!=""&&jry_wb_test_phone_number(tel.value)==false)
		{
			jry_wb_beautiful_alert.alert("请填写正确信息","电话错误",function(){tel.focus();tel.classList.add('error');});
			return false;
		}
		jry_wb_ajax_load_data('do_add.php?action=send_tel&debug='+jry_wb_get_get().debug,function (data)
		{
			data=JSON.parse(data);
			jry_wb_loading_off();
			if(data.code)
			{
				jry_wb_beautiful_alert.alert('已发送','');
			}
			else
			{
				if(data.reason==100005)
					jry_wb_beautiful_alert.alert("发送失败","请检查验证码大小写",function(){vcode.focus();vcode.classList.add('error');	});
				else if(data.reason==100002)
					jry_wb_beautiful_alert.alert("发送失败","请检查验证码,点击图片可以换一张哦",function(){vcode.focus();vcode.classList.add('error');});
				else if(data.reason==100008)
					jry_wb_beautiful_alert.alert("发送失败","手机号格式错误",function(){tel.focus();tel.classList.add('error');});
				else if(data.reason==100009)
					jry_wb_beautiful_alert.alert("发送失败","手机号重复",function(){tel.focus();tel.classList.add('error');});
				else if(data.reason==100003)
					jry_wb_beautiful_alert.alert("发送失败","电话验证码发送频繁");
			}
		},[{'name':'vcode','value':vcode.value},{'name':'tel','value':tel.value}],true);
		return true;		
	};
<?php } ?>
	var tr			=document.createElement('tr')		;body	.appendChild(tr)		;
	var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('buttons')		;td.setAttribute('colspan',2);
	var button		=document.createElement('button')	;td		.appendChild(button)	;button		.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_error');button.innerHTML='清空';
	button.onclick=function()
	{
		name.value='';
		password1.value='';
		password2.value='';
		<?php if(JRY_WB_CHECK_TEL_SWITCH){ ?>tel.value='';<?php } ?>
		<?php if(JRY_WB_CHECK_MAIL_SWITCH){ ?>mail.value='';<?php } ?>
		<?php if(JRY_WB_CHECK_TEL_SWITCH&&JRY_WB_SHORT_MESSAGE_SWITCH!=''){ ?>telcode.value='';<?php } ?>
		sexs[0].click();
	<?php foreach($JRY_WB_CONFIG_USER_EXTERN_MESSAGE as $one)if($one['type']!='cutter'){ 
		if($one['type']=='check')
		{ ?>
			<?php  echo $one['key']; ?>s[0].click();
		<?php }else{ ?>
		<?php  echo $one['key']; ?>.value='';	
	<?php }} ?>
		jry_wb_cache.delete('add');
	};
	var tijiao_button=document.createElement('button')	;td		.appendChild(tijiao_button)	;tijiao_button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');tijiao_button.innerHTML='注册';
	tijiao_button.onclick=function()
	{
		var sex=0;
		for(var i=0,n=sexs.length;i<n;i++)
			if(sexs[i].checked)
				sex=sexs[i].value;
		<?php foreach($JRY_WB_CONFIG_USER_EXTERN_MESSAGE as $one)if($one['type']!='cutter'){
		if($one['type']=='check'){ ?>
		for(var i=0,n=<?php echo $one['key']; ?>s.length;i<n;i++)
			if(<?php echo $one['key']; ?>s[i].checked)
				<?php echo $one['key']; ?>=<?php echo $one['key']; ?>s[i].value;
		<?php }} ?>
		if(!check_all({'target':tijiao_button}))return false;
		var extern={<?php foreach($JRY_WB_CONFIG_USER_EXTERN_MESSAGE as $one)if($one['type']!='cutter'){ ?>'<?php echo $one['key']; ?>':<?php echo $one['key']; ?><?php if($one['type']!='check'){ ?>.value<?php } ?>,<?php } ?>};
		extern=encodeURIComponent(JSON.stringify(extern))
		jry_wb_ajax_load_data('do_add.php?debug='+jry_wb_get_get().debug,function (data)
		{
			data=JSON.parse(data);
			jry_wb_loading_off();
			if(data.code)
			{
				jry_wb_beautiful_alert.alert('已注册,第一遍提醒','您的<span class="jry_wb_mainpages_register_result_id">ID='+data.id+'</span><br>ID是您登录本网站的<span class="jry_wb_mainpages_register_result_id">唯一</span>凭证<br>请牢记',function()
				{
					jry_wb_cache.delete('add');
					jry_wb_beautiful_alert.alert('已注册,第二遍提醒','您的<span class="jry_wb_mainpages_register_result_id">ID='+data.id+'</span><br>ID是您登录本网站的<span class="jry_wb_mainpages_register_result_id">唯一</span>凭证<br>请牢记',function()
					{
						jry_wb_beautiful_alert.alert('已注册,第三遍提醒','您的<span class="jry_wb_mainpages_register_result_id">ID='+data.id+'</span><br>ID是您登录本网站的<span class="jry_wb_mainpages_register_result_id">唯一</span>凭证<br>请牢记',function()
						{
							jry_wb_ajax_load_data('do_login.php',function(data)
							{
								data=JSON.parse(data);
								jry_wb_loading_off();
								if(data.code)
								{
									jry_wb_beautiful_alert.alert("登录成功",'距上次登录'+data.message.hour+'小时'+data.message.minute+'分钟'+(data.message.green_money==null?'':'<br>随机奖励绿币'+data.message.green_money),function()
									{
										window.location.href='<?php if($_SESSION['url']!='')echo $_SESSION['url'];else echo jry_wb_print_href("home","","",1)?>';
									});
									return ;
								}
								else
								{
									jry_wb_beautiful_alert.alert("自动登录失败","",function(){window.location.href="login.php"});
								}
							},[{'name':'id','value':data.id},{'name':'password','value':password1.value},{'name':'vcode','value':vcode.value},{'name':'type','value':0}]);							
						});
					});
				});
				if(data.send)
					jry_wb_beautiful_alert.alert('验证邮件已发送','请进入邮箱完成绑定',function(){});
			}
			else
			{
				if(data.reason==100005)
					jry_wb_beautiful_alert.alert("注册失败","请检查验证码大小写",function(){vcode.focus();vcode.classList.add('error');	});
				else if(data.reason==100002)
					jry_wb_beautiful_alert.alert("注册失败","请检查验证码,点击图片可以换一张哦",function(){vcode.focus();vcode.classList.add('error');});
				else if(data.reason==100008)
					jry_wb_beautiful_alert.alert("注册失败","手机号格式错误",function(){tel.focus();tel.classList.add('error');});
				else if(data.reason==100009)
					jry_wb_beautiful_alert.alert("注册失败","手机号重复",function(){tel.focus();tel.classList.add('error');});
				<?php if(JRY_WB_CHECK_TEL_SWITCH&&JRY_WB_SHORT_MESSAGE_SWITCH!=''){ ?>	
				else if(data.reason==100003)
					jry_wb_beautiful_alert.alert("注册失败","电话验证码发送频繁");
				else if(data.reason==100010)
					jry_wb_beautiful_alert.alert("注册失败","手机验证码错误",function(){telcode.focus();telcode.classList.add('error');});	
				<?php } ?>
				else if(data.reason==100011)
					jry_wb_beautiful_alert.alert("注册失败","密码不同",function(){password2.focus();password2.classList.add('error');});	
				else if(data.reason==100012)
					jry_wb_beautiful_alert.alert("注册失败","密码太短",function(){password1.focus();password1.classList.add('error');});
				else if(data.reason==100013)
					jry_wb_beautiful_alert.alert("注册失败","昵称为空",function(){name.focus();name.classList.add('error');});				
				<?php if(JRY_WB_INVITE_CODE){ ?>
				else if(data.reason==100019)
					jry_wb_beautiful_alert.alert("注册失败","试图使用不存在的验证码");	
				<?php } ?>
				<?php if(JRY_WB_CHECK_MAIL_SWITCH){ ?>
				else if(data.reason==100014)
					jry_wb_beautiful_alert.alert("注册失败","邮箱错误的格式",function(){mail.focus();mail.classList.add('error');});
				else if(data.reason==100015)
					jry_wb_beautiful_alert.alert("注册失败","邮箱别人绑定过了",function(){mail.focus();mail.classList.add('error');});
				else if(data.reason==100016)
					jry_wb_beautiful_alert.alert("注册失败","邮件发送失败，联系开发组");	
				else if(data.reason==100017)
					jry_wb_beautiful_alert.alert("注册失败",data.extern.name+"为空或错误",function(){eval(data.extern.key).focus();eval(data.extern.key).classList.add('error');});
				<?php } ?>
				}
		},[{'name':'name','value':name.value},<?php if(JRY_WB_CHECK_TEL_SWITCH){ ?>{'name':'tel','value':tel.value},<?php } ?><?php if(JRY_WB_CHECK_MAIL_SWITCH){ ?>{'name':'mail','value':mail.value},<?php } ?>{'name':'sex','value':sex},{'name':'password1','value':password1.value},{'name':'password2','value':password2.value},{'name':'vcode','value':vcode.value},<?php if(JRY_WB_CHECK_TEL_SWITCH&&JRY_WB_SHORT_MESSAGE_SWITCH!=''){ ?>{'name':'telcode','value':telcode.value},<?php } ?><?php if(JRY_WB_INVITE_CODE){ ?>{'name':'invitecode','value':invitecode.value},<?php } ?>{'name':'extern','value':extern}],true);
		return true;
	};
	jry_wb_set_shortcut(jry_wb_keycode_enter,tijiao_button.onclick);
	var get=JSON.parse(jry_wb_cache.get('add'));
	if(get!=null)
	{
		name.value=get.name;
		<?php if(JRY_WB_CHECK_TEL_SWITCH){ ?>tel.value=get.tel;<?php } ?>
		<?php if(JRY_WB_INVITE_CODE){ ?>invitecode.value=get.invitecode;<?php } ?>
		password1.value=get.password1;
		password2.value=get.password2;
		<?php if(JRY_WB_CHECK_MAIL_SWITCH){ ?>mail.value=get.mail;<?php } ?>
		<?php if(JRY_WB_CHECK_TEL_SWITCH&&JRY_WB_SHORT_MESSAGE_SWITCH!=''){ ?>	
		telcode.value=get.telcode;
		<?php } ?>
		for(var i=0,n=sexs.length;i<n;i++)
			if(sexs[i].value==get.sex)
				sexs[i].click();
	<?php foreach($JRY_WB_CONFIG_USER_EXTERN_MESSAGE as $one)if($one['type']!='cutter'){ 
		if($one['type']=='check')
		{ ?>
		for(var i=0,n=<?php  echo $one['key']; ?>s.length;i<n;i++)
			if(<?php  echo $one['key']; ?>s[i].value==get.<?php  echo $one['key']; ?>)
				<?php  echo $one['key']; ?>s[i].click();
		<?php }else{ ?>
		<?php  echo $one['key']; ?>.value=get.<?php  echo $one['key']; ?>;	
	<?php }} ?>
	}
	delete get;
	function check_all(e)
	{
		save();
		if(e==undefined)e=window.event;
		if(e.target!=name) if(!name.onkeyup(e))return false;
		if(e.target!=password1)if(!password1.onkeyup(e))return false;
		if(e.target!=password2)if(!password2.onkeyup(e))return false;
		<?php if(JRY_WB_CHECK_TEL_SWITCH){ ?>if(e.target!=tel)if(!tel.onkeyup(e))return false;<?php } ?>
		<?php if(JRY_WB_CHECK_MAIL_SWITCH){ ?>if(e.target!=mail)if(!mail.onkeyup(e))return false;<?php } ?>
	<?php foreach($JRY_WB_CONFIG_USER_EXTERN_MESSAGE as $one)if($one['type']!='check'&&$one['type']!='cutter'){ ?>
		if(e.target!=<?php echo $one['key']; ?>)if(!<?php echo $one['key']; ?>.onkeyup(e))return false;
	<?php } ?>
		if(e.target!=vcode)if(!vcode.onkeyup(e))return false;
	<?php if(JRY_WB_CHECK_TEL_SWITCH&&JRY_WB_SHORT_MESSAGE_SWITCH!=''){ ?>
		if(e.target==tijiao_button&&telcode.value=='')return jry_wb_beautiful_alert.alert("请填写完整信息","电话验证码为空",function(){telcode.focus();telcode.classList.add('error');}),false;
	<?php } ?>
		
		return true;
	}
	function save()
	{
		var sex;
		for(var i=0,n=sexs.length;i<n;i++)
			if(sexs[i].checked)
				sex=sexs[i].value;
	<?php foreach($JRY_WB_CONFIG_USER_EXTERN_MESSAGE as $one)if($one['type']!='cutter'){
	if($one['type']=='check'){ ?>
	var <?php echo $one['key']; ?>=0;
	for(var i=0,n=<?php echo $one['key']; ?>s.length;i<n;i++)
		if(<?php echo $one['key']; ?>s[i].checked)
			<?php echo $one['key']; ?>=<?php echo $one['key']; ?>s[i].value;
	<?php }} ?>	
		jry_wb_cache.set('add',JSON.stringify({'name':name.value,<?php if(JRY_WB_CHECK_TEL_SWITCH){ ?>'tel':tel.value,<?php } ?><?php if(JRY_WB_INVITE_CODE){ ?>'invitecode':invitecode.value,<?php } ?><?php if(JRY_WB_CHECK_MAIL_SWITCH){ ?>'mail':mail.value,<?php } ?>'sex':sex,'password1':password1.value,'password2':password2.value,<?php if(JRY_WB_CHECK_TEL_SWITCH&&JRY_WB_SHORT_MESSAGE_SWITCH!=''){ ?>'telcode':telcode.value,<?php } ?>
			<?php foreach($JRY_WB_CONFIG_USER_EXTERN_MESSAGE as $one)if($one['type']!='cutter'){ ?>'<?php echo $one['key']; ?>':<?php echo $one['key']; ?><?php if($one['type']!='check'){ ?>.value<?php } ?>,<?php } ?>	
		}));
	}	
}
<?php if(false){ ?></script><?php } ?>	