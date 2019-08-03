<?php
	header("content-type: application/x-javascript");
	include_once("../jry_wb_tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_user_extern_message.php");	
?>
<?php if(false){ ?><script><?php } ?>
function jry_wb_mainpages_forget(area)
{
	jry_wb_include_css('mainpages/forget');
	area.innerHTML=area.style=area.className='';
	time1=time2=time3=time4=time5=0;
	var body		=document.createElement('table')	;area	.appendChild(body)		;body		.classList.add('jry_wb_mainpages_forget')	;body.setAttribute('align','center');
	var typetr		=document.createElement('tr')		;body	.appendChild(typetr)	;
	var td			=document.createElement('td')		;typetr	.appendChild(td)		;td			.classList.add('type')			;td.innerHTML='重置方式';
	var td			=document.createElement('td')		;typetr	.appendChild(td)		;td			.classList.add('type_v')		;
	var types		=[];
	var type		=0;
<?php if(JRY_WB_CHECK_TEL_SWITCH&&JRY_WB_SHORT_MESSAGE_SWITCH!=''){ ?>	
	var input		=document.createElement('input')	;td		.appendChild(input)		;input		.type='radio'					;types.push(input)	;input.name='type';input.value=1;
	input.onclick=function()
	{
		if(body.getAttribute('type')=='tel')
			return;
		body.setAttribute('type','tel');
		while(vcodetr.previousElementSibling!==typetr)
			vcodetr.parentNode.removeChild(vcodetr.previousElementSibling);
		var tr			=document.createElement("tr")		;vcodetr.parentNode.insertBefore(tr,vcodetr);		
		var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('type')			;td.innerHTML='电话';
		var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('type_v')		;
		var tel			=document.createElement('input')	;td		.appendChild(tel)		;tel		.classList.add('tel')			;
		tel.onkeyup=function()
		{
			if(tel.value!=""&&(jry_wb_test_phone_number(tel.value)==false))
			{
				if(((new Date())-time4)>5000)
				{
					time4=new Date();
					jry_wb_beautiful_right_alert.alert("电话错误",2000,"auto","error");
				}	
				tel.classList.add('error');
				return false;
			}
			else
				tel.classList.remove('error'),time4=0;
		};
		var tr			=document.createElement("tr")		;vcodetr.parentNode.insertBefore(tr,vcodetr);		
		var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('telcode')		;td.innerHTML='电话验证码';
		var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('telcode_v')		;
		var telcode		=document.createElement('input')	;td		.appendChild(telcode)	;
		telcode.onkeyup=function()
		{
			if(telcode.value!=''&&telcode.value.length!=6)
			{
				if(((new Date())-time5)>5000)
				{
					time5=new Date();
					jry_wb_beautiful_right_alert.alert("6位验证码",2000,"auto","error");
					
				}
				telcode.classList.add('error');
				return false;
			}
			else
				telcode.classList.remove('error'),time5=0;
			return true;		
		};
		var button		=document.createElement('button')	;td		.appendChild(button)	;button		.classList.add('jry_wb_button','jry_wb_button_size_middle','jry_wb_color_ok');button.innerHTML='获取验证码';
		button.onclick=function()
		{
			if(vcode.value=='')
				return jry_wb_beautiful_alert.alert("请填写完整信息","验证码为空",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";}),false;
			if(tel.value!=""&&jry_wb_test_phone_number(tel.value)==false)
				return jry_wb_beautiful_alert.alert("请填写正确信息","电话错误",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";}),false;
			jry_wb_ajax_load_data('do_forget.php?action=send_tel',function (data)
			{
				data=JSON.parse(data);
				jry_wb_loading_off();
				if(data.code)
					jry_wb_beautiful_alert.alert('已发送','');
				else
				{
					if(data.reason==100005)
						jry_wb_beautiful_alert.alert("发送失败","请检查验证码大小写",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";	});
					else if(data.reason==100002)
						jry_wb_beautiful_alert.alert("发送失败","请检查验证码,点击图片可以换一张哦",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";});
					else if(data.reason==100008)
						jry_wb_beautiful_alert.alert("发送失败","手机号格式错误",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
					else if(data.reason==100018)
						jry_wb_beautiful_alert.alert("发送失败","手机号不存在",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
					else if(data.reason==100003)
						jry_wb_beautiful_alert.alert("发送失败","电话验证码发送频繁");
				}
			},[{'name':'vcode','value':vcode.value},{'name':'tel','value':tel.value}],true);
			return true;		
		};
		submit.onclick=function()
		{
			if(vcode.value=='')
				return jry_wb_beautiful_alert.alert("请填写完整信息","验证码为空",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";}),false;
			if(tel.value!=""&&jry_wb_test_phone_number(tel.value)==false)
				return jry_wb_beautiful_alert.alert("请填写正确信息","电话错误",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";}),false;
			if(telcode.value==''||telcode.value.length!=6)
				return jry_wb_beautiful_alert.alert("请填写正确信息","电话验证码错误",function(){telcode.focus();telcode.style.border="5px solid #ff0000",telcode.style.margin="0px 0px";}),false;
			if(password1.value=='')
				return jry_wb_beautiful_alert.alert("请填写完整信息","密码为空",function(){password1.focus();password1.style.border="5px solid #ff0000",password1.style.margin="0px 0px";}),false;				
			if(password2.value=='')
				return jry_wb_beautiful_alert.alert("请填写完整信息","密码为空",function(){password2.focus();password2.style.border="5px solid #ff0000",password2.style.margin="0px 0px";}),false;
			if(password2.value!=password1.value)
				return jry_wb_beautiful_alert.alert("请填写正确信息","两次密码不同",function(){password2.focus();password2.style.border="5px solid #ff0000",password2.style.margin="0px 0px";}),false;
			jry_wb_ajax_load_data('do_forget.php?action=chenge_password&type=tel',function(data)
			{
				data=JSON.parse(data);
				jry_wb_loading_off();
				if(data.code)
				{
					jry_wb_beautiful_alert.alert('重置成功','即将登录',function()
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
						},[{'name':'id','value':tel.value},{'name':'password','value':password1.value},{'name':'vcode','value':vcode.value},{'name':'type','value':1}]);							
					});				
				}
				else
				{
					if(data.reason==100005)
						jry_wb_beautiful_alert.alert("重置失败","请检查验证码大小写",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";	});
					else if(data.reason==100002)
						jry_wb_beautiful_alert.alert("重置失败","请检查验证码,点击图片可以换一张哦",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";});
					else if(data.reason==100008)
						jry_wb_beautiful_alert.alert("重置失败","手机号格式错误",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
					else if(data.reason==100018)
						jry_wb_beautiful_alert.alert("重置失败","手机号不存在",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
					else if(data.reason==100010)
						jry_wb_beautiful_alert.alert("重置失败","手机验证码错误",function(){telcode.focus();telcode.style.border="5px solid #ff0000",telcode.style.margin="0px 0px";});	
					else if(data.reason==100011)
						jry_wb_beautiful_alert.alert("重置失败","密码不同",function(){password2.focus();password2.style.border="5px solid #ff0000",password2.style.margin="0px 0px";});	
					else if(data.reason==100012)
						jry_wb_beautiful_alert.alert("重置失败","密码太短",function(){password1.focus();password1.style.border="5px solid #ff0000",password1.style.margin="0px 0px";});				
				}
			},[{'name':'vcode','value':vcode.value},{'name':'tel','value':tel.value},{'name':'telcode','value':telcode.value},{'name':'password1','value':password1.value},{'name':'password2','value':password2.value}],true);		
		};
		window.onresize();		
	};
	var span		=document.createElement('span')		;td		.appendChild(span)		;span		.classList.add('tel')			;span.innerHTML='手机';
<?php } ?>
<?php if(JRY_WB_MAIL_SWITCH!=''&&JRY_WB_MAIL_SWITCH!=''){?>
	var input		=document.createElement('input')	;td		.appendChild(input)		;input		.type='radio'					;types.push(input)	;input.name='type';input.value=2;input.onclick=function(){type=2;key_td.innerHTML='邮箱'	;};
	var span		=document.createElement('span')		;td		.appendChild(span)		;span		.classList.add('mail')			;span.innerHTML='邮箱';
	input.onclick=function()
	{
		if(body.getAttribute('type')=='mail')
			return;
		body.setAttribute('type','mail');	
		while(vcodetr.previousElementSibling!==typetr)
			vcodetr.parentNode.removeChild(vcodetr.previousElementSibling);
		var tr			=document.createElement("tr")		;vcodetr.parentNode.insertBefore(tr,vcodetr);		
		var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('type')			;td.innerHTML='邮箱';
		var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('type_v')		;
		var mail		=document.createElement('input')	;td		.appendChild(mail)		;mail		.classList.add('mail')			;		
		mail.onkeyup=function()
		{
			if(mail.value!=""&&(jry_wb_test_mail(mail.value)==false))
			{
				if(((new Date())-time4)>5000)
				{
					time4=new Date();
					jry_wb_beautiful_right_alert.alert("邮箱错误",2000,"auto","error");
				}	
				mail.classList.add('error');
				return false;
			}
			else
				mail.classList.remove('error'),time4=0;
		};
		var tr			=document.createElement("tr")		;vcodetr.parentNode.insertBefore(tr,vcodetr);		
		var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('mailcode')		;td.innerHTML='邮箱验证码';
		var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('mailcode_v');
		var mailcode	=document.createElement('input')	;td.appendChild(mailcode)		;
		mailcode.onkeyup=function()
		{
			if(mailcode.value!=''&&mailcode.value.length!=6)
			{
				if(((new Date())-time5)>5000)
				{
					time5=new Date();
					jry_wb_beautiful_right_alert.alert("6位验证码",2000,"auto","error");
					
				}
				mailcode.classList.add('error');
				return false;
			}
			else
				mailcode.classList.remove('error'),time5=0;
			return true;		
		};
		var button		=document.createElement('button')	;td		.appendChild(button)	;button		.classList.add('jry_wb_button','jry_wb_button_size_middle','jry_wb_color_ok');button.innerHTML='获取验证码';
		button.onclick=function()
		{
			if(vcode.value=='')
				return jry_wb_beautiful_alert.alert("请填写完整信息","验证码为空",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";}),false;
			if(mail.value!=""&&jry_wb_test_mail(mail.value)==false)
				return jry_wb_beautiful_alert.alert("请填写正确信息","邮箱错误",function(){mail.focus();mail.style.border="5px solid #ff0000",mail.style.margin="0px 0px";}),false;
			jry_wb_ajax_load_data('do_forget.php?action=send_mail',function (data)
			{
				data=JSON.parse(data);
				jry_wb_loading_off();
				if(data.code)
					jry_wb_beautiful_alert.alert('已发送','');
				else
				{
					if(data.reason==100005)
						jry_wb_beautiful_alert.alert("发送失败","请检查验证码大小写",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";	});
					else if(data.reason==100002)
						jry_wb_beautiful_alert.alert("发送失败","请检查验证码,点击图片可以换一张哦",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";});
					else if(data.reason==100014)
						jry_wb_beautiful_alert.alert("注册失败","邮箱错误的格式",function(){mail.focus();mail.style.border="5px solid #ff0000",mail.style.margin="0px 0px";});
					else if(data.reason==100018)
						jry_wb_beautiful_alert.alert("发送失败","邮箱不存在",function(){mail.focus();mail.style.border="5px solid #ff0000",mail.style.margin="0px 0px";});
				}
			},[{'name':'vcode','value':vcode.value},{'name':'mail','value':mail.value}],true);
			return true;		
		};
		submit.onclick=function()
		{
			if(vcode.value=='')
				return jry_wb_beautiful_alert.alert("请填写完整信息","验证码为空",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";}),false;
			if(mail.value!=""&&jry_wb_test_mail(mail.value)==false)
				return jry_wb_beautiful_alert.alert("请填写正确信息","邮箱错误",function(){mail.focus();mail.style.border="5px solid #ff0000",mail.style.margin="0px 0px";}),false;
			if(mailcode.value==''||mailcode.value.length!=6)
				return jry_wb_beautiful_alert.alert("请填写正确信息","邮箱证码错误",function(){mailcode.focus();mailcode.style.border="5px solid #ff0000",mailcode.style.margin="0px 0px";}),false;
			if(password1.value=='')
				return jry_wb_beautiful_alert.alert("请填写完整信息","密码为空",function(){password1.focus();password1.style.border="5px solid #ff0000",password1.style.margin="0px 0px";}),false;				
			if(password2.value=='')
				return jry_wb_beautiful_alert.alert("请填写完整信息","密码为空",function(){password2.focus();password2.style.border="5px solid #ff0000",password2.style.margin="0px 0px";}),false;
			if(password2.value!=password1.value)
				return jry_wb_beautiful_alert.alert("请填写正确信息","两次密码不同",function(){password2.focus();password2.style.border="5px solid #ff0000",password2.style.margin="0px 0px";}),false;
			jry_wb_ajax_load_data('do_forget.php?action=chenge_password&type=mail',function(data)
			{
				data=JSON.parse(data);
				jry_wb_loading_off();
				if(data.code)
				{
					jry_wb_beautiful_alert.alert('重置成功','即将登录',function()
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
						},[{'name':'id','value':mail.value},{'name':'password','value':password1.value},{'name':'vcode','value':vcode.value},{'name':'type','value':2}]);							
					});				
				}
				else
				{
					if(data.reason==100005)
						jry_wb_beautiful_alert.alert("重置失败","请检查验证码大小写",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";	});
					else if(data.reason==100002)
						jry_wb_beautiful_alert.alert("重置失败","请检查验证码,点击图片可以换一张哦",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";});
					else if(data.reason==100014)
						jry_wb_beautiful_alert.alert("重置失败","邮箱格式错误",function(){mail.focus();mail.style.border="5px solid #ff0000",mail.style.margin="0px 0px";});
					else if(data.reason==100018)
						jry_wb_beautiful_alert.alert("重置失败","邮箱不存在",function(){mail.focus();mail.style.border="5px solid #ff0000",mail.style.margin="0px 0px";});
					else if(data.reason==100016)
						jry_wb_beautiful_alert.alert("重置失败","邮箱验证码错误",function(){mailcode.focus();mailcode.style.border="5px solid #ff0000",mailcode.style.margin="0px 0px";});	
					else if(data.reason==100011)
						jry_wb_beautiful_alert.alert("重置失败","密码不同",function(){password2.focus();password2.style.border="5px solid #ff0000",password2.style.margin="0px 0px";});	
					else if(data.reason==100012)
						jry_wb_beautiful_alert.alert("重置失败","密码太短",function(){password1.focus();password1.style.border="5px solid #ff0000",password1.style.margin="0px 0px";});				
				}
			},[{'name':'vcode','value':vcode.value},{'name':'mail','value':mail.value},{'name':'mailcode','value':mailcode.value},{'name':'password1','value':password1.value},{'name':'password2','value':password2.value}],true);		
		};	
		window.onresize();		
	};
<?php } ?>
	var vcodetr		=document.createElement('tr')		;body	.appendChild(vcodetr)	;
	var td			=document.createElement('td')		;vcodetr.appendChild(td)		;td			.classList.add('vcode')			;td.innerHTML='验证码';
	var td			=document.createElement('td')		;vcodetr.appendChild(td)		;td			.classList.add('vcode_v')		;
	var vcode		=document.createElement('input')	;td		.appendChild(vcode)		;vcode		.type='text'					;
	vcode.onfocus=vcode.onkeyup=function()
	{
		if(vcode.value!=''&&vcode.value.length!=4)
		{
			if(((new Date())-time1)>5000)
			{
				time1=new Date();
				jry_wb_beautiful_right_alert.alert("4位验证码",2000,"auto","error");
			}
			vcode.classList.add('error');
		}
		else
			vcode.classList.remove('error'),time1=0;
	};
	var img			=document.createElement('img')		;td		.appendChild(img)		;img.onclick=function(){img.src=jry_wb_message.jry_wb_host+'jry_wb_tools/jry_wb_vcode.php?r='+Math.random()};img.onclick();
	var tr			=document.createElement("tr")		;body	.appendChild(tr)		;
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('password1')		;td.innerHTML='密码';
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('password1_v')	;
	var password1	=document.createElement("input")	;td		.appendChild(password1)	;password1	.type='password'				;
	password1.onfocus=password1.onkeyup=function(e)
	{
		if(password1.value!=''&&password1.value.length<8)
		{
			if(((new Date())-time2)>5000)
			{
				time2=new Date();
				jry_wb_beautiful_right_alert.alert("密码长度大于8位",2000,"auto","error");
			}
			password1.classList.add('error');
			return false;
		}
		else
			password1.classList.remove('error'),time2=0;
		return true;
	};
	var tr			=document.createElement("tr")		;body	.appendChild(tr)		;
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('password2')		;td.innerHTML='再输密码';
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('password2_v')	;
	var password2	=document.createElement("input")	;td		.appendChild(password2)	;password2	.type='password'				;
	password2.onfocus=password2.onkeyup=function(e)
	{
		if(password2.value!=''&&(password2.value!=password1.value||password2.value.length<8))
		{
			if(((new Date())-time3)>5000)
			{
				time3=new Date();
				if(password2.value.length<8)
					jry_wb_beautiful_right_alert.alert("密码长度大于8位",2000,"auto","error");
				else
					jry_wb_beautiful_right_alert.alert("两次不一样耶",2000,"auto","error");
			}
			password2.classList.add('error')
			return false;
		}
		else
			password2.classList.remove('error'),time3=0;
		return true;
	};
	var tr			=document.createElement('tr')		;body	.appendChild(tr)		;
	var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('buttons')		;td.setAttribute('colspan',2);
	var submit		=document.createElement('button')	;td		.appendChild(submit)	;submit		.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');submit.innerHTML='重置';
	
}
<?php if(false){ ?></script><?php } ?>	