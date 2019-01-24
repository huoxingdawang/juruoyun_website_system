<?php
	include_once("../tools/jry_wb_includes.php");
	$login=jry_wb_print_head("",true,true,false,array('use','manage','managehengfu'),false);	
	$action=$_GET['action'];
	if($login!='ok')
	{
		echo json_encode(array('login'=>false,'reasion'=>$login));
		exit();	
	}
	if($action=='list')
	{
		$conn2=jry_wb_connect_database();
		$st = $conn2->prepare('SELECT * FROM '.constant('jry_wb_database_mainpage').'hengfu ORDER BY id');
		$st->execute();
		$i=0;
		$json=array();
		foreach($st->fetchAll()as $hengfu)
				array_push($json,array('id'=>(int)$hengfu['id'],'words'=>$hengfu['words']));
		echo json_encode($json);
	}
?>