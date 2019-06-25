<?php
	function jry_wb_online_judge_manage_save($conn,$user,$manager,$q,$question,$source,$config,$exdata,$question_type,$classes)
	{
		jry_wb_check_compentence($user,['manageonlinejudgequestion']);
		if($q==NULL)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700001,'file'=>__FILE__,'line'=>__LINE__)));
		if($manager==NULL)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700002,'extern'=>$q['question_id'],'file'=>__FILE__,'line'=>__LINE__)));
		foreach($q['class'] as $class)
			if($manager[$class]!==true)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700002,'extern'=>$q['question_id'],'file'=>__FILE__,'line'=>__LINE__)));		
		if(!is_array($classes))
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700006,'file'=>__FILE__,'line'=>__LINE__)));
		$classes=array_unique($classes);
		foreach($classes as $class)
			if($manager[$class]!==true)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700002,'extern'=>$q['question_id'],'file'=>__FILE__,'line'=>__LINE__)));	
		foreach($q['class'] as $class)
			if(($buf=array_search($class))!==false)
				unset($old_classes[$buf]);
		foreach($old_classes as $class)
			if($manager[$class]!==true)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700002,'extern'=>$q['question_id'],'file'=>__FILE__,'line'=>__LINE__)));			
		if(is_nan($question_type)||$question_type<1||$question_type>4)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700005,'file'=>__FILE__,'line'=>__LINE__)));
		if(is_null(json_decode($config)))
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700003,'file'=>__FILE__,'line'=>__LINE__)));
		if(is_null(json_decode($exdata)))
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700004,'file'=>__FILE__,'line'=>__LINE__)));
		$st=$conn->prepare('UPDATE '.JRY_WB_DATABASE_ONLINE_JUDGE.'question_list SET `question_type`=?,`question`=?,`source`=?,`config`=?,`exdata`=?,`lasttime`=?,`class`=? WHERE question_id=? LIMIT 1');
		$st->bindValue(1,$question_type);
		$st->bindValue(2,$question);
		$st->bindValue(3,$source);
		$st->bindValue(4,$config);
		$st->bindValue(5,$exdata);
		$st->bindValue(6,jry_wb_get_time());
		$st->bindValue(7,json_encode($classes));
		$st->bindValue(8,$q['question_id']);
		$st->execute();
		return array('code'=>true);
	}
