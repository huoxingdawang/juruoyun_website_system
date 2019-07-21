<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	try{jry_wb_check_compentence(NULL,array('use','manage','managehengfu'));}catch(jry_wb_exception $e){echo $e->getMessage();exit();}
	$action=$_GET['action'];
	$hengfu_id=$_POST['hengfu_id'];
	if($action=='chenge'&&$_POST['words']!='')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("UPDATE ".JRY_WB_DATABASE_MAINPAGE."hengfu SET words=? WHERE hengfu_id=? LIMIT 1;");
		$st->bindParam(1,$_POST['words']);
		$st->bindParam(2,$hengfu_id);			
		$st->execute();
		echo json_encode(array('code'=>true,'hengfu_id'=>$hengfu_id));
	}
	else if($action=='delete')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("UPDATE ".JRY_WB_DATABASE_MAINPAGE."hengfu SET `delete`=1,words='' WHERE hengfu_id=? LIMIT 1;");
		$st->bindParam(1,$hengfu_id);
		$st->execute();	
		echo json_encode(array('code'=>true,'hengfu_id'=>$hengfu_id));		
	}
	else if($action=='enable'||$action=='disable')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("UPDATE ".JRY_WB_DATABASE_MAINPAGE."hengfu SET enable=? WHERE hengfu_id=? LIMIT 1;");
		$st->bindValue(1,($action=='enable'?1:0));		
		$st->bindParam(2,$hengfu_id);
		$st->execute();	
		echo json_encode(array('code'=>true,'hengfu_id'=>$hengfu_id));		
	}	
	else if($action=='add'&&$_POST['words']!='')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("INSERT INTO ".JRY_WB_DATABASE_MAINPAGE."hengfu (`words`) VALUES (?);");
		$st->bindParam(1,$_POST['words']);
		$st->execute();
		echo json_encode(array('code'=>true));
	}
	else
	{
		echo json_encode(array('code'=>false,'reasion'=>000000));
	}
?>