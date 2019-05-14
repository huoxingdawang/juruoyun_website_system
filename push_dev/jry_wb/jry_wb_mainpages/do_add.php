<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_user_extern_message.php");	
	if(!constant('jry_wb_host_switch')&&$_GET['debug']!=1)
	{
		?><script>window.location="<?php echo constant('jry_wb_host_addr')?>mainpages/add.php"</script><?php
		exit();
	}
	try
	{
		if($_GET['action']=='send_tel')
		{
			if(!constant('jry_wb_check_tel_switch'))
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>000000,'file'=>__FILE__,'line'=>__LINE__)));		
			if(constant('jry_wb_short_message_switch')=='')
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
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users where tel=?');
			$st->bindParam(1,$_POST['tel']);
			$st->execute();
			$all=$st->fetchAll();
			if(count($all)!=0)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100009,'file'=>__FILE__,'line'=>__LINE__)));		
			require_once "../tools/jry_wb_short_message.php";
			if(($code=jry_wb_get_short_message_code($_POST['tel']))==-1)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100003,'file'=>__FILE__,'line'=>__LINE__)));
			jry_wb_send_short_message($_POST['tel'],Array ("code"=>$code),constant('jry_wb_short_message_aly_add_user'));	
			echo json_encode(array('code'=>true));
			exit();		
		}
		$psw1=$_POST["password1"];
		$psw2=$_POST["password2"];
		$sex=$_POST["sex"];
		$tel=$_POST["tel"];
		$name=$_POST["name"];
		$vcode=$_POST["vcode"];
		$extern=json_decode(urldecode($_POST["extern"]),true);
		if($vcode!=$_SESSION['vcode']||$vcode=='')
		{
			if(strtolower($_POST['vcode'])==strtolower($_SESSION['vcode']))
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100005,'file'=>__FILE__,'line'=>__LINE__)));		
			else
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100002,'file'=>__FILE__,'line'=>__LINE__)));		
		}
		
		if($name=="")
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100013,'file'=>__FILE__,'line'=>__LINE__)));	
		if(strlen($psw1)<8)	
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100012,'file'=>__FILE__,'line'=>__LINE__)));	
		if($psw1!=$psw2)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100011,'file'=>__FILE__,'line'=>__LINE__)));
		if(constant('jry_wb_check_tel_switch'))
		{
			if(!jry_wb_test_phone_number($tel))
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100008,'file'=>__FILE__,'line'=>__LINE__)));
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
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100010,'file'=>__FILE__,'line'=>__LINE__)));
			}
			
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users where tel=?');
			$st->bindParam(1,$tel);
			$st->execute();
			if(count($st->fetchAll())!=0)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100009,'file'=>__FILE__,'line'=>__LINE__)));
			if(constant('jry_wb_short_message_switch')!='')
			{		
				$st = $conn->prepare('DELETE FROM '.constant('jry_wb_database_general').'tel_code where tel=?');
				$st->bindParam(1,$tel);
				$st->execute();
			}			
		}
		$send=false;
		if(constant('jry_wb_check_mail_switch'))
		{
			if(!jry_wb_test_mail($_POST['mail']))
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100014,'file'=>__FILE__,'line'=>__LINE__)));		
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users where mail=?');
			$st->bindParam(1,$_POST['mail']);
			$st->execute();
			if(count($st->fetchAll())!=0)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100015,'file'=>__FILE__,'line'=>__LINE__)));		

		}
		foreach($jry_wb_config_user_extern_message as $one)
		{
			if($one['type']=='cutter')
				continue;
			if($extern[$one['key']]=='')
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100017,'extern'=>array('key'=>$one['key'],'name'=>$one['name']),'file'=>__FILE__,'line'=>__LINE__)));
			if($one['type']=='china_id')
				if(jry_wb_test_china_id_card($extern[$one['key']])===false)
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100017,'extern'=>array('key'=>$one['key'],'name'=>$one['name']),'file'=>__FILE__,'line'=>__LINE__)));
			if($one['type']=='tel')
				if(jry_wb_test_phone_number($extern[$one['key']])===false)
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100017,'extern'=>array('key'=>$one['key'],'name'=>$one['name']),'file'=>__FILE__,'line'=>__LINE__)));
			if($one['type']=='mail')
				if(jry_wb_test_mail($extern[$one['key']])===false)
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100017,'extern'=>array('key'=>$one['key'],'name'=>$one['name']),'file'=>__FILE__,'line'=>__LINE__)));
			if($one['connect']!=NULL)
			{
				foreach($one['connect'] as $connect)
				{
					if($one['type']=='china_id'&&$connect=='sex')
					{
						if(jry_wb_get_sex_by_china_id_card($extern[$one['key']])!==(int)$sex)
							throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100017,'extern'=>array('key'=>$one['key'],'name'=>$one['name']),'file'=>__FILE__,'line'=>__LINE__)));
					}
					else if($connect=='tel')
					{
						if($extern[$one['key']]==$tel)
							throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100017,'extern'=>array('key'=>$one['key'],'name'=>$one['name']),'file'=>__FILE__,'line'=>__LINE__)));
					}
					else if($connect=='namee'||$connect=='name')
					{
						if($extern[$one['key']]==$name)
							throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100017,'extern'=>array('key'=>$one['key'],'name'=>$one['name']),'file'=>__FILE__,'line'=>__LINE__)));
					}
					else if($connect=='mail')
					{
						if($extern[$one['key']]==$_POST['mail'])
							throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100017,'extern'=>array('key'=>$one['key'],'name'=>$one['name']),'file'=>__FILE__,'line'=>__LINE__)));
					}
					else
					{
						if($extern[$one['key']]==$extern[$connect])
							throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100017,'extern'=>array('key'=>$one['key'],'name'=>$one['name']),'file'=>__FILE__,'line'=>__LINE__)));
					}
				}
			}
			if(is_object($one['checker_php'])===true)
				if($one['checker_php']($extern)!==true)
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100017,'extern'=>array('key'=>$one['key'],'name'=>$one['name']),'file'=>__FILE__,'line'=>__LINE__)));
				
		}
		$psw1=md5($psw1);
		$conn=jry_wb_connect_database();
		$now=jry_wb_get_time();//时间
		if($sex==0)
			$q = 'INSERT INTO '.constant('jry_wb_database_general').'users (name,password,sex,enroldate,head,tel,lasttime,extern) VALUES (?,?,?,?,head=\'{"type":"default_head_woman"}\',?,?,?)';
		else
			$q = 'INSERT INTO '.constant('jry_wb_database_general').'users (name,password,sex,enroldate,head,tel,lasttime,extern) VALUES (?,?,?,?,head=\'{"type":"default_head_man"}\',?,?,?)';
		$st = $conn->prepare($q);
		$st->bindParam(1,$name);
		$st->bindParam(2,$psw1);
		$st->bindParam(3,$sex);
		$st->bindParam(4,$now);
		$st->bindParam(5,$tel);
		$st->bindParam(6,$now);
		$st->bindParam(7,json_encode($extern));
		$st->execute();
		$jry_wb_login_user['id']=$conn->lastInsertId();
		if(constant('jry_wb_check_mail_switch'))
		{		
			if(constant('jry_wb_mail_switch')!='')
			{
				jry_wb_send_mail_code($_POST['mail'],"jry_wb_mainpages/do_chenge.php?action=mail&");
				$send=true;	
			}
			else
			{
				$st = $conn->prepare('UPDATE '.constant('jry_wb_database_general').'users SET mail=? WHERE id=? ');
				$st->bindParam(1,$_POST['mail']);
				$st->bindParam(2,$jry_wb_login_user['id']);
				$st->execute();					
			}
		}			
		jry_wb_echo_log(constant('jry_wb_log_type_add'),'');
		$_SESSION['vcode']='';
		echo json_encode(array('code'=>true,'id'=>$jry_wb_login_user['id'],'send'=>$send));
	}
	catch(jry_wb_exception $e)
	{
		echo $e->getMessage();
		exit();
	}	
?>