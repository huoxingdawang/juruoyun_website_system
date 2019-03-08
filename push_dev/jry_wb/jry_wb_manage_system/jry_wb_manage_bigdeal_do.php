<?php
	include_once("../tools/jry_wb_includes.php");
	$login=jry_wb_print_head("",true,true,false,array('use','manage','managebigdeal'),false);	
	$action=$_GET['action'];
	$bigdeal_id=$_POST['bigdeal_id'];
	if($login!='ok')
	{
		echo json_encode(array('login'=>false,'reasion'=>$login,'code'=>0));
		exit();	
	}
	if($action=='chenge'&&$_POST['name']!=''&&$_POST['time']!='')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("UPDATE ".constant('jry_wb_database_mainpage')."bigdeal SET name=?,time=? WHERE bigdeal_id=? LIMIT 1;");
		$st->bindParam(1,$_POST['name']);
		$st->bindParam(2,$_POST['time']);
		$st->bindParam(3,$bigdeal_id);	
		$st->execute();
		echo json_encode(array('code'=>1,'bigdeal_id'=>$bigdeal_id));
	}
	else if($action=='delete')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("DELETE FROM ".constant('jry_wb_database_mainpage')."bigdeal WHERE bigdeal_id=? LIMIT 1;");
		$st->bindParam(1,$bigdeal_id);
		$st->execute();			
		echo json_encode(array('code'=>1,'bigdeal_id'=>$bigdeal_id));
	}
	else if($action=='enable'||$action=='disable')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("UPDATE ".constant('jry_wb_database_mainpage')."bigdeal SET enable=? WHERE bigdeal_id=? LIMIT 1;");
		$st->bindValue(1,($action=='enable'?1:0));		
		$st->bindParam(2,$bigdeal_id);
		$st->execute();	
		echo json_encode(array('code'=>1,'bigdeal_id'=>$bigdeal_id));		
	}		
	else if($action=='add'&&$_POST['name']!=''&&$_POST['time']!='')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("INSERT INTO ".constant('jry_wb_database_mainpage')."bigdeal (`name`,`time`,`id`) VALUES (?,?,?);");
		$st->bindParam(1,$_POST['name']);
		$st->bindParam(2,$_POST['time']);
		$st->bindParam(3,$jry_wb_login_user['id']);		
		$st->execute();
		echo json_encode(array('code'=>1,'bigdeal_id'=>$bigdeal_id));
	}
	else
	{
		echo json_encode(array('reasion'=>'unknow','code'=>0));
	}
?>