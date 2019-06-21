<?php
	include_once("jry_wb_chat_includes.php");
	function jry_wb_chat_get_chat_rooms($conn,&$user)
	{
		jry_wb_check_compentence($user,['usechat'],NULL);
		jry_wb_chat_get_user($conn,$user);
		if($user['ch_ei']==null)
			return array();
		$data=[];
		foreach($user['ch_ei']['chat_rooms'] as $room)
			$data[]=(int)$room;
		return $data;
	}
