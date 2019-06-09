<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_set_file_ok($conn,$user,$file_id,$size)
	{
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'file_list SET uploading=0 , size=? WHERE `file_id`=? AND id=?');
		$st->bindValue(1,$size);
		$st->bindValue(2,$file_id);
		$st->bindValue(3,$user['id']);
		$st->execute();
	}				
?>