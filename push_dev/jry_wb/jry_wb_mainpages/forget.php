<?php
	include_once('../jry_wb_tools/jry_wb_includes.php');
	session_start();
	jry_wb_print_head('重置密码',false,false,true);
?>
<?php echo jry_wb_include_css($jry_wb_login_user['style'],'mainpages/forget'); ?>
<div class='jry_wb_top_toolbar'>
	<?php jry_wb_print_logo(false);?>	
	<?php jry_wb_print_href('login','');?>
	<?php jry_wb_print_href('add_user','');?>
	<?php jry_wb_print_href('forget','active');?>
</div>
<div align="center">
	<table id='area'  border="1" cellspacing="0" cellpadding="0">
		<tr>
			<td width="200" class='h55'>
				类型
			</td>
			<td width="400" class='h56'>
<?php $flag=true; ?>
<?php if(JRY_WB_CHECK_TEL_SWITCH&&JRY_WB_SHORT_MESSAGE_SWITCH!=''){ ?>	
				<input type="radio" name="type" value="1" onchange='by_tel()' <?php if($flag) echo 'checked'; ?>/>
				手机号&nbsp;
<?php $flag=false;} ?>
<?php if(JRY_WB_MAIL_SWITCH!=''&&JRY_WB_MAIL_SWITCH!=''){?>			
				<input type="radio" name="type" value="2" onchange='by_mail()' <?php if($flag) echo 'checked'; ?>/>
				邮箱
<?php $flag=false;} ?>
			</td>
		</tr>
		<tr id='vcodetr'>
			<td class='h55'>验证码</td>
			<td>
				<input name="vcode" type="text" id="vcode" class="h56" size="4"/>
				<img id="vcodesrc" src="" onload="window.onresize()"/>
			</td>
		</tr>
		<tr  id='mark'>
			<td class='h55'>新密码</td>
			<td><input name="password1" type="password" id="password1" class="h56" /></td>
		</tr>
		<tr>
			<td class='h55'>再输密码</td>
			<td><input name="password1" type="password" id="password2" class="h56" /></td>
		</tr>		
		<tr>
			<td colspan="2">
                <div align="center">
					<button type="button" id='submit' class="jry_wb_button jry_wb_button_size_big jry_wb_color_ok">重置</button>
                </div>
			</td>
		</tr>
	</table>
</div>
<script language="javascript">
vcodesrc=document.getElementById('vcodesrc');
vcodesrc.src=jry_wb_message.jry_wb_host+'jry_wb_tools/jry_wb_vcode.php?r='+Math.random();
vcodesrc.onclick=function(){vcodesrc.src=jry_wb_message.jry_wb_host+'jry_wb_tools/jry_wb_vcode.php?r='+Math.random()};
vcode=document.getElementById('vcode');
mark=document.getElementById('mark');
area=document.getElementById('area');
vcodetr=document.getElementById('vcodetr');
submit=document.getElementById('submit');
time1=time2=time3=time4=time5=0;
vcode.onfocus=vcode.onkeyup=function(e)
{
	if(vcode.value!=''&&vcode.value.length!=4)
	{
		if(((new Date())-time1)>5000)
		{
			time1=new Date();
			jry_wb_beautiful_right_alert.alert("4位验证码",2000,"auto","error");
			
		}
		vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";
		return false;
	}
	else
		vcode.style.border="",vcode.style.margin="",time1=0;
	return true;
};
password1.onfocus=password1.onkeyup=function(e)
{
	if(password1.value!=''&&password1.value.length<8)
	{
		if(((new Date())-time2)>5000)
		{
			time2=new Date();
			jry_wb_beautiful_right_alert.alert("密码长度大于8位",2000,"auto","error");
		}
		password1.style.border="5px solid #ff0000",password1.style.margin="0px 0px";
		return false;
	}
	else
		password1.style.border="",password1.style.margin="",time2=0;
	return true;
};
password2.onfocus=password2.onkeyup=function(e)
{
	if(password2.value!=''&&(password2.value!=document.getElementById('password1').value||password2.value.length<8))
	{
		if(((new Date())-time3)>5000)
		{
			time3=new Date();
			if(password2.value.length<8)
				jry_wb_beautiful_right_alert.alert("密码长度大于8位",2000,"auto","error");
			else
				jry_wb_beautiful_right_alert.alert("两次不一样耶",2000,"auto","error");
		}
		password2.style.border="5px solid #ff0000",password2.style.margin="0px 0px";
		return false;
	}
	else
		password2.style.border="",password2.style.margin="",time3=0;
	return true;
};
<?php $flag=true; ?>
<?php if(JRY_WB_CHECK_TEL_SWITCH&&JRY_WB_SHORT_MESSAGE_SWITCH!=''){ ?>	
function by_tel()
{
	if(area.getAttribute('type')=='tel')
		return;
	area.setAttribute('type','tel');
	while(mark.previousElementSibling!==vcodetr)
		mark.parentNode.removeChild(mark.previousElementSibling);
	var tr=document.createElement('tr');mark.parentNode.insertBefore(tr,mark);
	var td=document.createElement('td');tr.appendChild(td);
	td.classList.add('h55');
	td.innerHTML='电话';
	var td=document.createElement('td');tr.appendChild(td);
	var tel=document.createElement('input');td.appendChild(tel);
	tel.classList.add('h56');
	tel.name=tel.id='tel';
	tel.onkeyup=function()
	{
		if(tel.value!=""&&(jry_wb_test_phone_number(tel.value)==false))
		{
			if(((new Date())-time4)>5000)
			{
				time4=new Date();
				jry_wb_beautiful_right_alert.alert("电话错误",2000,"auto","error");
			}	
			tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";
			return false;
		}
		else
			tel.style.border="",tel.style.margin="",time4=0;
	};
	var tr=document.createElement('tr');mark.parentNode.insertBefore(tr,mark);
	var td=document.createElement('td');tr.appendChild(td);
	td.classList.add('h55');
	td.innerHTML='电话验证码';
	var td=document.createElement('td');tr.appendChild(td);
	var telcode=document.createElement('input');td.appendChild(telcode);
	telcode.classList.add('h56');
	telcode.setAttribute('size',6);
	telcode.name=telcode.id='telcode';	
	telcode.onkeyup=function()
	{
		if(telcode.value!=''&&telcode.value.length!=6)
		{
			if(((new Date())-time5)>5000)
			{
				time5=new Date();
				jry_wb_beautiful_right_alert.alert("6位验证码",2000,"auto","error");
				
			}
			telcode.style.border="5px solid #ff0000",telcode.style.margin="0px 0px";
			return false;
		}
		else
			telcode.style.border="",telcode.style.margin="",time5=0;
		return true;		
	};
	var button=document.createElement('button');td.appendChild(button);
	button.classList.add('jry_wb_button','jry_wb_button_size_middle','jry_wb_color_ok');
	button.innerHTML='获取验证码';
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
		},[{'name':'vcode','value':vcode.value},{'name':'tel','value':tel.value},{'name':'phonecode','value':telcode.value},{'name':'password1','value':password1.value},{'name':'password2','value':password2.value}],true);		
	};
	window.onresize();
}
<?php if($flag) echo 'by_tel();'; ?>
<?php $flag=false;} ?>
<?php if(JRY_WB_MAIL_SWITCH!=''&&JRY_WB_MAIL_SWITCH!=''){?>
function by_mail()
{
	if(area.getAttribute('type')=='mail')
		return;
	area.setAttribute('type','mail');	
	while(mark.previousElementSibling!==vcodetr)
		mark.parentNode.removeChild(mark.previousElementSibling);
	var tr=document.createElement('tr');mark.parentNode.insertBefore(tr,mark);
	var td=document.createElement('td');tr.appendChild(td);
	td.classList.add('h55');
	td.innerHTML='邮箱';	
	var td=document.createElement('td');tr.appendChild(td);	
	var mail=document.createElement('input');td.appendChild(mail);
	mail.classList.add('h56');
	mail.name=mail.id='mail';
	mail.onkeyup=function()
	{
		if(mail.value!=""&&(jry_wb_test_mail(mail.value)==false))
		{
			if(((new Date())-time4)>5000)
			{
				time4=new Date();
				jry_wb_beautiful_right_alert.alert("邮箱错误",2000,"auto","error");
			}	
			mail.style.border="5px solid #ff0000",mail.style.margin="0px 0px";
			return false;
		}
		else
			mail.style.border="",mail.style.margin="",time4=0;
	};
	var tr=document.createElement('tr');mark.parentNode.insertBefore(tr,mark);
	var td=document.createElement('td');tr.appendChild(td);
	td.classList.add('h55');
	td.innerHTML='邮箱验证码';
	var td=document.createElement('td');tr.appendChild(td);
	var mailcode=document.createElement('input');td.appendChild(mailcode);
	mailcode.classList.add('h56');
	mailcode.setAttribute('size',6);
	mailcode.name=mailcode.id='mailcode';	
	mailcode.onkeyup=function()
	{
		if(mailcode.value!=''&&mailcode.value.length!=6)
		{
			if(((new Date())-time5)>5000)
			{
				time5=new Date();
				jry_wb_beautiful_right_alert.alert("6位验证码",2000,"auto","error");
				
			}
			mailcode.style.border="5px solid #ff0000",mailcode.style.margin="0px 0px";
			return false;
		}
		else
			mailcode.style.border="",mailcode.style.margin="",time5=0;
		return true;		
	};
	var button=document.createElement('button');td.appendChild(button);
	button.classList.add('jry_wb_button','jry_wb_button_size_middle','jry_wb_color_ok');
	button.innerHTML='获取验证码';
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
}
<?php if($flag) echo 'by_mail();'; ?>
<?php $flag=false;} ?>
</script>
<?php jry_wb_print_tail();?>