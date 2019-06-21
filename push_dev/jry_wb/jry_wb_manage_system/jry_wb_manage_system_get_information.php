<?php
	include_once("../tools/jry_wb_includes.php");
	function competence_check($data)
	{
		global $jry_wb_login_user;
		$data=json_decode($data);
		foreach($data as $one)
			if(!($jry_wb_login_user['compentence'][$one]))
				return false;
		return true;
	}
	function load($father,$conn)
	{
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_MANAGE_SYSTEM.'list where father=? ORDER BY name ASC');
		$st->bindParam(1,$father);
		$st->execute();
		foreach($st->fetchAll()as $list)
			if(competence_check($list['competence']))
				$ans[]=array('url'=>$list['url'],'name'=>$list['name'],'competence'=>$list['competence'],'hash'=>$list['hash'],'is_script'=>$list['is_script'],'init_script'=>$list['init_script'],'run_script'=>$list['run_script'],'children'=>$list['next']==NULL?NULL:load($list['next'],$conn));
		return $ans;
	}
	echo json_encode(load('root',jry_wb_connect_database()));
?>