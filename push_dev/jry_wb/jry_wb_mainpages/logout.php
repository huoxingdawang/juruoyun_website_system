<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	setcookie('id',-1,time()-1,'/',JRY_WB_DOMIN,NULL,false);
	setcookie('code','',time()-1,'/',JRY_WB_DOMIN,NULL,true);
	jry_wb_print_head("登出",false,false,false);
	$conn=jry_wb_connect_database();
	$q ="DELETE FROM ".JRY_WB_DATABASE_GENERAL."login where id=?";
	$st = $conn->prepare($q);
	$st->bindParam(1,$jry_wb_login_user['id']);
	$st->execute();	
	$url="window.location.href='".jry_wb_print_href("home","","",1)."'";
	jry_wb_echo_log(constant('jry_wb_log_type_logout'),'by self');
?>
<script language=javascript>
	jry_wb_indexeddb_clear();
	jry_wb_beautiful_alert.alert("登出成功","登出成功","<?php echo $url?>");
</script>