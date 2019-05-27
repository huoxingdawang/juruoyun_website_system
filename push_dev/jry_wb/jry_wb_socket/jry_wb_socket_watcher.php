<?php
	cli_set_process_title('jry_wb_socket_watcher');
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
	echo ("\n".jry_wb_php_cli_color('Watcher OK','green')."\nby ".jry_wb_php_cli_color('juruoyun web system '.constant('jry_wb_version'),'light_green')."\n");
	$redis=new Redis;
	$redis->connect('127.0.0.1',6379);	
	$conn=jry_wb_connect_database();
	$st =$conn->prepare("SELECT * FROM ".constant('jry_wb_database_log')."socket ORDER BY log_socket_id DESC LIMIT ".($argv[1]==''?50:((int)$argv[1])));
	$st->execute();			
	$data=$st->fetchAll();
	$data=array_reverse($data);
	foreach($data as $one)
		echo $one['data']."\n";
	while(1)
	{
		if($message=$redis->lpop('log'))
		{
			$st =$conn->prepare("INSERT INTO ".constant('jry_wb_database_log')."socket (`data`) VALUES(?)");
			$st->bindParam(1,$message);
			$st->execute();			
			echo $message."\n";
		}
		else
			sleep(1);
	}