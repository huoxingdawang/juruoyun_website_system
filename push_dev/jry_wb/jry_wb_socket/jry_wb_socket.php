<?php
	include_once("jry_wb_cli_includes.php");
	include_once("../jry_wb_chat/jry_wb_chat_includes.php");
	//信道分配
	//2:任务分发
	//3:log输出
	cli_set_process_title(JRY_WB_REDIS_PREFIX.'jry_wb_socket_core');	
	if((!jry_wb_test_is_cli_mode())){header('HTTP/1.1 404 Not Found');header("status: 404 Not Found");include('../../404.php');exit();}
	if(JRY_WB_SOCKET_SWITCH!==true)
	{
		jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color('Failed!','light_red').' Please set '.jry_wb_php_cli_color('jry_wb_socket_switch','cyan').' to '.jry_wb_php_cli_color('true','green'));
		exit();
	}
	$child_list=array();
	$jry_wb_message_queue = msg_get_queue(ftok(dirname(__FILE__),'m'));
	$pid=getmypid();
	jry_wb_cli_echo_log("\n\n\n\n\n\n\n\n\n\n\n");
	jry_wb_cli_echo_log('JRY CLI Core '.jry_wb_php_cli_color('OK','green')."\nBy ".jry_wb_php_cli_color('juruoyun web system '.JRY_WB_VERSION,'light_green'));
	function creat_child($callback)
	{
		global $child_list;
		global $pid;
		$pid=pcntl_fork();
		if($pid==-1)
			jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color('Failed!','light_red').' On '.jry_wb_php_cli_color('pcntl_fork','cyan').' At FILE:'.jry_wb_php_cli_color(__FILE__,'yellow').' LINE:'.jry_wb_php_cli_color(__LINE__,'yellow')."\n");
		else if ($pid == 0)
		{
			$pid=posix_getpid();
			$callback();
			exit();
		}
		else
		{
			$child_list[$pid]=1;
			return $pid;
		}		
	}
	global $clients;
	global $users;
	global $clients_listener;
	global $c_to_u;
	global $users_id;	
	function start_socket_listener()
	{
		global $pid;
		global $jry_wb_message_queue;
		global $users_id;
		global $users;
		global $c_to_u;
		global $clients;		
		jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').'JRY CLI Core Start Listener '.jry_wb_php_cli_color('OK','green'));
		include_once("jry_wb_socket_listener.php");	
	}
	function start_socket_do()
	{
		global $pid;
		global $jry_wb_message_queue;		
		jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').'JRY CLI Core Start Do '.jry_wb_php_cli_color('OK','green'));
		include_once("jry_wb_socket_do.php");	
	}
	function start_socket_poster()
	{
		global $pid;
		global $jry_wb_message_queue;
		jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').'JRY CLI Core Start Poster '.jry_wb_php_cli_color('OK','green'));
		include_once("jry_wb_socket_poster.php");
	}	
	$listeners=[];
	$dos=[];
	$posters=[];
	$listeners[creat_child('start_socket_listener')]=true;
	for($i=0;$i<($argv[1]==''?2:((int)$argv[1]));$i++)
		$dos[creat_child('start_socket_do')]=true;
	$posters[creat_child('start_socket_poster')]=true;
	while(!empty($child_list))
		if(($child_pid=pcntl_wait($status))>0)
		{
			unset($child_list[$child_pid]);
			if($listeners[$child_pid])
			{
				$listeners[creat_child('start_socket_listener')]=true;
				unset($listeners[$child_pid]);
			}
			else if($dos[$child_pid])
			{
				$dos[creat_child('start_socket_do')]=true;
				unset($dos[$child_pid]);
			}
			else if($posters[$child_pid])
			{
				$posters[creat_child('start_socket_poster')]=true;
				unset($posters[$child_pid]);
			}
		}
	jry_wb_cli_echo_log(jry_wb_php_cli_color('Stop OK','green'));