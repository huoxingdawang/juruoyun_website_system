<?php
	error_reporting(E_ALL^E_NOTICE^E_WARNING);
	if(($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'])==__FILE__)
	{
		echo 'fuck you';
		exit();
	}		
	include_once("../jry_wb_configs/jry_wb_config_socket.php");	
	include_once("../tools/jry_wb_includes.php");
	include_once('jry_wb_php_cli_color.php');
	include_once('jry_wb_cli_get_machine.php');
	include_once('jry_wb_socket_send_to_user.php');
	include_once('jry_wb_socket_decode.php');
	include_once('jry_wb_socket_send.php');