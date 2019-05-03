<?php
	include_once("jry_wb_cli_includes.php");
	function jry_wb_socket_send_to_user($from,$to_id,$type,$data)
	{
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
		echo jry_wb_php_cli_color($from['id'].'-'.$from['name'],'light_blue')."\t".jry_wb_php_cli_color('send ','green').' to '.jry_wb_php_cli_color($to['id'].'-'.$to['name'],'light_blue').' cnt:'.jry_wb_php_cli_color($cnt,'magenta').' total:'.jry_wb_php_cli_color($length,'magenta').'/B'."\n";
		return 1;
	}