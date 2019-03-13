<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_tp_github_oauth_config.php");
	include_once("../jry_wb_configs/jry_wb_tp_gitee_oauth_config.php");
	include_once("../jry_wb_configs/jry_wb_tp_mi_oauth_config.php");	
	include_once("../jry_wb_configs/jry_wb_tp_qq_oauth_config.php");
	if((!constant('jry_wb_host_switch'))&&$_COOKIE['password']!=NULL&&$_COOKIE['id']!=NULL&&(!$_GET['debug']))
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
			$users=$data[0];
			if($users['password']==$_COOKIE['password'])
			{
				$q="SELECT * FROM ".constant('jry_wb_database_general')."users where id=?;";
				$st = $conn->prepare($q);
				$st->bindParam(1,$users['id']);
				$st->execute();
				if(count($st->fetchAll())==0)
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
				$type=8;
				require(constant('jry_wb_local_dir')."/jry_wb_mainpages/do_login.php");
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
<div align="center">
	<table  border="1" cellspacing="0" cellpadding="0">
		<tr>
			<td width="200">
				<h55>类型</h55>
			</td>
			<td width="400">
				<input type="radio" name="type" value="0" checked />
				<h56>id&nbsp;</h56>
				<input type="radio" name="type" value="1"/>
				<h56>手机号&nbsp;</h56>
				<input type="radio" name="type" value="2"/>
				<h56>邮箱</h56>
			</td>
		</tr>
		<tr>
			<td> 
				<h55>登录账号</h55>
			</td>
			<td>
					<input name="id" type="text" class="h56" id="id"/>
			</td>
		</tr>
		<tr>
			<td>
				<h55>密码</h55>
			</td>
			<td>
				<input name="password" type="password" id="password" class="h56" />
			</td>
		</tr>
		<tr>
			<td>
				<h55>验证码</h55>
			</td>
			<td>
				<input name="vcode" type="text" id="vcode" class="h56" size="4"/>
				<img id="vcodesrc" src="<?php echo jry_wb_print_href("verificationcode",0,"",1);?>" onload="window.onresize()" onclick="document.getElementById('vcodesrc').src='<?php echo jry_wb_print_href("verificationcode",0,"",1);?>?r='+Math.random()"/>
			</td>
		</tr>
		<tr>
			<td colspan="2">
                <div align="center">
						 <button type="button"  onclick="check();" class="jry_wb_button jry_wb_button_size_big jry_wb_color_ok">登录</button>
                </div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
                <div align="center">
					<?php if($jry_wb_tp_qq_oauth_config!=null){?><span class="jry_wb_icon jry_wb_icon_qq" onclick='qqlogin()' style='color:#36AAE8;font-size:30px;' ></span><?php  }?>
					<?php if(constant('jry_wb_tp_gitee_oauth_config_client_id')!=''){?><span class="jry_wb_icon jry_wb_icon_mayun" onclick='giteelogin()' style='color:rgb(216, 30, 6);font-size:30px;' ></span><?php  }?>
					<?php if(constant('jry_wb_tp_github_oauth_config_client_id')!=''){?><span class="jry_wb_icon jry_wb_icon_git" onclick='gayhublogin()' style='color:#00ff00;font-size:30px;' ></span><?php  }?>
					<?php if(constant('jry_wb_tp_mi_oauth_config_client_id')!=''){?><span class="jry_wb_icon jry_wb_icon_xiaomi" onclick='milogin()' style='color:rgb(253, 88, 62);font-size:30px;' ></span><?php  }?>
                </div>
			</td>
		</tr>		
	</table>
	<a target="_blank" href="<?php echo jry_wb_print_href("forget",'','',true);?>">老子把账户密码忘了</a><br>
	<a target="_blank" href="<?php echo jry_wb_print_href("xieyi",'','',true);?>">登录即代表同意《蒟蒻云用户协议》</a><br>
	<a target="_blank" href="<?php echo jry_wb_print_href("zhinan",'','',true);?>">用户指南</a><br>
	<a href>验证码区分大小写</a><br>
	<a href>问题或建议点边上的小虫子</a>	
</div>
<script language="javascript">
function check()
{ 
	var id= document.getElementById("id").value;
	var password= document.getElementById("password").value;
	var vcode= document.getElementById("vcode").value;
	var types=document.getElementsByName('type');
	var type=0;
	for(var i=0,n=types.length;i<n;i++)
		if(types[i].checked)
			type=types[i].value;
	if(id=="")
    {
        jry_wb_beautiful_alert.alert("请填写完整信息","ID为空",function()
		{
			document.getElementById("id").focus();
		});
        return;
    }
	if(password=="")
    {
		jry_wb_beautiful_alert.alert("请填写完整信息","密码为空",function()
		{
			document.getElementById("password").focus();
		});
       return;
    }
	if(vcode=="")
    {
        jry_wb_beautiful_alert.alert("请填写完整信息","验证码为空",function()
		{
			document.getElementById("vcode").focus();
		});
        return;
    }
	jry_wb_ajax_load_data('do_login.php',function(data)
	{
		data=JSON.parse(data);
		jry_wb_loading_off();
		if(data.state==1)
		{
			jry_wb_beautiful_alert.alert("登录成功",'距上次登录'+data.message.hour+'小时'+data.message.minute+'分钟'+(data.message.green_money==null?'':'<br>随机奖励绿币'+data.message.green_money),function()
			{
				window.location.href='<?php if($_SESSION['url']!='')echo $_SESSION['url'];else echo jry_wb_print_href("home","","",1)?>';
			});
			return ;
		}
		else if(data.state==-1)
		{
			jry_wb_beautiful_alert.alert("登录失败","请检查验证码大小写");
			document.getElementById("vcode").focus();
		}
		else if(data.state==-2)
		{
			jry_wb_beautiful_alert.alert("登录失败","不存在的账号");
			document.getElementById("id").focus();
		}
		else if(data.state==-3)
		{
			jry_wb_beautiful_alert.alert("登录失败","密码错误");
			document.getElementById("password").focus();
		}
	},[{'name':'id','value':id},{'name':'password','value':password},{'name':'vcode','value':vcode},{'name':'type','value':type}]);	
}
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
document.getElementById('id').focus();
function qqlogin()
{
	newwindow=window.open("jry_wb_qq_oauth.php","TencentLogin","width=450,height=320,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");	
	var timer=setInterval(function(){
		if(newwindow.closed)
		{
			clearInterval(timer);
			window.location.href='<?php if($_SESSION['url']!='')echo $_SESSION['url'];else echo jry_wb_print_href("home","","",1)?>';
		}
	},500);
}
function gayhublogin()
{
	newwindow=window.open("jry_wb_github_oauth.php","GithubLogin","width=450,height=700,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");	
	var timer=setInterval(function(){
		if(newwindow.closed)
		{
			clearInterval(timer);
			window.location.href='<?php if($_SESSION['url']!='')echo $_SESSION['url'];else echo jry_wb_print_href("home","","",1)?>';
		}
	},500);	
}
function giteelogin()
{
	newwindow=window.open("jry_wb_gitee_oauth.php","GiteeLogin","width=1200,height=700,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");	
	var timer=setInterval(function(){
		if(newwindow.closed)
		{
			clearInterval(timer);
			window.location.href='<?php if($_SESSION['url']!='')echo $_SESSION['url'];else echo jry_wb_print_href("home","","",1)?>';
		}
	},500);	
}
function milogin()
{
	newwindow=window.open("jry_wb_mi_oauth.php","MiLogin","width=450,height=700,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");	
	var timer=setInterval(function(){
		if(newwindow.closed)
		{
			clearInterval(timer);
			window.location.href='<?php if($_SESSION['url']!='')echo $_SESSION['url'];else echo jry_wb_print_href("home","","",1)?>';
		}
	},500);
}
</script>
<?php
	jry_wb_print_tail();
?>
