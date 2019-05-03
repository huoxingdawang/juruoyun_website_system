<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_new_file($conn,$user,$father,$name,$type,$area,$size)
	{
		$st = $conn->prepare('INSERT INTO '.constant('jry_wb_database_netdisk').'file_list (`id`,`father`,`name`,`type`,`area`,`size`,`lasttime`,`uploading`) VALUES (?,?,?,?,?,?,?,?)');
		$st->bindValue(1,$user['id']);
		$st->bindValue(2,$father);
		$st->bindValue(3,str_replace("&","/37",$name));
		$st->bindValue(4,str_replace("&","/37",$type));
		$st->bindValue(5,$area['area_id']);
		$st->bindValue(6,$size);
		$st->bindValue(7,jry_wb_get_time());
		$st->bindValue(8,1);
		$st->execute();
		return $conn->lastInsertId();
	}				
?>