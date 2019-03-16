<?php
	include_once("../tools/jry_wb_includes.php");
	$conn=jry_wb_connect_database();
	$admin_mode=(($_GET['admin_mode']=='true')&&($jry_wb_login_user['id']!=-1)&&$jry_wb_login_user['manageusers']&&$jry_wb_login_user['manage']);	
	if($_GET['action']=='new')
	{
		$q='SELECT *,'.constant('jry_wb_database_general_prefix').'users.id AS id
			FROM '.constant('jry_wb_database_manage_system').'competence 
			INNER JOIN '.constant('jry_wb_database_general').'users  ON ('.constant('jry_wb_database_general_prefix').'users.type = '.constant('jry_wb_database_manage_system_prefix').'competence.type) 
			LEFT JOIN '.constant('jry_wb_database_general').'login  ON ('.constant('jry_wb_database_general_prefix').'users.id = '.constant('jry_wb_database_general_prefix')."login.id)
			order by ".constant('jry_wb_database_general')."users.id desc limit 1";
		$st = $conn->prepare($q);
		$st->execute();
		foreach($st->fetchAll()as $user);
	}
	else
	{
		$q='SELECT *,'.constant('jry_wb_database_general_prefix').'users.id AS id
			FROM '.constant('jry_wb_database_manage_system').'competence 
			INNER JOIN '.constant('jry_wb_database_general').'users  ON ('.constant('jry_wb_database_general_prefix').'users.type = '.constant('jry_wb_database_manage_system_prefix').'competence.type) 
			LEFT JOIN '.constant('jry_wb_database_general').'login  ON ('.constant('jry_wb_database_general_prefix').'users.id = '.constant('jry_wb_database_general_prefix')."login.id)
			where ".constant('jry_wb_database_general_prefix')."users.id =? LIMIT 1";
		$st = $conn->prepare($q);
		$st->bindParam(1,$_GET['id']);
		$st->execute();
		foreach($st->fetchAll()as $user);
	}
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
	if(!$user['use']&&!$jry_wb_login_user['manageusers'])
	{
		echo json_encode(array(	'id'=>(int)$_GET['id'],
								'use'=>(int)$user['use'],
								'ips'=>''
						));
		exit();		
	}
	if((strtotime($user['lasttime'])-strtotime(urldecode($_GET['lasttime'])))<=0)
	{
		echo json_encode(array('id'=>-1,'use'=>1));
		exit();
	}
	if($user['oauth_qq']!='')
		$user['oauth_qq']=json_decode($user['oauth_qq']);
	if($user['oauth_github']!='')
		$user['oauth_github']=json_decode($user['oauth_github']);	
	if($user['oauth_mi']!='')
		$user['oauth_mi']=json_decode($user['oauth_mi']);	
	if($user['oauth_gitee']!='')
		$user['oauth_gitee']=json_decode(preg_replace('/\\\n/i','<br>',$user['oauth_gitee']));
	$head=jry_wb_get_user_head($user);
	$ip=array();
	if($user['ip_show']||($admin_mode))
	{
		$i=0;
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'login where id=?');
		$st->bindParam(1,$user['id']);
		$st->execute();
		$user['ips']=$st->fetchAll();
		foreach($user['ips']as $ips)
		{
			$arr=jry_wb_get_ip_address($ips['ip']);
			if($arr->data->isp=='unknow')
				$ip[$i]='未知地区|'.$ips['time'].' '.jry_wb_get_device_from_database($ips['device']);
			else if($arr->data->isp=='内网IP')
				$ip[$i]='内网IP|'.$ips['time'].'|'.jry_wb_get_device_from_database($ips['device']);
			else
				$ip[$i] = $arr->data->country.$arr->data->region.$arr->data->city.$arr->data->isp.'|'.$ips['time'].'|'.jry_wb_get_device_from_database($ips['device']);
			$i++;
		}
	}
	if($user['mail']!=''&&(!$admin_mode))
	{
		if($user['mail_show']==0)
		{
			$buf=explode('@',$user['mail']);
			$user['mail']=substr_replace($buf[0],'****',3,count($buf[0])-3).'@'.$buf[1];
		}else if($user['mail_show']==1)
		{
			$buf=explode('@',$user['mail']);
			$count=count($buf[0]);
			$user['mail']='';
			for($i=0;$i<$count;$i++)
				$user['mail'].='*';
			$user['mail'].='@'.$buf[1];
		}
	}
	if($user['tel']!=''&&(!$admin_mode))
	{
		if($user['tel_show']==0)
			$user['tel']=substr_replace($user['tel'],'****',3,4);
		else if($user['tel_show']==1)
			$user['tel']=substr_replace($user['tel'],'***********',0,11);
	}
	if($_GET['action']=='new')
		$id=$user['id'];
	else
		$id=$_GET['id'];
	$user['head_special']=json_decode($user['head_special']);
	if($user['head_special']->mouse_on->times!=-1&&($user['head_special']->mouse_out->times==0||$user['head_special']->mouse_out->speed==0))
	{
		$user['head_special']->mouse_out->speed=$user['head_special']->mouse_on->speed;
		$user['head_special']->mouse_out->direction=(($user['head_special']->mouse_on->direction)?0:1);
		$user['head_special']->mouse_out->times=1;
	}
	$user['head_special']->mouse_out->result=jry_wb_get_user_head_style_out($user);
	$user['head_special']->mouse_on->result=jry_wb_get_user_head_style_on($user);
	$data=array('id'=>(int)$id,
				'head'=>$head,
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
				'oauth_qq'=>$user['oauth_qq']->message,
				'oauth_mi'=>$user['oauth_mi']->message,
				'oauth_github'=>$user['oauth_github']->message,
				'oauth_gitee'=>$user['oauth_gitee']->message,
				'login_addr'=>($user['ip_show']||($admin_mode))?$ip:-1,
				'password'=>($admin_mode)?$user['password']:''
				);
	if(!$admin_mode)
	{
		$data['oauth_qq']=null;
		$data['oauth_mi']=null;
		$data['oauth_github']=null;
		$data['oauth_gitee']=null;
	}
	echo json_encode($data);
?>