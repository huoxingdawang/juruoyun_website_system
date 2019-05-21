<?php
	include_once("jry_wb_chat_includes.php");
	function jry_wb_chat_get_chat_rooms($conn,&$user)
	{
		if(!$user['usechat'])
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>'usechat')));
		jry_wb_chat_get_user($conn,$user);
		if($user['ch_ei']==null)
			return array();
		$data=[];
		foreach($user['ch_ei']['chat_rooms'] as $room)
			$data[]=(int)$room;
		return $data;
	}
