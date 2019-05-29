<?php
	include_once("jry_wb_chat_includes.php");
	function jry_wb_chat_add_room($conn,&$user,$big=true)
	{
		if(!$user['usechat'])
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>'usechat')));
		if(!$user['addchatroom'])
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>'addchatroom')));
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
