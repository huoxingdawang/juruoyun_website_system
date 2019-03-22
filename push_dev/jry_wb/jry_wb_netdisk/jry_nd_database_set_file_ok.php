<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_set_file_ok($conn,$user,$file_id,$size)
	{
		$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET uploading=? , size=? WHERE `file_id`=? AND id=?');
		$st->bindValue(1,0);
		$st->bindValue(2,$size);
		$st->bindValue(3,$file_id);
		$st->bindValue(4,$user['id']);
		$st->execute();
	}				
?>