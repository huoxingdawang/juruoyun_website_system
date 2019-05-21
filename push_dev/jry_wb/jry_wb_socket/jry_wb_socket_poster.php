<?php
	cli_set_process_title('jry_wb_socket_poster');
	include_once("jry_wb_cli_includes.php");
	include_once("../jry_wb_chat/jry_wb_chat_includes.php");
	if((!jry_wb_test_is_cli_mode())){header('HTTP/1.1 404 Not Found');header("status: 404 Not Found");include('../../404.php');exit();}
	if(constant('jry_wb_socket_switch')!==true)
	{
		echo jry_wb_php_cli_color('Failed!','light_red').' Please set '.jry_wb_php_cli_color('jry_wb_socket_switch','cyan').' to '.jry_wb_php_cli_color('true','green')."\n";
		exit();
	}
	$jry_wb_message_queue_id=ftok(dirname(__FILE__),'m');
	$jry_wb_message_queue = msg_get_queue($jry_wb_message_queue_id);	
	$conn=jry_wb_connect_database();
	$conn=jry_wb_connect_database();
	$redis=new Redis;
	$redis->connect('127.0.0.1');		
	while(1)
	{
		$rel=msg_receive($jry_wb_message_queue,4,$msgtype,1024,$buf);		
		if($data=json_decode($redis->lpop('post'),true))
		{
			if($data['from']==null||$data['from']['id']==null||$data['from']['name']==null)
			{
				jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color('Failed!','light_red').' On '.jry_wb_php_cli_color('transport','cyan').' At FILE:'.jry_wb_php_cli_color(__FILE__,'yellow').' LINE:'.jry_wb_php_cli_color(__LINE__,'yellow').' Because no from data');			
				continue;
			}		
			jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color($data['from']['id'].'-'.$data['from']['name'],'light_blue')."\t".jry_wb_php_cli_color('transport ','green').jry_wb_php_cli_color(strlen(json_encode($data['data'])),'magenta').'/B data '.substr(json_encode($data['data']),0,100)."\tto ".json_encode($data['to_id']));
			if(($socket=socket_create(AF_INET,SOCK_STREAM,SOL_TCP))==false)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>500001,'file'=>__FILE__,'line'=>__LINE__)));
			if(socket_connect($socket,'127.0.0.1',constant('jry_wb_socket_port'))==false)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>500002,'file'=>__FILE__,'line'=>__LINE__)));
			$data=json_encode($data);
			if(!socket_write($socket,$data,strlen($data)))
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>500003,'file'=>__FILE__,'line'=>__LINE__)));
			socket_close($socket);
		}
		else
			sleep(1);
	}