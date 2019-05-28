<?php
	include_once("jry_wb_chat_includes.php");
	function jry_wb_chat_delete_room($conn,&$user,&$roomm)
	{
		if(!$user['usechat'])
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>'usechat')));
		if(!$user['deletechatroom'])
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>'deletechatroom')));
		if(is_string($roomm)||is_int($roomm))
			$room=jry_wb_chat_get_chat_room($conn,(int)$roomm);
		else
			$room=$roomm;
		if($room==null)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>600001,'extern'=>array('chat_room'=>$roomm),'file'=>__FILE__,'line'=>__LINE__)));
		if($user['id']!=$room['id'])
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>'deletechatroom')));
		if(is_string($room['users']))
			$room['users']=json_decode($room['users']);		
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_CHAT.'rooms SET `delete`=1,last_say_time="1926-08-17 00:00:00",lasttime=? WHERE  chat_room_id=?');
		$st->bindValue(1,jry_wb_get_time());
		$st->bindValue(2,$room['chat_room_id']);
		$st->execute();
		$st = $conn->prepare('DELETE FROM '.JRY_WB_DATABASE_CHAT.'message WHERE  chat_room_id=?');
		$st->bindValue(1,$room['chat_room_id']);
		$st->execute();
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_CHAT.'users SET lasttime=?,chat_rooms=JSON_REMOVE(chat_rooms,JSON_UNQUOTE(JSON_SEARCH(chat_rooms,"one",?))) WHERE  JSON_CONTAINS(chat_rooms,?)');
		$st->bindValue(1,jry_wb_get_time());
		$st->bindValue(2,(string)$room['chat_room_id']);
		$st->bindValue(3,json_encode(array((string)$room['chat_room_id'])));
		$st->execute();
		jry_wb_send_to_socket($user,$room['users'],200004,array('room'=>$room['chat_room_id'],'lasttime'=>jry_wb_get_time()));
	}
