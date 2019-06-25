<?php 
	include_once("jry_wb_online_judge_manage_includes.php");
	try
	{	
		jry_wb_check_compentence(NULL,['manageonlinejudge','use','manage']);
		$manager=NULL;
		$action=$_GET['action'];
		$st = $conn->prepare('SELECT class_id FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'classes WHERE JSON_CONTAINS(manager,?)');
		$st->bindParam(1,$jry_wb_login_user['id']);
		$st->execute();
		$manager=[];
		foreach ($st->fetchAll() as $one)
			$manager[$one['class_id']]=true;
		if($action=='set_use')
			echo json_encode(jry_wb_online_judge_manage_set_use($conn,$jry_wb_login_user,jry_wb_online_judge_get_question_admin($conn,$jry_wb_login_user,$_POST['question_id'],$manager),$_POST['use']));
		else if($action=='save')
			echo json_encode(jry_wb_online_judge_manage_save($conn,$jry_wb_login_user,jry_wb_online_judge_get_question_admin($conn,$jry_wb_login_user,$_POST['question_id'],$manager),str_replace('/37','&',str_replace('/43','+',$_POST['question'])),str_replace('/37','&',str_replace('/43','+',$_POST['source'])),str_replace('/37','&',str_replace('/43','+',$_POST['config'])),str_replace('/37','&',str_replace('/43','+',$_POST['exdata']))));
		else if($action=='new')
			echo json_encode(jry_wb_online_judge_manage_new($conn,$jry_wb_login_user));
		else
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>000000,'file'=>__FILE__,'line'=>__LINE__)));
	}
	catch(jry_wb_exception $e)
	{
		die($e->getMessage());
	}