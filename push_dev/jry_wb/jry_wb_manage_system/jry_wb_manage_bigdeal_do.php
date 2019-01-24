<?php
	include_once("../tools/jry_wb_includes.php");
	$login=jry_wb_print_head("",true,true,false,array('use','manage','managebigdeal'),false);	
	$action=$_GET['action'];
	$id=$_POST['id'];
	if($login!='ok')
	{
		echo json_encode(array('login'=>false,'reasion'=>$login,'code'=>0));
		exit();	
	}
	if($action=='chenge'&&$_POST['name']!=''&&$_POST['time']!='')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("UPDATE ".constant('jry_wb_database_mainpage')."big_deal SET name=?,time=? WHERE id=? LIMIT 1;");
		$st->bindParam(1,$_POST['name']);
		$st->bindParam(2,$_POST['time']);
		$st->bindParam(3,$id);	
		$st->execute();
		echo json_encode(array('code'=>1,'id'=>$id));
	}
	else if($action=='delete')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("DELETE FROM ".constant('jry_wb_database_mainpage')."big_deal WHERE id=? LIMIT 1;");
		$st->bindParam(1,$id);
		$st->execute();			
		echo json_encode(array('code'=>1,'id'=>$id));
	}
	else if($action=='add'&&$_POST['name']!=''&&$_POST['time']!='')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("INSERT INTO ".constant('jry_wb_database_mainpage')."big_deal (`name`,`time`) VALUES (?,?);");
		$st->bindParam(1,$_POST['name']);
		$st->bindParam(2,$_POST['time']);
		$st->execute();
		echo json_encode(array('code'=>1,'id'=>$id));
	}
	else
	{
		echo json_encode(array('reasion'=>'unknow','code'=>0));
	}
?>