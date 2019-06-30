<?php
	include_once("jry_wb_cli_includes.php");
	function jry_wb_cli_echo_log($text)
	{
		$redis=new Redis;
		$redis->connect(JRY_WB_REDIS_ADDR,JRY_WB_REDIS_PORT);	
		if(JRY_WB_REDIS_PASSWORD!='')
			$redis->auth(JRY_WB_REDIS_PASSWORD);  		
		$redis->rpush(JRY_WB_REDIS_PREFIX.'log',$text);
	}