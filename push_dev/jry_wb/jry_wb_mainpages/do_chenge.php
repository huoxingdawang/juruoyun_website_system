<?php
	include_once("../tools/jry_wb_includes.php");
	$conn=jry_wb_connect_database();
	$st = $conn->prepare('DELETE FROM '.constant('jry_wb_database_general').'mail_code where time<?');
	$st->bindParam(1,date("Y-m-d H:i:s",time()-12*60*60));
	$st->execute();	
	$st = $conn->prepare('DELETE FROM '.constant('jry_wb_database_general').'tel_code where time<?');
	$st->bindParam(1,date("Y-m-d H:i:s",time()-5*60));
	$st->execute();
	try
	{
		jry_wb_print_head("",true,false,false,array(),false,false);
		if($_GET['action']=='unlock')
		{
			if(jry_wb_send_mail('lijunyandeyouxiang@163.com',
			$_GET['id'].'申请解封',
			$_GET['id'].'在'.jry_wb_get_time().'申请解封<br>'.'请及时处理<br>'.
			'<a href="'.constant('jry_wb_host').'manage_system/index.php">点击进入管理员中心</a><br>'.
			'<a href="'.constant('jry_wb_host').'manage_system/do_user.php?action=unlock&id='.$_GET['id'].'">点击发送解封通知</a>'
			))
				echo json_encode(array('data'=>'OK'));
			else
				echo json_encode(array('data'=>'mail'));
			exit();
		}
		else if($_GET['action']=='send_tel')
		{
			if(constant('jry_wb_short_message_switch')=='')
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>000000,'file'=>__FILE__,'line'=>__LINE__)));
			if($_POST['vcode']!=$_SESSION['vcode']||$_POST['vcode']=='')
			{
				if(strtolower($_POST['vcode'])==strtolower($_SESSION['vcode']))
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100005,'file'=>__FILE__,'line'=>__LINE__)));
				else
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100002,'file'=>__FILE__,'line'=>__LINE__)));
			}
			if($_POST['tel']==$jry_wb_login_user['tel'])
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100004,'file'=>__FILE__,'line'=>__LINE__)));
			if(!jry_wb_test_phone_number($_POST['tel']))
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100008,'file'=>__FILE__,'line'=>__LINE__)));
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users where tel=?');
			$st->bindParam(1,$_POST['tel']);
			$st->execute();
			if(count($st->fetchAll())!=0)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100009,'file'=>__FILE__,'line'=>__LINE__)));	
			require_once "../tools/jry_wb_short_message.php";
			if(($code=jry_wb_get_short_message_code($_POST['tel']))==-1)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100003,'file'=>__FILE__,'line'=>__LINE__)));	
			jry_wb_send_short_message($_POST['tel'],Array ("code"=>$code),constant('jry_wb_short_message_aly_chenge'));
			echo json_encode(array('code'=>true));
			exit();
		}
		else if($_GET['action']=='setsonglist')
		{
			$q ="update ".constant('jry_wb_database_general')."users set background_music_list=?,lasttime=? where id=? ";
			$st = $conn->prepare($q);
			$st->bindParam(1,urldecode($_POST["data"]));	
			$st->bindParam(2,jry_wb_get_time());
			$st->bindParam(3,$jry_wb_login_user[id]);
			$st->execute();			
			echo json_encode(array('code'=>true,'data'=>$_POST['data']));
			exit();			
		}
		else if($_GET['action']=='trust')
		{
			$st = $conn->prepare("update ".constant('jry_wb_database_general')."login set trust=1 where id=? AND code=?");
			$st->bindParam(1,$jry_wb_login_user['id']);
			$st->bindParam(2,$_COOKIE['code']);
			$st->execute();			
			echo json_encode(array('code'=>true));
			exit();		
		}
		else if($_GET['action']=='untrust')
		{
			$st = $conn->prepare("update ".constant('jry_wb_database_general')."login set trust=0 where id=? AND code=?");
			$st->bindParam(1,$jry_wb_login_user['id']);
			$st->bindParam(2,$_POST['code']);
			$st->execute();			
			echo json_encode(array('code'=>true));
			exit();		
		}
		else if($_GET['action']=='logout')
		{
			$st = $conn->prepare("DELETE FROM ".constant('jry_wb_database_general')."login where id=? AND code=?");
			$st->bindParam(1,$jry_wb_login_user['id']);
			$st->bindParam(2,$_POST['code']);
			$st->execute();			
			echo json_encode(array('code'=>true));
			exit();		
		}	
		else if($_GET['action']=='chengehead')
		{
			if($_GET['type']=='default')
			{
				if($jry_wb_login_user['sex']==0&&$jry_wb_login_user['head']!='default_head_woman')
				{
					$q ="update ".constant('jry_wb_database_general')."users set head='default_head_woman',lasttime=? where id=?";
					$st = $conn->prepare($q);
					$st->bindParam(1,jry_wb_get_time());
					$st->bindParam(2,$jry_wb_login_user['id']);
					$st->execute();
				}
				else if(($jry_wb_login_user['sex']==1||$jry_wb_login_user['sex']==2)&&$jry_wb_login_user['head']!='default_head_man')
				{
					$q ="update ".constant('jry_wb_database_general')."users set head='default_head_man',lasttime=? where id=?";
					$st = $conn->prepare($q);
					$st->bindParam(1,jry_wb_get_time());
					$st->bindParam(2,$jry_wb_login_user['id']);
					$st->execute();
				}			
				echo json_encode(array('code'=>true));
				return;
			}
			else if($_GET['type']=='gravatar')
			{
				$headers = @get_headers('http://www.gravatar.com/avatar/' .md5($jry_wb_login_user['mail']). '?d=404');
				if (preg_match("|200|", $headers[0])) 
				{
					$q ="update ".constant('jry_wb_database_general')."users set head='gravatar',lasttime=? where id=?";
					$st = $conn->prepare($q);
					$st->bindParam(1,jry_wb_get_time());
					$st->bindParam(2,$jry_wb_login_user['id']);
					$st->execute();	
					echo json_encode(array('code'=>true));
					return;
				}
				else
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>300000,'file'=>__FILE__,'line'=>__LINE__)));		
			}
			else if($_GET['type']=='qq')
			{
				if(strtolower(array_pop(explode("@",$jry_wb_login_user['mail'])))=='qq.com'||$jry_wb_login_user['oauth_qq']!='')
				{
					$q ="update ".constant('jry_wb_database_general')."users set head='qq',lasttime=? where id=?";
					$st = $conn->prepare($q);
					$st->bindParam(1,jry_wb_get_time());
					$st->bindParam(2,$jry_wb_login_user['id']);
					$st->execute();
					echo json_encode(array('code'=>true));
					return;
				}			
			}
			else if($_GET['type']=='github')
			{
				if($jry_wb_login_user['oauth_github']!='')
				{
					$q ="update ".constant('jry_wb_database_general')."users set head='github',lasttime=? where id=?";
					$st = $conn->prepare($q);
					$st->bindParam(1,jry_wb_get_time());
					$st->bindParam(2,$jry_wb_login_user['id']);
					$st->execute();
					echo json_encode(array('code'=>true));
					return;
				}			
			}
			else if($_GET['type']=='mi')
			{
				if($jry_wb_login_user['oauth_mi']!='')
				{
					$q ="update ".constant('jry_wb_database_general')."users set head='mi',lasttime=? where id=?";
					$st = $conn->prepare($q);
					$st->bindParam(1,jry_wb_get_time());
					$st->bindParam(2,$jry_wb_login_user['id']);
					$st->execute();
					echo json_encode(array('code'=>true));
					return;
				}			
			}
			else if($_GET['type']=='gitee')
			{
				if($jry_wb_login_user['oauth_gitee']!='')
				{
					$q ="update ".constant('jry_wb_database_general')."users set head='gitee',lasttime=? where id=?";
					$st = $conn->prepare($q);
					$st->bindParam(1,jry_wb_get_time());
					$st->bindParam(2,$jry_wb_login_user['id']);
					$st->execute();
					echo json_encode(array('code'=>true));
					return;
				}			
			}		
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>000000,'file'=>__FILE__,'line'=>__LINE__)));		
		}	
		else if($_GET['action']=='untpin')
		{
			if($_GET['type']=='qq')
				$q ="update ".constant('jry_wb_database_general')."users set oauth_qq=NULL,lasttime=? where id=?";
			else if($_GET['type']=='github')
				$q ="update ".constant('jry_wb_database_general')."users set oauth_github=NULL,lasttime=? where id=?";		
			else if($_GET['type']=='mi')
				$q ="update ".constant('jry_wb_database_general')."users set oauth_mi=NULL,lasttime=? where id=?";
			else if($_GET['type']=='gitee')
				$q ="update ".constant('jry_wb_database_general')."users set oauth_gitee=NULL,lasttime=? where id=?";
			$st = $conn->prepare($q);
			$st->bindParam(1,jry_wb_get_time());
			$st->bindParam(2,$jry_wb_login_user['id']);
			$st->execute();		
			echo json_encode(array('code'=>true));
			exit();
		}
		else if($_GET['action']=='send_mail')
		{
			if(constant('jry_wb_mail_switch')=='')
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>000000,'file'=>__FILE__,'line'=>__LINE__)));
			if($_POST['vcode']!=$_SESSION['vcode']||$_POST['vcode']=='')
			{
				if(strtolower($_POST['vcode'])==strtolower($_SESSION['vcode']))
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100005,'file'=>__FILE__,'line'=>__LINE__)));		
				else
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100002,'file'=>__FILE__,'line'=>__LINE__)));		
			}
			if($_POST['mail']==$jry_wb_login_user['mail'])
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100004,'file'=>__FILE__,'line'=>__LINE__)));		
			if(!jry_wb_test_mail($_POST['mail']))
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100014,'file'=>__FILE__,'line'=>__LINE__)));		
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users where mail=?');
			$st->bindParam(1,$_POST['mail']);
			$st->execute();
			if(count($st->fetchAll())!=0)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100015,'file'=>__FILE__,'line'=>__LINE__)));
			jry_wb_send_mail_code($_POST['mail'],"jry_wb_mainpages/do_chenge.php?action=mail&");
			echo json_encode(array('code'=>true));
			exit();
		}		
		else if($_GET['action']=='mail')
		{
			if(constant('jry_wb_mail_switch')=='')
			{
				$mail=$_POST['mail'];
				if($_POST['vcode']!=$_SESSION['vcode']||$_POST['vcode']=='')
				{
					if(strtolower($_POST['vcode'])==strtolower($_SESSION['vcode']))
						throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100005,'file'=>__FILE__,'line'=>__LINE__)));		
					else
						throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100002,'file'=>__FILE__,'line'=>__LINE__)));		
				}
				if($_POST['mail']==$jry_wb_login_user['mail'])
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100004,'file'=>__FILE__,'line'=>__LINE__)));
				if(!jry_wb_test_mail($_POST['mail']))
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100014,'file'=>__FILE__,'line'=>__LINE__)));	
				$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users where mail=?');
				$st->bindParam(1,$_POST['mail']);
				$st->execute();
				if(count($st->fetchAll())!=0)
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100015,'file'=>__FILE__,'line'=>__LINE__)));		
			}
			else
			{
				jry_wb_print_head("用户管理|邮箱绑定",true,false,false,array(),true,false);	
				$_SESSION['url']='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];	
				$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'mail_code where code=?');
				$st->bindParam(1,$_GET['code']);
				$st->execute();		
				foreach($st->fetchAll()as $code);
				if($code==null){?><script language=javascript>jry_wb_beautiful_alert.alert('不合法的验证码','','self.location=document.referrer;');</script><?php	exit();}
				$mail=$code['mail'];
				if(!jry_wb_test_mail($mail)){?><script language=javascript>jry_wb_beautiful_alert.alert('请填写正确信息','邮箱错误','self.location=document.referrer;');</script><?php	exit();}
				$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users where mail=?');
				$st->bindParam(1,$mail);
				$st->execute();
				foreach($st->fetchAll()as $users)
				if($users['id']!=''&&$users['id']!=$jry_wb_login_user['id']){?><script language=javascript>jry_wb_beautiful_alert.alert('请填写非重复信息','邮箱重复'	,'self.location=document.referrer;');</script>		<?php	exit();}
			}
			$set_head='';
			if($jry_wb_login_user['head']=='gravatar')
			{
				$uri = 'http://www.gravatar.com/avatar/'.md5($_POST['mail']).'?d=404';
				$headers = @get_headers($uri);
				if (preg_match("|200|", $headers[0]))
				{
					$jry_wb_gravatar_user_head='';
					$set_head='';
				}
				else
				{
					if($jry_wb_login_user['sex']==0)
						$set_head=' ,head=\'default_head_woman\' ';					
					else
						$set_head=' ,head=\'default_head_man\' ';
					$jry_wb_gravatar_user_head=$uri;
				}
			}
			else
			{
				$uri = 'http://www.gravatar.com/avatar/'.md5($_POST['mail']).'?d=404';
				$headers = @get_headers($uri);
				if (preg_match("|200|", $headers[0]))
					$jry_wb_gravatar_user_head=$uri;
				else
					$jry_wb_gravatar_user_head='';
			}			
			$q ="update ".constant('jry_wb_database_general')."users set mail=?,lasttime=?".$set_head." where id=? ";
			$st = $conn->prepare($q);
			$st->bindParam(1,($jry_wb_login_user['mail']=$mail));
			$st->bindParam(2,jry_wb_get_time());
			$st->bindParam(3,$jry_wb_login_user['id']);
			$st->execute();
			if(constant('jry_wb_mail_switch')=='')
			{
				echo json_encode(array('code'=>true,'mail'=>$mail,'jry_wb_gravatar_user_head'=>$jry_wb_gravatar_user_head,'head'=>($set_head==''?'':($jry_wb_login_user['sex']==0?constant('jry_wb_defult_woman_picture'):constant('jry_wb_defult_man_picture')))));
			}
			else
			{
				?><script language=javascript>jry_wb_beautiful_alert.alert("修改成功","","window.location.href='chenge.php'");</script><?php
			}			 
			exit();
		}
		else if($_GET['action']=='tel')
		{
			
			if($_POST['vcode']!=$_SESSION['vcode']||$_POST['vcode']=='')
			{
				if(strtolower($_POST['vcode'])==strtolower($_SESSION['vcode']))
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100005,'file'=>__FILE__,'line'=>__LINE__)));
				else
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100002,'file'=>__FILE__,'line'=>__LINE__)));
			}
			if($_POST['tel']==$jry_wb_login_user['tel'])
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100004,'file'=>__FILE__,'line'=>__LINE__)));
			if(!jry_wb_test_phone_number($_POST['tel']))
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100008,'file'=>__FILE__,'line'=>__LINE__)));
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users where tel=?');
			$st->bindParam(1,$_POST['tel']);
			$st->execute();
			if(count($st->fetchAll())!=0)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100009,'file'=>__FILE__,'line'=>__LINE__)));	
			if(constant('jry_wb_check_tel_switch')&&constant('jry_wb_short_message_switch')!='')
			{
				$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'tel_code where tel=?');
				$st->bindParam(1,$_POST['tel']);
				$st->execute();	
				foreach($st->fetchAll()as $tels);		
				if($_POST['phonecode']!=$tels['code']||$_POST['phonecode']=='')
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100010,'file'=>__FILE__,'line'=>__LINE__)));		
				$st = $conn->prepare('DELETE FROM '.constant('jry_wb_database_general').'tel_code where code=?');
				$st->bindParam(1,$_POST['phonecode']);
				$st->execute();	
			}
			$q ="update ".constant('jry_wb_database_general')."users set tel=?,lasttime=? where id=? ";
			$st = $conn->prepare($q);
			$st->bindParam(1,$_POST['tel']);
			$st->bindParam(2,jry_wb_get_time());
			$st->bindParam(3,$jry_wb_login_user['id']);
			$st->execute();
			echo json_encode(array('code'=>true));				
			exit();
		}	
		else if($_GET['action']=='specialfact')
		{
			$q ="update ".constant('jry_wb_database_general')."users set word_special_fact=?,follow_mouth=?,head_special=?,lasttime=? where id=? ";
			$st = $conn->prepare($q);
			$st->bindParam(1,urldecode($_POST["word_special_fact"]));	
			$st->bindParam(2,urldecode($_POST["follow_mouth"]));	
			$st->bindParam(3,json_encode($head_special=array(	'mouse_on'=>array(	'speed'=>(float)$_POST['mouse_on_speed'],
																					'direction'=>(float)$_POST['mouse_on_direction'],
																					'times'=>(float)$_POST['mouse_on_times']
																				),
																'mouse_out'=>array(	'speed'=>(float)$_POST['mouse_out_speed'],
																					'direction'=>(float)$_POST['mouse_out_direction'],
																					'times'=>(float)$_POST['mouse_out_times']
																				))));
			$st->bindParam(4,jry_wb_get_time());
			$st->bindParam(5,$jry_wb_login_user[id]); 
			$st->execute();
			echo json_encode(array('code'=>true,'head_special'=>$head_special));				
			exit();		
		}		
		else if($_GET['action']=='show')
		{
			$q ="update ".constant('jry_wb_database_general')."users set tel_show=?,mail_show=?,ip_show=?,oauth_show=?,lasttime=? where id=? ";
			$st = $conn->prepare($q);
			$st->bindParam(1,urldecode($_POST["tel_show"]));	
			$st->bindParam(2,urldecode($_POST["mail_show"]));			
			$st->bindParam(3,urldecode($_POST["ip_show"]));			
			$st->bindParam(4,urldecode($_POST["oauth_show"]));
			$st->bindParam(5,jry_wb_get_time());
			$st->bindParam(6,$jry_wb_login_user['id']);
			$st->execute();
			echo json_encode(array('code'=>true,'head_special'=>$head_special));				
			exit();			
		}
		else if($_GET['action']=='pas')
		{
			$psw1=$_POST["password1"];
			$psw2=$_POST["password2"];
			$psw_yuan=md5($_POST["password_yuan"]);
			if(strlen($psw1)<8)	
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100012,'file'=>__FILE__,'line'=>__LINE__)));		
			if($psw1!=$psw2)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100011,'file'=>__FILE__,'line'=>__LINE__)));		
			if($jry_wb_login_user['password']!=$psw_yuan)	
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100006,'file'=>__FILE__,'line'=>__LINE__)));		
			$st = $conn->prepare("DELETE FROM ".constant('jry_wb_database_general')."login where id=?");
			$st->bindParam(1,$jry_wb_login_user['id']);
			$st->execute();	
			$st = $conn->prepare("update ".constant('jry_wb_database_general')."users set password=?,lasttime=? where id=? ");
			$st->bindParam(1,md5($psw1));	
			$st->bindParam(2,jry_wb_get_time());
			$st->bindParam(3,$jry_wb_login_user[id]);
			$st->execute();	
			echo json_encode(array('code'=>true));				
			exit();			
		}	
		else if($_GET['action']=='simple')
		{
			$name=$_POST["name"];
			$sex=$_POST["sex"];
			$zhushi=$_POST["zhushi"];
			$language=$_POST["language"];
			$style_id=$_POST["style_id"];		
			if($name=="")
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100013,'file'=>__FILE__,'line'=>__LINE__)));		
			$q ="update ".constant('jry_wb_database_general')."users set name=? , sex=?,zhushi=?,language=?,style_id=?,lasttime=? where id=? ";
			$st = $conn->prepare($q);
			$st->bindParam(1,$name);
			$st->bindParam(2,($sex));
			$st->bindParam(3,$zhushi);
			$st->bindParam(4,$language);
			$st->bindParam(5,$style_id);
			$st->bindParam(6,jry_wb_get_time());
			$st->bindParam(7,$jry_wb_login_user[id]);
			$st->execute();
			echo json_encode(array('code'=>true,'style'=>jry_wb_load_style($style_id)));				
			exit();				
		}
		else if($_GET['action']=='extern')
		{
			$extern=json_decode(urldecode($_POST["extern"]),true);
			foreach(constant('jry_wb_config_user_extern_message') as $one)
			{
				if($extern[$one['key']]=='')
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100017,'extern'=>array('key'=>$one['key'],'name'=>$one['name']),'file'=>__FILE__,'line'=>__LINE__)));
				if($one['type']=='china_id')
					if(jry_wb_test_china_id_card($extern[$one['key']])===false)
						throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100017,'extern'=>array('key'=>$one['key'],'name'=>$one['name']),'file'=>__FILE__,'line'=>__LINE__)));
				if($one['type']=='tel')
					if(jry_wb_test_phone_number($extern[$one['key']])===false)
						throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100017,'extern'=>array('key'=>$one['key'],'name'=>$one['name']),'file'=>__FILE__,'line'=>__LINE__)));
				if($one['type']=='mail')
					if(jry_wb_test_mail($extern[$one['key']])===false)
						throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100017,'extern'=>array('key'=>$one['key'],'name'=>$one['name']),'file'=>__FILE__,'line'=>__LINE__)));
				if($one['connect']!=NULL)
				{
					foreach($one['connect'] as $connect)
					{
						if($one['type']=='china_id'&&$connect=='sex')
						{
							
						}
						else if($connect=='tel')
						{
							if($extern[$one['key']]==$jry_wb_login_user['tel'])
								throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100017,'extern'=>array('key'=>$one['key'],'name'=>$one['name']),'file'=>__FILE__,'line'=>__LINE__)));
						}
						else if($connect=='namee'||$connect=='name')
						{
							if($extern[$one['key']]==$jry_wb_login_user['name'])
								throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100017,'extern'=>array('key'=>$one['key'],'name'=>$one['name']),'file'=>__FILE__,'line'=>__LINE__)));
						}
						else if($connect=='mail')
						{
							if($extern[$one['key']]==$jry_wb_login_user['mail'])
								throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100017,'extern'=>array('key'=>$one['key'],'name'=>$one['name']),'file'=>__FILE__,'line'=>__LINE__)));
						}
						else
						{
							if($extern[$one['key']]==$extern[$connect])
								throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100017,'extern'=>array('key'=>$one['key'],'name'=>$one['name']),'file'=>__FILE__,'line'=>__LINE__)));
						}
					}
				}
			}
			$st = $conn->prepare("update ".constant('jry_wb_database_general')."users set extern=? , lasttime=? where id=? ");
			$st->bindParam(1,json_encode($extern));
			$st->bindParam(2,jry_wb_get_time());
			$st->bindParam(3,$jry_wb_login_user['id']);
			$st->execute();
			echo json_encode(array('code'=>true));				
			exit();				
		}
		else
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>000000,'file'=>__FILE__,'line'=>__LINE__)));		
	}
	catch(jry_wb_exception $e)
	{
		echo $e->getMessage();
		exit();
	}
?>
