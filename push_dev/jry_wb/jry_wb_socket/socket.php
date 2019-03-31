<?php
	include_once("jry_wb_cli_include.php");
// 1 登录	
$host = '127.0.0.1';
$port = 2222;
$maxClient = 1000;
const MSG_TYPE_HANDSHAKE = 0;//握住信息
const MSG_TYPE_MESSAGE = 1;//正常聊天信息
const MSG_TYPE_DISCONNECT = -1;//退出信息
const MSG_TYPE_JOIN = 2;//请求加入信息，给特定用户
const MSG_TYPE_LOGIN = 3;//加入聊天信息，给全体发
 
	ob_implicit_flush();
	$master=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
	if($master===FALSE)
	{
		echo 'socket_create() failed:'.socket_strerror(socket_last_error());
		exit();
	}
	socket_set_option($master, SOL_SOCKET, SO_REUSEADDR, 1);
	$bind=socket_bind($master,constant('jry_wb_socket_host'),constant('jry_wb_socket_port')."\n");
	if($bind===FALSE)
	{
		echo 'socket_bind() failed:'.socket_strerror(socket_last_error())."\n";
		exit();
	}
	$listen=socket_listen($master,constant('jry_wb_socket_max_client'));
	if($listen===FALSE)
	{
		echo 'socket_listen() failed:'.socket_strerror(socket_last_error())."\n";
		exit();
	}
	$clients = array();
	$users = array();
	$users_id=array_column($users,'id');	
	echo ("\n".jry_wb_php_cli_color('OK','green')."\nat ".jry_wb_php_cli_color(constant('jry_wb_socket_host').':'.constant('jry_wb_socket_port'),'cyan')."\nby ".jry_wb_php_cli_color('juruoyun web system '.constant('jry_wb_version'),'light_green')."\n");
	while(1)
	{
		$sockets = $clients;
		$sockets[] = $master;
		$write = NULL;
		$except = NULL;
		$tv_sec = NULL;
		socket_select($sockets, $write, $except, $tv_sec);
		//循环有状态变化的socket
		foreach ($sockets as $socket)
		{
			if ($socket === $master)
			{
				$client = socket_accept($master);
				if ($client === FALSE)
					echo 'socket_accept() failed:'.socket_strerror(socket_last_error())."\n";
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
					socket_getpeername($client,$ip);					
					jry_wb_pretreatment($user,$cookie,$ip);
					if($user['id']==-1)
					{
						echo 'Not login user at '.$ip."\n";
						socket_close($client);
						continue;
					}
					//print_r($user);
					if(preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $header, $match))//冒号后面有个空格
					{
						$secKey = $match[1];
						$secAccept = base64_encode(sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', TRUE));//握手算法固定的
						$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
						"Upgrade: websocket\r\n" .
						"Connection: Upgrade\r\n" .
						"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
						socket_write($client, $upgrade, strlen($upgrade));
					}
					$result=array_search($user['id'],$users_id);
					if($result===false)
					{
						$user['sockets']=array();
						$user['sockets'][]=$client;
						$users[]=$user;
						$users_id=array_column($users,'id');
						echo jry_wb_php_cli_color($user['id'].'-'.$user['name'],'light_blue').' connected at '.jry_wb_php_cli_color($ip,'cyan').' now have '.($count=1).' connect '.jry_wb_php_cli_color('new','yellow')."\n";
					}
					else
					{
						$users[$result]['sockets'][]=$client;
						echo jry_wb_php_cli_color($user['id'].'-'.$user['name'],'light_blue').' connected at '.jry_wb_php_cli_color($ip,'cyan').' now have '.($count=count($users[$result]['sockets']))." connect(s)\n";
					}
					jry_wb_socket_send($client,json_encode(array('code'=>true,'type'=>1,'data'=>array('connect_count'=>$count))));
				}
			}
			else
			{
				//其他socket的状态变化
				$bytes = socket_recv($socket, $buf, 1024, 0);//读取发送过来的信息的字节数
				$data = frameDecode($buf);//正常信息为json字符串，
				if ($bytes === FALSE)
				{
					echo 'socket_recv() failed:'.socket_strerror(socket_last_error());
				}
				elseif($bytes <= 6 || empty($data) || !is_object(json_decode($data)))
				{
					$index = array_search($socket, $clients);//寻找该socket在用户列表中的位置
					$userInfo = $users[$index];
					socket_getpeername($socket, $ip);//获取用户IP地址
					$response = frameEncode(json_encode(array('type' => MSG_TYPE_DISCONNECT, 'msg' => $userInfo, 'time' => $time)));
					sendMessage($response);
	 
					unset($clients[$index]);//删除用户
					unset($users[$index]);
					socket_close($socket);
					echo "user $ip($index) disconnect\r\n";
				}
				else
				{
					//正常聊天信息
					$data = json_decode($data);//对象
					print_r($data);
					sendMessage(frameEncode(json_encode(array('code'=>true,'extern'=>'get','data'=>$data))));
					
					/*if ($data->type == MSG_TYPE_JOIN)
					{
						//握手成功请求加入
						$index = array_search($socket, $clients);
						$users[$index] = $data->userinfo;//记录用户信息，含id的用户名的json字符串
						sendUserList($socket, $data->userinfo);//发送用户列表
						echo "ask to join in \r\n";
					}
					elseif($data->type == MSG_TYPE_MESSAGE)
					{
						$response = frameEncode(json_encode(array('type' => MSG_TYPE_MESSAGE, 'msg' => $data->msg, 'time' => $time, 'username' => $data->username)));
						sendMessage($response);
						echo "receive message\r\n";
					}*/
	 
				}
			}
		}
	 
	}
	function jry_wb_socket_send($client,$message)
	{
		$b1=0x80|(0x1&0x0f);
		$length=strlen($message);
		if($length<=125)
		{
			$header=pack('CC',$b1,$length);
		}
		elseif($length>125&&$length<65536)
		{
			$header=pack('CCn',$b1,126,$length);
		}
		elseif($length>=65536)
		{
			$header=pack('CCNN',$b1,127,$length);
		}
		$message=$header.$message;
		socket_write($client,$message,strlen($message));		
	}
/**
 * 编码数据帧
 * Enter description here ...
 * @param unknown_type $text
 */
function frameEncode($text)
{
	$b1 = 0x80 | (0x1 & 0x0f);
    $length = strlen($text);
 
    if($length <= 125)
    {
        $header = pack('CC', $b1, $length);
    }
    elseif($length > 125 && $length < 65536)
    {
        $header = pack('CCn', $b1, 126, $length);
    }
    elseif($length >= 65536)
    {
        $header = pack('CCNN', $b1, 127, $length);
    }
    return $header.$text;
}
 
/**
 * 解码数据帧
 * Enter description here ...
 * @param unknown_type $text
 */
function frameDecode($text) {
    $length = ord($text[1]) & 127;
    if($length == 126) 
    {
        $masks = substr($text, 4, 4);
        $data = substr($text, 8);
    }
    elseif($length == 127) 
    {
        $masks = substr($text, 10, 4);
        $data = substr($text, 14);
    }
    else
    {
        $masks = substr($text, 2, 4);
        $data = substr($text, 6);
    }
    $text = "";
    for ($i = 0; $i < strlen($data); ++$i) 
    {
        $text .= $data[$i] ^ $masks[$i%4];
    }
    return $text;
}
 
/**
 * 发送信息
 * Enter description here ...
 * @param unknown_type $msg
 */
function sendMessage($msg, $receiver = '')
{
	echo 'send '.$msg."\n";
    if (!empty($receiver))
    {
        socket_write($receiver, $msg, strlen($msg));
    }
    else
    {
        global $clients;
        foreach ($clients as $client)
        {
            socket_write($client, $msg, strlen($msg));
        }
    }
}
/**
 * 给某用户发送在线用户列表
 * Enter description here ...
 * @param unknown_type $client
 */
function sendUserList($client, $userinfo)
{
    global $users;
    $userList = json_encode($users);
    $time = date('Y-m-d H:i:s', time());
    $response = frameEncode(json_encode(array('type' => MSG_TYPE_JOIN, 'msg' => $userList, 'time' => $time, 'count' => count($users))));
    socket_write($client, $response, strlen($response));//给特定用户发送在线用户列表
    echo "send user list \r\n";
    //通知其他用户有新用户登陆
    sendMessage(frameEncode(json_encode(array('type' => MSG_TYPE_LOGIN, 'msg' => $userinfo, 'time' => $time))));
    echo "login in success\r\n";
}	