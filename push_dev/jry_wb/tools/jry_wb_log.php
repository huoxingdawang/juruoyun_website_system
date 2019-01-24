<?php
	include_once("jry_wb_includes.php");
	define('jry_wb_log_type_test',0);
	define('jry_wb_log_type_logout',1);
	define('jry_wb_log_type_add',2);
	define('jry_wb_log_type_login',3);
	define('jry_wb_log_type_forget',4);
	function jry_wb_echo_log($type,$data,$id=-1)
	{
		global $jry_wb_login_user; 
		if($id==-1)
			$id=$jry_wb_login_user['id'];
		$q ="INSERT INTO ".constant('jry_wb_database_log')."data (`id`,`time`,`type`,`data`) VALUES(?,?,?,?)";
		$st = jry_wb_connect_database()->prepare($q);
		$st->bindParam(1,$id);
		$st->bindParam(2,date("Y-m-d H;i:s",time()));
		$st->bindParam(3,$type);
		$st->bindParam(4,$data);
		$st->execute();	
	}
?>