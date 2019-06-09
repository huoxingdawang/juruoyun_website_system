<?php	
	include_once("../tools/jry_wb_includes.php");
	include_once("jry_wb_online_judge_includes.php");
	$ojclassid		=$_GET[ojclassid];
	$action			=$_GET[action];
	$conn2=jry_wb_connect_database();
	$start_time = microtime(true);
	if($action=='question_list')
		echo json_encode(jry_wb_online_judge_get_question_list($conn,$_GET['lasttime']));
	else if($action=='logs')
		echo json_encode(jry_wb_online_judge_get_logs($conn,$_GET['lasttime']));
	else if($action=='classes')
		echo json_encode(jry_wb_online_judge_get_classes($conn,$_GET['lasttime']));
	else if($action=='error')
	{
		try{jry_wb_check_compentence();}catch(jry_wb_exception $e){echo $e->getMessage();exit();}
		echo json_encode(jry_wb_online_judge_get_error($conn,$jry_wb_login_user,$_GET['lasttime']));
	}
?>