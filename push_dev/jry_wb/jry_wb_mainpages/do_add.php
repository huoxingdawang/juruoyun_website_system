<?php
	include_once("../tools/jry_wb_includes.php");
	if(!constant('jry_wb_host_switch')&&$_GET['debug']!=1)
	{
		?><script>window.location="<?php echo constant('jry_wb_host_addr')?>mainpages/add.php"</script><?php
		exit();
	}	
	if($_GET['action']=='send_tel')
	{
		if(!constant('jry_wb_check_tel_switch'))
			exit();
		if(constant('jry_wb_short_message_switch')=='')
			exit();
		if($_POST['vcode']!=$_SESSION['vcode']||$_POST['vcode']=='')
		{
			if(strtolower($_POST['vcode'])==strtolower($_SESSION['vcode']))
				echo json_encode(array('code'=>false,'reason'=>100005));
			else
				echo json_encode(array('code'=>false,'reason'=>100002));
			exit();
		}
		if(!jry_wb_test_phone_number($_POST['tel']))
		{
			echo json_encode(array('code'=>false,'reason'=>100008));
			exit();
		}
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users where tel=?');
		$st->bindParam(1,$_POST['tel']);
		$st->execute();
		$all=$st->fetchAll();
		if(count($all)!=0)
		{
			echo json_encode(array('code'=>false,'reason'=>100009,'extern'=>$all));
			exit();
		}			
		require_once "../tools/SignatureHelper.php";
		if(($code=gettelsmscode($_POST['tel']))==-1)
		{
			echo json_encode(array('code'=>false,'reason'=>100003));
			exit();
		}
		sendsms($_POST['tel'],Array ("code"=>$code),constant('jry_wb_short_message_aly_add_user'));	
		echo json_encode(array('code'=>true));
		exit();		
	}
	$psw1=$_POST["password1"];
	$psw2=$_POST["password2"];
	$sex=$_POST["sex"];
	$tel=$_POST["tel"];
	$name=$_POST["name"];
	$vcode=$_POST["vcode"];
	if($vcode!=$_SESSION['vcode']||$vcode=='')
	{
		if(strtolower($vcode)==strtolower($_SESSION['vcode']))
			echo json_encode(array('code'=>false,'reason'=>100005));
		else
			echo json_encode(array('code'=>false,'reason'=>100002));
		exit();
	}
	
	if($name=="")
	{
		echo json_encode(array('code'=>false,'reason'=>100013));
		exit();
	}		
	if(strlen($psw1)<8)	
	{
		echo json_encode(array('code'=>false,'reason'=>100012));
		exit();
	}		
	if($psw1!=$psw2)
	{
		echo json_encode(array('code'=>false,'reason'=>100011));
		exit();
	}
	if(constant('jry_wb_check_tel_switch'))
	{
		if(!jry_wb_test_phone_number($tel))
		{
			echo json_encode(array('code'=>false,'reason'=>100008));
			exit();
		}
		if(constant('jry_wb_short_message_switch')!='')
		{
			$st = $conn->prepare('DELETE FROM '.constant('jry_wb_database_general').'tel_code where time<?');
			$st->bindParam(1,date("Y-m-d H:i:s",time()-5*60));
			$st->execute();
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'tel_code where tel=?');
			$st->bindParam(1,$_POST['tel']);
			$st->execute();	
			foreach($st->fetchAll()as $tels);	
			if($_POST['phonecode']!=$tels['code']||$_POST['phonecode']=='')
			{
				echo json_encode(array('code'=>false,'reason'=>100010));
				exit();
			}
		}
		
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users where tel=?');
		$st->bindParam(1,$tel);
		$st->execute();
		if(count($st->fetchAll())!=0)
		{
			echo json_encode(array('code'=>false,'reason'=>100009));
			exit();
		}
		if(constant('jry_wb_short_message_switch')!='')
		{		
			$st = $conn->prepare('DELETE FROM '.constant('jry_wb_database_general').'tel_code where tel=?');
			$st->bindParam(1,$tel);
			$st->execute();
		}			
	}
	$psw1=md5($psw1);
	$conn=jry_wb_connect_database();
	$now=jry_wb_get_time();//时间
	if($sex==0)
		$q = "INSERT INTO ".constant('jry_wb_database_general')."users (name,password,sex,enroldate,head,tel,lasttime) VALUES (?,?,?,?,'default_head_woman',?,?)";
	else
		$q = "INSERT INTO ".constant('jry_wb_database_general')."users (name,password,sex,enroldate,tel,lasttime) VALUES (?,?,?,?,?,?)";
	$st = $conn->prepare($q);
	$st->bindParam(1,$name);
	$st->bindParam(2,$psw1);
	$st->bindParam(3,$sex);
	$st->bindParam(4,$now);
	$st->bindParam(5,$tel);
	$st->bindParam(6,$now);
	$st->execute();
	$jry_wb_login_user['id']=$conn->lastInsertId();
	jry_wb_echo_log(constant('jry_wb_log_type_add'),'');
	echo json_encode(array('code'=>true,'id'=>$jry_wb_login_user['id']));
?>