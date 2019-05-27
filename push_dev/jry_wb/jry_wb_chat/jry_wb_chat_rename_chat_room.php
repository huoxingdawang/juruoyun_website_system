<?php
	include_once("jry_wb_chat_includes.php");
	function jry_wb_chat_rename_chat_room($conn,&$user,&$roomm,$to_name)
	{
		if($to_name=='')
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>600004,'extern'=>array('chat_room'=>$roomm),'file'=>__FILE__,'line'=>__LINE__)));
		if(!$user['usechat'])
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>'usechat')));
		if(!$user['renamechatroom'])
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>'renamechatroom')));		
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
		if($user['id']!=$room['id'])
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>'renamechatroom')));	
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_CHAT.'rooms SET  name=?, lasttime=? WHERE chat_room_id=?');
		$st->bindValue(1,$to_name);
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$room['chat_room_id']);
		$st->execute();
		jry_wb_send_to_socket($user,$room['users'],200008,array('room'=>$room['chat_room_id'],'name'=>$to_name));		
	}
