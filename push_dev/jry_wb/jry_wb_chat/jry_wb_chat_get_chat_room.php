<?php
	include_once("jry_wb_chat_includes.php");
	function jry_wb_chat_get_chat_room($conn,$room_id,$user,$lasttime='')
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
		return jry_wb_chat_get_chat_room_by_data($all[0],$user);
	}
	function jry_wb_chat_get_chat_room_by_data($data,$user)
	{
		if($data['users']==NULL)
			$data['users']=array();
		else
			$data['users']=json_decode($data['users']);
		if(count($data['users'])==2&&$data['big']==false&&(!in_array($user['id'],$data['users'])))
			return NULL;
		if($data['head']==NULL)
			$data['head']=array('type'=>'default');
		else
			$data['head']=json_decode($data['head'],true);		
		return array(	'chat_room_id'=>$data['chat_room_id'],
						'id'=>$data['id'],
						'name'=>$data['name'], 
						'head'=>$data['head'],
						'cream_time'=>$data['cream_time'],
						'lasttime'=>$data['lasttime'],
						'last_add_time'=>$data['last_add_time'],
						'last_say_time'=>$data['last_say_time'],
						'say_count'=>$data['say_count'],
						'big'=>$data['big'],
						'users'=>$data['users']);		
	}
