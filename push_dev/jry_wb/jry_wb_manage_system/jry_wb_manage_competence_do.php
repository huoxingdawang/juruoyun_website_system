<?php
	include_once("../tools/jry_wb_includes.php");
	$method=$_GET['method'];
	$login=jry_wb_print_head('',true,false,false,array('use','manage','managecompentence'),false);
	if($login!='ok')
	{
		echo json_encode(array('login'=>false,'reasion'=>$login));
		exit();			
	}	
	if($method=='chenge')
	{
		$name=preg_replace('/[^a-zA-Z]/','',urldecode($_GET['name']));
		$value=urldecode($_GET['value']);
		$type=urldecode($_GET['type']);
		$conn=jry_wb_connect_database();
		$st = $conn->prepare("UPDATE ".constant('jry_wb_database_manage_system')."competence SET `".$name."`=? WHERE type=? LIMIT 1;");
		//$st->bindParam(1,$name);
		$st->bindParam(1,$value);
		$st->bindParam(2,$type);
		$st->execute();
		echo json_encode(array('data'=>'Chenge '.$name.' to '.$value.' at '.$type.'OK!'));
	}
	if($method=='new')
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare("INSERT INTO ".constant('jry_wb_database_manage_system')."competence (`competencename`) VALUES ('new');");
		$st->execute();	
		echo json_encode(array('data'=>'New OK!'));
	}
	if($method=='add')
	{
		$name=preg_replace('/[^a-zA-Z]/','',urldecode($_GET['name']));
		$default=0;
		$conn=jry_wb_connect_database();
		$st = $conn->prepare("ALTER TABLE ".constant('jry_wb_database_manage_system')."competence ADD COLUMN `".$name."` TINYINT(1)  NOT NULL DEFAULT '".$default."';");
		$st->execute();	
		$st = $conn->prepare("UPDATE ".constant('jry_wb_database_manage_system')."competence SET `".$name."`=?;");
		$st->bindParam(1,$default);
		$st->execute();
		echo json_encode(array('data'=>'Add competence '.$name.' OK!'));
	}
	if($method=='delete')
	{
		$name=preg_replace('/[^a-zA-Z]/','',urldecode($_GET['name']));		
		$conn=jry_wb_connect_database();
		$st = $conn->prepare("ALTER TABLE ".constant('jry_wb_database_manage_system')."competence DROP COLUMN `".$name."`");
		$st->execute();
		echo json_encode(array('data'=>'Delete competence '.$name.' OK!'));
	}
?>