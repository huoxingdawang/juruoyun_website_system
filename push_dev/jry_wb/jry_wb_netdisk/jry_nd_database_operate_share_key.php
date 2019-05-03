<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_delete_share_key($conn,$share,$user)
	{
		if($share['id']!=$user['id'])
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>230000,'file'=>__FILE__,'line'=>__LINE__)));
		$st = $conn->prepare('UPDATE '.constant('jry_wb_database_netdisk').'share SET `key`="",`lasttime`=? WHERE share_id=? AND id=? LIMIT 1;');
		$st->bindValue(1,jry_wb_get_time());
		$st->bindValue(2,$share['share_id']);
		$st->bindValue(3,$user['id']);
		$st->execute();
		$st = $conn->prepare('UPDATE '.constant('jry_wb_database_netdisk').'users SET lasttime=? WHERE `id`=?;');
		$st->bindValue(1,jry_wb_get_time());
		$st->bindValue(2,$user['id']);
		$st->execute();
	}
	function jry_nd_database_chenge_share_key($conn,$share,$user)
	{
		if($share['id']!=$user['id'])
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>230000,'file'=>__FILE__,'line'=>__LINE__)));
		$st = $conn->prepare('UPDATE '.constant('jry_wb_database_netdisk').'share SET `key`=?,`lasttime`=? WHERE share_id=? AND id=? LIMIT 1;');
		$st->bindValue(1,jry_wb_get_random_string(30));
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$share['share_id']);
		$st->bindValue(4,$user['id']);
		$st->execute();
		$st = $conn->prepare('UPDATE '.constant('jry_wb_database_netdisk').'users SET lasttime=? WHERE `id`=?;');
		$st->bindValue(1,jry_wb_get_time());
		$st->bindValue(2,$user['id']);
		$st->execute();
	}		
?>