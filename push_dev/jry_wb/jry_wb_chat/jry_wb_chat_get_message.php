<?php
	include_once("jry_wb_chat_includes.php");
	function jry_wb_chat_get_message($conn,&$user,&$roomm,$lasttime='1926-08-17 00:00:00')
	{
		if(!$user['usechat'])
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>'usechat')));
		jry_wb_chat_get_user($conn,$user,true);
		if(is_string($roomm)||is_int($roomm))
			$room=jry_wb_chat_get_chat_room($conn,(int)$roomm);
		else
			$room=$roomm;
		if($room==null)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>600001,'extern'=>array('chat_room'=>$roomm),'file'=>__FILE__,'line'=>__LINE__)));
		if($room['delete']==1)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>600001,'extern'=>array('chat_room'=>$roomm),'file'=>__FILE__,'line'=>__LINE__)));
		if(is_string($room['users']))
			$room['users']=json_decode($room['users']);
		if(array_search($user['id'],$room['users'])===false)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>600002,'extern'=>array('chat_room_id'=>$room['chat_room_id']),'file'=>__FILE__,'line'=>__LINE__)));
		if($lasttime==NULL||$lasttime=='')
			$lasttime='1926-08-17 00:00:00';
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_CHEAT.'message WHERE chat_room_id=? AND send_time>?');
		$st->bindValue(1,$room['chat_room_id']);
		$st->bindValue(2,$lasttime);
		$st->execute();
		$data=array();
		foreach($st->fetchAll() as $one)
			$data[]=array(	'chat_text_id'=>$one['chat_text_id'],
							'id'=>$one['id'],
							'chat_room_id'=>$one['chat_room_id'],
							'message'=>$one['message'],
							'send_time'=>$one['send_time']);
		return $data;
	}
