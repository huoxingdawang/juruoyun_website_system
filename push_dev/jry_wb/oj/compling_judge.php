<?php
	include_once("../tools/jry_wb_includes.php");
	function compling_judge($id,$ans,$data)
	{
		$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
		socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 10, "usec" => 0));
		socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array("sec" => 10, "usec" => 0));
		if(socket_connect($socket,constant('test_machine_jry_wb_host_ip'),constant('test_machine_port_for_web'))==false)
			return false;
		socket_write($socket,"test new",strlen("test new"));
		$returndata=json_decode(socket_read($socket,1024));
		socket_close($socket);	
		return $returndata;
	}
?>