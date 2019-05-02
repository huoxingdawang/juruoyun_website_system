<?php
	include_once("jry_wb_includes.php");
	if(($_SERVER['HTTP_HOST']!=constant('jry_wb_domin').(constant('jry_wb_port')==''?'':':').constant('jry_wb_port'))&&$jry_wb_socket_mode!==true)
	{
		header("Location:".constant('jry_wb_host'));
		exit();
	}	
	global $jry_wb_login_user;
	session_start();
	//预处理
	$jry_wb_socket_mode=false;	
	$jry_wb_keywords='';
	$jry_wb_description='';
	$conn=jry_wb_connect_database();
	function jry_wb_pretreatment(&$user,$cookie,$ip,$user_agent=NULL)
	{
		global $jry_wb_socket_mode;
		global $conn;
		if($user_agent===NULL)
			$user_agent=$_SERVER["HTTP_USER_AGENT"];		
		$q ="DELETE FROM ".constant('jry_wb_database_general')."login where time<? AND trust=0";
		$st = $conn->prepare($q);
		$st->bindValue(1,date("Y-m-d H;i:s",time()-constant('logintime')));
		$st->execute();
		$user=NULL;
		if($cookie['code']!=NULL&&$cookie['id']!=NULL)
		{
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'login WHERE id=? AND code=? AND device=? AND browser=? LIMIT 1');
			$st->bindValue(1,intval((($cookie['id']!='') ? $cookie['id'] : -1)));
			$st->bindValue(2,$cookie['code']);
			$st->bindValue(3,jry_wb_get_device(true,$user_agent));
			$st->bindValue(4,jry_wb_get_browser(true,$user_agent));
			$st->execute();
			foreach($st->fetchAll()as $one)
			{
				if($one['trust']&&$ip!=$one['ip'])
				{
					$st = $conn->prepare('UPDATE '.constant('jry_wb_database_general').'login SET ip=? WHERE id=? AND device=? AND browser=? AND code=?');
					$st->bindValue(1,$ip);
					$st->bindValue(2,intval((($cookie['id']!='') ? $cookie['id'] : -1)));
					$st->bindValue(3,jry_wb_get_device(true,$user_agent));
					$st->bindValue(4,jry_wb_get_browser(true,$user_agent));
					$st->bindValue(5,$cookie['code']);				
					$st->execute();				
				}		
				if($one['trust']||$ip==$one['ip'])
				{
					$user=$one;
					break;
				}
			}
			if($user==NULL)
				$user=NULL;
			else
			{
				$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users WHERE id=? LIMIT 1');
				$st->bindValue(1,$user['id']);
				$st->execute();				
				$datas=$st->fetchAll();
				if(count($datas)==0)
					$user=NULL;				
				else
				{
					$user=array_merge($user,$datas[0]);
					$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_manage_system').'competence WHERE type=? LIMIT 1');
					$st->bindValue(1,$user['type']);
					$st->execute();
					$datas=$st->fetchAll();
					if(count($datas)==0)
						$user=NULL;				
					else
						$user=array_merge($user,$datas[0]);
				}
			}
		}
		if($user==NULL)
		{
			$user['id']=-1;
			$_SESSION['language']=$user['language']=constant('jry_wb_default_language');
			setcookie('id',-1,time()-1,'/',constant('jry_wb_domin'),NULL,false);
			setcookie('code','',time()-1,'/',constant('jry_wb_domin'),NULL,true);
		}
		else
		{
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'login where id=? ORDER BY `device`,`time`,`browser`,`ip`');
			$st->bindValue(1,$user['id']);
			$st->execute();
			$user['ips']=$st->fetchAll();	
			$_SESSION['language']=$user['language'];
		}
		if(jry_wb_test_is_mobile())
			$user['jry_wb_test_is_mobile']='mobile';
		else if(jry_wb_test_is_weixin())
			$user['jry_wb_test_is_mobile']='weixin';
		else
			$user['jry_wb_test_is_mobile']='disktop';
		$user['browser']=jry_wb_get_browser();
		$user['device']=jry_wb_get_device();
		$user['manageusers']=$user['id']==-1?0:$user['manageusers'];
		if($user['id']==-1)
		{
			$user['style_id']=1;
			$user['head_special']='{"mouse_out":{"speed":2,"direction":1,"times":-1},"mouse_on":{"speed":2,"direction":1,"times":-1}}';
		}
		if($jry_wb_socket_mode===false)
		{
			foreach($user['ips']as $ips)
			{
				$arr=jry_wb_get_ip_address($ips['ip']);
				if($arr->data->isp=='unknow')
					$data='未知地区|'.$ips['time'].'|'.jry_wb_get_device_from_database($ips['device']).'|'.jry_wb_get_browser_from_database($ips['browser']);
				else if($arr->data->isp=='内网IP')
					$data='内网IP|'.$ips['time'].'|'.jry_wb_get_device_from_database($ips['device']).'|'.jry_wb_get_browser_from_database($ips['browser']);
				else
					$data=$arr->data->country.$arr->data->region.$arr->data->city.$arr->data->isp.'|'.$ips['time'].'|'.jry_wb_get_device_from_database($ips['device']).'|'.jry_wb_get_browser_from_database($ips['browser']);
				if($isthis=($cookie['code']==$ips['code']))
				{
					$user['logdate']=$ips['time'];
					if($ips['trust'])
						$time=time()+60*60*24*365*1000;
					else
						$time=time()+constant('logintime');
					setcookie('id',$user['id'],$time,'/',jry_wb_get_domain(),NULL,false);
					setcookie('id',$user['id'],$time,'/',constant('jry_wb_domin'),NULL,false);			
					setcookie('code',$ips['code'],$time,'/',constant('jry_wb_domin'),NULL,true);			
				}
				$user['login_addr'][]=array('isthis'=>$isthis,'data'=>$data,'trust'=>$ips['trust'],'code'=>$ips['code']);
			}
			$user['head_special']=json_decode($user['head_special']);
			if($user['head_special']->mouse_on->times!=-1&&($user['head_special']->mouse_out->times==0||$user['head_special']->mouse_out->speed==0))
			{
				$user['head_special']->mouse_out->speed=$user['head_special']->mouse_on->speed;
				$user['head_special']->mouse_out->direction=(($user['head_special']->mouse_on->direction)?0:1);
				$user['head_special']->mouse_out->times=1;
			}	
			$user['head_special']->mouse_out->result=jry_wb_get_user_head_style_out($user);
			$user['head_special']->mouse_on->result=jry_wb_get_user_head_style_on($user);
			$user['style']=jry_wb_load_style($user['style_id']);
			$user['background_music_list']=json_decode($user['background_music_list']==''||$user['id']==-1?'[{"slid": "0", "type": "songlist"}]':$user['background_music_list']);
			if($user['oauth_qq']!='')
				$user['oauth_qq']=json_decode($user['oauth_qq']);
			if($user['oauth_github']!='')
				$user['oauth_github']=json_decode($user['oauth_github']);	
			if($user['oauth_mi']!='')
				$user['oauth_mi']=json_decode($user['oauth_mi']);	
			if($user['oauth_gitee']!='')
				$user['oauth_gitee']=json_decode(preg_replace('/\\\n/i','<br>',$user['oauth_gitee']));
			if($user['extern']!='')
				$user['extern']=json_decode($user['extern']);					
		}
		else
		{
			unset($user['background_music_list']);
			unset($user['head_special']);
			unset($user['oauth_qq']);
			unset($user['oauth_github']);
			unset($user['oauth_mi']);
			unset($user['oauth_gitee']);
			unset($user['ips']);
			unset($user['zhushi']);
			unset($user['head']);
			unset($user['jry_wb_test_is_mobile']);
			unset($user['trust']);
			unset($user['browser']);
			unset($user['device']);
			unset($user['time']);
			unset($user['follow_mouth']);
			unset($user['word_special_fact']);
			unset($user['style_id']);
			unset($user['tel_show']);
			unset($user['mail_show']);
			unset($user['ip_show']);
			unset($user['greendate']);
			unset($user['logdate']);
			unset($user['enroldate']);
			unset($user['password']);
			unset($user['lasttime']);
			unset($user['color']);
		}
		$n=count($user);
		for($i=0;$i<$n;$i++)
			unset($user[$i]);		
	}
	jry_wb_pretreatment($jry_wb_login_user,$_COOKIE,$_SERVER['REMOTE_ADDR']);