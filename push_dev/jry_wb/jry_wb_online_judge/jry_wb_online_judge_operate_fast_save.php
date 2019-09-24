<?php
	include_once('jry_wb_online_judge_includes.php');
	function jry_wb_online_judge_operate_fast_save($mode,$time='')
	{
		if(($file=fopen('jry_wb_online_judge.fast_save_message','r'))==false)
		{
			fclose($file);
			$conn=jry_wb_connect_database();
			$data=array();
			if($mode=='classes')
			{
				$data['classes']=$time;
				$st = $conn->prepare('SELECT lasttime FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'logs ORDER BY lasttime DESC LIMIT 1;');
				$st->execute();
				$all=$st->fetchAll();
				$data['logs']=(count($all)==0?'1926-08-17 00:00:00':$all[0]['lasttime']);
				$st = $conn->prepare('SELECT lasttime FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'question_list ORDER BY lasttime DESC LIMIT 1;');
				$st->execute();
				$all=$st->fetchAll();
				$data['question_list']=(count($all)==0?'1926-08-17 00:00:00':$all[0]['lasttime']);
			}
			else if($mode=='logs')
			{
				$data['logs']=$time;
				$st = $conn->prepare('SELECT lasttime FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'classes ORDER BY lasttime DESC LIMIT 1;');
				$st->execute();
				$all=$st->fetchAll();
				$data['classes']=(count($all)==0?'1926-08-17 00:00:00':$all[0]['lasttime']);
				$st = $conn->prepare('SELECT lasttime FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'question_list ORDER BY lasttime DESC LIMIT 1;');
				$st->execute();
				$all=$st->fetchAll();
				$data['question_list']=(count($all)==0?'1926-08-17 00:00:00':$all[0]['lasttime']);
			}
			else if($mode=='question_list')
			{
				$data['question_list']=$time;
				$st = $conn->prepare('SELECT lasttime FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'classes ORDER BY lasttime DESC LIMIT 1;');
				$st->execute();
				$all=$st->fetchAll();
				$data['classes']=(count($all)==0?'1926-08-17 00:00:00':$all[0]['lasttime']);
				$st = $conn->prepare('SELECT lasttime FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'logs ORDER BY lasttime DESC LIMIT 1;');
				$st->execute();
				$all=$st->fetchAll();
				$data['logs']=(count($all)==0?'1926-08-17 00:00:00':$all[0]['lasttime']);
			}
			else if($mode=='get')
			{
				$st = $conn->prepare('SELECT lasttime FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'classes ORDER BY lasttime DESC LIMIT 1;');
				$st->execute();
				$all=$st->fetchAll();
				$data['classes']=(count($all)==0?'1926-08-17 00:00:00':$all[0]['lasttime']);
				$st = $conn->prepare('SELECT lasttime FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'logs ORDER BY lasttime DESC LIMIT 1;');
				$st->execute();
				$all=$st->fetchAll();
				$data['logs']=(count($all)==0?'1926-08-17 00:00:00':$all[0]['lasttime']);	
				$st = $conn->prepare('SELECT lasttime FROM '.JRY_WB_DATABASE_ONLINE_JUDGE.'question_list ORDER BY lasttime DESC LIMIT 1;');
				$st->execute();
				$all=$st->fetchAll();
				$data['question_list']=(count($all)==0?'1926-08-17 00:00:00':$all[0]['lasttime']);				
			}
			else
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700000,'file'=>__FILE__,'line'=>__LINE__)));
			$file2=fopen('jry_wb_online_judge.fast_save_message','w');
			fwrite($file2,json_encode($data));
			fclose($file2);
			return $data;
		}
		else
		{
			$data=json_decode(fread($file,filesize('jry_wb_online_judge.fast_save_message')),true);
			fclose($file);
			if($mode=='classes')
				$data['classes']=$time;
			else if($mode=='logs')
				$data['logs']=$time;
			else if($mode=='question_list')
				$data['question_list']=$time;
			else if($mode=='get')
				$data=$data;
			else
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>700000,'file'=>__FILE__,'line'=>__LINE__)));
			$file2=fopen('jry_wb_online_judge.fast_save_message','w');
			fwrite($file2,json_encode($data));
			fclose($file2);
			return $data;
		}
	}
?>	