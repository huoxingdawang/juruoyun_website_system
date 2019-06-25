<?php 
	include_once("jry_wb_online_judge_includes.php");
	function jry_wb_online_judge_get_question_list($conn,$lasttime='1926-08-17 00:00:00',$manager=NULL,$user=NULL)
	{
		if($manager!=NULL)
			jry_wb_check_compentence($user,['manageonlinejudgequestion']);
		if($lasttime=='')
			$lasttime='1926-08-17 00:00:00';
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'question_list WHERE lasttime>?');
		$st->bindParam(1,$lasttime);
		$st->execute();
		$json=array();
		$admin=false;
		foreach($st->fetchAll() as $one)
		{
			$one['class']=json_decode(str_replace('"','',$one['class']));
			if($admin=($manager!=NULL))
				foreach($one['class'] as $class)
					if(!($admin&=($manager[$class]===true)))
						break;
			$json[]=array(	'question_id'=>		$one['question_id'],
							"use"=>				$one['use'],
							"lasttime"=>		$one['lasttime'],
							'id'=>				($one['use']||$admin?$one['id']:''),
							'submit'=>			($one['use']||$admin?$one['submit']:''),
							'right'=>			($one['use']||$admin?$one['right']:''),
							'question_type'=>	($one['use']||$admin?$one['question_type']:''),
							"question"=>		($one['use']||$admin?mb_substr($one['question'],0,64,'utf-8'):''),
							"source"=>			($one['use']||$admin?$one['source']:''),
							"class"=>			($one['use']||$admin?$one['class']:''),
							"config"=>			($admin?json_decode($one['config']):NULL),
							"exdata"=>			($admin?json_decode($one['exdata']):NULL)
							);
			$admin=false;
		}
		return $json;
	}