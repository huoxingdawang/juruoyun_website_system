<?php
	include_once("jry_wb_cli_includes.php");
	function jry_wb_cli_echo_log($text)
	{
		$redis=new Redis;
		$redis->connect('127.0.0.1');
		$redis->rpush('log',$text);
	}