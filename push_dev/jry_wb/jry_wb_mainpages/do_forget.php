<?php
	include_once("../tools/jry_wb_includes.php");
	$action=$_GET['action'];
	$conn=jry_wb_connect_database();
	try
	{
		if($action=='send_tel')
		{
			if(JRY_WB_SHORT_MESSAGE_SWITCH=='')
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>000000,'file'=>__FILE__,'line'=>__LINE__)));		
			if($_POST['vcode']!=$_SESSION['vcode']||$_POST['vcode']=='')
			{
				if(strtolower($_POST['vcode'])==strtolower($_SESSION['vcode']))
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100005,'file'=>__FILE__,'line'=>__LINE__)));		
				else
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100002,'file'=>__FILE__,'line'=>__LINE__)));		
			}
			if(!jry_wb_test_phone_number($_POST['tel']))
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100008,'file'=>__FILE__,'line'=>__LINE__)));
			$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'users where tel=?');
			$st->bindParam(1,$_POST['tel']);
			$st->execute();
			$all=$st->fetchAll();
			if(count($all)==0)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100018,'file'=>__FILE__,'line'=>__LINE__)));		
			require_once "../tools/jry_wb_short_message.php";
			if(($code=jry_wb_get_short_message_code($_POST['tel']))==-1)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100003,'file'=>__FILE__,'line'=>__LINE__)));
			jry_wb_send_short_message($_POST['tel'],Array ("code"=>$code),JRY_WB_SHORT_MESSAGE_ALY_FORGET);	
			echo json_encode(array('code'=>true));
			exit();			
		}
		else if($action=='send_mail')
		{
			if(JRY_WB_MAIL_SWITCH=='')
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>000000,'file'=>__FILE__,'line'=>__LINE__)));	
			if($_POST['vcode']!=$_SESSION['vcode']||$_POST['vcode']=='')
			{
				if(strtolower($_POST['vcode'])==strtolower($_SESSION['vcode']))
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100005,'file'=>__FILE__,'line'=>__LINE__)));		
				else
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100002,'file'=>__FILE__,'line'=>__LINE__)));		
			}
			if(!jry_wb_test_mail($_POST['mail']))
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100014,'file'=>__FILE__,'line'=>__LINE__)));		
			$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'users where mail=?');
			$st->bindParam(1,$_POST['mail']);
			$st->execute();
			if(count($st->fetchAll())==0)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100018,'file'=>__FILE__,'line'=>__LINE__)));
			jry_wb_send_mail_code6($_POST['mail']);	
			echo json_encode(array('code'=>true));
			exit();					
		}
		else if($action=='chenge_password'&&$_GET['type']=='tel')
		{
			if(JRY_WB_SHORT_MESSAGE_SWITCH=='')
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>000000,'file'=>__FILE__,'line'=>__LINE__)));		
			if($_POST['vcode']!=$_SESSION['vcode']||$_POST['vcode']=='')
			{
				if(strtolower($_POST['vcode'])==strtolower($_SESSION['vcode']))
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100005,'file'=>__FILE__,'line'=>__LINE__)));		
				else
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100002,'file'=>__FILE__,'line'=>__LINE__)));		
			}
			if(!jry_wb_test_phone_number($_POST['tel']))
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100008,'file'=>__FILE__,'line'=>__LINE__)));			
			$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'users where tel=?');
			$st->bindParam(1,$_POST['tel']);
			$st->execute();
			$all=$st->fetchAll();
			if(count($all)==0)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100018,'file'=>__FILE__,'line'=>__LINE__)));
			if(strlen($_POST['password1'])<8)	
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100012,'file'=>__FILE__,'line'=>__LINE__)));				
			if($_POST['password1']!=$_POST['password2'])
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100011,'file'=>__FILE__,'line'=>__LINE__)));
			$st = $conn->prepare('DELETE FROM '.JRY_WB_DATABASE_GENERAL.'tel_code where time<?');
			$st->bindParam(1,date("Y-m-d H:i:s",time()-5*60));
			$st->execute();
			$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'tel_code where tel=?');
			$st->bindParam(1,$_POST['tel']);
			$st->execute();	
			foreach($st->fetchAll()as $tels);	
			if($_POST['phonecode']!=$tels['code']||$_POST['phonecode']=='')
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100010,'file'=>__FILE__,'line'=>__LINE__)));
			$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_GENERAL.'users SET password=? where tel=?');
			$st->bindParam(1,md5($_POST['password1']));
			$st->bindParam(2,$_POST['tel']);
			$st->execute();
			$st = $conn->prepare('DELETE FROM '.JRY_WB_DATABASE_GENERAL.'tel_code where tel=? and code=?');
			$st->bindParam(1,$_POST['tel']);
			$st->bindParam(2,$_POST['phonecode']);
			$st->execute();
			$st = $conn->prepare("DELETE FROM ".JRY_WB_DATABASE_GENERAL."login where id=?");
			$st->bindParam(1,$jry_wb_login_user['id']);
			$st->execute();				
			echo json_encode(array('code'=>true));
			exit();			
		}
		else if($action=='chenge_password'&&$_GET['type']=='mail')
		{
			if(JRY_WB_MAIL_SWITCH=='')
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>000000,'file'=>__FILE__,'line'=>__LINE__)));		
			if($_POST['vcode']!=$_SESSION['vcode']||$_POST['vcode']=='')
			{
				if(strtolower($_POST['vcode'])==strtolower($_SESSION['vcode']))
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100005,'file'=>__FILE__,'line'=>__LINE__)));		
				else
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100002,'file'=>__FILE__,'line'=>__LINE__)));		
			}
			if(!jry_wb_test_mail($_POST['mail']))
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100014,'file'=>__FILE__,'line'=>__LINE__)));
			$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'users where mail=?');
			$st->bindParam(1,$_POST['mail']);
			$st->execute();
			if(count($st->fetchAll())==0)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100018,'file'=>__FILE__,'line'=>__LINE__)));			
			if(strlen($_POST['password1'])<8)	
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100012,'file'=>__FILE__,'line'=>__LINE__)));				
			if($_POST['password1']!=$_POST['password2'])
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100011,'file'=>__FILE__,'line'=>__LINE__)));
			$st = $conn->prepare('DELETE FROM '.JRY_WB_DATABASE_GENERAL.'mail_code where time<?');
			$st->bindParam(1,date("Y-m-d H:i:s",time()-5*60));
			$st->execute();
			$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'mail_code where mail=?');
			$st->bindParam(1,$_POST['mail']);
			$st->execute();	
			foreach($st->fetchAll()as $mail);	
			if($_POST['mailcode']!=$mail['code']||$_POST['mailcode']=='')
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100016,'file'=>__FILE__,'line'=>__LINE__)));
			$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_GENERAL.'users SET password=? where mail=?');
			$st->bindParam(1,md5($_POST['password1']));
			$st->bindParam(2,$_POST['mail']);
			$st->execute();
			$st = $conn->prepare('DELETE FROM '.JRY_WB_DATABASE_GENERAL.'mail_code where mail=? and code=?');
			$st->bindParam(1,$_POST['mail']);
			$st->bindParam(2,$_POST['mailcode']);
			$st->execute();
			$st = $conn->prepare("DELETE FROM ".JRY_WB_DATABASE_GENERAL."login where id=?");
			$st->bindParam(1,$jry_wb_login_user['id']);
			$st->execute();				
			echo json_encode(array('code'=>true));
			exit();			
		}		
		else
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>000000,'file'=>__FILE__,'line'=>__LINE__)));			
	}
	catch(jry_wb_exception $e)
	{
		echo $e->getMessage();
		exit();
	}
?>