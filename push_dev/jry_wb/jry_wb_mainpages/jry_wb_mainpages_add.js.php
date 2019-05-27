<?php
	header("content-type: application/x-javascript");
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_user_extern_message.php");	
?>
<?php if(false){ ?><script><?php } ?>
var old_onkeydown=document.onkeydown;
document.onkeydown=function(e)
{
	if (!e) 
		e=window.event;
	var keycode=(e.keyCode||e.which);
	if(keycode==jry_wb_keycode_enter)
		check();
	return old_onkeydown();
};
namee=document.getElementById('name');
tijiao_button=document.getElementById('tijiao_button');
td1=document.getElementById('td1');
td2=document.getElementById('td2');
<?php if(constant('jry_wb_check_mail_switch')){ ?>
mail=document.getElementById('mail');
<?php } ?>
<?php if(constant('jry_wb_check_tel_switch')){ ?>
tel=document.getElementById('tel');
<?php } ?>
sexs=document.getElementsByName('sex');
sex=0;
password1=document.getElementById('password1');
password2=document.getElementById('password2');
vcode=document.getElementById('vcode');
vcode.style.width=0;
<?php if(constant('jry_wb_check_tel_switch')&&constant('jry_wb_short_message_switch')!=''){ ?>
phonecode=document.getElementById('phonecode');
tr_tel=document.getElementById('tr_tel');
<?php } ?>
vcodesrc=document.getElementById('vcodesrc');
vcodesrc.src=jry_wb_message.jry_wb_host+'tools/jry_wb_vcode.php?r='+Math.random();
vcodesrc.onclick=function(){vcodesrc.src=jry_wb_message.jry_wb_host+'tools/jry_wb_vcode.php?r='+Math.random()};
<?php foreach($jry_wb_config_user_extern_message as $one)if($one['type']!='cutter'){ 
	if($one['type']=='check')
	{ ?>
<?php  echo $one['key']; ?>s=document.getElementsByName('<?php  echo $one['key']; ?>');	
	<?php }else{ ?>
<?php  echo $one['key']; ?>=document.getElementById('<?php  echo $one['key']; ?>');	
<?php }} ?>	
jry_wb_add_load(function(){vcode.style.width=td2.clientWidth-25-vcodesrc.clientWidth;});
var get=JSON.parse(jry_wb_cache.get('add'));
if(get!=null)
{
	namee.value=get.name;
	<?php if(constant('jry_wb_check_tel_switch')){ ?>tel.value=get.tel;<?php } ?>
	password1.value=get.password1;
	password2.value=get.password2;
	<?php if(constant('jry_wb_check_mail_switch')){ ?>mail.value=get.mail;<?php } ?>
	<?php if(constant('jry_wb_check_tel_switch')&&constant('jry_wb_short_message_switch')!=''){ ?>	
	phonecode.value=get.phonecode;
	<?php } ?>
	for(var i=0,n=sexs.length;i<n;i++)
		if(sexs[i].value==get.sex)
			sexs[i].click();
<?php foreach($jry_wb_config_user_extern_message as $one)if($one['type']!='cutter'){ 
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
function clear_all()
{
	namee.value='';
	tel.value='';
	password1.value='';
	password2.value='';
	<?php if(constant('jry_wb_check_tel_switch')){ ?>tel.value='';<?php } ?>
	<?php if(constant('jry_wb_check_mail_switch')){ ?>mail.value='';<?php } ?>
	<?php if(constant('jry_wb_check_tel_switch')&&constant('jry_wb_short_message_switch')!=''){ ?>phonecode.value='';<?php } ?>
	sexs[0].click();
<?php foreach($jry_wb_config_user_extern_message as $one)if($one['type']!='cutter'){ 
	if($one['type']=='check')
	{ ?>
		<?php  echo $one['key']; ?>s[0].click();
	<?php }else{ ?>
	<?php  echo $one['key']; ?>.value='';	
<?php }} ?>
	jry_wb_cache.delete('add');
}
<?php if(constant('jry_wb_check_tel_switch')&&constant('jry_wb_short_message_switch')!=''){ ?>if(tel.value=="")
	tr_tel.style.display="none";
else
	phonecode.style.width=td2.clientWidth-document.getElementById('phonecode_button').clientWidth-25;
<?php } ?>	
if(namee.value=="")
	namee.focus();
else if(password1.value=="")
	password1.focus();
else if(password2.value=="")
	password2.focus();
<?php if(constant('jry_wb_check_tel_switch')){ ?>else if(tel.value=="")
	tel.focus();
<?php } ?>
<?php if(constant('jry_wb_check_mail_switch')){ ?>else if(mail.value=="")
	mail.focus();
<?php } ?>		
<?php foreach($jry_wb_config_user_extern_message as $one)if($one['type']!='check'&&$one['type']!='cutter'){ ?>
else if(<?php  echo $one['key']; ?>.value=="")
	<?php  echo $one['key']; ?>.focus();		
<?php } ?>
else if(vcode.value=="")
	vcode.focus();
<?php if(constant('jry_wb_check_tel_switch')&&constant('jry_wb_short_message_switch')!=''){ ?>else if(phonecode.value=="")
	phonecode.focus();	
<?php } ?>
function check()
{ 
	var sex=0;
	for(var i=0,n=sexs.length;i<n;i++)
		if(sexs[i].checked)
			sex=sexs[i].value;
	<?php foreach($jry_wb_config_user_extern_message as $one)if($one['type']!='cutter'){
	if($one['type']=='check'){ ?>
	for(var i=0,n=<?php echo $one['key']; ?>s.length;i<n;i++)
		if(<?php echo $one['key']; ?>s[i].checked)
			<?php echo $one['key']; ?>=<?php echo $one['key']; ?>s[i].value;
	<?php }} ?>
	if(!check_all({'target':tijiao_button}))return false;
	var extern={<?php foreach($jry_wb_config_user_extern_message as $one)if($one['type']!='cutter'){ ?>'<?php echo $one['key']; ?>':<?php echo $one['key']; ?><?php if($one['type']!='check'){ ?>.value<?php } ?>,<?php } ?>};
	extern=encodeURIComponent(JSON.stringify(extern))
	jry_wb_ajax_load_data('do_add.php?debug=<?php  echo $_GET['debug']?>',function (data)
	{
		data=JSON.parse(data);
		jry_wb_loading_off();
		if(data.code)
		{
			jry_wb_beautiful_alert.alert('已注册,第一遍提醒','您的<h56>ID='+data.id+'</h56><br>ID是您登录本网站的<h56>唯一</h56>凭证<br>请牢记',function()
			{
				jry_wb_cache.delete('add');
				jry_wb_beautiful_alert.alert('已注册,第二遍提醒','您的<h56>ID='+data.id+'</h56><br>ID是您登录本网站的<h56>唯一</h56>凭证<br>请牢记',function()
				{
					jry_wb_beautiful_alert.alert('已注册,第三遍提醒','您的<h56>ID='+data.id+'</h56><br>ID是您登录本网站的<h56>唯一</h56>凭证<br>请牢记',function()
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
				jry_wb_beautiful_alert.alert("注册失败","请检查验证码大小写",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";	});
			else if(data.reason==100002)
				jry_wb_beautiful_alert.alert("注册失败","请检查验证码,点击图片可以换一张哦",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";});
			else if(data.reason==100008)
				jry_wb_beautiful_alert.alert("注册失败","手机号格式错误",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
			else if(data.reason==100009)
				jry_wb_beautiful_alert.alert("注册失败","手机号重复",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
			<?php if(constant('jry_wb_check_tel_switch')&&constant('jry_wb_short_message_switch')!=''){ ?>	
			else if(data.reason==100003)
				jry_wb_beautiful_alert.alert("注册失败","电话验证码发送频繁");
			else if(data.reason==100010)
				jry_wb_beautiful_alert.alert("注册失败","手机验证码错误",function(){phonecode.focus();phonecode.style.border="5px solid #ff0000",phonecode.style.margin="0px 0px";});	
			<?php } ?>
			else if(data.reason==100011)
				jry_wb_beautiful_alert.alert("注册失败","密码不同",function(){password2.focus();password2.style.border="5px solid #ff0000",password2.style.margin="0px 0px";});	
			else if(data.reason==100012)
				jry_wb_beautiful_alert.alert("注册失败","密码太短",function(){password1.focus();password1.style.border="5px solid #ff0000",password1.style.margin="0px 0px";});
			else if(data.reason==100013)
				jry_wb_beautiful_alert.alert("注册失败","昵称为空",function(){namee.focus();namee.style.border="5px solid #ff0000",namee.style.margin="0px 0px";});				
			<?php if(constant('jry_wb_check_mail_switch')){ ?>
			else if(data.reason==100014)
				jry_wb_beautiful_alert.alert("注册失败","邮箱错误的格式",function(){mail.focus();mail.style.border="5px solid #ff0000",mail.style.margin="0px 0px";});
			else if(data.reason==100015)
				jry_wb_beautiful_alert.alert("注册失败","邮箱别人绑定过了",function(){mail.focus();mail.style.border="5px solid #ff0000",mail.style.margin="0px 0px";});
			else if(data.reason==100016)
				jry_wb_beautiful_alert.alert("注册失败","邮件发送失败，联系开发组");	
			else if(data.reason==100017)
				jry_wb_beautiful_alert.alert("注册失败",data.extern.name+"为空或错误",function(){eval(data.extern.key).focus();eval(data.extern.key).style.border="5px solid #ff0000",eval(data.extern.key).style.margin="0px 0px";});
			<?php } ?>
			}
	},[{'name':'name','value':namee.value},<?php if(constant('jry_wb_check_tel_switch')){ ?>{'name':'tel','value':tel.value},<?php } ?><?php if(constant('jry_wb_check_mail_switch')){ ?>{'name':'mail','value':mail.value},<?php } ?>{'name':'sex','value':sex},{'name':'password1','value':password1.value},{'name':'password2','value':password2.value},{'name':'vcode','value':vcode.value},<?php if(constant('jry_wb_check_tel_switch')&&constant('jry_wb_short_message_switch')!=''){ ?>{'name':'phonecode','value':phonecode.value},<?php } ?>{'name':'extern','value':extern}],true);
	return true;
}
<?php if(constant('jry_wb_check_tel_switch')&&constant('jry_wb_short_message_switch')!=''){ ?>	
function check_tel()
{ 
	if(vcode.value=='')
	{
		jry_wb_beautiful_alert.alert("请填写完整信息","验证码为空",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";});
		return false;		
	}
	if(tel.value!=""&&jry_wb_test_phone_number(tel.value)==false)
	{
		jry_wb_beautiful_alert.alert("请填写正确信息","电话错误",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
		return false;
	}
	jry_wb_ajax_load_data('do_add.php?action=send_tel&debug=<?php  echo $_GET['debug']?>',function (data)
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
				jry_wb_beautiful_alert.alert("发送失败","请检查验证码大小写",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";	});
			else if(data.reason==100002)
				jry_wb_beautiful_alert.alert("发送失败","请检查验证码,点击图片可以换一张哦",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";});
			else if(data.reason==100008)
				jry_wb_beautiful_alert.alert("发送失败","手机号格式错误",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
			else if(data.reason==100009)
				jry_wb_beautiful_alert.alert("发送失败","手机号重复",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
			else if(data.reason==100003)
				jry_wb_beautiful_alert.alert("发送失败","电话验证码发送频繁");
		}
	},[{'name':'vcode','value':vcode.value},{'name':'tel','value':tel.value}],true);
	return true;
}
<?php } ?>	
time1=0;
time2=0;
time3=0;
time4=0;
time5=0;
time6=0;
<?php foreach($jry_wb_config_user_extern_message as $one)if($one['type']!='cutter'){ ?>
time_<?php echo $one['key']; ?>=0;
<?php } ?>
function save()
{
	for(var i=0,n=sexs.length;i<n;i++)
		if(sexs[i].checked)
			sex=sexs[i].value;
<?php foreach($jry_wb_config_user_extern_message as $one)if($one['type']!='cutter'){
if($one['type']=='check'){ ?>
for(var i=0,n=<?php echo $one['key']; ?>s.length;i<n;i++)
	if(<?php echo $one['key']; ?>s[i].checked)
		<?php echo $one['key']; ?>=<?php echo $one['key']; ?>s[i].value;
<?php }} ?>	
	jry_wb_cache.set('add',JSON.stringify({'name':namee.value,<?php if(constant('jry_wb_check_tel_switch')){ ?>'tel':tel.value,<?php } ?><?php if(constant('jry_wb_check_mail_switch')){ ?>'mail':mail.value,<?php } ?>'sex':sex,'password1':password1.value,'password2':password2.value,<?php if(constant('jry_wb_check_tel_switch')&&constant('jry_wb_short_message_switch')!=''){ ?>'phonecode':phonecode.value,<?php } ?>
		<?php foreach($jry_wb_config_user_extern_message as $one)if($one['type']!='cutter'){ ?>'<?php echo $one['key']; ?>':<?php echo $one['key']; ?><?php if($one['type']!='check'){ ?>.value<?php } ?>,<?php } ?>	
	}));
}
namee.onfocus=namee.onkeyup=function(e)
{
	if(e==undefined)e=window.event;
	if(e.target==this)check_all(e);
	if(e.target==tijiao_button&&this.value=='')return jry_wb_beautiful_alert.alert("请填写完整信息","名字为空",function(){namee.focus();namee.style.border="5px solid #ff0000",namee.style.margin="0px 0px";}),false;
	if(namee.value=='')
	{
		if(((new Date())-time5)>5000)
		{
			time5=new Date();
			jry_wb_beautiful_right_alert.alert("昵称不为空",2000,"auto","error");
		}
		namee.style.border="5px solid #ff0000",namee.style.margin="0px 0px";
		return false;
	}
	else
		namee.style.border="",namee.style.margin="",time5=0;
	return true;
};
<?php if(constant('jry_wb_check_tel_switch')&&constant('jry_wb_short_message_switch')!=''){ ?>	
phonecode.onkeyup=function(){check_all();};
<?php } ?>
for(var i=0,n=sexs.length;i<n;i++)
	sexs[i].onclick=function()
	{
		if(event.target==this)check_all();
		for(var i=0,n=sexs.length;i<n;i++)
			if(sexs[i].checked)
				sex=sexs[i].value;
	};
password1.onfocus=password1.onkeyup=function(e)
{
	if(e==undefined)e=window.event;
	if(e.target==this)check_all(e);
	if(e.target==tijiao_button&&this.value=='')return jry_wb_beautiful_alert.alert("请填写完整信息","密码为空",function(){password1.focus();password1.style.border="5px solid #ff0000",password1.style.margin="0px 0px";}),false;
	if(password1.value!=''&&password1.value.length<8)
	{
		if(((new Date())-time1)>5000)
		{
			time1=new Date();
			jry_wb_beautiful_right_alert.alert("密码长度大于8位",2000,"auto","error");
		}
		password1.style.border="5px solid #ff0000",password1.style.margin="0px 0px";
		return false;
	}
	else
		password1.style.border="",password1.style.margin="",time1=0;
	return true;
};
password2.onfocus=password2.onkeyup=function(e)
{
	if(e==undefined)e=window.event;
	if(e.target==this)check_all(e);
	if(e.target==tijiao_button&&this.value=='')return jry_wb_beautiful_alert.alert("请填写完整信息","密码为空",function(){password2.focus();password2.style.border="5px solid #ff0000",password2.style.margin="0px 0px";}),false;
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
		return false;
	}
	else
		password2.style.border="",password2.style.margin="",time2=0;
	return true;
};
<?php if(constant('jry_wb_check_mail_switch')){ ?>
mail.onfocus=mail.onkeyup=function(e)
{
	if(e==undefined)e=window.event;
	if(e.target==this)check_all(e);
	if(e.target==tijiao_button&&this.value=='')return jry_wb_beautiful_alert.alert("请填写完整信息","邮箱为空",function(){mail.focus();mail.style.border="5px solid #ff0000",mail.style.margin="0px 0px";}),false;
	if(mail.value!=""&&(jry_wb_test_mail(mail.value)==false))
	{
		if(((new Date())-time6)>5000)
		{
			time6=new Date();
			jry_wb_beautiful_right_alert.alert("邮箱错误",2000,"auto","error");
		}	
		mail.style.border="5px solid #ff0000",mail.style.margin="0px 0px";
	}
	else
		mail.style.border="",mail.style.margin="",time6=0;
	return true;
};	
<?php } ?>	
<?php if(constant('jry_wb_check_tel_switch')){ ?>	
tel.onfocus=tel.onkeyup=function(e)
{
	if(e==undefined)e=window.event;
	if(e.target==this)check_all(e);
	if(e.target==tijiao_button&&this.value=='')return jry_wb_beautiful_alert.alert("请填写完整信息","电话为空",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";}),false;
	if(tel.value!=""&&(jry_wb_test_phone_number(tel.value)==false))
	{
		if(((new Date())-time3)>5000)
		{
			time3=new Date();
			jry_wb_beautiful_right_alert.alert("电话错误",2000,"auto","error");
		}	
		tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";
		return false;
	}
	else
	{
		tel.style.border="",tel.style.margin="";
		<?php if(constant('jry_wb_check_tel_switch')&&constant('jry_wb_short_message_switch')!=''){ ?>	
		if(tel.value!="")
			tr_tel.style.display="",window.onresize(),phonecode.style.width=td2.clientWidth-document.getElementById('phonecode_button').clientWidth-25,time3=0;
		<?php } ?>
	}
	return true;
};
<?php } ?>
vcode.onfocus=vcode.onkeyup=function(e)
{
	if(e==undefined)e=window.event;
	if(e.target==this)check_all(e);
	if(e.target==tijiao_button&&this.value=='')return jry_wb_beautiful_alert.alert("请填写完整信息","验证码为空",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";}),false;
	if(vcode.value!=''&&vcode.value.length!=4)
	{
		if(((new Date())-time4)>5000)
		{
			time4=new Date();
			jry_wb_beautiful_right_alert.alert("4位验证码",2000,"auto","error");
			
		}
		vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";
		return false;
	}
	else
		vcode.style.border="",vcode.style.margin="",time4=0;
	return true;
};
<?php foreach($jry_wb_config_user_extern_message as $one)if($one['type']!='cutter'){
if($one['type']=='check')
{ ?>
for(var i=0,n=<?php echo $one['key']; ?>s.length;i<n;i++)
	<?php echo $one['key']; ?>s[i].onclick=function(){if(event.target==this)check_all();};
<?php }else {?>
<?php echo $one['key']; ?>.onfocus=<?php echo $one['key']; ?>.onkeyup=<?php echo $one['key']; ?>.onchenge=<?php echo $one['key']; ?>.onblur=function(e)
{
	if(e==undefined)e=window.event;
	if(e.target==this)check_all(e);
	if(e.target==tijiao_button&&this.value=='')return jry_wb_beautiful_alert.alert("请填写完整信息","<?php echo $one['name']; ?>为空",function(){<?php echo $one['key']; ?>.focus();<?php echo $one['key']; ?>.style.border="5px solid #ff0000",<?php echo $one['key']; ?>.style.margin="0px 0px";}),false;
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
	<?php
	}
	if($one['connect']!=NULL)
	{
		foreach($one['connect'] as $connect)
		{
			if($one['type']=='china_id'&&$connect=='sex')
			{ ?>
				if(<?php echo $one['key']; ?>.value!=""&&(jry_wb_get_sex_by_china_id_card(<?php echo $one['key']; ?>.value)!=sex))
				{
					jry_wb_beautiful_right_alert.alert("<?php echo $one['name']; ?>与性别不符",2000,"auto","error");
					<?php echo $one['key']; ?>.style.border="5px solid #ff0000",<?php echo $one['key']; ?>.style.margin="0px 0px";
					return false;
				}
				else
					<?php echo $one['key']; ?>.style.border="",<?php echo $one['key']; ?>.style.margin="";
			<?php }else{ ?>
				if(<?php echo $one['key']; ?>.value!=""&&(<?php echo $one['key']; ?>.value==<?php echo $connect.($connect=='sex'?'':'.value'); ?>))
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
<?php } ?>
	return true;
};
<?php }} ?>
function check_all(e)
{
	save();
	if(e==undefined)e=window.event;
	if(e.target!=namee) if(!namee.onkeyup(e))return false;
	if(e.target!=password1)if(!password1.onkeyup(e))return false;
	if(e.target!=password2)if(!password2.onkeyup(e))return false;
	<?php if(constant('jry_wb_check_tel_switch')){ ?>if(e.target!=tel)if(!tel.onkeyup(e))return false;<?php } ?>
	<?php if(constant('jry_wb_check_mail_switch')){ ?>if(e.target!=mail)if(!mail.onkeyup(e))return false;<?php } ?>
<?php foreach($jry_wb_config_user_extern_message as $one)if($one['type']!='check'&&$one['type']!='cutter'){ ?>
	if(e.target!=<?php echo $one['key']; ?>)if(!<?php echo $one['key']; ?>.onkeyup(e))return false;
<?php } ?>
	if(e.target!=vcode)if(!vcode.onkeyup(e))return false;
<?php if(constant('jry_wb_check_tel_switch')&&constant('jry_wb_short_message_switch')!=''){ ?>	
	if(e.target==tijiao_button&&phonecode.value=='')return jry_wb_beautiful_alert.alert("请填写完整信息","电话验证码为空",function(){phonecode.focus();phonecode.style.border="5px solid #ff0000",phonecode.style.margin="0px 0px";}),false;
<?php } ?>
	
	return true;
}
setInterval(function(){save();},1000);
<?php if(false){ ?></script><?php } ?>	