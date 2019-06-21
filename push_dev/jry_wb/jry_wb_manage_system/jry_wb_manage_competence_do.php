<?php
	include_once("../tools/jry_wb_includes.php");
	$method=$_GET['method'];
	try{jry_wb_check_compentence(NULL,array('use','manage','managecompentence'));}catch(jry_wb_exception $e){echo $e->getMessage();exit();}
	if($method=='chenge')
	{
		$name=preg_replace('/[^a-zA-Z]/','',urldecode($_GET['name']));
		$value=urldecode($_GET['value']);
		$type=urldecode($_GET['type']);
		$conn=jry_wb_connect_database();
		$st = $conn->prepare("UPDATE ".JRY_WB_DATABASE_MANAGE_SYSTEM."competence SET `".$name."`=? WHERE type=? LIMIT 1;");
		$st->bindParam(1,$value);
		$st->bindParam(2,$type);
		$st->execute();
		echo json_encode(array('code'=>true,'data'=>'Chenge '.$name.' to '.$value.' at '.$type.'OK!'));
	}
	if($method=='new')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("INSERT INTO ".JRY_WB_DATABASE_MANAGE_SYSTEM."competence (`competencename`) VALUES ('new');");
		$st->execute();	
		echo json_encode(array('code'=>true,'data'=>'New OK!'));
	}
	if($method=='add')
	{
		$name=preg_replace('/[^a-zA-Z]/','',urldecode($_GET['name']));
		$default=0;
		$conn=jry_wb_connect_database();
		$st = $conn->prepare("ALTER TABLE ".JRY_WB_DATABASE_MANAGE_SYSTEM."competence ADD COLUMN `".$name."` TINYINT(1)  NOT NULL DEFAULT '".$default."';");
		$st->execute();	
		$st = $conn->prepare("UPDATE ".JRY_WB_DATABASE_MANAGE_SYSTEM."competence SET `".$name."`=?;");
		$st->bindParam(1,$default);
		$st->execute();
		echo json_encode(array('code'=>true,'data'=>'Add competence '.$name.' OK!'));
	}
	if($method=='delete')
	{
		$name=preg_replace('/[^a-zA-Z]/','',urldecode($_GET['name']));		
		$conn=jry_wb_connect_database();
		$st = $conn->prepare("ALTER TABLE ".JRY_WB_DATABASE_MANAGE_SYSTEM."competence DROP COLUMN `".$name."`");
		$st->execute();
		echo json_encode(array('code'=>true,'data'=>'Delete competence '.$name.' OK!'));
	}
?>