<?php
	include_once("jry_wb_includes.php");
	define('jry_wb_log_type_green_money_login_add',0);
	define('jry_wb_log_type_green_money_pay_nd_size',1);
	define('jry_wb_log_type_green_money_pay_nd_fast_size',2);
	define('jry_wb_log_type_green_money_invite_user',3);
	function jry_wb_set_green_money($conn,&$user,$money,$by)
	{
		if($user['green_money']+$money<0)
			return false;
		jry_wb_echo_log(constant('jry_wb_log_type_green_money'),array('money'=>$money,'by'=>$by),$user['id']);
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_GENERAL.'users set greendate=GREATEST(greendate,?), green_money=green_money+?,lasttime=? where id=? ');
		if(constant('jry_wb_log_type_green_money_login_add')==$by)
			$st->bindParam(1,$user['greendate']=jry_wb_get_time());
		else
			$st->bindParam(1,$user['greendate']);
		$st->bindParam(2,$money);
		$st->bindParam(3,jry_wb_get_time());
		$st->bindParam(4,$user['id']);
		$st->execute();
		$user['green_money']+=$money;
		return true;
	}
?>