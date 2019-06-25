<?php	
	include_once("../jry_wb_tools/jry_wb_includes.php");
	include_once("jry_wb_online_judge_includes.php");
	$ojclassid		=$_GET[ojclassid];
	$action			=$_GET[action];
	try
	{
		$manager=NULL;
		if($_GET['admin_mode']==1)
		{
			jry_wb_check_compentence(NULL,['manageonlinejudge','use','manage']);
			$st = $conn->prepare('SELECT class_id FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'classes WHERE JSON_CONTAINS(manager,?)');
			$st->bindParam(1,$jry_wb_login_user['id']);
			$st->execute();
			$manager=[];
			foreach ($st->fetchAll() as $one)
				$manager[$one['class_id']]=true;
		}
		if($action=='question_list')
			echo json_encode(jry_wb_online_judge_get_question_list($conn,$_GET['lasttime'],$manager,$jry_wb_login_user));
		else if($action=='logs')
			echo json_encode(jry_wb_online_judge_get_logs($conn,$_GET['lasttime']));
		else if($action=='classes')
			echo json_encode(jry_wb_online_judge_get_classes($conn,$_GET['lasttime']));
		else if($action=='error')
			echo json_encode(jry_wb_online_judge_get_error($conn,$jry_wb_login_user,$_GET['lasttime']));
	}
	catch(jry_wb_exception $e)
	{
		die($e->getMessage());
	}
?>