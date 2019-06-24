<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	if(($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'])==__FILE__)
	{
		$id=$_POST['id'];
		$psw=md5($_POST['password']);
		$vcode=$_POST['vcode'];
		$type=$_POST['type'];
		if($type=='')
			$type=$_GET['type'];
		$show='';
		if($_POST['vcode']!=$_SESSION['vcode']||$_POST['vcode']=='')
		{
			if(strtolower($_POST['vcode'])==strtolower($_SESSION['vcode']))
				echo json_encode(array('code'=>false,'reason'=>100005,'vcode'=>$_SESSION['vcode']));
			else
				echo json_encode(array('code'=>false,'reason'=>100002,'vcode'=>$_SESSION['vcode']));
			exit();
		}
	}
	if($type=="1")
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'users where tel=? LIMIT 1');
		$st->bindValue(1,$id);
		$st->execute();
		$user=$st->fetchAll()[0];
	}
	else if($type=="2")
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'users where mail=? LIMIT 1');
		$st->bindValue(1,$id);
		$st->execute();
		$user=$st->fetchAll()[0];
	}
	else if($type=='4')
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL."users WHERE oauth_qq->'$.openid'=? AND oauth_qq->'$.access_token'=? LIMIT 1");
		$st->bindValue(1,$open_id);
		$st->bindValue(2,$access_token);
		$st->execute();
		$user=$st->fetchAll()[0];
	}
	else if($type=='5')
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL."users WHERE oauth_github->'$.message.node_id'=? LIMIT 1");
		$st->bindValue(1,$github_id);
		$st->execute();
		$user=$st->fetchAll()[0];
	}	
	else if($type=='6')
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL."users WHERE oauth_mi->'$.message.unionId'=? LIMIT 1");
		$st->bindValue(1,$unionId);
		$st->execute();
		$user=$st->fetchAll()[0];
	}
	else if($type=='7')
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL."users WHERE oauth_gitee->'$.message.id'=? LIMIT 1");
		$st->bindValue(1,$gitee_id,PDO::PARAM_INT);
		$st->execute();
		$user=$st->fetchAll()[0];		
	}		
	else if($type=='8')
	{
		
	}
	else
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'users where id=? LIMIT 1');
		$st->bindParam(1,$id);
		$st->execute();
		$user=$st->fetchAll()[0];
		if($psw!=$user['password']&&($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'])==__FILE__)
		{
			echo json_encode(array('code'=>false,'reason'=>100006));
			return ;
		}
	}
	if($user==NULL)
	{
		if(($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'])==__FILE__)
			echo json_encode(array('code'=>false,'reason'=>100007));
		else
		{
			$user['style']=jry_wb_load_style(1);			
			jry_wb_print_head("登录失败",false,false,false);
			?>
			<script>
				jry_wb_beautiful_alert.alert("登录失败",'不存在的账户',function()
				{
					window.close();
					window.location.href='<?php if($_SESSION['url']!='')echo $_SESSION['url'];else echo jry_wb_print_href("home","","",1)?>';
				});
			</script>
			<?php
		}	
		exit();
	}
	$jry_wb_login_user=$user;
	jry_wb_echo_log(constant('jry_wb_log_type_login'),array('type'=>$type,'device'=>jry_wb_get_device(true),'ip'=>$_SERVER['REMOTE_ADDR'],'browser'=>jry_wb_get_browser(true)));	
	$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_GENERAL.'users SET logdate=? where id=? ');
	$st->bindParam(1,jry_wb_get_time());	
	$st->bindParam(2,$jry_wb_login_user['id']);
	$st->execute();
	if(strtotime($jry_wb_login_user['greendate'].' + '.JRY_WB_LOGIN_TIME.' seconds')<time())
		jry_wb_set_green_money($conn,$jry_wb_login_user,$green_money=rand(JRY_WB_LOGIN_GREEN_MONEY['min'],JRY_WB_LOGIN_GREEN_MONEY['max']),constant('jry_wb_log_type_green_money_login_add'));
	$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'login where id=? AND device=? AND code=? AND ip=? AND browser=?');
	$st->bindParam(1,$jry_wb_login_user['id']);
	$st->bindParam(2,jry_wb_get_device(true));
	$st->bindParam(3,$_COOKIE['code']);
	$st->bindParam(4,$_SERVER['REMOTE_ADDR']);
	$st->bindParam(5,jry_wb_get_browser(true));	
	$st->execute();
	$all=$st->fetchAll();
	setcookie('id',$jry_wb_login_user['id'],time()+JRY_WB_LOGIN_TIME,'/',jry_wb_get_domain(),NULL,false);
	setcookie('id',$jry_wb_login_user['id'],time()+JRY_WB_LOGIN_TIME,'/',JRY_WB_DOMIN,NULL,false);
	if(count($all)!=0)
	{
		setcookie('code',$all[0]['code'],time()+JRY_WB_LOGIN_TIME,'/',JRY_WB_DOMIN,NULL,true);
		$st = $conn->prepare("update ".JRY_WB_DATABASE_GENERAL."login SET time=? where id=? AND ip=? AND device=? AND code=? AND browser=?");
		$st->bindParam(1,jry_wb_get_time());	
		$st->bindParam(2,$jry_wb_login_user['id']);
		$st->bindParam(3,$_SERVER['REMOTE_ADDR']);
		$st->bindParam(4,jry_wb_get_device(true));
		$st->bindParam(5,$_COOKIE['code']);
		$st->bindParam(6,jry_wb_get_browser(true));		
		$st->execute();
	}
	else
	{
		$code=jry_wb_get_random_string(50);
		$code.=md5(jry_wb_get_time()).md5($jry_wb_login_user['mail'].$jry_wb_login_user['id']);
		setcookie('code',$code,time()+JRY_WB_LOGIN_TIME,'/',JRY_WB_DOMIN,NULL,true);
		$st = $conn->prepare('INSERT INTO '.JRY_WB_DATABASE_GENERAL."login (id,ip,time,device,code,browser) VALUES(?,?,?,?,?,?)");
		$st->bindParam(1,$jry_wb_login_user['id']);
		$st->bindParam(2,$_SERVER['REMOTE_ADDR']);
		$st->bindParam(3,jry_wb_get_time());	
		$st->bindParam(4,jry_wb_get_device(true));
		$st->bindParam(5,$code);
		$st->bindParam(6,jry_wb_get_browser(true));
		$st->execute();
	}
	
	$date=floor((strtotime(jry_wb_get_time())-strtotime($jry_wb_login_user['logdate']))/86400);
	$hour=floor((strtotime(jry_wb_get_time())-strtotime($jry_wb_login_user['logdate']))/3600);
	$minute=floor((strtotime(jry_wb_get_time())-strtotime($jry_wb_login_user['logdate']))/60)-$hour*60;
	
	if(($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'])==__FILE__)
		echo json_encode(array('code'=>1,'message'=>array('hour'=>$hour,'minute'=>$minute,'green_money'=>$green_money)));
	else
	{
		$jry_wb_login_user['style']=jry_wb_load_style($jry_wb_login_user['style_id']);		
		jry_wb_print_head("登录",false,false,false,array('use'),true,false);
?>
<script>
	jry_wb_beautiful_alert.alert("登录成功",'距上次登录<?php  echo $hour;?>小时<?php  echo $minute;?>分钟<?php if($green_money!=null)echo '<br>随机奖励绿币'.$green_money;?>',function()
	{
		window.close();
		window.location.href='<?php if($_SESSION['url']!='')echo $_SESSION['url'];else echo jry_wb_print_href("home","","",1)?>';
	});
</script>
<?php
	}	
?>