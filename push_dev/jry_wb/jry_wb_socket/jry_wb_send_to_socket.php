<?php
	include_once("../jry_wb_configs/jry_wb_config_socket.php");	
	include_once("../tools/jry_wb_includes.php");
	function jry_wb_send_to_socket($user,$type,$data)
	{
		if(jry_wb_test_is_cli_mode())
			return;
		if(($socket=socket_create(AF_INET,SOCK_STREAM,SOL_TCP))===FALSE)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>500001,'file'=>__FILE__,'line'=>__LINE__)));
		if(socket_connect($socket,'127.0.0.1',constant('jry_wb_socket_port').'')===FALSE)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>500002,'file'=>__FILE__,'line'=>__LINE__)));
		$data=array('data'=>array('code'=>true,'type'=>$type,'data'=>$data),'user'=>array('id'=>$user['id'],'code'=>$user['code']));
		$data=json_encode($data);
		if(!socket_write($socket,$data,strlen($data)))
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>500003,'file'=>__FILE__,'line'=>__LINE__)));
	}
//	jry_wb_send_to_socket($jry_wb_login_user,200000,array('room'=>1,'message'=>'哈哈'));
?>