<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	try{jry_wb_check_compentence(NULL,array('use','manage','managetanmu'));}catch(jry_wb_exception $e){echo $e->getMessage();exit();}
	$action=$_GET['action'];
	if($action=='list')
	{
		$conn2=jry_wb_connect_database();
		$st = $conn2->prepare('SELECT * FROM '.JRY_WB_DATABASE_MAINPAGE.'tanmu ORDER BY id');
		$st->execute();
		$i=0;
		$json=array();
		foreach($st->fetchAll()as $hengfu)
				array_push($json,array('id'=>(int)$hengfu['id'],'words'=>$hengfu['words']));
		echo json_encode($json);
	}
?>