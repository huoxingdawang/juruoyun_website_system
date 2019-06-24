<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	try{jry_wb_check_compentence(NULL,array('use','manage','manageusers'));}catch(jry_wb_exception $e){echo $e->getMessage();exit();}
	$action=$_GET['action'];
	if($action=='list')
	{
		$conn2=jry_wb_connect_database();
		$st = $conn2->prepare('SELECT * FROM '.JRY_WB_DATABASE_MANAGE_SYSTEM.'competence INNER JOIN '.JRY_WB_DATABASE_GENERAL.'users  ON ('.JRY_WB_DATABASE_GENERAL_PREFIX.'users.type = '.JRY_WB_DATABASE_MANAGE_SYSTEM_PREFIX.'competence.type AND lasttime>?) ORDER BY id');
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