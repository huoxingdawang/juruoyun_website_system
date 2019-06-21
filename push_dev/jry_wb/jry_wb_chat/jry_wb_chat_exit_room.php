<?php
	include_once("jry_wb_chat_includes.php");
	function jry_wb_chat_exit_room($conn,&$user,&$roomm)
	{
		jry_wb_check_compentence($user,['usechat'],$user['code']);
		if(jry_wb_chat_get_user($conn,$user)==null)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>'usechat')));
		if(is_string($roomm)||is_int($roomm))
			$room=jry_wb_chat_get_chat_room($conn,(int)$roomm,$user);
		else
			$room=$roomm;
		if($room==null)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>600001,'extern'=>array('chat_room'=>$roomm),'file'=>__FILE__,'line'=>__LINE__)));
		if(is_string($room['users']))
			$room['users']=json_decode($room['users']);
		if(($i=array_search($user['id'],$room['users']))===false)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>600000,'extern'=>array('chat_room_id'=>$room['chat_room_id']),'file'=>__FILE__,'line'=>__LINE__)));
		array_splice($room['users'],$i,1);
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_CHAT.'rooms SET  users=?, last_add_time=?,lasttime=? WHERE chat_room_id=?');
		$st->bindValue(1,json_encode($room['users']));
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,jry_wb_get_time());
		$st->bindValue(4,$room['chat_room_id']);
		$st->execute();
		if(($i=array_search((string)$room['chat_room_id'],$user['ch_ei']['chat_rooms']))===false)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>600000,'extern'=>array('chat_room_id'=>$room['chat_room_id']),'file'=>__FILE__,'line'=>__LINE__)));
		array_splice($user['ch_ei']['chat_rooms'],$i,1);
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_CHAT.'users SET  chat_rooms=?, lasttime=? WHERE id=?');
		$st->bindValue(1,json_encode($user['ch_ei']['chat_rooms']));
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$user['id']);		
		$st->execute();
		jry_wb_send_to_socket($user,$room['users'],200002,array('room'=>$room['chat_room_id']));		
		jry_wb_send_to_socket($user,$user['id'],200002,array('room'=>$room['chat_room_id'],'lasttime'=>jry_wb_get_time()));		
	}
