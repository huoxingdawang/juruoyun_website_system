<?php
	function jry_wb_cli_add_task($task)
	{
		$jry_wb_message_queue=msg_get_queue(ftok(dirname(__FILE__),'m'));		
		$redis = new Redis;
		$redis->connect(JRY_WB_REDIS_ADDR,JRY_WB_REDIS_PORT);
		if(JRY_WB_REDIS_PASSWORD!='')
			$redis->auth(JRY_WB_REDIS_PASSWORD);  
		$redis->rpush(JRY_WB_REDIS_PREFIX.'task',json_encode($task));
		msg_send($jry_wb_message_queue,2,'1');
	}