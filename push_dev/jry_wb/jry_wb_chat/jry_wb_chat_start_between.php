<?php
	include_once("jry_wb_chat_includes.php");
	function jry_wb_chat_start_between($conn,$user1,$user2)
	{
		if($user1==NULL||$user2==NULL)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100001,'extern'=>array('chat_room'=>$roomm),'file'=>__FILE__,'line'=>__LINE__,'extern'=>'usechat')));	
		if($user1['id']==$user2['id'])
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>600002,'file'=>__FILE__,'line'=>__LINE__)));
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_CHAT.'rooms WHERE users=JSON_ARRAY(?,?) OR users=JSON_ARRAY(?,?) AND big =0 AND `delete`=0 LIMIT 1');
		$st->bindValue(1,$user1['id'],PDO::PARAM_INT);
		$st->bindValue(2,$user2['id'],PDO::PARAM_INT);
		$st->bindValue(3,$user2['id'],PDO::PARAM_INT);
		$st->bindValue(4,$user1['id'],PDO::PARAM_INT);
		$st->execute();
		$all=$st->fetchAll();
		if(count($all)===0)
		{
			jry_wb_check_compentence($user2,['usechat'],NULL);		
			$room=jry_wb_chat_add_room($conn,$user1,false);
			jry_wb_chat_enter_room($conn,$user2,$room);
			return $room;
		}
		return $all[0]['chat_room_id'];
	}