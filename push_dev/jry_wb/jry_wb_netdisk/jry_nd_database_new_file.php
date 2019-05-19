<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_new_file($conn,$user,&$father,$name,$type,$area,$size)
	{
		if(is_int($father)||is_string($father))
			if(($father=jry_nd_database_get_file($conn,$user,$father))===null)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200006,'file'=>__FILE__,'line'=>__LINE__)));		
		if(is_string($father['share_list']))
			$father['share_list']=json_decode($father['share_list']);
		$st = $conn->prepare('INSERT INTO '.constant('jry_wb_database_netdisk').'file_list (`id`,`father`,`name`,`type`,`area`,`size`,`lasttime`,`uploading`,`share`,`share_list`) VALUES (?,?,?,?,?,?,?,?,?,?)');
		$st->bindValue(1,$user['id']);
		$st->bindValue(2,$father['file_id']);
		$st->bindValue(3,str_replace("&","/37",$name));
		$st->bindValue(4,str_replace("&","/37",$type));
		$st->bindValue(5,$area['area_id']);
		$st->bindValue(6,$size);
		$st->bindValue(7,jry_wb_get_time());
		$st->bindValue(8,1);
		$st->bindValue(9,$father['share']);
		$st->bindValue(10,json_encode($father['share_list']));
		$st->execute();
		return $conn->lastInsertId();
	}				
?>