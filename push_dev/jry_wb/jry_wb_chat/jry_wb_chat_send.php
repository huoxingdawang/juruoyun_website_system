<?php
	include_once("jry_wb_chat_includes.php");
	function jry_wb_chat_send($conn,$sender,&$roomm,$message)
	{
		if($message==NULL||$message=='')
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>600003,'extern'=>array('chat_room'=>$roomm),'file'=>__FILE__,'line'=>__LINE__)));
		if(!$sender['usechat'])
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>'usechat')));		
		if(is_string($roomm)||is_int($roomm))
			$room=jry_wb_chat_get_chat_room($conn,(int)$roomm);
		else
			$room=$roomm;
		if($room==null)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>600001,'extern'=>array('chat_room'=>$roomm),'file'=>__FILE__,'line'=>__LINE__)));
		if(is_string($room['users']))
			$room['users']=json_decode($room['users']);
		if(array_search($sender['id'],$room['users'])===false)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>600002,'extern'=>array('chat_room_id'=>$room['chat_room_id']),'file'=>__FILE__,'line'=>__LINE__)));
		$st = $conn->prepare('INSERT INTO '.JRY_WB_DATABASE_CHEAT.'message (id,chat_room_id,message,send_time) VALUES (?,?,?,?)');
		$st->bindValue(1,$sender['id']);
		$st->bindValue(2,$room['chat_room_id']);
		$st->bindValue(3,$message);
		$st->bindValue(4,jry_wb_get_time());
		$st->execute();
		$chat_text_id=$conn->lastInsertId();
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_CHEAT.'rooms SET say_count=say_count+1 , last_say_time=?,lasttime=? WHERE chat_room_id=?');
		$st->bindValue(1,jry_wb_get_time());
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$room['chat_room_id']);
		$st->execute();
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_CHEAT.'users SET  say_count=say_count+1, lasttime=? WHERE id=?');
		$st->bindValue(1,jry_wb_get_time());
		$st->bindValue(2,$sender['id']);		
		$st->execute();
		jry_wb_send_to_socket($sender,$room['users'],200000,array('room'=>$room['chat_room_id'],'message'=>$message,'send_time'=>jry_wb_get_time(),'chat_text_id'=>$chat_text_id));
	}
