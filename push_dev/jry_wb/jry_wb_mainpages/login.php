<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_tp_github_oauth_config.php");
	include_once("../jry_wb_configs/jry_wb_tp_gitee_oauth_config.php");
	include_once("../jry_wb_configs/jry_wb_tp_mi_oauth_config.php");	
	include_once("../jry_wb_configs/jry_wb_tp_qq_oauth_config.php");
	session_start();
	if((!JRY_WB_HOST_SWITCH)&&$_COOKIE['password']!=NULL&&$_COOKIE['id']!=NULL&&(!$_GET['debug']))
	{
		$conn=jry_wb_connect_database();
		$host_conn=jry_wb_connect_host_database();
		$q='SELECT * FROM '.constant('jry_wb_host_database_general').'users
			LEFT JOIN '.constant('jry_wb_host_database_general').'login  ON ('.constant('jry_wb_host_database_general_prefix').'users.id = '.constant('jry_wb_host_database_general_prefix')."login.id)
			where ".constant('jry_wb_host_database_general_prefix')."users.id =? AND device=? LIMIT 1";
		$st = $host_conn->prepare($q);
		$st->bindParam(1,intval((isset($_COOKIE['id'])?$_COOKIE['id']:-1)));
		$st->bindParam(2,jry_wb_get_device(true));
		$st->execute();
		$data=$st->fetchAll();
		if(count($data)!=0)
		{
			$user=$data[0];
			if($user['password']==$_COOKIE['password'])
			{
				$q="SELECT * FROM ".JRY_WB_DATABASE_GENERAL."users where id=?;";
				$st = $conn->prepare($q);
				$st->bindParam(1,$user['id']);
				$st->execute();
				if(count($jry_wb_login_user=$st->fetchAll())==0)
				{
					jry_wb_print_head("登录",false,false,true);		
					?><script language=javascript>
						jry_wb_beautiful_alert.alert("请联系开发组","");
					</script>
					<h1>请联系开发组</h1>
					<h2>QQ:1176402460</h2>
					<h2>邮箱:lijunyandeyouxiang@163.com</h2><?php
					exit();
				}
				$jry_wb_login_user=$jry_wb_login_user[0];
				$type=8;
				require(JRY_WB_LOCAL_DIR."/jry_wb_mainpages/do_login.php");
				exit();
			}
		}
	}	
	jry_wb_print_head("登录",false,false,true);
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>	
	<?php jry_wb_print_href("login","active");?>
	<?php jry_wb_print_href('add_user',"");?>
</div>
<div id='area'></div>
<script language="javascript">
function jry_wb_login(area)
{
	jry_wb_include_css('mainpages/login');
	area.innerHTML=area.style=area.className='';
	time1=0;
	time2=0;
	time3=0;
	var body		=document.createElement('table')	;area	.appendChild(body)		;body		.classList.add('jry_wb_login')	;body.setAttribute('align','center');
	var tr			=document.createElement('tr')		;body	.appendChild(tr)		;
	var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('type')			;td.innerHTML='登录方式';
	var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('type_v')		;
	var types		=[];
	var type		=0;
	var input		=document.createElement('input')	;td		.appendChild(input)		;input		.type='radio'					;types.push(input)	;input.name='type';input.value=0;input.onclick=function(){type=0;key_td.innerHTML='ID'		;};input.setAttribute('checked','');input.focus();
	var span		=document.createElement('span')		;td		.appendChild(span)		;span		.classList.add('id')			;span.innerHTML='id';
	var input		=document.createElement('input')	;td		.appendChild(input)		;input		.type='radio'					;types.push(input)	;input.name='type';input.value=1;input.onclick=function(){type=1;key_td.innerHTML='手机'	;};
	var span		=document.createElement('span')		;td		.appendChild(span)		;span		.classList.add('tel')			;span.innerHTML='手机';
	var input		=document.createElement('input')	;td		.appendChild(input)		;input		.type='radio'					;types.push(input)	;input.name='type';input.value=2;input.onclick=function(){type=2;key_td.innerHTML='邮箱'	;};
	var span		=document.createElement('span')		;td		.appendChild(span)		;span		.classList.add('mail')			;span.innerHTML='邮箱';
	var tr			=document.createElement('tr')		;body	.appendChild(tr)		;
	var key_td		=document.createElement('td')		;tr		.appendChild(key_td)	;key_td		.classList.add('key')			;key_td.innerHTML='ID';
	var key_vtd		=document.createElement('td')		;tr		.appendChild(key_vtd)	;key_vtd	.classList.add('key_v')			;
	var id			=document.createElement('input')	;key_vtd.appendChild(id)		;id			.type='text'					;
	id.onfocus=id.onkeyup=function()
	{
		var type=0;
		for(var i=0,n=types.length;i<n;i++)
			if(types[i].checked)
				type=types[i].value;
		if(id.value!=""&&(type==1)&&(!jry_wb_test_phone_number(id.value)))
		{
			if(((new Date())-time3)>5000)
			{
				time3=new Date();
				jry_wb_beautiful_right_alert.alert("错误的电话格式",2000,"auto","error");
			}
			id.classList.add('error')
		}
		else if(id.value!=""&&(type==2)&&(!jry_wb_test_mail(id.value)))
		{
			if(((new Date())-time3)>5000)
			{
				time3=new Date();
				jry_wb_beautiful_right_alert.alert("错误的邮箱格式",2000,"auto","error");
			}
			id.classList.add('error')
		}
		else
			id.classList.remove('error'),time3=0;
	};
	var tr			=document.createElement("tr")		;body	.appendChild(tr)		;
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('password')		;td.innerHTML='密码';
	var td			=document.createElement("td")		;tr		.appendChild(td)		;td			.classList.add('password_v')	;
	var password	=document.createElement("input")	;td		.appendChild(password)	;password	.type='password'				;
	password.onfocus=password.onkeyup=function()
	{
		if(password.value!=''&&password.value.length<8)
		{
			if(((new Date())-time1)>5000)
			{
				time1=new Date();
				jry_wb_beautiful_right_alert.alert("密码长度大于8位",2000,"auto","error");
			}
			password.classList.add('error');
		}
		else
			password.classList.remove('error'),time1=0;
	};
	var tr			=document.createElement('tr')		;body	.appendChild(tr)		;
	var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('vcode')			;td.innerHTML='验证码';
	var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('vcode_v')		;
	var vcode		=document.createElement('input')	;td		.appendChild(vcode)		;vcode		.type='text'					;
	vcode.onfocus=vcode.onkeyup=function()
	{
		if(vcode.value!=''&&vcode.value.length!=4)
		{
			if(((new Date())-time2)>5000)
			{
				time2=new Date();
				jry_wb_beautiful_right_alert.alert("4位验证码",2000,"auto","error");
			}
			vcode.classList.add('error');
		}
		else
			vcode.classList.remove('error'),time2=0;
	};
	var img			=document.createElement('img')		;td		.appendChild(img)		;img.onclick=function(){img.src=jry_wb_message.jry_wb_host+'jry_wb_tools/jry_wb_vcode.php?r='+Math.random()};img.onclick();
	var tr			=document.createElement('tr')		;body	.appendChild(tr)		;
	var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('buttons')		;td.setAttribute('colspan',2);
	var button		=document.createElement('button')	;td		.appendChild(button)	;button		.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');button.innerHTML='登录';
	button.onclick=function()
	{ 
		for(var i=0,n=types.length;i<n;i++)
			if(types[i].checked)
				type=types[i].value;
		if(id.value=="")
		{
			jry_wb_beautiful_alert.alert("请填写完整信息","ID为空",function(){id.focus();id.classList.add('error')});
			return;
		}
		if((type==1)&&(!jry_wb_test_phone_number(id.value)))
		{
			jry_wb_beautiful_alert.alert("请填写正确信息","错误的电话格式",function(){id.focus();id.classList.add('error')});		
			return;
		}
		else if((type==2)&&(!jry_wb_test_mail(id.value)))
		{
			jry_wb_beautiful_alert.alert("请填写正确信息","错误的邮箱格式",function(){id.focus();id.classList.add('error')});				
			return;
		}	
		if(password.value=="")
		{
			jry_wb_beautiful_alert.alert("请填写完整信息","密码为空",function(){password.focus();password.classList.add('error');});
		   return;
		}
		if(vcode.value=="")
		{
			jry_wb_beautiful_alert.alert("请填写完整信息","验证码为空",function(){vcode.focus();vcode.classList.add('error');});
			return;
		}
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
				if(data.reason==100005)
					jry_wb_beautiful_alert.alert("登录失败","请检查验证码大小写",function(){vcode.focus();vcode.classList.add('error');});
				else if(data.reason==100002)
					jry_wb_beautiful_alert.alert("登录失败","请检查验证码,点击图片可以换一张哦",function(){vcode.focus();vcode.classList.add('error');});
				else if(data.reason==100007)
					jry_wb_beautiful_alert.alert("登录失败","不存在的账号",function(){id.classList.add('error');id.focus();});
				else if(data.reason==100006)
					jry_wb_beautiful_alert.alert("登录失败","密码错误",function(){password.classList.add('error');password.focus();});
			}
		},[{'name':'id','value':id.value},{'name':'password','value':password.value},{'name':'vcode','value':vcode.value},{'name':'type','value':type}]);	
	};
	jry_wb_set_shortcut(jry_wb_keycode_enter,button.onclick);
<?php if(JRY_WB_OAUTH_SWITCH){ ?>	
	var tr			=document.createElement('tr')		;body	.appendChild(tr)		;
	var td			=document.createElement('td')		;tr		.appendChild(td)		;td			.classList.add('tpin')		;td.setAttribute('colspan',2);
<?php if($JRY_WB_TP_QQ_OAUTH_CONFIG!=null){?>
	var span=document.createElement('span');td.appendChild(span);span.classList.add('jry_wb_icon','jry_wb_icon_qq','qq');
	span.onclick=function()
	{
		newwindow=window.open("jry_wb_qq_oauth.php","TencentLogin","width=450,height=700,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");	
		var timer=setInterval(function(){
			if(newwindow.closed)
			{
				clearInterval(timer);
				if(jry_wb_cookie.get('id')!=undefined&&jry_wb_cookie.get('id')!=-1)			
					window.location.href='<?php if($_SESSION['url']!='')echo $_SESSION['url'];else echo jry_wb_print_href("home","","",1)?>';
			}
		},500);
	};
<?php  }?>
<?php if(JRY_WB_TP_GITEE_OAUTH_CLIENT_ID!=''){?>
	var span=document.createElement('span');td.appendChild(span);span.classList.add('jry_wb_icon','jry_wb_icon_mayun','gitee');
	span.onclick=function()
	{
		newwindow=window.open("jry_wb_gitee_oauth.php","GiteeLogin","width=1200,height=700,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");	
		var timer=setInterval(function(){
			if(newwindow.closed)
			{
				clearInterval(timer);
				if(jry_wb_cookie.get('id')!=undefined&&jry_wb_cookie.get('id')!=-1)			
					window.location.href='<?php if($_SESSION['url']!='')echo $_SESSION['url'];else echo jry_wb_print_href("home","","",1)?>';
			}
		},500);			
	};
<?php } ?>
<?php if(JRY_WB_TP_GITHUB_OAUTH_CLIENT_ID!=''){?>
	var span=document.createElement('span');td.appendChild(span);span.classList.add('jry_wb_icon','jry_wb_icon_git','github');
	span.onclick=function()
	{	
		newwindow=window.open("jry_wb_github_oauth.php","GithubLogin","width=450,height=700,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");	
		var timer=setInterval(function(){
			if(newwindow.closed)
			{
				clearInterval(timer);
				if(jry_wb_cookie.get('id')!=undefined&&jry_wb_cookie.get('id')!=-1)
					window.location.href='<?php if($_SESSION['url']!='')echo $_SESSION['url'];else echo jry_wb_print_href("home","","",1)?>';
			}
		},500);
	};
<?php } ?>
<?php if(JRY_WB_TP_MI_OAUTH_CLIENT_ID!=''){?>
	var span=document.createElement('span');td.appendChild(span);span.classList.add('jry_wb_icon','jry_wb_icon_mi','mi');
	span.onclick=function()
	{
		newwindow=window.open("jry_wb_mi_oauth.php","MiLogin","width=450,height=700,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");	
		var timer=setInterval(function(){
			if(newwindow.closed)
			{
				clearInterval(timer);
				if(jry_wb_cookie.get('id')!=undefined&&jry_wb_cookie.get('id')!=-1)
					window.location.href='<?php if($_SESSION['url']!='')echo $_SESSION['url'];else echo jry_wb_print_href("home","","",1)?>';
			}
		},500);		
	};
<?php } ?>	
<?php } ?>
}
jry_wb_login(document.getElementById('area'));
</script>
<?php
	jry_wb_print_tail();
?>
