<?php
	include_once("../tools/jry_wb_includes.php");
	setcookie('id',-1,time()-1,'/',constant('jry_wb_domin'),NULL,false);
	setcookie('code','',time()-1,'/',constant('jry_wb_domin'),NULL,true);
	jry_wb_print_head("登出",false,false,false);
	if($_GET['action']=='all')
	{
		$conn=jry_wb_connect_database();
		$q ="DELETE FROM ".constant('jry_wb_database_general')."login where id=?";
		$st = $conn->prepare($q);
		$st->bindParam(1,$jry_wb_login_user['id']);
		$st->execute();	
	}
	else
	{
		$conn=jry_wb_connect_database();
		$q ="DELETE FROM ".constant('jry_wb_database_general')."login where id=? AND ip=? AND device=?";
		$st = $conn->prepare($q);
		$st->bindParam(1,$jry_wb_login_user['id']);
		$st->bindParam(2,$_SERVER['REMOTE_ADDR']);
		$st->bindParam(3,jry_wb_get_device(true));
		$st->execute();	
	}
	$st = $conn->prepare("update ".constant('jry_wb_database_general')."users SET lasttime=? where id=?");
	$st->bindParam(1,jry_wb_get_time());
	$st->bindParam(2,$jry_wb_login_user['id']); 
	$st->execute();	
	$url="window.location.href='".jry_wb_print_href("home","","",1)."'";
	jry_wb_echo_log(constant('jry_wb_log_type_logout'),'by self');
?>
<script language=javascript>
	jry_wb_cache.delete_all();
	jry_wb_beautiful_alert.alert("登出成功","登出成功","<?php echo $url?>");
</script>