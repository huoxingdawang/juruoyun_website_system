<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	$conn=jry_wb_connect_database();
	$admin_mode=(($_GET['admin_mode']=='true')&&($jry_wb_login_user['id']!=-1)&&$jry_wb_login_user['compentence']['manageusers']&&$jry_wb_login_user['compentence']['manage']);	
	$user=jry_wb_get_user($conn,$_GET['id'],$admin_mode);
	if($user==null)
	{
		echo json_encode(array(	'id'=>(int)$_GET['id'],
								'use'=>1,
								'show'=>null,
								'name'=>null,
								'head'=>null,
								'ips'=>''
						));
		exit();			
	}
	if(!$user['use']&&!$jry_wb_login_user['compentence']['manageusers'])
	{
		echo json_encode(array(	'id'=>(int)$_GET['id'],
								'use'=>(int)$user['use'],
								'ips'=>''
						));
		exit();		
	}
	header('Etag: '.$user['lasttime']);
	if((strtotime($user['lasttime'])-strtotime(urldecode($_SERVER['HTTP_IF_NONE_MATCH'])))<=0)
	{
		header('HTTP/1.1 304');  
		exit();
	}
	if($_GET['action']=='new')
		$id=$user['id'];
	else
		$id=$_GET['id'];
	$data=array('id'=>(int)$id,
				'head'=>$user['head'],
				'head_special'=>$user['head_special'],
				'green_money'=>$user['green_money'],
				'enroldate'=>$user['enroldate'],
				'competencename'=>$user['competencename'],
				'color'=>$user['color'],						
				'name'=>$user['name'],
				'sex'=>$user['sex'],
				'tel'=>$user['tel'],
				'mail'=>$user['mail'],
				'language'=>$user['language'],
				'zhushi'=>$user['zhushi'],
				'lasttime'=>$user['lasttime'],
				'lasttime_sync'=>jry_wb_get_time(),
				'type'=>$user['type'],
				'use'=>$user['use'],
				'oauth'=>(($admin_mode||$user['oauth_show'])?array(	'qq'		=>array('message'=>$user['oauth']->qq		->message),
																	'mi'		=>array('message'=>$user['oauth']->mi		->message),
																	'github'	=>array('message'=>$user['oauth']->github	->message),
																	'gitee'		=>array('message'=>$user['oauth']->gitee	->message)
																	):null),
				'login_addr'=>($user['ip_show']||($admin_mode))?$user['login_addr']:-1,
				'password'=>($admin_mode)?$user['password']:'',
				'extern'=>($admin_mode)?json_decode($user['extern']):''
				);
	echo json_encode($data);
?>