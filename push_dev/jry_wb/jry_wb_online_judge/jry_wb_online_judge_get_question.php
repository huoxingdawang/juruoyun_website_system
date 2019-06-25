<?php 
	include_once("jry_wb_online_judge_includes.php");
	function jry_wb_online_judge_get_question($conn,$user,$question_id,$class=array(),$manager=NULL)
	{
		if($manager!=NULL)
			return jry_wb_online_judge_get_question_admin($conn,$user,$question_id,$manager);
		if($question_id==0)
		{
			$q='SELECT * FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'question_list ';
			$a=0;
			foreach($class as $c)
				($q.=((($a++)==0?' WHERE (':' OR ').' JSON_CONTAINS(class,?) '));
			$q.=') AND `use`=1 ORDER BY rand() LIMIT 1';
			$st=$conn->prepare($q);
			$i=1;
			foreach($class as $c)
				$st->bindParam($i++,json_encode($c));
		}
		else
			($st=$conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'question_list WHERE question_id=? AND `use`=1 LIMIT 1'))->bindParam(1,$question_id);
		$st->execute();
		$all=$st->fetchAll();
		if(count($all)==0)
			return NULL;
		$st=$conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'error WHERE id=? AND question_id=? LIMIT 1');
		$st->bindParam(1,$user['id']);
		$st->bindParam(2,$all[0]['question_id']);
		$st->execute();
		$error=$st->fetchAll();
		return array(	'question_id'=>(int)$all[0]['question_id'],
						'id'=>$all[0]['id'],
						'submit'=>$all[0]['submit'],
						'right'=>$all[0]['right'],
						'question_type'=>$all[0]['question_type'],
						"question"=>$all[0]['question'],
						"config"=>json_decode($all[0]['config'],true),
						"exdata"=>json_decode($all[0]['exdata'],true),
						"source"=>$all[0]['source'],
						"lasttime"=>$all[0]['lasttime'],
						"class"=>json_decode(str_replace('"','',$all[0]['class']),true),
						"error"=>(count($error)==0)?NULL:array(	'error_id'=>$error[0]['error_id'],
																'question_id'=>$error[0]['question_id'],
																'id'=>$error[0]['id'],
																'lasttime'=>$error[0]['lasttime'],
																'maxtimes'=>$error[0]['maxtimes'],
																'times'=>$error[0]['times'],
																'extern'=>$error[0]['extern']
																)
					);
	}
	function jry_wb_online_judge_get_question_admin($conn,$user,$question_id,$manager)
	{
		jry_wb_check_compentence($user,['manageonlinejudgequestion']);		
		($st=$conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'question_list WHERE question_id=? LIMIT 1'))->bindParam(1,$question_id);
		$st->execute();
		$all=$st->fetchAll();
		if(count($all)==0)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700001,'extern'=>$question_id,'file'=>__FILE__,'line'=>__LINE__)));
		$all[0]['class']=json_decode(str_replace('"','',$all[0]['class']));
		if($manager==NULL)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700002,'extern'=>$question_id,'file'=>__FILE__,'line'=>__LINE__)));
		foreach($all[0]['class'] as $class)
			if($manager[$class]!==true)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700002,'extern'=>$question_id,'file'=>__FILE__,'line'=>__LINE__)));
		return array(	'question_id'=>		$all[0]['question_id'],
						'use'=>				$all[0]['use'],
						'lasttime'=>		$all[0]['lasttime'],
						'id'=>				$all[0]['id'],
						'submit'=>			$all[0]['submit'],
						'right'=>			$all[0]['right'],
						'question_type'=>	$all[0]['question_type'],
						'question'=>		mb_substr($all[0]['question'],0,64,'utf-8'),
						'source'=>			$all[0]['source'],
						'class'=>			$all[0]['class'],
						'config'=>			json_decode($all[0]['config']),
						'exdata'=>			json_decode($all[0]['exdata'])
						);
	}
	