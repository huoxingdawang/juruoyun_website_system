<?php 
	include_once("jry_wb_online_judge_includes.php");
	function jry_wb_online_judge_get_classes($conn,$lasttime='1926-08-17 00:00:00')
	{
		if($lasttime=='')
			$lasttime='1926-08-17 00:00:00';
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'classes WHERE lasttime>?');
		$st->bindParam(1,$lasttime);
		$st->execute();
		$json=array();
		foreach($st->fetchAll() as $one)
			$json[]=array(	'class_id'=>$one['class_id'],
							'class_name'=>$one['class_name'],
							'id'=>$one['id'],
							"lasttime"=>$one['lasttime'],
							"father"=>$one['father'],
							"manager"=>json_decode(str_replace('"','',$one['manager'])),
							);
		return $json;
	}