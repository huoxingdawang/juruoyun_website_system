<?php
	function jry_wb_cli_add_task($task)
	{
		$jry_wb_message_queue=msg_get_queue(ftok(dirname(__FILE__),'m'));		
		$redis = new Redis;
		$redis->connect('127.0.0.1');
		$redis->rpush('task',json_encode($task));
		msg_send($jry_wb_message_queue,2,'1');
	}