<?php
	include_once("../tools/jry_wb_includes.php");
	try
	{
		jry_wb_print_head("",true,true,false,array('use','manage','manageusers'),false);	
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
		$st = $conn2->prepare('SELECT * FROM '.constant('jry_wb_database_manage_system').'competence INNER JOIN '.constant('jry_wb_database_general').'users  ON ('.constant('jry_wb_database_general_prefix').'users.type = '.constant('jry_wb_database_manage_system_prefix').'competence.type AND lasttime>?) ORDER BY id');
		$st->bindParam(1,urldecode($_GET['lasttime']));
		$st->execute();
		$i=0;
		$json=array();
		foreach($st->fetchAll()as $users)
			if(($jry_wb_login_user['order']<$users['order']))
				array_push($json,array('id'=>(int)$users['id'],'use'=>$users['use'],'name'=>$users['name']));
		echo json_encode($json);
	}
	
?>