<?php
	function jry_wb_online_judge_manage_set_use($conn,$user,$question,$use)
	{
		jry_wb_check_compentence($user,['manageonlinejudgequestion']);
		if($question==NULL)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700001,'file'=>__FILE__,'line'=>__LINE__)));
		$st=$conn->prepare('UPDATE '.JRY_WB_DATABASE_ONLINE_JUDGE.'question_list SET `use`=?,`lasttime`=? WHERE question_id=? LIMIT 1');
		$st->bindValue(1,$use);
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$question['question_id']);
		$st->execute();
		return array('code'=>true);
	}
