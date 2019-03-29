<?php
	include_once("../tools/jry_wb_includes.php");
	$conn=jry_wb_connect_database();
	$st = $conn->prepare('DELETE FROM '.constant('jry_wb_database_general').'mail_code where time<?');
	$st->bindParam(1,date("Y-m-d H:i:s",time()-12*60*60));
	$st->execute();	
	$st = $conn->prepare('DELETE FROM '.constant('jry_wb_database_general').'tel_code where time<?');
	$st->bindParam(1,date("Y-m-d H:i:s",time()-5*60));
	$st->execute();
	if($_GET['action']=='unlock')
	{
		jry_wb_print_head("",true,false,false,array(),false,false);
		if(jry_wb_send_mail('lijunyandeyouxiang@163.com',
		$_GET['id'].'申请解封',
		$_GET['id'].'在'.jry_wb_get_time().'申请解封<br>'.'请及时处理<br>'.
		'<a href="'.constant('jry_wb_host').'manage_system/index.php">点击进入管理员中心</a><br>'.
		'<a href="'.constant('jry_wb_host').'manage_system/do_user.php?action=unlock&id='.$_GET['id'].'">点击发送解封通知</a>'
		))
			echo json_encode(array('data'=>'OK'));
		else
			echo json_encode(array('data'=>'mail'));
		exit();
	}
	else if($_GET['action']=='send_tel')
	{
		try
		{
			jry_wb_print_head("",true,false,false,array(),false,false);
		}
		catch(jry_wb_exception $e)
		{
			echo $e->getMessage();
			exit();
		}
		if($_POST['vcode']!=$_SESSION['vcode']||$_POST['vcode']=='')
		{
			echo json_encode(array('code'=>false,'reason'=>100002));
			exit();
		}
		if($_POST['tel']==$jry_wb_login_user['tel'])
		{
			echo json_encode(array('code'=>false,'reason'=>100004));
			exit();
		}
		require_once "../tools/SignatureHelper.php";
		if(($code=gettelsmscode($_POST['tel']))==-1)
		{
			echo json_encode(array('code'=>false,'reason'=>100003));
			exit();
		}
		sendsms($_POST['tel'],Array ("code"=>$code),constant('jry_wb_short_message_aly_chenge'));
		echo json_encode(array('code'=>true));
		exit();
	}
	else if($_GET['action']=='setsonglist')
	{
		try
		{
			jry_wb_print_head("",true,false,false,array(),false,false);
		}
		catch(jry_wb_exception $e)
		{
			echo $e->getMessage();
			exit();
		}
		$q ="update ".constant('jry_wb_database_general')."users set background_music_list=?,lasttime=? where id=? ";
		$st = $conn->prepare($q);
		$st->bindParam(1,urldecode($_POST["data"]));	
		$st->bindParam(2,jry_wb_get_time());
		$st->bindParam(3,$jry_wb_login_user[id]);
		$st->execute();			
		echo json_encode(array('code'=>true,'data'=>$_POST['data']));
		exit();			
	}
	else if($_GET['action']=='trust')
	{
		try
		{
			jry_wb_print_head("",true,false,false,array(),false,false);
		}
		catch(jry_wb_exception $e)
		{
			echo $e->getMessage();
			exit();
		}	
		$st = $conn->prepare("update ".constant('jry_wb_database_general')."login set trust=1 where id=? AND code=?");
		$st->bindParam(1,$jry_wb_login_user['id']);
		$st->bindParam(2,$_COOKIE['code']);
		$st->execute();			
		echo json_encode(array('code'=>true));
		exit();		
	}
	else if($_GET['action']=='untrust')
	{
		try
		{
			jry_wb_print_head("",true,false,false,array(),false,false);
		}
		catch(jry_wb_exception $e)
		{
			echo $e->getMessage();
			exit();
		}
		$st = $conn->prepare("update ".constant('jry_wb_database_general')."login set trust=0 where id=? AND code=?");
		$st->bindParam(1,$jry_wb_login_user['id']);
		$st->bindParam(2,$_POST['code']);
		$st->execute();			
		echo json_encode(array('code'=>true));
		exit();		
	}
	else if($_GET['action']=='logout')
	{
		try
		{
			jry_wb_print_head("",true,false,false,array(),false,false);
		}
		catch(jry_wb_exception $e)
		{
			echo $e->getMessage();
			exit();
		}	
		$st = $conn->prepare("DELETE FROM ".constant('jry_wb_database_general')."login where id=? AND code=?");
		$st->bindParam(1,$jry_wb_login_user['id']);
		$st->bindParam(2,$_POST['code']);
		$st->execute();			
		echo json_encode(array('code'=>true));
		exit();		
	}	
	else if($_GET['action']=='chengehead')
	{
		try
		{
			jry_wb_print_head("",true,false,false,array(),false,false);
		}
		catch(jry_wb_exception $e)
		{
			echo $e->getMessage();
			exit();
		}
		if($_GET['type']=='default')
		{
			if($jry_wb_login_user['sex']==0&&$jry_wb_login_user['head']!='default_head_woman')
			{
				$q ="update ".constant('jry_wb_database_general')."users set head='default_head_woman',lasttime=? where id=?";
				$st = $conn->prepare($q);
				$st->bindParam(1,jry_wb_get_time());
				$st->bindParam(2,$jry_wb_login_user['id']);
				$st->execute();
			}
			else if(($jry_wb_login_user['sex']==1||$jry_wb_login_user['sex']==2)&&$jry_wb_login_user['head']!='default_head_man')
			{
				$q ="update ".constant('jry_wb_database_general')."users set head='default_head_man',lasttime=? where id=?";
				$st = $conn->prepare($q);
				$st->bindParam(1,jry_wb_get_time());
				$st->bindParam(2,$jry_wb_login_user['id']);
				$st->execute();
			}			
			echo json_encode(array('code'=>true));
			return;
		}
		else if($_GET['type']=='gravatar')
		{
			$headers = @get_headers('http://www.gravatar.com/avatar/' .md5($jry_wb_login_user['mail']). '?d=404');
			if (preg_match("|200|", $headers[0])) 
			{
				$q ="update ".constant('jry_wb_database_general')."users set head='gravatar',lasttime=? where id=?";
				$st = $conn->prepare($q);
				$st->bindParam(1,jry_wb_get_time());
				$st->bindParam(2,$jry_wb_login_user['id']);
				$st->execute();	
				echo json_encode(array('code'=>true));
				return;
			}
			else
			{
				echo json_encode(array('code'=>false,'reason'=>300000));
				exit();
			}		
		}
		else if($_GET['type']=='qq')
		{
			if(strtolower(array_pop(explode("@",$jry_wb_login_user['mail'])))=='qq.com'||$jry_wb_login_user['oauth_qq']!='')
			{
				$q ="update ".constant('jry_wb_database_general')."users set head='qq',lasttime=? where id=?";
				$st = $conn->prepare($q);
				$st->bindParam(1,jry_wb_get_time());
				$st->bindParam(2,$jry_wb_login_user['id']);
				$st->execute();
				echo json_encode(array('code'=>true));
				return;
			}			
		}
		else if($_GET['type']=='github')
		{
			if($jry_wb_login_user['oauth_github']!='')
			{
				$q ="update ".constant('jry_wb_database_general')."users set head='github',lasttime=? where id=?";
				$st = $conn->prepare($q);
				$st->bindParam(1,jry_wb_get_time());
				$st->bindParam(2,$jry_wb_login_user['id']);
				$st->execute();
				echo json_encode(array('code'=>true));
				return;
			}			
		}
		else if($_GET['type']=='mi')
		{
			if($jry_wb_login_user['oauth_mi']!='')
			{
				$q ="update ".constant('jry_wb_database_general')."users set head='mi',lasttime=? where id=?";
				$st = $conn->prepare($q);
				$st->bindParam(1,jry_wb_get_time());
				$st->bindParam(2,$jry_wb_login_user['id']);
				$st->execute();
				echo json_encode(array('code'=>true));
				return;
			}			
		}
		else if($_GET['type']=='gitee')
		{
			if($jry_wb_login_user['oauth_gitee']!='')
			{
				$q ="update ".constant('jry_wb_database_general')."users set head='gitee',lasttime=? where id=?";
				$st = $conn->prepare($q);
				$st->bindParam(1,jry_wb_get_time());
				$st->bindParam(2,$jry_wb_login_user['id']);
				$st->execute();
				echo json_encode(array('code'=>true));
				return;
			}			
		}		
		echo json_encode(array('code'=>false,'reason'=>000000));
		exit();		
	}	
	else if($_GET['action']=='untpin')
	{
		try
		{
			jry_wb_print_head("",true,false,false,array(),false,false);
		}
		catch(jry_wb_exception $e)
		{
			echo $e->getMessage();
			exit();
		}
		if($_GET['type']=='qq')
			$q ="update ".constant('jry_wb_database_general')."users set oauth_qq=NULL,lasttime=? where id=?";
		else if($_GET['type']=='github')
			$q ="update ".constant('jry_wb_database_general')."users set oauth_github=NULL,lasttime=? where id=?";		
		else if($_GET['type']=='mi')
			$q ="update ".constant('jry_wb_database_general')."users set oauth_mi=NULL,lasttime=? where id=?";
		else if($_GET['type']=='gitee')
			$q ="update ".constant('jry_wb_database_general')."users set oauth_gitee=NULL,lasttime=? where id=?";
		$st = $conn->prepare($q);
		$st->bindParam(1,jry_wb_get_time());
		$st->bindParam(2,$jry_wb_login_user['id']);
		$st->execute();		
		echo json_encode(array('code'=>true));
		exit();
	}
	if($_GET['action']=='mail')
		$_SESSION['url']='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];	
	jry_wb_print_head("用户管理",true,false,false,array(),true,false);	
	if($_GET['action']=='mail_send')
	{
		$vcode=$_POST["vcode"];
		$mail=$_POST["mail"];
		if($vcode!= $_SESSION['vcode'])	{?><script language=javascript>jry_wb_beautiful_alert.alert('请填写正确信息','验证码错误'	,'self.location=document.referrer;');</script>		<?php	exit();}
		if(!jry_wb_test_mail($mail))			{?><script language=javascript>jry_wb_beautiful_alert.alert('请填写正确信息','邮箱错误'					,'self.location=document.referrer;');</script>		<?php	exit();}

		if(!jry_wb_send_mail_code($mail,"jry_wb_mainpages/do_chenge.php?action=mail&"))
		{?><script language=javascript>jry_wb_beautiful_alert.alert('发送失败','<?php echo $mail->ErrorInfo?>'	,'self.location=document.referrer;');</script>		<?php	exit();}
		else{?><script language=javascript>jry_wb_beautiful_alert.alert('验证邮件已发送到您邮箱的辣鸡箱中','请注意查收'	,'self.location=document.referrer;');</script>		<?php exit();}
		exit();
	}	
	else if($_GET['action']=='simple')
	{
		$name=$_POST["name"];
		$sex=$_POST["sex"];
		$zhushi=$_POST["zhushi"];
		$language=$_POST["language"];
		$style_id=$_POST["style_id"];		
		if($name==""){?><script language=javascript>jry_wb_beautiful_alert.alert('请填写完整信息','名字为空'					,'self.location=document.referrer;');</script>		<?php	exit();}
		$q ="update ".constant('jry_wb_database_general')."users set name=? , sex=?,zhushi=?,language=?,style_id=?,lasttime=? where id=? ";
		$st = $conn->prepare($q);
		$st->bindParam(1,$name);
		$st->bindParam(2,($sex));
		$st->bindParam(3,$zhushi);
		$st->bindParam(4,$language);
		$st->bindParam(5,$style_id);
		$st->bindParam(6,jry_wb_get_time());
		$st->bindParam(7,$jry_wb_login_user[id]);
		$st->execute();
		$jry_wb_login_user['sex']=$sex;
	}
	else if($_GET['action']=='tel')
	{
		if($_POST['vcode']!=$_SESSION['vcode']||$_POST['vcode']==''){?><script language=javascript>jry_wb_beautiful_alert.alert('请填写正确信息','验证码错误'					,'self.location=document.referrer;');</script>		<?php	exit();}
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'tel_code where tel=?');
		$st->bindParam(1,$_POST['tel']);
		$st->execute();	
		foreach($st->fetchAll()as $tels);		
		if($_POST['phonecode']!=$tels['code']||$_POST['phonecode']==''){?><script language=javascript>jry_wb_beautiful_alert.alert('请填写正确信息','手机验证码错误'					,'self.location=document.referrer;');</script>		<?php	exit();}					
		$tel=$_POST["tel"];
		if(!jry_wb_test_phone_number($tel))				{?><script language=javascript>jry_wb_beautiful_alert.alert('请填写正确信息','电话错误'					,'self.location=document.referrer;');</script>		<?php	exit();}
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users where tel=?');
		$st->bindParam(1,$tel);
		$st->execute();
		foreach($st->fetchAll()as $users)if($users[id]!=''&&$users[id]!=$jry_wb_login_user[id])	{?><script language=javascript>jry_wb_beautiful_alert.alert('请填写非重复信息','电话重复'	,'self.location=document.referrer;');</script>		<?php	exit();}		
		
		$q ="update ".constant('jry_wb_database_general')."users set tel=?,lasttime=? where id=? ";
		$st = $conn->prepare($q);
		$st->bindParam(1,$tel);
		$st->bindParam(2,jry_wb_get_time());
		$st->bindParam(3,$jry_wb_login_user[id]);
		$st->execute();
		
		$st = $conn->prepare('DELETE FROM '.constant('jry_wb_database_general').'tel_code where code=?');
		$st->bindParam(1,$_POST['phonecode']);
		$st->execute();	
		
	}	
	else if($_GET['action']=='mail')
	{
		$_SESSION['url']='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'mail_code where code=?');
		$st->bindParam(1,$_GET['code']);
		$st->execute();		
		foreach($st->fetchAll()as $code);
		if($code==null){?><script language=javascript>jry_wb_beautiful_alert.alert('不合法的验证码',''					,'self.location=document.referrer;');</script>		<?php	exit();}
		$mail=$code['mail'];
		if(!jry_wb_test_mail($mail))						{?><script language=javascript>jry_wb_beautiful_alert.alert('请填写正确信息','邮箱错误'					,'self.location=document.referrer;');</script>		<?php	exit();}
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users where mail=?');
		$st->bindParam(1,$mail);
		$st->execute();
		foreach($st->fetchAll()as $users)if($users[id]!=''&&$users[id]!=$jry_wb_login_user[id])	{?><script language=javascript>jry_wb_beautiful_alert.alert('请填写非重复信息','邮箱重复'	,'self.location=document.referrer;');</script>		<?php	exit();}
		$q ="update ".constant('jry_wb_database_general')."users set mail=?,lasttime=? where id=? ";
		$st = $conn->prepare($q);
		$st->bindParam(1,($jry_wb_login_user['mail']=$mail));
		$st->bindParam(2,jry_wb_get_time());
		$st->bindParam(3,$jry_wb_login_user[id]);
		$st->execute();
	}	
	else if($_GET['action']=='pas')
	{
		$psw1=$_POST["password1"];
		$psw2=$_POST["password2"];
		$psw_yuan=md5($_POST["password_yuan"]);
		if($psw1!=$psw2)							{?><script language=javascript>jry_wb_beautiful_alert.alert('请填写完整信息','试图修改密码但两次密码不同'	,'self.location=document.referrer;');</script>		<?php	exit();}
		if((strlen($psw1)<8)&&($psw1!=''))							{?><script language=javascript>jry_wb_beautiful_alert.alert('请填写正确信息','密码太短'					,'self.location=document.referrer;');</script>		<?php 	exit();}
		if($jry_wb_login_user[password]!=$psw_yuan)	{?><script language=javascript>jry_wb_beautiful_alert.alert('请填写正确信息','密码错误'					,'self.location=document.referrer;');</script>		<?php	exit();}
		$st = $conn->prepare("DELETE FROM ".constant('jry_wb_database_general')."login where id=?");
		$st->bindParam(1,$jry_wb_login_user['id']);
		$st->execute();	
		$st = $conn->prepare("update ".constant('jry_wb_database_general')."users set password=?,lasttime=? where id=? ");
		$st->bindParam(1,md5($psw1));	
		$st->bindParam(2,jry_wb_get_time());
		$st->bindParam(3,$jry_wb_login_user[id]);
		$st->execute();			
	}	
	else if($_GET['action']=='show')
	{
		$q ="update ".constant('jry_wb_database_general')."users set tel_show=?,mail_show=?,ip_show=?,lasttime=? where id=? ";
		$st = $conn->prepare($q);
		$st->bindParam(1,urldecode($_POST["tel_show"]));	
		$st->bindParam(2,urldecode($_POST["mail_show"]));			
		$st->bindParam(3,urldecode($_POST["ip_show"]));			
		$st->bindParam(4,jry_wb_get_time());
		$st->bindParam(5,$jry_wb_login_user[id]);
		$st->execute();			
	}
	else if($_GET['action']=='specialfact')
	{
		$q ="update ".constant('jry_wb_database_general')."users set word_special_fact=?,follow_mouth=?,head_special=?,lasttime=? where id=? ";
		$st = $conn->prepare($q);
		$st->bindParam(1,urldecode($_POST["word_special_fact"]));	
		$st->bindParam(2,urldecode($_POST["follow_mouth"]));	
		$st->bindParam(3,json_encode
			(
				array
				(
				'mouse_on'=>array
				(
					'speed'=>$_POST['mouse_on_speed'],
					'direction'=>$_POST['mouse_on_direction'],
					'times'=>$_POST['mouse_on_times']
				),
				'mouse_out'=>array
				(
					'speed'=>$_POST['mouse_out_speed'],
					'direction'=>$_POST['mouse_out_direction'],
					'times'=>$_POST['mouse_out_times']
				)
			)
		));
		$st->bindParam(4,jry_wb_get_time());
		$st->bindParam(5,$jry_wb_login_user[id]); 
		$st->execute();
	}		
?>
<script language=javascript>jry_wb_beautiful_alert.alert("修改成功","","window.location.href='chenge.php'");</script>
