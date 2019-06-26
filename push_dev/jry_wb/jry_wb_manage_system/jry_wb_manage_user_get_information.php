<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	try{jry_wb_check_compentence(NULL,array('use','manage','manageusers'));}catch(jry_wb_exception $e){echo $e->getMessage();exit();}
	$action=$_GET['action'];
	if($action=='list')
	{
		$conn2=jry_wb_connect_database();
		$st = $conn2->prepare('UPDATE '.JRY_WB_DATABASE_GENERAL.'users SET `order`=(SELECT MIN(`order`) FROM '.JRY_WB_DATABASE_MANAGE_SYSTEM.'competence WHERE `type` IN (SUBSTRING_INDEX(SUBSTRING(JSON_UNQUOTE('.JRY_WB_DATABASE_GENERAL_PREFIX.'users.type),2),\']\',1)))');
		$st->execute();
		$st = $conn2->prepare('SELECT `id`,`name`,`use` FROM '.JRY_WB_DATABASE_GENERAL.'users WHERE `lasttime`>? AND `order`>?');
		$st->bindValue(1,urldecode($_GET['lasttime']));
		$st->bindValue(2,$jry_wb_login_user['order']);
		$st->execute();
		$json=array();
		foreach($st->fetchAll()as $users)
			$json[]=array('id'=>(int)$users['id'],'use'=>$users['use'],'name'=>$users['name']);
		echo json_encode($json);
	}
	
?>