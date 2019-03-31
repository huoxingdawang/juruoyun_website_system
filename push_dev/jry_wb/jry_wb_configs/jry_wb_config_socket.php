<?php
	include_once('jry_wb_config_default_system.php');
	define('jry_wb_socket_host','0.0.0.0');
	define('jry_wb_socket_port',1217);
	define('jry_wb_socket_max_client',1000);
	$jry_wb_socket_mode=true;
	if(($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'])==__FILE__)
	{
		echo 'fuck you';
		exit();
	}	