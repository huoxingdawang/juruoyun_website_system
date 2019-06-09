<?php
	include_once("jry_wb_online_judge_includes.php");
	function get_next_time($data,$error)
	{
		if($error)
			$minute=0.5;
		else if($data['times']>3)
			$minute=120;
		else if($data['times']>0)
			$minute=10;
		else if($data['times']>-2)//小于2次
			$minute=8;
		else if($data['times']>-4)//小于4次
			$minute=4;
		else if($data['times']>-6)//小于6次
			$minute=2;
		return date('Y-m-d H:i:s',time()+$minute*60);
	}	
	try
	{
		jry_wb_check_compentence();
		$class=json_decode(preg_replace('/(^\d\[\]\,)/i','',urldecode($_POST['class'])));
		$ans_question_id=$_POST['ans_question_id'];
		$question_id=$_POST['question_id'];
		$ans=$_POST['ans'];
		if($ans_question_id!='')
		{
			if(($question_ans=jry_wb_online_judge_get_question($conn,$jry_wb_login_user,$ans_question_id))==NULL)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700001,'extern'=>$question_id,'file'=>__FILE__,'line'=>__LINE__)));
			$check_result=-1;
			$mode=0;
			if($question_ans['question_type']==1)
			{
				if($ans==$question_ans['config']['ans'])
					$check_result=true;
				else
					$check_result=false;
				$mode=1;
			}
			else if($question_ans['question_type']==2)
			{
				if($ans==$question_ans['config']['ans'])
					$check_result=true;
				else
					$check_result=false;			
				$mode=1;
			}
			else if($question_ans['question_type']==3)
			{
				if($ans==$question_ans['config']['ans'])
					$check_result=true;
				else
					$check_result=false;			
				$mode=1;
			}
			else if($question_ans['question_type']==4)
			{
				$mode=1;
				
			}
			
			$st = $conn->prepare("SELECT times FROM ".JRY_WB_DATABASE_ONLINE_JUDGE."error WHERE id=? AND question_id=?");
			$st->bindParam(1,$jry_wb_login_user['id']);
			$st->bindParam(2,$ans_question_id);
			$st->execute();
			foreach($st->fetchAll() as $erroryuan);
			if($check_result===true)
				if($st->rowCount()==0)
					$q="INSERT INTO ".JRY_WB_DATABASE_ONLINE_JUDGE."error (times,maxtimes,extern,lasttime,id,question_id) VALUES (0,0,?,?,?,?);";
				else
					$q="UPDATE ".JRY_WB_DATABASE_ONLINE_JUDGE."error SET times=times+1,extern=?,lasttime=? WHERE id=? AND question_id=?;";
			else if($check_result===false)
				if($st->rowCount()==0)
					$q="INSERT INTO ".JRY_WB_DATABASE_ONLINE_JUDGE."error (times,maxtimes,extern,lasttime,id,question_id) VALUES (-1,-1,?,?,?,?);";
				else
					$q="UPDATE ".JRY_WB_DATABASE_ONLINE_JUDGE."error SET times=times-1,extern=?,lasttime=?,maxtimes= (case when maxtimes > times then times else maxtimes end)  WHERE id=? AND question_id=?;";
			else
				if($st->rowCount()==0)
					$q="INSERT INTO ".JRY_WB_DATABASE_ONLINE_JUDGE."error (times,maxtimes,extern,lasttime,id,question_id) VALUES (0,0,?,?,?,?);";
				else
					$q="UPDATE ".JRY_WB_DATABASE_ONLINE_JUDGE."error SET times=0,extern=?,lasttime=? WHERE id=? AND question_id=?;";				
			$st = $conn->prepare($q);
			$st->bindValue(1,json_encode(array('nexttime'=>($question_ans['config']['autojump']?get_next_time($erroryuan,$check_result):NULL))));
			$st->bindValue(2,jry_wb_get_time());
			$st->bindValue(3,$jry_wb_login_user['id']);
			$st->bindValue(4,$ans_question_id);
			$st->execute();
			$st = $conn->prepare('INSERT INTO '.JRY_WB_DATABASE_ONLINE_JUDGE.'logs (id,question_id,`time`,lasttime,ans,result,testconfig) VALUES (?,?,?,?,?,?,?);');
			$st->bindValue(1,$jry_wb_login_user['id']);
			$st->bindValue(2,$ans_question_id);
			$st->bindValue(3,jry_wb_get_time());
			$st->bindValue(4,jry_wb_get_time());
			$st->bindValue(5,$ans);
			$st->bindValue(6,json_encode(array('result'=>($check_result===true?'right':($check_result===false?'error':'undefined')))));
			$st->bindValue(7,json_encode(array('mode'=>$mode)));
			$st->execute();
			$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_ONLINE_JUDGE.'question_list SET `submit`=`submit`+1 , `right`=`right`+? WHERE question_id=?;');
			$st->bindValue(1,($check_result===true?1:0));
			$st->bindValue(2,$ans_question_id);
			$st->execute();
		}
		$question=NULL;
		$count='N/A';
		if(($question_ans['config']['autojump']&&$check_result===true)||($ans_question_id==''||$ans_question_id==0))
		{
			$count='N/A';
			if($question_id!=''&&$question_id!=0)
				$reason='point';
			else if(($buf=check_error_question($conn,$jry_wb_login_user,$ans_question_id,$class))!==false)
			{
				$count=$buf['count'];
				$question_id=$buf['question_id'];	
				$reason='error';
			}
			else if(($buf=check_not_do_question($conn,$jry_wb_login_user,$ans_question_id,$class))!==false)
			{
				$count=$buf['count'];
				$question_id=$buf['question_id'];	
				$reason='notdo';
			}				
			else
				$reason='rand';
			
			
			
			
			
			if(($question=jry_wb_online_judge_get_question($conn,$jry_wb_login_user,$question_id,$class))==NULL)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700001,'extern'=>$question_id,'file'=>__FILE__,'line'=>__LINE__)));
			unset($question['config']);
		}
		echo json_encode(array('code'=>true,'reason'=>$reason,'count'=>$count,'question'=>$question,'result'=>$check_result));
	}
	catch(jry_wb_exception $e)
	{
		echo $e->getMessage();
	}
	function check_error_question($conn,$user,$ans_question_id,$class)
	{
		$q='SELECT 	'.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'question_list.question_id
			FROM 	'.JRY_WB_DATABASE_ONLINE_JUDGE.'question_list , '.JRY_WB_DATABASE_ONLINE_JUDGE.'error 
			WHERE  	'.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'question_list.question_id='.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'error.question_id
			AND		'.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'error.id=?
			AND		'.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'error.times<0
			AND		'.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'error.question_id!=?
			AND		'.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'error.extern->\'$.nexttime\' < ?		
			AND		'.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'question_list.config->\'$.autojump\'=true
			';				
		$a=0;
		foreach($class as $c)
			($q.=((($a++)==0?' AND (':' OR ').' JSON_CONTAINS(class,?) '));
		if(count($class)!=0)
			$q.=')';
		$st = $conn->prepare($q);
		$st->bindParam(1,$user['id']);		
		$st->bindParam(2,$ans_question_id);
		$st->bindParam(3,jry_wb_get_time());
		$i=4;
		foreach($class as $c)
			$st->bindParam($i++,json_encode($c));
		$st->execute();	
		return ($st->rowCount()==0)?false:array('question_id'=>$st->fetchAll()[0]['question_id'],'count'=>$st->rowCount());
	}
	function check_not_do_question($conn,$user,$ans_question_id,$class)
	{
		$q='SELECT	'.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'question_list.question_id
			FROM 	'.JRY_WB_DATABASE_ONLINE_JUDGE.'question_list
			WHERE	'.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'question_list.question_id NOT IN (
			SELECT question_id	FROM	'.JRY_WB_DATABASE_ONLINE_JUDGE.'error 
								WHERE	'.JRY_WB_DATABASE_ONLINE_JUDGE.'error.id=?
			)			
			AND		'.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'question_list.question_id!=?
			AND		'.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'question_list.config->\'$.autojump\'=true
		';
		$a=0;
		foreach($class as $c)
			($q.=((($a++)==0?' AND (':' OR ').' JSON_CONTAINS(class,?) '));
		if(count($class)!=0)
			$q.=')';			
		$q.='ORDER BY rand()';
		$st = $conn->prepare($q);	
		$st->bindParam(1,$user['id']);	
		$st->bindParam(2,$ans_question_id);
		$i=3;
		foreach($class as $c)
			$st->bindParam($i++,json_encode($c));		
		$st->execute();
		return ($st->rowCount()==0)?false:array('question_id'=>$st->fetchAll()[0]['question_id'],'count'=>$st->rowCount());
	}
?>