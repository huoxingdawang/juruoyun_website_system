<?php
	include_once("jry_wb_includes.php");	
	global $jry_wb_login_user;
	session_start();
	//预处理
	$jry_wb_keywords='';
	$jry_wb_description='';
	$conn=jry_wb_connect_database();
	$q ="DELETE FROM ".constant('jry_wb_database_general')."login where time<? AND trust=0";
	$st = $conn->prepare($q);
	$st->bindParam(1,date("Y-m-d H;i:s",time()-constant('logintime')));
	$st->execute();	
	if($_COOKIE['code']!=NULL&&$_COOKIE['id']!=NULL)
	{
		$q='SELECT * FROM '.constant('jry_wb_database_manage_system').'competence 
			INNER JOIN '.constant('jry_wb_database_general').'users  ON ('.constant('jry_wb_database_general_prefix').'users.type = '.constant('jry_wb_database_manage_system_prefix').'competence.type) 
			LEFT JOIN '.constant('jry_wb_database_general').'login  ON ('.constant('jry_wb_database_general_prefix').'users.id = '.constant('jry_wb_database_general_prefix')."login.id)
			where ".constant('jry_wb_database_general_prefix')."users.id =? AND device=? AND code=? LIMIT 1";
		$st = $conn->prepare($q);
		$st->bindParam(1,intval((isset($_COOKIE['id']) ? $_COOKIE['id'] : -1)));
		$st->bindParam(2,jry_wb_get_device(true));
		$st->bindParam(3,$_COOKIE['code']);
		$st->execute();
		foreach($st->fetchAll()as $one)
		{
			if($one['trust']||$_SERVER['REMOTE_ADDR']==$one['ip'])
				$jry_wb_login_user=$one;
			if($one['trust']&&$_SERVER['REMOTE_ADDR']!=$one['ip'])
			{
				$st = $conn->prepare('UPDATE '.constant('jry_wb_database_general').'login SET ip=? WHERE id=? AND device=? AND code=?');
				$st->bindParam(1,$_SERVER['REMOTE_ADDR']);
				$st->bindParam(2,intval((isset($_COOKIE['id']) ? $_COOKIE['id'] : -1)));
				$st->bindParam(3,jry_wb_get_device(true));
				$st->bindParam(4,$_COOKIE['code']);				
				$st->execute();				
			}
		}
		if($jry_wb_login_user==NULL)
		{
			$jry_wb_login_user['id']=-1;
			$_SESSION['language']=$jry_wb_login_user['language']=constant('jry_wb_default_language');
			setcookie('id','',-1,'/',jry_wb_get_domain(),NULL,false);
			setcookie('code','',-1,'/',$_SERVER['HTTP_HOST'],NULL,true);			
			
		}
		else
		{
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'login where id=? ORDER BY `device`,`time`,`browser`,`ip`');
			$st->bindParam(1,$jry_wb_login_user['id']);
			$st->execute();
			$jry_wb_login_user['ips']=$st->fetchAll();	
			$_SESSION['language']=$jry_wb_login_user['language'];
		}
	}
	else
	{
		$jry_wb_login_user=NULL;
		$jry_wb_login_user['id']=-1;
		$_SESSION['language']=$jry_wb_login_user['language']=constant('jry_wb_default_language');	
	}
	if(jry_wb_test_is_mobile())
		$jry_wb_login_user['jry_wb_test_is_mobile']='mobile';
	else if(jry_wb_test_is_weixin())
		$jry_wb_login_user['jry_wb_test_is_mobile']='weixin';
	else
		$jry_wb_login_user['jry_wb_test_is_mobile']='disktop';
	$jry_wb_login_user['browser']=jry_wb_get_browser();
	$jry_wb_login_user['device']=jry_wb_get_device();
	$jry_wb_login_user['manageusers']=$jry_wb_login_user['id']==-1?0:$jry_wb_login_user['manageusers'];
	foreach($jry_wb_login_user['ips']as $ips)
	{
		$arr=jry_wb_get_ip_address($ips['ip']);
		if($arr->data->isp=='unknow')
			$data='未知地区|'.$ips['time'].'|'.jry_wb_get_device_from_database($ips['device']).'|'.jry_wb_get_browser_from_database($ips['browser']);
		else if($arr->data->isp=='内网IP')
			$data='内网IP|'.$ips['time'].'|'.jry_wb_get_device_from_database($ips['device']).'|'.jry_wb_get_browser_from_database($ips['browser']);
		else
			$data=$arr->data->country.$arr->data->region.$arr->data->city.$arr->data->isp.'|'.$ips['time'].'|'.jry_wb_get_device_from_database($ips['device']).'|'.jry_wb_get_browser_from_database($ips['browser']);
		if($isthis=($_COOKIE['code']==$ips['code']))
		{
			$jry_wb_login_user['logdate']=$ips['time'];
			setcookie('id',$jry_wb_login_user['id'],time()+60*60*24*100,'/',jry_wb_get_domain(),NULL,false);
			setcookie('code',$ips['code'],time()+60*60*24*100,'/',$_SERVER['HTTP_HOST'],NULL,true);			
		}
		$jry_wb_login_user['login_addr'][]=array('isthis'=>$isthis,'data'=>$data,'trust'=>$ips['trust'],'code'=>$ips['code']);
	}
	if($jry_wb_login_user['id']==-1)
	{
		$jry_wb_login_user['style_id']=1;
		$jry_wb_login_user['head_special']='{"mouse_out":{"speed":2,"direction":1,"times":-1},"mouse_on":{"speed":2,"direction":1,"times":-1}}';
	}
	$jry_wb_login_user['head_special']=json_decode($jry_wb_login_user['head_special']);
	if($jry_wb_login_user['head_special']->mouse_on->times!=-1&&($jry_wb_login_user['head_special']->mouse_out->times==0||$jry_wb_login_user['head_special']->mouse_out->speed==0))
	{
		$jry_wb_login_user['head_special']->mouse_out->speed=$jry_wb_login_user['head_special']->mouse_on->speed;
		$jry_wb_login_user['head_special']->mouse_out->direction=(($jry_wb_login_user['head_special']->mouse_on->direction)?0:1);
		$jry_wb_login_user['head_special']->mouse_out->times=1;
	}	
	$jry_wb_login_user['head_special']->mouse_out->result=jry_wb_get_user_head_style_out($jry_wb_login_user);
	$jry_wb_login_user['head_special']->mouse_on->result=jry_wb_get_user_head_style_on($jry_wb_login_user);
	$jry_wb_login_user['style']=jry_wb_load_style($jry_wb_login_user['style_id']);
	$jry_wb_login_user['background_music_list']=json_decode($jry_wb_login_user['background_music_list']==''||$jry_wb_login_user['id']==-1?'[{"slid": "0", "type": "songlist"}]':$jry_wb_login_user['background_music_list']);
	if($jry_wb_login_user['oauth_qq']!='')
		$jry_wb_login_user['oauth_qq']=json_decode($jry_wb_login_user['oauth_qq']);
	if($jry_wb_login_user['oauth_github']!='')
		$jry_wb_login_user['oauth_github']=json_decode($jry_wb_login_user['oauth_github']);	
	if($jry_wb_login_user['oauth_mi']!='')
		$jry_wb_login_user['oauth_mi']=json_decode($jry_wb_login_user['oauth_mi']);	
	if($jry_wb_login_user['oauth_gitee']!='')
		$jry_wb_login_user['oauth_gitee']=json_decode(preg_replace('/\\\n/i','<br>',$jry_wb_login_user['oauth_gitee']));
?>