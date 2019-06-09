<?php 
	include_once("jry_wb_online_judge_includes.php");
	function jry_wb_online_judge_get_error($conn,$user,$lasttime='1926-08-17 00:00:00')
	{
		if($lasttime=='')
			$lasttime='1926-08-17 00:00:00';
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'error WHERE lasttime>? AND id=?');
		$st->bindParam(1,$lasttime);
		$st->bindParam(2,$user['id']);		
		$st->execute();
		$json=array();
		foreach($st->fetchAll() as $one)
			$json[]=array(	'error_id'=>$one['error_id'],
							'question_id'=>$one['question_id'],
							'id'=>$one['id'],
							"lasttime"=>$one['lasttime'],
							"maxtimes"=>$one['maxtimes'],
							"times"=>$one['times'],
							"extern"=>json_decode($one['extern']),
							);
		return $json;
	}