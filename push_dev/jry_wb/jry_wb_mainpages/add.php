<?php
	include_once("../tools/jry_wb_includes.php");
	jry_wb_print_head("注册",false,false,true);
	if(!constant('jry_wb_host_switch')&&$_GET['debug']!=1)
	{
		?><script>window.location="<?php echo constant('jry_wb_host_addr')?>mainpages/add.php"</script><?php
		exit();
	}
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>	
	<?php jry_wb_print_href("login","");?>
	<?php jry_wb_print_href('add_user',"active");?>
</div>
<body>
<div align="center" >
	<table  border="1" cellspacing="0" cellpadding="0">
		<tr>
			<td width="200" id="td1">
				<h55>昵称</h55>
			</td>
			<td width="300" id="td2">
				<input name="name" type="text" id="name" class="h56"/>
			</td>
		</tr>
		<tr>
			<td>
				<h55>密码</h55>
			</td>
			<td>
				<input name="password1" type="password" class="h56" id="password1"/>
			</td>
		</tr>
		<tr>
			<td>
				<h55>再输密码</h55>
			</td>
			<td>
				<input name="password2" type="password" id="password2" class="h56"/>
			</td>
		</tr>
		<tr>
			<td>
				<h55>性别</h55>
			</td>
			<td>
				<input type="radio" name="sex" value="1" checked/>
				<h56>男</h56>
				<input style="margin-left:20px;" type="radio" name="sex" value="0" />
				<h56>女</h56>
				<input style="margin-left:20px;" type="radio" name="sex" value="2" />
				<h56>女装大佬</h56>		
			</td>
		</tr>
		
		<tr>
<?php if(constant('jry_wb_check_tel_switch')){ ?>
			<td width="200">
				<h55>电话</h55>
			</td>
			<td width="300">
				<input name="tel" type="text" id="tel" class="h56"/>
			</td>
		</tr>
<?php } ?>			
		<tr>
			<td>
				<h55>验证码</h55>
			</td>
			<td>
				<input name="vcode" type="text" id="vcode" class="h56" style="width:200px"/>
				<img id="vcodesrc" src="<?php echo jry_wb_print_href("verificationcode",0,"",1);?>" onload="window.onresize()" onclick="document.getElementById('vcodesrc').src='<?php echo jry_wb_print_href("verificationcode",0,"",1);?>?r='+Math.random()"/>
			</td>
		</tr>
<?php if(constant('jry_wb_check_tel_switch')){ ?>
		<tr id="tr_tel">
			<td>
				<h55>电话验证码</h55>
			</td>
			<td>
				<input name="phonecode" type="text" id="phonecode" class="h56" size="4" onclick="";/>
				<button id="button" name="button" class="jry_wb_button jry_wb_button_size_middle jry_wb_color_ok" type="button" onclick="check_tel()">获取验证码</button>
		</tr>
<?php } ?>
		<tr>
			<td colspan="2">
			<div align="center">
				<button onclick="return check();" class="jry_wb_button jry_wb_button_size_big jry_wb_color_ok"/>提交</button>
			</div>
			</td>
		</tr>
	</table>
	<a target="_blank" href="<?php echo jry_wb_print_href("xieyi",'','',true);?>">注册即代表同意《蒟蒻云用户协议》</a><br>
	<a target="_blank" href="<?php echo jry_wb_print_href("zhinan",'','',true);?>">用户指南</a><br>
	<a href>验证码区分大小写</a><br>
	<a href>问题或建议点边上的小虫子</a>
</div>
<script language="javascript">
	namee=document.getElementById('name');
	td1=document.getElementById('td1');
	td2=document.getElementById('td2');
	tel=document.getElementById('tel');
	sexs=document.getElementsByName('sex');
	password1=document.getElementById('password1');
	password2=document.getElementById('password2');
	vcode=document.getElementById('vcode');
	phonecode=document.getElementById('phonecode');
	vcodesrc=document.getElementById('vcodesrc');
	tr_tel=document.getElementById('tr_tel');
	vcode.style.width=td2.clientWidth-25-vcodesrc.clientWidth;
	var get=JSON.parse(jry_wb_cache.get('login'));
	if(get!=null)
	{
		namee.value=get.name;
		tel.value=get.tel;
		password1.value=get.password1;
		password2.value=get.password2;
		phonecode.value=get.phonecode;
		for(var i=0,n=sexs.length;i<n;i++)
			if(sexs[i].value==get.sex)
				sexs[i].click();
	}
	delete get;
	if(tel.value=="")
		tr_tel.style.display="none";
	if(namee.value=="")
		namee.focus();
	else if(tel.value=="")
		tel.focus();
	else if(password1.value=="")
		password1.focus();
	else if(vcode.value=="")
		vcode.focus();
	else if(phonecode.value=="")
		phonecode.focus();
	function check()
	{ 
		if(namee.value=="")
			return jry_wb_beautiful_alert.alert("请填写完整信息","名字为空",function(){namee.focus();namee.style.border="5px solid #ff0000",namee.style.margin="0px 0px";});
		else if(vcode.value=="")
			return jry_wb_beautiful_alert.alert("请填写完整信息","验证码为空",function(){vcode.focus();vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";});
		else if(password1.value=="")
			return jry_wb_beautiful_alert.alert("请填写完整信息","密码为空",function(){password1.focus();password1.style.border="5px solid #ff0000",password1.style.margin="0px 0px";});
		else if(password2.value=="")
			return jry_wb_beautiful_alert.alert("请填写完整信息","密码为空",function(){password2.focus();password2.style.border="5px solid #ff0000",password2.style.margin="0px 0px";});
		else if(password1.value.length<8)
			return jry_wb_beautiful_alert.alert("请填写正确信息","密码太短",function(){password1.focus();password1.style.border="5px solid #ff0000",password1.style.margin="0px 0px";});
		else if(password1.value!=password2.value)
			return jry_wb_beautiful_alert.alert("请填写正确信息","密码不同",function(){password2.focus();password2.style.border="5px solid #ff0000",password2.style.margin="0px 0px";});
		<?php if(constant('jry_wb_check_tel_switch')){ ?>
		else if(tel.value!=""&&jry_wb_test_phone_number(tel.value)==false)
			return jry_wb_beautiful_alert.alert("请填写正确信息","电话错误",function(){tel.focus();tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";});
		else if(phonecode.value=='')
			return jry_wb_beautiful_alert.alert("请填写完整信息","电话验证码为空",function(){phonecode.focus();phonecode.style.border="5px solid #ff0000",phonecode.style.margin="0px 0px";});
		<?php } ?>
		var sex=0;
		for(var i=0,n=sexs.length;i<n;i++)
			if(sexs[i].checked)
				sex=sexs[i].value;
		jry_wb_ajax_load_data('do_add.php?debug=<?php  echo $_GET['debug']?>',function (data)
		{
			data=JSON.parse(data);
			jry_wb_loading_off();
			if(data.code)
			{
				jry_wb_beautiful_alert.alert('已注册,第一遍提醒','您的<h56>ID='+data.id+'</h56><br>ID是您登录本网站的<h56>唯一</h56>凭证<br>请牢记',function()
				{
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
				else if(data.reason==100003)
					jry_wb_beautiful_alert.alert("注册失败","电话验证码发送频繁");
				else if(data.reason==100010)
					jry_wb_beautiful_alert.alert("注册失败","手机验证码错误",function(){phonecode.focus();phonecode.style.border="5px solid #ff0000",phonecode.style.margin="0px 0px";});	
				else if(data.reason==100011)
					jry_wb_beautiful_alert.alert("注册失败","密码不同",function(){password2.focus();password2.style.border="5px solid #ff0000",password2.style.margin="0px 0px";});	
				else if(data.reason==100012)
					jry_wb_beautiful_alert.alert("注册失败","密码太短",function(){password1.focus();password1.style.border="5px solid #ff0000",password1.style.margin="0px 0px";});
				else if(data.reason==100013)
					jry_wb_beautiful_alert.alert("注册失败","昵称为空",function(){namee.focus();namee.style.border="5px solid #ff0000",namee.style.margin="0px 0px";});				
				}
		},[{'name':'name','value':namee.value},{'name':'tel','value':tel.value},{'name':'sex','value':sex},{'name':'password1','value':password1.value},{'name':'password2','value':password2.value},{'name':'vcode','value':vcode.value},{'name':'phonecode','value':phonecode.value}],true);
		return true;
	}
	<?php if(constant('jry_wb_check_tel_switch')){ ?>
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
	function save()
	{
		var sex=0;
		for(var i=0,n=sexs.length;i<n;i++)
			if(sexs[i].checked)
				sex=sexs[i].value;	
		jry_wb_cache.set('login',JSON.stringify({'name':namee.value,'tel':tel.value,'sex':sex,'password1':password1.value,'password2':password2.value,'phonecode':phonecode.value}));
	}
	namee.onfocus=namee.onkeyup=function()
	{
		save();		
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
	phonecode.onkeyup=function(){save();};
	for(var i=0,n=sexs.length;i<n;i++)
		sexs[i].onclick=function(){save();};
	password1.onfocus=password1.onkeyup=function()
	{
		save();
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
		save();
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
	tel.onfocus=tel.onkeyup=function()
	{
		save();
		if(tel.value!=""&&(jry_wb_test_phone_number(tel.value)==false))
		{
			if(((new Date())-time3)>5000)
			{
				time3=new Date();
				jry_wb_beautiful_right_alert.alert("电话错误",2000,"auto","error");
			}	
			tel.style.border="5px solid #ff0000",tel.style.margin="0px 0px";
		}
		else
		{
			tel.style.border="",tel.style.margin="";
			if(tel.value!="")
				tr_tel.style.display="",window.onresize();
		}			
	};
	vcode.onfocus=vcode.onkeyup=function()
	{
		save();
		if(vcode.value!=''&&vcode.value.length!=4)
		{
			if(((new Date())-time4)>5000)
			{
				time4=new Date();
				jry_wb_beautiful_right_alert.alert("4位验证码",2000,"auto","error");
			}
			vcode.style.border="5px solid #ff0000",vcode.style.margin="0px 0px";
		}
		else
			vcode.style.border="",vcode.style.margin="",time4=0;
	};
</script>
<?php jry_wb_print_tail()?>
