<?php
	include_once("../tools/jry_wb_includes.php");
	$action=$_GET['action'];
	$conn=jry_wb_connect_database();
	if($action=='serchid')
	{
		$id=$_GET['id'];
		$q='SELECT mail,mail_show
		FROM '.constant('jry_wb_database_general').'users
		WHERE id = ?';
		$st = $conn->prepare($q);
		$st->bindParam(1,$id);
		$st->execute();	
		$user=$st->fetchAll()[0];
		if($user['mail']!=''&&$user['mail_show']==0)
		{
			$buf=explode('@',$user['mail']);
			$user['mail']=substr_replace($buf[0],'****',3,count($buf[0])-3).'@'.$buf[1];
		}
		jry_wb_echo_log(constant('jry_wb_log_type_forget'),'serchid',$id);
		echo json_encode($user['mail']);
	}else if($action=='sendemail')
	{
		$id=$_GET['id'];
		$q='SELECT mail,mail_show
		FROM '.constant('jry_wb_database_general').'users
		WHERE id = ?';
		$st = $conn->prepare($q);
		$st->bindParam(1,$id);
		$st->execute();	
		$user=$st->fetchAll()[0];
		if($user==null)
			exit();
		jry_wb_send_mail_code($user['mail'],"mainpages/do_forget.php?action=checkmail&");
		jry_wb_echo_log(constant('jry_wb_log_type_forget'),'sendemail',$id);
	}
	else if($action=='checkmail')
	{
		jry_wb_print_head("",false,false,false,array(),true,false);	
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'mail_code where code=?');
		$st->bindParam(1,$_GET['code']);
		$st->execute();		
		foreach($st->fetchAll()as $code);
		if($code==null){?><script language=javascript>jry_wb_beautiful_alert.alert('不合法的验证码',''					,'window.location.href="forget.php"');</script>		<?php	exit();}
		$st = $conn->prepare('DELETE FROM '.constant('jry_wb_database_general').'mail_code where code=?');
		$st->bindParam(1,$_GET['code']);
		$st->execute();	
		$srcstr = "1234567890-=!@()_+qwertyuiopasdfghjkl:zxcvbnm,./QWERTYUIOPASDFGHJKLZXCVBNM";
		mt_srand();
		$password='';
		$n=strlen($srcstr);
		for ($i = 0; $i < 16; $i++) 
			$password.=$srcstr[mt_rand(0, $n)];
		$q='UPDATE '.constant('jry_wb_database_general').'users SET  password=? WHERE mail=?';
		$st = $conn->prepare($q);
		$st->bindParam(1,md5($password));
		$st->bindParam(2,$code['mail']);		
		$st->execute();
		?> <script language=javascript>jry_wb_beautiful_alert.alert("修改成功","您的密码为: <?php echo $password; ?>请牢记","window.location.href='login.php'");</script><?php
//		jry_wb_echo_log(constant('jry_wb_log_type_forget'),'checkmail',$id);
	}else if($action=='checktel') 
	{
		$q='SELECT name FROM '.constant('jry_wb_database_general').'users WHERE tel = ?';
		$st = $conn->prepare($q);
		$st->bindParam(1,$_POST['tel']);
		$_SESSION['tel']=$_POST['tel'];
		$st->execute();	
		$user=$st->fetchAll();
		if(count($user)==0)
			echo json_encode(null);
		else
			echo json_encode($user[0]['name']);
//		jry_wb_echo_log(constant('jry_wb_log_type_forget'),'checktel',$id);
	}
	else if($action=='sendtelcode')
	{
		if($_SESSION['tel']=='')
			return;
		require_once "../tools/SignatureHelper.php";
		if(($code=gettelsmscode($_SESSION['tel']))==-1)
		{
			echo json_encode("toofast");
			exit();
		}
		sendsms($_SESSION['tel'],Array ("code"=>$code),constant('jry_wb_short_message_aly_forget')); 	
		echo json_encode('OK');
		exit();
	}else if($action=='checkcode')
	{
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'tel_code where tel=?');
		$st->bindParam(1,$_SESSION['tel']);
		$st->execute();		
		foreach($st->fetchAll()as $tels);
		$st = $conn->prepare('DELETE FROM '.constant('jry_wb_database_general').'tel_code where code=?');
		$st->bindParam(1,$_GET['code']);
		$st->execute();			
		if($tels['code']!=$_GET['code'])
			echo -1;
		else
		{
			$srcstr = "1234567890-=!@()_+qwertyuiopasdfghjkl:zxcvbnm,./QWERTYUIOPASDFGHJKLZXCVBNM";
			mt_srand();
			$password='';
			$n=strlen($srcstr);
			for ($i = 0; $i < 16; $i++) 
				$password.=$srcstr[mt_rand(0, $n)];
			$q='UPDATE '.constant('jry_wb_database_general').'users SET password=? WHERE tel=?';
			$st = $conn->prepare($q);
			$st->bindParam(1,md5($password));
			$st->bindParam(2,$_SESSION['tel']);
			$st->execute();						
			echo $password;
		}
	}
	
?>