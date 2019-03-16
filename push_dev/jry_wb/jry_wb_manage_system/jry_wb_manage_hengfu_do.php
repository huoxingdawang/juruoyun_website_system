<?php
	include_once("../tools/jry_wb_includes.php");
	try
	{
		jry_wb_print_head("",true,true,false,array('use','manage','managehengfu'),false);	
	}
	catch(jry_wb_exception $e)
	{
		echo $e->getMessage();
		exit();
	}	
	$action=$_GET['action'];
	$hengfu_id=$_POST['hengfu_id'];
	if($action=='chenge'&&$_POST['words']!='')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("UPDATE ".constant('jry_wb_database_mainpage')."hengfu SET words=? WHERE hengfu_id=? LIMIT 1;");
		$st->bindParam(1,$_POST['words']);
		$st->bindParam(2,$hengfu_id);			
		$st->execute();
		echo json_encode(array('code'=>true,'hengfu_id'=>$hengfu_id));
	}
	else if($action=='delete')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("DELETE FROM ".constant('jry_wb_database_mainpage')."hengfu WHERE hengfu_id=? LIMIT 1;");
		$st->bindParam(1,$hengfu_id);
		$st->execute();	
		echo json_encode(array('code'=>true,'hengfu_id'=>$hengfu_id));		
	}
	else if($action=='enable'||$action=='disable')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("UPDATE ".constant('jry_wb_database_mainpage')."hengfu SET enable=? WHERE hengfu_id=? LIMIT 1;");
		$st->bindValue(1,($action=='enable'?1:0));		
		$st->bindParam(2,$hengfu_id);
		$st->execute();	
		echo json_encode(array('code'=>true,'hengfu_id'=>$hengfu_id));		
	}	
	else if($action=='add'&&$_POST['words']!='')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("INSERT INTO ".constant('jry_wb_database_mainpage')."hengfu (`words`,`id`) VALUES (?,?);");
		$st->bindParam(1,$_POST['words']);
		$st->bindParam(2,$jry_wb_login_user['id']);
		$st->execute();
		echo json_encode(array('code'=>true));
	}
	else
	{
		echo json_encode(array('code'=>false,'reasion'=>000000));
	}
?>