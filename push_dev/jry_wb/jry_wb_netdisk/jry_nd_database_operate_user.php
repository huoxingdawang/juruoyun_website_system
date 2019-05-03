<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_operate_user_used_uploading($conn,&$user,$used,$uploading)
	{
		$st = $conn->prepare('UPDATE '.constant('jry_wb_database_netdisk').'users SET size_used=size_used+? , size_uploading=size_uploading+? , lasttime=? WHERE `id`=?;');
		$st->bindValue(1,$used);
		$st->bindValue(2,$uploading);
		$st->bindValue(3,jry_wb_get_time());
		$st->bindValue(4,$user['id']);
		$st->execute();
		$user['nd_ei']['size_used']+=$used;
		$user['nd_ei']['size_uploading']+=$uploading;
	}
	function jry_nd_database_set_user_used_uploading($conn,&$user,$used,$uploading)
	{
		$st = $conn->prepare('UPDATE '.constant('jry_wb_database_netdisk').'users SET size_used=? , size_uploading=? , lasttime=? WHERE `id`=?;');
		$st->bindValue(1,$used);
		$st->bindValue(2,$uploading);
		$st->bindValue(3,jry_wb_get_time());
		$st->bindValue(4,$user['id']);
		$st->execute();
		$user['nd_ei']['size_used']=$used;
		$user['nd_ei']['size_uploading']=$uploading;
	}	
	function jry_nd_database_operate_user_fast($conn,&$user,$fast_size)
	{
		$st = $conn->prepare('UPDATE '.constant('jry_wb_database_netdisk').'users SET fast_size=fast_size+?,lasttime=? WHERE `id`=?;');
		$st->bindValue(1,$fast_size);
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$user['id']);
		$st->execute();
		$user['nd_ei']['fast_size']+=$fast_size;
	}
	function jry_nd_database_operate_user_size($conn,&$user,$size_total)
	{
		$st = $conn->prepare('UPDATE '.constant('jry_wb_database_netdisk').'users SET size_total=size_total+?,lasttime=? WHERE `id`=?;');
		$st->bindValue(1,$size_total);
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$user['id']);
		$st->execute();
		$user['nd_ei']['size_total']+=$size_total;
	}		
?>