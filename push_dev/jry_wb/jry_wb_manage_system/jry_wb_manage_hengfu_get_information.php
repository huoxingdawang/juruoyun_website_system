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
	if($action=='list')
	{
		$conn2=jry_wb_connect_database();
		$st = $conn2->prepare('SELECT * FROM '.constant('jry_wb_database_mainpage').'hengfu ORDER BY hengfu_id');
		$st->execute();
		$i=0;
		$json=array();
		foreach($st->fetchAll()as $hengfu)
				array_push($json,array('hengfu_id'=>(int)$hengfu['hengfu_id'],'words'=>$hengfu['words'],'enable'=>$hengfu['enable']));
		echo json_encode($json);
	}
?>