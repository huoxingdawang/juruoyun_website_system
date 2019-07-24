<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	try{jry_wb_check_compentence(NULL,array('use','manage','managetanmu'));}catch(jry_wb_exception $e){echo $e->getMessage();exit();}
	$action=$_GET['action'];
	$tanmu_id=$_POST['tanmu_id'];
	if($action=='chenge'&&$_POST['words']!='')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("UPDATE ".JRY_WB_DATABASE_MAINPAGE."tanmu SET words=? WHERE tanmu_id=? LIMIT 1;");
		$st->bindParam(1,$_POST['words']);
		$st->bindParam(2,$tanmu_id);			
		$st->execute();
		echo json_encode(array('code'=>1,'tanmu_id'=>$tanmu_id));
	}
	else if($action=='delete')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("UPDATE ".JRY_WB_DATABASE_MAINPAGE."tanmu SET `delete`=1,words='' WHERE tanmu_id=? LIMIT 1;");
		$st->bindParam(1,$tanmu_id);
		$st->execute();	
		echo json_encode(array('code'=>1,'tanmu_id'=>$tanmu_id));		
	}
	else if($action=='add'&&$_POST['words']!='')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("INSERT INTO ".JRY_WB_DATABASE_MAINPAGE."tanmu (`words`) VALUES (?);");
		$st->bindParam(1,$_POST['words']);
		$st->execute();
		echo json_encode(array('code'=>1));
	}
	else
	{
		echo json_encode(array('reasion'=>'unknow','code'=>0));
	}
?>