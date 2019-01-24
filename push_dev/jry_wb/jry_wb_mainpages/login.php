<?php
	include_once("../tools/jry_wb_includes.php");
	if((!constant('jry_wb_host_switch'))&&$_COOKIE['password']!=NULL&&$_COOKIE['id']!=NULL&&(!$_GET['debug']))
	{
		$conn=jry_wb_connect_database();
		$q='SELECT * FROM '.constant('jry_wb_host_database_general').'users
			LEFT JOIN '.constant('jry_wb_host_database_general').'login  ON ('.constant('jry_wb_host_database_general_prefix').'users.id = '.constant('jry_wb_host_database_general_prefix')."login.id)
			where ".constant('jry_wb_host_database_general_prefix')."users.id =? AND ip=? AND device=? LIMIT 1";
		$st = $conn->prepare($q);
		$st->bindParam(1,intval((isset($_COOKIE['id'])?$_COOKIE['id']:-1)));
		$st->bindParam(2,$_SERVER['REMOTE_ADDR']);
		$st->bindParam(3,jry_wb_get_device(true));
		$st->execute();
		foreach($st->fetchAll()as $user);
		if($user['password']==$_COOKIE['password'])
		{
			$q="SELECT * FROM ".constant('jry_wb_database_general')."users where id=?;";
			$st = $conn->prepare($q);
			$st->bindParam(1,$user['id']);
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
			setcookie('id',$user['id'],time()+constant('logintime'),'/',jry_wb_get_domain(),NULL,true);
			setcookie('password',$user['password'],time()+constant('logintime'),'/',jry_wb_get_domain(),NULL,true);
			$q="update ".constant('jry_wb_database_general')."users set logdate=?,lasttime=? where id=?;";
			$st = $conn->prepare($q);
			$st->bindParam(1,jry_wb_get_time());
			$st->bindParam(2,jry_wb_get_time());
			$st->bindParam(3,$user['id']);
			$st->execute();
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'login where id=? AND device=? AND code=? AND ip=?');
			$st->bindParam(1,$user['id']);
			$st->bindParam(2,jry_wb_get_device(true));
			$st->bindParam(3,$_COOKIE['code']);
			$st->bindParam(4,$_SERVER['REMOTE_ADDR']);
			$st->execute();
			$all=$st->fetchAll();		
			setcookie('id',$user['id'],time()+constant('logintime'),'/',jry_wb_get_domain(),NULL,true);
			setcookie('password',$user['password'],time()+constant('logintime'),'/',jry_wb_get_domain(),NULL,true);
			if(count($all)!=0)
			{
				setcookie('code',$all[0]['code'],time()+constant('logintime'),'/',$_SERVER['HTTP_HOST'],NULL,true);
				$st = $conn->prepare("update ".constant('jry_wb_database_general')."login SET time=? where id=? AND ip=? AND device=? AND code=? AND browser=?");
				$st->bindParam(1,jry_wb_get_time());	
				$st->bindParam(2,$user['id']);
				$st->bindParam(3,$_SERVER['REMOTE_ADDR']);
				$st->bindParam(4,jry_wb_get_device(true));
				$st->bindParam(5,$_COOKIE['code']);
				$st->bindParam(6,jry_wb_get_browser(true));
				$st->execute();
			}
			else
			{
				$srcstr='123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWYZ';
				$code='';
				mt_srand();
				for ($i = 0; $i < 100; $i++) 
					$code.=$srcstr[mt_rand(0, 50)];
				$code.=md5(jry_wb_get_time()).md5($user['mail'].$user['id']);
				setcookie('code',$code,time()+constant('logintime'),'/',$_SERVER['HTTP_HOST'],NULL,true);
				$st = $conn->prepare('INSERT INTO '.constant('jry_wb_database_general')."login (id,ip,time,device,code,browser) VALUES(?,?,?,?,?,?)");
				$st->bindParam(1,$user['id']);
				$st->bindParam(2,$_SERVER['REMOTE_ADDR']);
				$st->bindParam(3,jry_wb_get_time());	
				$st->bindParam(4,jry_wb_get_device(true));				
				$st->bindParam(5,$code);
				$st->bindParam(6,jry_wb_get_browser(true));
				$st->execute();
			}
			jry_wb_print_head("登录",false,false,true);
		?><script language=javascript>
			jry_wb_beautiful_alert.alert("登录成功","",function()
			{
				jry_wb_cache.delete_all();
				jry_wb_cache.set('jry_wb_login_user_id',parseInt("<?php  echo $user['id'];?>"));
				window.location.href='<?php if($_SESSION['url']!='')echo $_SESSION['url'];else echo jry_wb_print_href("home","","",1)?>';
			});
		</script><?php
			$jry_wb_login_user['id']=$user['id'];
			jry_wb_echo_log(constant('jry_wb_log_type_login'),'by other');
			exit();
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
</script>
<?php
	jry_wb_print_tail();
?>
