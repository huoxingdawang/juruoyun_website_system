<?php
	include_once("jry_wb_cli_includes.php");
	if((!jry_wb_test_is_cli_mode())){header('HTTP/1.1 404 Not Found');header("status: 404 Not Found");include('../../404.php');exit();}
	function jry_wb_socket_send_to_user($from,$to_id,$type,$data)
	{
		if(is_object($from))
			$from=json_decode(json_encode($from),true);
		global $users_id;
		global $users;
		global $c_to_u;
		global $clients;
		$to_index=array_search($to_id,$users_id);
		if($to_index===false)
			return 0;
		$to=$users[$to_index];
		$cnt=0;
		$length=0;
		foreach ($c_to_u as $i=>$id)
			if($id==$to['id'])
			{
				$length+=jry_wb_socket_send($clients[$i],array('code'=>true,'type'=>$type,'from'=>$from['id'],'data'=>$data));
				$cnt++;
			}
		jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color($from['id'].'-'.$from['name'],'light_blue')."\t".jry_wb_php_cli_color('send ','green').' to '.jry_wb_php_cli_color($to_id.'-'.$to['name'],'light_blue').' cnt:'.jry_wb_php_cli_color($cnt,'magenta').' total:'.jry_wb_php_cli_color($length,'magenta').'/B');
		return 1;
	}