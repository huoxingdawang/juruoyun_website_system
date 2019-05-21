<?php
	cli_set_process_title('jry_wb_socket_listener');
	include_once("jry_wb_cli_includes.php");
	if((!jry_wb_test_is_cli_mode())){header('HTTP/1.1 404 Not Found');header("status: 404 Not Found");include('../../404.php');exit();}
	if(constant('jry_wb_socket_switch')!==true)
	{
		jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color('Failed!','light_red').' Please set '.jry_wb_php_cli_color('jry_wb_socket_switch','cyan').' to '.jry_wb_php_cli_color('true','green'));
		exit();
	}
	ob_implicit_flush();
	$master=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
	if($master===FALSE)
	{
		jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color('Failed!','light_red').' On '.jry_wb_php_cli_color('socket_create()','cyan').' At FILE:'.jry_wb_php_cli_color(__FILE__,'yellow').' LINE:'.jry_wb_php_cli_color(__LINE__,'yellow').' Because '.socket_strerror(socket_last_error()));
		exit();
	}
	socket_set_option($master, SOL_SOCKET, SO_REUSEADDR, 1);
	if(socket_bind($master,constant('jry_wb_socket_host'),constant('jry_wb_socket_port'))===FALSE)
	{
		jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color('Failed!','light_red').' On '.jry_wb_php_cli_color('socket_bind()','cyan').' At FILE:'.jry_wb_php_cli_color(__FILE__,'yellow').' LINE:'.jry_wb_php_cli_color(__LINE__,'yellow').' Because '.socket_strerror(socket_last_error()));
		exit();
	}
	$listen=socket_listen($master,constant('jry_wb_socket_max_client'));
	if($listen===FALSE)
	{
		jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color('Failed!','light_red').' On '.jry_wb_php_cli_color('socket_listen()','cyan').' At FILE:'.jry_wb_php_cli_color(__FILE__,'yellow').' LINE:'.jry_wb_php_cli_color(__LINE__,'yellow').' Because '.socket_strerror(socket_last_error()));
		exit();
	}
	global $clients;
	global $users;
	global $clients_listener;
	global $c_to_u;
	global $users_id;	
	$clients=array();
	$users=array();
	$clients_listener=array();
	$c_to_u=array();
	$users_id=array_column($users,'id');
	jry_wb_cli_echo_log('JRY Socket Listener '.jry_wb_php_cli_color('OK','green')."\nat ".jry_wb_php_cli_color(constant('jry_wb_socket_host').':'.constant('jry_wb_socket_port'),'cyan'));
	global $jry_wb_message_queue;
	while(1)
	{
		$sockets=$clients;
		$sockets[]=$master;
		$write=NULL;
		$except=NULL;
		$tv_sec=NULL;
		socket_select($sockets, $write, $except, $tv_sec);
		//循环有状态变化的socket
		foreach ($sockets as $socket)
		{
			if($socket===$master)
			{
				$client = socket_accept($master);
				if ($client === FALSE)
					jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color('Failed!','light_red').' On '.jry_wb_php_cli_color('socket_accept()','cyan').' At FILE:'.jry_wb_php_cli_color(__FILE__,'yellow').' LINE:'.jry_wb_php_cli_color(__LINE__,'yellow').' Because '.socket_strerror(socket_last_error()));
				else
				{
					socket_getpeername($client,$ip);
					if($ip=='127.0.0.1')
					{
						$data='';
						while(1)
						{
							$buf=socket_read($client,1024);
							if($buf=='')
								break;
							else
								$data.=$buf;
						}
						$data=json_decode($data,true);
						if($data['c_index']===-1||$data['c_index']===NULL)
						{
							if(is_array($data['to_id']))
								foreach($data['to_id'] as $to_id)
									jry_wb_socket_send_to_user($data['from'],$to_id,$data['type'],$data['data']);
							else
								jry_wb_socket_send_to_user($data['from'],$data['to_id'],$data['type'],$data['data']);
						}
						else
							if($clients[$data['c_index']]!==NULL&&$c_to_u[$data['c_index']]==$data['to_id'])
								jry_wb_socket_send($clients[$data['c_index']],array('code'=>true,'type'=>$data['type'],'from'=>$data['from']['id'],'data'=>$data['data']));
					}
					else
					{
						$header = socket_read($client, 1024);
						preg_match("/User-Agent: (.*)\r\n/", $header,$user_agent);
						$user_agent=$user_agent[1];
						preg_match("/Cookie: (.*)\r\n/", $header,$buf);
						$buf=explode(";",$buf[1]);
						$cookie=array();
						foreach($buf as $onecookie)
						{
							$buf2=explode("=",$onecookie);
							$cookie[str_replace(' ','',$buf2[0])]=str_replace(' ','',$buf2[1]);
						}
						jry_wb_pretreatment($user,$cookie,$ip,$user_agent);
						if(preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $header, $match))
						{
							$secKey = $match[1];
							$secAccept = base64_encode(sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', TRUE));
							$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
							"Upgrade: websocket\r\n" .
							"Connection: Upgrade\r\n" .
							"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
							socket_write($client, $upgrade, strlen($upgrade));
						}
						if($user['id']==-1)
						{
							jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').'Not login user at '.jry_wb_php_cli_color($ip."\t".jry_wb_get_ip_address_string($ip),'cyan'));
							jry_wb_socket_send($client,(array('code'=>false,'reason'=>100000)));
							socket_close($client);
							continue;
						}
						$result=array_search($user['id'],$users_id);
						if($result!==false&&($users[$result]['count']>(constant('jry_wb_socket_max_client_per_user')-1)))
						{
							jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color($user['id'].'-'.$user['name'],'light_blue')."\t".jry_wb_php_cli_color('to much','red').' at '.jry_wb_php_cli_color($ip."\t".jry_wb_get_ip_address_string($ip),'cyan')."\t".' Total user:'.jry_wb_php_cli_color(count($users),'magenta').' Total clients:'.jry_wb_php_cli_color(count($clients),'magenta').' Total c_to_u:'.jry_wb_php_cli_color(count($c_to_u),'magenta'));
							jry_wb_socket_send($client,(array('code'=>false,'reason'=>500000)));
							socket_close($client);
							continue;			
						}
						$clients[]=$client;
						$clients_listener[]=array();
						$c_to_u[]=$user['id'];
						$count=0;
						if($result===false)
						{
							$count=$user['count']=1;
							$users[]=$user;
							$users_id=array_column($users,'id');
							jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color($user['id'].'-'.$user['name'],'light_blue')."\t".jry_wb_php_cli_color('connected','green').' at '.jry_wb_php_cli_color($ip."\t".jry_wb_get_ip_address_string($ip),'cyan')."\t".'now have '.jry_wb_php_cli_color($user['count'],'magenta').' connect '.jry_wb_php_cli_color('new','yellow').' Total user:'.jry_wb_php_cli_color(count($users),'magenta').' Total clients:'.jry_wb_php_cli_color(count($clients),'magenta').' Total c_to_u:'.jry_wb_php_cli_color(count($c_to_u),'magenta'));
						}
						else
						{
							$users[$result]['count']++;
							$count=$users[$result]['count'];
							jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color($user['id'].'-'.$user['name'],'light_blue')."\t".jry_wb_php_cli_color('connected','green').' at '.jry_wb_php_cli_color($ip."\t".jry_wb_get_ip_address_string($ip),'cyan')."\t".'now have '.jry_wb_php_cli_color($users[$result]['count'],'magenta').' connect(s) Total user:'.jry_wb_php_cli_color(count($users),'magenta').' Total clients:'.jry_wb_php_cli_color(count($clients),'magenta').' Total c_to_u:'.jry_wb_php_cli_color(count($c_to_u),'magenta'));
						}
						jry_wb_socket_send($client,(array('code'=>true,'type'=>100000,'data'=>array('count'=>$count))));
					}
				}
			}
			else
			{
				$c_index=array_search($socket,$clients);
				$id=$c_to_u[$c_index];
				$u_index=array_search($id,$users_id);
				$user=$users[$u_index];
				socket_getpeername($socket,$ip);
				$bytes=socket_recv($socket,$data,1024*1024*10,0);
				$data=jry_wb_socket_decode($data);
				if ($bytes === FALSE)
					jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color('Failed!','light_red').jry_wb_php_cli_color($user['id'].'-'.$user['name'],'light_blue').' On '.jry_wb_php_cli_color('socket_recv()','cyan').' At FILE:'.jry_wb_php_cli_color(__FILE__,'yellow').' LINE:'.jry_wb_php_cli_color(__LINE__,'yellow').' Because '.socket_strerror(socket_last_error()));
				else if($bytes<=1||empty($data)||!is_object(json_decode($data)))
				{					
					$users[$u_index]['count']--;
					unset($clients[$c_index]);
					unset($clients_listener[$c_index]);
					unset($c_to_u[$c_index]);
					socket_close($socket);
					jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color($user['id'].'-'.$user['name'],'light_blue')."\t".jry_wb_php_cli_color('disconnect','yellow').' at '.jry_wb_php_cli_color($ip."\t".jry_wb_get_ip_address_string($ip),'cyan')."\t".'now have '.jry_wb_php_cli_color($users[$result]['count'],'magenta').' connect(s) Total user:'.jry_wb_php_cli_color(count($users),'magenta').' Total clients:'.jry_wb_php_cli_color(count($clients),'magenta').' Total c_to_u:'.jry_wb_php_cli_color(count($c_to_u),'magenta'));
				}
				else
				{
					jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color($user['id'].'-'.$user['name'],'light_blue')."\t".jry_wb_php_cli_color('get ','green').jry_wb_php_cli_color(strlen($data),'magenta').'/B data '.substr($data,0,100));
					$data = json_decode($data);
					if($data->code==false)
					{
					}
					else
					{
						$data->user=$user;
						$data->c_index=$c_index;
						if($data->type==100000)
							jry_wb_socket_send($socket,(array('code'=>true,'type'=>'100000')));
						else if($data->type==100001)
						{
							if(is_int($data->data->add))
							{
								if(array_search($data->data->add,$clients_listener[$c_index])===false)
									$clients_listener[$c_index][]=$data->data->add;
							}
							else
							{
								foreach($data->data->add as $add)
									if(array_search($add,$clients_listener[$c_index])===false)
										$clients_listener[$c_index][]=$add;
							}
							jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color($user['id'].'-'.$user['name'],'light_blue')."\t".jry_wb_php_cli_color('add listener','green').' now have '.json_encode($clients_listener[$c_index]));
						}
						else if($data->type==100002)
						{
							if(is_int($data->data->del))
							{
								if(($i=array_search($data->data->del,$clients_listener[$c_index]))!==false)
									array_splice($clients_listener[$c_index],$i,1);
							}
							else
							{
								foreach($data->data->del as $del)
									if(($i=array_search($del,$clients_listener[$c_index]))!==false)
										array_splice($clients_listener[$c_index],$i,1);
							}							
							jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color($user['id'].'-'.$user['name'],'light_blue')."\t".jry_wb_php_cli_color('delete listener','green').' now have '.json_encode($clients_listener[$c_index]));
						}						
						else if($data->type==100003)
						{
							jry_wb_socket_send($socket,(array('code'=>true,'type'=>'100003','data'=>array('listener'=>$clients_listener[$c_index]))));
							jry_wb_cli_echo_log(jry_wb_php_cli_color(jry_wb_get_time()."\t",'brown').jry_wb_php_cli_color($user['id'].'-'.$user['name'],'light_blue')."\t".jry_wb_php_cli_color('get listener','green').' now have '.json_encode($clients_listener[$c_index]));
						}
						else
							jry_wb_cli_add_task($data);
					}
				}
			}
		}
	 
	}