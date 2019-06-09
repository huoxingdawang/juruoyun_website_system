<?php 
	include_once("jry_wb_online_judge_includes.php");
	function jry_wb_online_judge_get_logs($conn,$lasttime='1926-08-17 00:00:00')
	{
		if($lasttime=='')
			$lasttime='1926-08-17 00:00:00';
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'logs WHERE lasttime>?');
		$st->bindParam(1,$lasttime);
		$st->execute();
		$json=array();
		foreach($st->fetchAll() as $one)
			$json[]=array(	'log_id'=>$one['log_id'],
							'question_id'=>$one['question_id'],
							'id'=>$one['id'],
							"time"=>$one['time'],
							"lasttime"=>$one['lasttime'],
							"ans"=>$one['ans'],
							"result"=>json_decode($one['result']),
							"testconfig"=>json_decode($one['testconfig'])
							);
		return $json;
	}