<?php
	function jry_wb_online_judge_manage_new($conn,$user)
	{
		jry_wb_check_compentence($user,['manageonlinejudgequestion','manageonlinejudgeaddquestion']);
		$st=$conn->prepare('INSERT INTO '.JRY_WB_DATABASE_ONLINE_JUDGE.'question_list (`id`,`lasttime`) VALUES(?,?)');
		$st->bindValue(1,$user['id']);
		$st->bindValue(2,jry_wb_get_time());
		$st->execute();
		return array('code'=>true,'question_id'=>$conn->lastInsertId());
	}
