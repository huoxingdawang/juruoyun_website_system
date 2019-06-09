<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_operate_user_used_uploading($conn,&$user,$used,$uploading)
	{
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'users SET size_used=size_used+? , size_uploading=size_uploading+? , lasttime=? WHERE `id`=?;');
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
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'users SET size_used=? , size_uploading=? , lasttime=? WHERE `id`=?;');
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
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'users SET fast_size=fast_size+?,lasttime=? WHERE `id`=?;');
		$st->bindValue(1,$fast_size);
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$user['id']);
		$st->execute();
		$user['nd_ei']['fast_size']+=$fast_size;
	}
	function jry_nd_database_operate_user_size($conn,&$user,$size_total)
	{
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'users SET size_total=size_total+?,lasttime=? WHERE `id`=?;');
		$st->bindValue(1,$size_total);
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$user['id']);
		$st->execute();
		$user['nd_ei']['size_total']+=$size_total;
	}
	function jry_nd_database_add_user_size($conn,&$user,$size,$endtime)
	{
		$st = $conn->prepare('INSERT INTO '.JRY_WB_DATABASE_NETDISK.'size_package (`id`,`size`,`endtime`) VALUES (?,?,?);');
		$st->bindValue(1,$user['id']);
		$st->bindValue(2,$size);
		$st->bindValue(3,$endtime);
		$st->execute();
		jry_nd_database_operate_user_size($conn,$user,$size);
	}
//UPDATE netdisk_users,netdisk_size_package SET size_total =(select IFNULL(SUM(netdisk_size_package.size),0)+20480 from netdisk_size_package WHERE netdisk_users.id=netdisk_size_package.id),lasttime=NOW();	
?>