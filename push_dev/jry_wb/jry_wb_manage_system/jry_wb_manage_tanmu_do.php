<?php
	include_once("../tools/jry_wb_includes.php");
	try{jry_wb_check_compentence(NULL,array('use','manage','managetanmu'));}catch(jry_wb_exception $e){echo $e->getMessage();exit();}
	$action=$_GET['action'];
	$id=$_POST['id'];
	if($action=='chenge'&&$_POST['words']!='')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("UPDATE ".constant('jry_wb_database_mainpage')."tanmu SET words=? WHERE id=? LIMIT 1;");
		$st->bindParam(1,$_POST['words']);
		$st->bindParam(2,$id);			
		$st->execute();
		echo json_encode(array('code'=>1,'id'=>$id));
	}
	else if($action=='delete')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("DELETE FROM ".constant('jry_wb_database_mainpage')."tanmu WHERE id=? LIMIT 1;");
		$st->bindParam(1,$id);
		$st->execute();	
		echo json_encode(array('code'=>1,'id'=>$id));		
	}
	else if($action=='add'&&$_POST['words']!='')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("INSERT INTO ".constant('jry_wb_database_mainpage')."tanmu (`words`) VALUES (?);");
		$st->bindParam(1,$_POST['words']);
		$st->execute();
		echo json_encode(array('code'=>1));
	}
	else
	{
		echo json_encode(array('reasion'=>'unknow','code'=>0));
	}
?>