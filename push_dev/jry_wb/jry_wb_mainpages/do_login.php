<?php
	include_once("../tools/jry_wb_includes.php");	
	$id=$_POST['id'];
	$psw=md5($_POST['password']);
	$vcode=$_POST['vcode'];
	$type=$_POST['type'];
	$show='';
	if($vcode!= $_SESSION['vcode']||$vcode=='')
	{
		echo json_encode(array('state'=>-1));
		return ;
	}
	if($type=="1")
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users where tel=? LIMIT 1');
		$st->bindParam(1,$id);
		$st->execute();
		foreach($st->fetchAll()as $users);				
	}else if($type=="2")
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users where mail=? LIMIT 1');
		$st->bindParam(1,$id);
		$st->execute();
		foreach($st->fetchAll()as $users);			
	}
	else
	{
		@$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users where id=? LIMIT 1');
		$st->bindParam(1,$id);
		$st->execute();
		foreach($st->fetchAll()as $users);		
	}
	if($users==NULL)
	{
		echo json_encode(array('state'=>-2));
		return ;
	}
	else if($psw!=$users['password'])
	{
		echo json_encode(array('state'=>-3));
		return ;
	}
	$date=floor((strtotime(jry_wb_get_time())-strtotime($users[greendate]))/86400);
	$hour=floor((strtotime(jry_wb_get_time())-strtotime($users[greendate]))/3600);
	$q="update ".constant('jry_wb_database_general')."users set logdate='".jry_wb_get_time()."'";
	if($hour>=9||$date>=1)
		$q.=' ,greendate="'.jry_wb_get_time().'" , green_money=green_money+'.($green_money=rand(2,10));
	$q.="  where id=? ";
	$st = $conn->prepare($q);
	$st->bindParam(1,$users['id']);
	$st->execute();
	$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'login where id=? AND device=? AND code=? AND ip=? AND browser=?');
	$st->bindParam(1,$users['id']);
	$st->bindParam(2,jry_wb_get_device(true));
	$st->bindParam(3,$_COOKIE['code']);
	$st->bindParam(4,$_SERVER['REMOTE_ADDR']);
	$st->bindParam(5,jry_wb_get_browser(true));	
	$st->execute();
	$all=$st->fetchAll();
	setcookie('id',$users['id'],time()+constant('logintime'),'/',jry_wb_get_domain(),NULL,true);
	setcookie('password',$users['password'],time()+constant('logintime'),'/',jry_wb_get_domain(),NULL,true);
	if(count($all)!=0)
	{
		setcookie('code',$all[0]['code'],time()+constant('logintime'),'/',$_SERVER['HTTP_HOST'],NULL,true);
		$st = $conn->prepare("update ".constant('jry_wb_database_general')."login SET time=? where id=? AND ip=? AND device=? AND code=? AND browser=?");
		$st->bindParam(1,jry_wb_get_time());	
		$st->bindParam(2,$users['id']);
		$st->bindParam(3,$_SERVER['REMOTE_ADDR']);
		$st->bindParam(4,jry_wb_get_device(true));
		$st->bindParam(5,$_COOKIE['code']);
		$st->bindParam(6,jry_wb_get_browser(true));		
		$st->execute();
	}
	else
	{
		$srcstr='123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWYZ';
		$code='';
		mt_srand();
		for ($i = 0; $i < 100; $i++) 
			$code.=$srcstr[mt_rand(0, 50)];
		$code.=md5(jry_wb_get_time()).md5($users['mail'].$users['id']);
		setcookie('code',$code,time()+constant('logintime'),'/',$_SERVER['HTTP_HOST'],NULL,true);
		$st = $conn->prepare('INSERT INTO '.constant('jry_wb_database_general')."login (id,ip,time,device,code,browser) VALUES(?,?,?,?,?,?)");
		$st->bindParam(1,$users['id']);
		$st->bindParam(2,$_SERVER['REMOTE_ADDR']);
		$st->bindParam(3,jry_wb_get_time());	
		$st->bindParam(4,jry_wb_get_device(true));				
		$st->bindParam(5,$code);
		$st->bindParam(6,jry_wb_get_browser(true));
		$st->execute();
	}
	$st = $conn->prepare("update ".constant('jry_wb_database_general')."users SET lasttime='".jry_wb_get_time()."' where id=?");
	$st->bindParam(1,$users['id']);
	$st->execute();	
	$date=floor((strtotime(jry_wb_get_time())-strtotime($users[logdate]))/86400);
	$hour=floor((strtotime(jry_wb_get_time())-strtotime($users[logdate]))/3600);
	$minute=floor((strtotime(jry_wb_get_time())-strtotime($users[logdate]))/60)-$hour*60;
	$jry_wb_login_user['id']=$users['id'];
	jry_wb_echo_log(constant('jry_wb_log_type_login'),'by password by '.$type);	
	echo json_encode(array('state'=>1,'message'=>array('hour'=>$hour,'minute'=>$minute,'green_money'=>$green_money)));
?>