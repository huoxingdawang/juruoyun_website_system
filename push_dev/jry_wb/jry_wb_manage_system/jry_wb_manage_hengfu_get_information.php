<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	try{jry_wb_check_compentence(NULL,array('use','manage','managehengfu'));}catch(jry_wb_exception $e){echo $e->getMessage();exit();}
	$action=$_GET['action'];
	if($action=='list')
	{
		$conn2=jry_wb_connect_database();
		$st = $conn2->prepare('SELECT * FROM '.JRY_WB_DATABASE_MAINPAGE.'hengfu ORDER BY hengfu_id');
		$st->execute();
		$i=0;
		$json=array();
		foreach($st->fetchAll()as $hengfu)
				array_push($json,array('hengfu_id'=>(int)$hengfu['hengfu_id'],'delete'=>$hengfu['delete'],'words'=>$hengfu['words'],'enable'=>$hengfu['enable']));
		echo json_encode(array('code'=>true,'data'=>$json));
	}
?>