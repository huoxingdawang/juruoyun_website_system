<?php
	include_once("jry_wb_chat_includes.php");
	function jry_wb_chat_add_room($conn,&$user,$big=true)
	{
		jry_wb_check_compentence($user,['usechat','addchatroom'],$user['code']);
		$st = $conn->prepare('INSERT INTO '.JRY_WB_DATABASE_CHAT.'rooms (id,cream_time,lasttime,big) VALUES (?,?,?,?)');
		$st->bindValue(1,$user['id']);
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,jry_wb_get_time());
		$st->bindValue(4,(int)$big);
		$st->execute();
		$room=$conn->lastInsertId();
		jry_wb_chat_enter_room($conn,$user,$room);
		return $room;
	}
