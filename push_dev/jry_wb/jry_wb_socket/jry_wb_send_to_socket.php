<?php
	include_once("../jry_wb_configs/jry_wb_config_socket.php");	
	include_once("../jry_wb_tools/jry_wb_includes.php");
	function jry_wb_send_to_socket($from,$to_id,$type,$data,$c_index=-1)
	{
		if(JRY_WB_SOCKET_SWITCH!=true)
			return;
		$jry_wb_message_queue=msg_get_queue(ftok(dirname(__FILE__),'m'));
		if(is_array($to_id)&&$to_id['id']!==NULL)
			$to_id=$to_id['id'];
		else if(is_array($to_id))
			$to_id=$to_id;
		else if(is_object($to_id))
			$to_id=$to_id->id;
		else if(is_string($to_id))
			$to_id=(int)$to_id;
		unset($from['background_music_list']);
		unset($from['head_special']);
		unset($from['oauth_qq']);
		unset($from['oauth_github']);
		unset($from['oauth_mi']);
		unset($from['oauth_gitee']);
		unset($from['ips']);
		unset($from['zhushi']);
		unset($from['head']);
		unset($from['jry_wb_test_is_mobile']);
		unset($from['trust']);
		unset($from['browser']);
		unset($from['device']);
		unset($from['time']);
		unset($from['follow_mouth']);
		unset($from['word_special_fact']);
		unset($from['style_id']);
		unset($from['tel_show']);
		unset($from['mail_show']);
		unset($from['ip_show']);
		unset($from['greendate']);
		unset($from['logdate']);
		unset($from['enroldate']);
		unset($from['password']);
		unset($from['lasttime']);
		unset($from['color']);
		$redis = new Redis;
		$redis->connect('127.0.0.1');
		$redis->rpush('post',json_encode(array('from'=>$from,'to_id'=>$to_id,'type'=>$type,'data'=>$data,'c_index'=>$c_index)));				
		msg_send($jry_wb_message_queue,4,'1');		
	}
?>