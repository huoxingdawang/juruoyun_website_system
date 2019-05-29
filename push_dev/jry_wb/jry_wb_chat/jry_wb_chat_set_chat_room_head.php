<?php
	include_once("jry_wb_chat_includes.php");
	function jry_wb_chat_set_chat_room_head($conn,&$user,&$roomm,$to_head)
	{
		if($to_head=='')
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>600005,'extern'=>array('chat_room'=>$roomm),'file'=>__FILE__,'line'=>__LINE__)));	
		if(is_string($to_head))
			$to_head=json_decode($to_head,true);
		else if(is_object($to_head))
			$to_head=json_decode(json_encode($to_head),true);
		if($to_head['type']!='default')
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>600005,'extern'=>array('chat_room'=>$roomm),'file'=>__FILE__,'line'=>__LINE__)));	
		if(!$user['usechat'])
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>'usechat')));
		if(!$user['setchatroomhead'])
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>'setchatroomhead')));		
		jry_wb_chat_get_user($conn,$user,true);
		if(is_string($roomm)||is_int($roomm))
			$room=jry_wb_chat_get_chat_room($conn,(int)$roomm,$user);
		else
			$room=$roomm;
		if($room==null)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>600001,'extern'=>array('chat_room'=>$roomm),'file'=>__FILE__,'line'=>__LINE__)));
		if($room['delete']==1)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>600001,'extern'=>array('chat_room'=>$roomm),'file'=>__FILE__,'line'=>__LINE__)));
		if(is_string($room['users']))
			$room['users']=json_decode($room['users']);
		if($user['id']!=$room['id'])
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>'setchatroomhead')));	
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_CHAT.'rooms SET head=?, lasttime=? WHERE chat_room_id=?');
		$st->bindValue(1,json_encode($to_head));
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$room['chat_room_id']);
		$st->execute();
		jry_wb_send_to_socket($user,$room['users'],200009,array('room'=>$room['chat_room_id'],'head'=>$to_head,'lasttime'=>jry_wb_get_time()));		
	}
