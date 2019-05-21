<?php
	cli_set_process_title('jry_wb_socket_do');
	include_once("jry_wb_cli_includes.php");
	include_once("../jry_wb_chat/jry_wb_chat_includes.php");
	if((!jry_wb_test_is_cli_mode())){header('HTTP/1.1 404 Not Found');header("status: 404 Not Found");include('../../404.php');exit();}
	if(constant('jry_wb_socket_switch')!==true)
	{
		echo jry_wb_php_cli_color('Failed!','light_red').' Please set '.jry_wb_php_cli_color('jry_wb_socket_switch','cyan').' to '.jry_wb_php_cli_color('true','green')."\n";
		exit();
	}
	$conn=jry_wb_connect_database();
	$redis=new Redis;
	$redis->connect('127.0.0.1');
	$jry_wb_message_queue=msg_get_queue(ftok(dirname(__FILE__),'m'));	
	while(1)
	{
		$rel=msg_receive($jry_wb_message_queue,2,$msgtype,1024,$buf);		
		if($task=json_decode($redis->lpop('task')))
		{
			$task->user=json_decode(json_encode($task->user),true);
			try
			{
				if($task->type==200000)
					jry_wb_chat_send($conn,$task->user,$task->data->room,$task->data->message);
				else if($task->type==200001)
					jry_wb_chat_enter_room($conn,$task->user,$task->data->room);
				else if($task->type==200002)
					jry_wb_chat_exit_room($conn,$task->user,$task->data->room);
				else if($task->type==200003)
					jry_wb_chat_add_room($conn,$task->user);			
				else if($task->type==200004)
					jry_wb_chat_delete_room($conn,$task->user,$task->data->room);
				else if($task->type==200005)			
					jry_wb_send_to_socket($task->user,$task->user['id'],200005,jry_wb_chat_get_chat_rooms($conn,$task->user),$task->c_index);				
				else if($task->type==200006)
				{
					$data=[];
					if(is_int($task->data->room))
						$data=jry_wb_chat_get_message($conn,$task->user,$task->data->room,$task->data->lasttime);
					else
						foreach($task->data->room as $room)
							$data=array_merge($data,jry_wb_chat_get_message($conn,$task->user,$room,$task->data->lasttime));
					jry_wb_send_to_socket($task->user,$task->user['id'],200006,$data,$task->c_index);
				}
				else if($task->type==200007)
				{
					$data=[];
					if(is_int($task->data->room))
					{
						if(($buf=jry_wb_chat_get_chat_room($conn,$task->data->room,$task->data->lasttime))!==null)
							$data[]=$buf;
					}
					else
						foreach($task->data->room as $room)
							if(($buf=jry_wb_chat_get_chat_room($conn,$room,$task->data->lasttime))!==null)
								$data[]=$buf;
					jry_wb_send_to_socket($task->user,$task->user['id'],200007,$data,$task->c_index);
				}
				else if($task->type==200008)			
					jry_wb_chat_rename_chat_room($conn,$task->user,$task->data->room,$task->data->to_name);
				else if($task->type==200009)
					jry_wb_chat_set_chat_room_head($conn,$task->user,$task->data->room,$task->data->to_head);
				else
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>000000,'file'=>__FILE__,'line'=>__LINE__)));
			}
			catch (jry_wb_exception $e)
			{
				$error=json_decode($e->getMessage());
				jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color('Failed!','light_red').' At FILE:'.jry_wb_php_cli_color($error->file,'yellow').' LINE:'.jry_wb_php_cli_color($error->line,'yellow').' Because '.jry_wb_php_cli_color($error->reason,'blue'));			
				jry_wb_send_to_socket(array('id'=>-2,'name'=>'ES'),$task->user['id'],100004,$error);
			}
		}
		else
			sleep(1);
	}