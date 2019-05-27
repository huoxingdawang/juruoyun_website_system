<?php
	include_once("jry_wb_chat_includes.php");
	function jry_wb_chat_get_chat_room($conn,$room_id,$lasttime='')
	{
		if($lasttime=='')
			$lasttime='1926-08-17 00:00:00';
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_CHAT.'rooms WHERE chat_room_id=? AND lasttime>? LIMIT 1');
		$st->bindValue(1,$room_id,PDO::PARAM_INT);
		$st->bindValue(2,$lasttime);
		$st->execute();
		$all=$st->fetchAll();
		if(count($all)===0)
			return null;
		if($all[0]['users']==NULL)
			$all[0]['users']=array();
		else
			$all[0]['users']=json_decode($all[0]['users']);
		if($all[0]['head']==NULL)
			$all[0]['head']=array('type'=>'default');
		else
			$all[0]['head']=json_decode($all[0]['head'],true);		
		return array(	'chat_room_id'=>$all[0]['chat_room_id'],
						'id'=>$all[0]['id'],
						'name'=>$all[0]['name'],
						'head'=>$all[0]['head'],
						'cream_time'=>$all[0]['cream_time'],
						'lasttime'=>$all[0]['lasttime'],
						'last_add_time'=>$all[0]['last_add_time'],
						'last_say_time'=>$all[0]['last_say_time'],
						'say_count'=>$all[0]['say_count'],
						'users'=>$all[0]['users']);
	}
