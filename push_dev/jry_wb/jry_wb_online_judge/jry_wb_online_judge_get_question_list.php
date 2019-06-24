<?php 
	include_once("jry_wb_online_judge_includes.php");
	function jry_wb_online_judge_get_question_list($conn,$lasttime='1926-08-17 00:00:00')
	{
		if($lasttime=='')
			$lasttime='1926-08-17 00:00:00';
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'question_list WHERE lasttime>?');
		$st->bindParam(1,$lasttime);
		$st->execute();
		$json=array();
		foreach($st->fetchAll() as $one)
			$json[]=array(	'question_id'=>		$one['question_id'],
							"use"=>				$one['use'],
							"lasttime"=>		$one['lasttime'],
							'id'=>				($one['use']?$one['id']:''),
							'question_type'=>	($one['use']?$one['question_type']:''),
							"question"=>		($one['use']?mb_substr($one['question'],0,64,'utf-8'):''),
							"source"=>			($one['use']?$one['source']:''),
							"class"=>			($one['use']?json_decode(str_replace('"','',$one['class'])):'')
							);
		return $json;
	}