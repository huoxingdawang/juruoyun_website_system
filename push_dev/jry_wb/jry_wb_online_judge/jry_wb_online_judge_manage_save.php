<?php
	function jry_wb_online_judge_manage_save($conn,$user,$q,$question,$source,$config,$exdata)
	{
		jry_wb_check_compentence($user,['manageonlinejudgequestion']);
		if($q==NULL)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700001,'file'=>__FILE__,'line'=>__LINE__)));
		if(is_null(json_decode($config)))
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700003,'file'=>__FILE__,'line'=>__LINE__)));
		if(is_null(json_decode($exdata)))
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700004,'file'=>__FILE__,'line'=>__LINE__)));
		$st=$conn->prepare('UPDATE '.JRY_WB_DATABASE_ONLINE_JUDGE.'question_list SET `question`=?,`source`=?,`config`=?,`exdata`=?,`lasttime`=? WHERE question_id=? LIMIT 1');
		$st->bindValue(1,$question);
		$st->bindValue(2,$source);
		$st->bindValue(3,$config);
		$st->bindValue(4,$exdata);
		$st->bindValue(5,jry_wb_get_time());
		$st->bindValue(6,$q['question_id']);
		$st->execute();
		return array('code'=>true);
	}
