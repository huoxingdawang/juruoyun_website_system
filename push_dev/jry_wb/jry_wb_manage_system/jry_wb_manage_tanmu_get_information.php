<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	try{jry_wb_check_compentence(NULL,array('use','manage','managetanmu'));}catch(jry_wb_exception $e){echo $e->getMessage();exit();}
	$action=$_GET['action'];
	if($action=='list')
	{
		$conn2=jry_wb_connect_database();
		$st = $conn2->prepare('SELECT * FROM '.JRY_WB_DATABASE_MAINPAGE.'tanmu ORDER BY tanmu_id');
		$st->execute();
		$i=0;
		$json=array();
		foreach($st->fetchAll()as $tanmu)
				array_push($json,array('tanmu_id'=>(int)$tanmu['tanmu_id'],'delete'=>$tanmu['delete'],'words'=>$tanmu['words']));
		echo json_encode(array('code'=>true,'data'=>$json));
	}
?>