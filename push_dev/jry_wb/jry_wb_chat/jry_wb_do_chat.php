<?php
	include_once("jry_wb_chat_includes.php");
	try
	{
		jry_wb_print_head("",true,true,true,array('use','usechat'),false);
	}
	catch(jry_wb_exception $e)
	{
		echo $e->getMessage();
		exit();
	}
	$action=$_GET['action'];
	try
	{
		$data=[];
		if($action=='send'||$action==200000)
			jry_wb_chat_send($conn,$jry_wb_login_user,$_POST['room'],urldecode($_POST['message']));					
		else if($action=='enter_room'||$action==200001)
			jry_wb_chat_enter_room($conn,$jry_wb_login_user,$_POST['room']);
		else if($action=='exit_room'||$action==200002)
			jry_wb_chat_exit_room($conn,$jry_wb_login_user,$_POST['room']);
		else if($action=='add_room'||$action==200003)
			jry_wb_chat_add_room($conn,$jry_wb_login_user);
		else if($action=='delete_room'||$action==200004)
			jry_wb_chat_delete_room($conn,$jry_wb_login_user,$_POST['room']);
		else if($action=='get_rooms'||$action==200005)
			$data=jry_wb_chat_get_chat_rooms($conn,$jry_wb_login_user);
		else if($action=='get_message'||$action==200006)
		{
			$rooms=json_decode($_POST['room']);
			if(is_int($rooms))
				$data=jry_wb_chat_get_message($conn,$jry_wb_login_user,$rooms,$_POST['lasttime']);
			else
				foreach($rooms as $room)
					$data=array_merge($data,jry_wb_chat_get_message($conn,$jry_wb_login_user,$room,$_POST['lasttime']));
		}
		else if($action=='get_room'||$action==200007)
		{
			$rooms=json_decode($_POST['room']);
			if(is_int($rooms))
			{
				if(($buf=jry_wb_chat_get_chat_room($conn,$rooms,$_POST['lasttime']))!==null)
					$data[]=$buf;
			}
			else
				foreach($rooms as $room)
					if(($buf=jry_wb_chat_get_chat_room($conn,$room,$_POST['lasttime']))!==null)
						$data[]=$buf;
		}
		else if($action=='rename_room'||$action==200008)
			jry_wb_chat_rename_chat_room($conn,$jry_wb_login_user,$_POST['room'],$_POST['to_name']);		
		else if($action=='reset_room_head'||$action==200009)
			jry_wb_chat_set_chat_room_head($conn,$jry_wb_login_user,$_POST['room'],$_POST['to_head']);		
		else
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>000000,'file'=>__FILE__,'line'=>__LINE__)));
		echo json_encode(array('code'=>true,'data'=>$data));
	}
	catch (jry_wb_exception $e)
	{
		echo $e->getMessage();
		exit();
	}
	
	