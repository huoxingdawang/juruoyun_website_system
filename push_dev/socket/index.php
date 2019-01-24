<?php
	include_once('config.php');
	$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
	socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 10, "usec" => 0));
	socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array("sec" => 10, "usec" => 0));
	if(socket_connect($socket,constant('test_machine_jry_wb_host_ip'),constant('test_machine_port_for_web'))==false)
	{
		echo 'Connect fail massege:'.socket_strerror(socket_last_error()).'<br>';
		return;
	}
	echo "Sending<br>";
	socket_write($socket,"test new",strlen("test new"));
	echo (socket_read($socket,1024));
	socket_close($socket);
