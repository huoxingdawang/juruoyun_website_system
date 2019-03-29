<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_set_file_extern($conn,$file,$action,$code)
	{
		$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET extern=? , lasttime=? WHERE `file_id`=?;');
		if($action=='open')
			$st->bindValue(1,json_encode(array('open'=>$code,'download'=>$file['extern']->download)));
		else
			$st->bindValue(1,json_encode(array('download'=>$code,'open'=>$file['extern']->open)));
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$file['file_id']);
		$st->execute();			
	}
?>