<?php
	include_once("jry_wb_cheat_includes.php");
	function jry_wb_cheat_send_call_back($conn,$sender,&$room,$text)
	{
		if(is_int($room))
			$room=jry_wb_cheat_get_cheat_room($conn,$room);
		if($room==null)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>600001,'file'=>__FILE__,'line'=>__LINE__)));
		if(is_string($room['users']))
			$room['users']=json_decode($room['users']);
		if(array_search($sender['id'],$room['users'])===false)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>600000,'file'=>__FILE__,'line'=>__LINE__)));
		$st = $conn->prepare('INSERT INTO '.JRY_WB_DATABASE_CHEAT.'text (sender_id,cheat_room_id,text,send_time) VALUES (?,?,?,?)');
		$st->bindParam(1,$sender['id']);
		$st->bindParam(2,$room['cheat_room_id']);
		$st->bindParam(3,$text);
		$st->bindParam(4,jry_wb_get_time());
		$st->execute();
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_CHEAT.'rooms SET say_count=say_count+1 , last_say_time=? WHERE cheat_room_id=?');
		$st->bindParam(1,jry_wb_get_time());
		$st->bindParam(2,$room['cheat_room_id']);
		$st->execute();
		$cnt=0;
		if(jry_wb_test_is_cli_mode())
		{
			foreach($room['users'] as $user)
				$cnt+=jry_wb_socket_send_to_user($sender,$user,200000,$text);
		}
		return $cnt;
	}
