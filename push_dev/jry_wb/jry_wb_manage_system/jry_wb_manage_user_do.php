<?php
	include_once("../tools/jry_wb_includes.php");
	try
	{
		jry_wb_print_head("控制系统",true,false,false,array('use','manage','manageusers'),false);
	}
	catch(jry_wb_exception $e)
	{
		echo $e->getMessage();
		exit();
	}
	if($_GET['action']=='')
	{
		$id=(int)$_GET['id'];
		if($_POST==null)
		{
			echo json_encode(array('code'=>false,'reason'=>400000));
			exit();
		}
		$cmd="UPDATE ".constant('jry_wb_database_general')."users SET ";
		foreach ($_POST as $key => $value) 
			$cmd.='`'.(preg_replace('/[^a-zA-Z]/','',urldecode($key))."`=?,");
		$cmd.=" lasttime=? WHERE id=? LIMIT 1;";
		$conn2=jry_wb_connect_database();
		$st = $conn2->prepare($cmd);
		$i=1;
		foreach ($_POST as $key => &$value) 
		{
			$st->bindParam($i,$value);
			$i++;
		}
		$st->bindParam($i,jry_wb_get_time());
		$st->bindParam($i+1,$id);
		$st->execute();
		echo json_encode(array('code'=>true));
	}else if($_GET['action']=='name_not_ok')
	{
		$id=(int)$_GET['id'];		
		$cmd="UPDATE ".constant('jry_wb_database_general')."users SET `use`=0 , `lasttime`=? WHERE id=? LIMIT 1;";
		$conn2=jry_wb_connect_database();
		$st = $conn2->prepare($cmd);
		$st->bindParam(1,jry_wb_get_time());
		$st->bindParam(2,$id);
		$st->execute();
		$cmd="SELECT mail,name,id FROM ".constant('jry_wb_database_general')."users WHERE id=? LIMIT 1;";
		$st = $conn2->prepare($cmd);
		$st->bindParam(1,$id);		
		$st->execute();
		$data=$st->fetchAll()[0];
		if(jry_wb_send_mail($data['mail'],
		'昵称不合法被禁用通知',
		'尊敬的'.constant('jry_wb_name').'用户'.$data['id'].'('.$data['name'].')，您好：<br>'.
		'您的账号 '.$data['id'].' 的昵称 "'.$data['name'].'"在 '.jry_wb_get_time().' 被管理员认为不合法<br>'.
		'可能的原因是违反了<a href="'.constant('jry_wb_host').'mainpages/xieyi.php">蒟蒻云用户协议</a>，或相关法律法规，当然不排除您的昵称使管理员恶心呕吐导致管理员电脑损坏的可能性<br>'.
		'请您及时前往<a href="'.constant('jry_wb_host').'mainpages/chenge.php">蒟蒻云用户中心</a>进行修改<br>'.
		'蒟蒻云管理组感谢您的配合以及对国家相关法律法规的遵守<br>'.
		constant('jry_wb_name').'开发组，'.constant('jry_wb_name').'管理组 '.jry_wb_get_time()
		))
			echo json_encode(array('code'=>true));
		else
			echo json_encode(array('code'=>false,'reason'=>300001));			
	}else if($_GET['action']=='bangyouxiang')
	{
		$q='SELECT tel,name FROM '.constant('jry_wb_database_general').'users WHERE id = ?';
		$st = $conn->prepare($q);
		$st->bindParam(1,$_GET['id']);
		$st->execute();	
		$user=$st->fetchAll()[0];
		require_once "../tools/SignatureHelper.php";
		sendsms($user['tel'],Array ("name"=>$user['name']),constant('jry_wb_short_message_aly_connect_mail')); 	
		echo json_encode(array('code'=>true));
		exit();
	}
	else if($_GET['action']=='unlock')
	{
		$conn2=jry_wb_connect_database();		
		$id=(int)$_GET['id'];
		$cmd="SELECT mail,name,id FROM ".constant('jry_wb_database_general')."users WHERE id=? LIMIT 1;";
		$st = $conn2->prepare($cmd);
		$st->bindParam(1,$id);		
		$st->execute();
		$data=$st->fetchAll()[0];
		if(jry_wb_send_mail($data['mail'],
		'解封通知',
		'尊敬的用户，您好：<br>您的账号 '.$data['id'].' 在 '.jry_wb_get_time().' 被管理员认为合法,已被解封<br>'.
		'蒟蒻云管理组感谢您的耐心等待以及对国家相关法律法规的遵守<br>'.
		'蒟蒻云开发组，蒟蒻云管理组 '.jry_wb_get_time()
		))
			echo json_encode(array('code'=>true));
		else
			echo json_encode(array('code'=>false,'reason'=>300001));
	}	
?>