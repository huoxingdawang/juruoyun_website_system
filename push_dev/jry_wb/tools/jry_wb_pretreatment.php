<?php
	include_once("jry_wb_includes.php");
	if(($_SERVER['HTTP_HOST']!=JRY_WB_DOMIN.(JRY_WB_PORT==''?'':':').JRY_WB_PORT)&&(!jry_wb_test_is_cli_mode()))
	{
		header("Location:".JRY_WB_HOST);
		exit();
	}
	session_start();
	//预处理
	$jry_wb_keywords='';
	$jry_wb_description='';
	$conn=jry_wb_connect_database();
	function jry_wb_pretreatment($conn,&$user,$cookie,$ip,$user_agent=NULL)
	{
		global $jry_wb_socket_mode;
		if($user_agent===NULL)
			$user_agent=$_SERVER["HTTP_USER_AGENT"];		
		$q ="DELETE FROM ".JRY_WB_DATABASE_GENERAL."login where time<? AND trust=0";
		$st = $conn->prepare($q);
		$st->bindValue(1,date("Y-m-d H;i:s",time()-JRY_WB_LOGIN_TIME));
		$st->execute();
		$user=NULL;
		if($cookie['code']!=NULL&&$cookie['id']!=NULL)
		{
			$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'login WHERE id=? AND code=? AND device=? AND browser=? LIMIT 1');
			$st->bindValue(1,intval((($cookie['id']!='') ? $cookie['id'] : -1)));
			$st->bindValue(2,$cookie['code']);
			$st->bindValue(3,jry_wb_get_device(true,$user_agent));
			$st->bindValue(4,jry_wb_get_browser(true,$user_agent));
			$st->execute();
			foreach($st->fetchAll()as $one)
			{
				if($one['trust']&&$ip!=$one['ip'])
				{
					$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_GENERAL.'login SET ip=? WHERE id=? AND device=? AND browser=? AND code=?');
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
				$buf=jry_wb_get_user($conn,$user['id'],true);
				if($buf==NULL)
					$user=NULL;				
				else
					$user=array_merge($buf,$user);
			}
		}
		if($user==NULL)
		{
			$user['id']=-1;
			$_SESSION['language']=$user['language']=JRY_WB_DEFAULT_LANGUAGE;
			setcookie('id',-1,time()-1,'/',JRY_WB_DOMIN,NULL,false);
			setcookie('code','',time()-1,'/',JRY_WB_DOMIN,NULL,true);
		}
	}
	jry_wb_pretreatment($conn,$jry_wb_login_user,$_COOKIE,$_SERVER['REMOTE_ADDR']);