<?php
	include_once('jry_nd_direct_include.php');
	function jry_nd_direct_new_dir($conn,&$user,$father,$name='')
	{
		if(jry_nd_database_get_file($conn,$user,$file_id)===null)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200006,'file'=>__FILE__,'line'=>__LINE__)));
		$name=($name==''?('新建文件夹'.jry_wb_get_time().jry_wb_get_random_string(10)):$name);
		if(jry_nd_database_get_file_by_father_name_type($conn,$user,$father,$name,'',true)!=null)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200005,'file'=>__FILE__,'line'=>__LINE__)));		
		jry_nd_database_operate_user_used_uploading($conn,$user,0,0);
		$st = $conn->prepare('INSERT INTO '.JRY_WB_DATABASE_NETDISK.'file_list (`id`,`father`,`name`,`type`,`area`,`size`,`lasttime`,`uploading`,`isdir`) VALUES (?,?,?,?,?,?,?,?,?)');
		$st->bindValue(1,$user['id']);
		$st->bindValue(2,$father);
		$st->bindValue(3,$name);
		$st->bindValue(4,'');
		$st->bindValue(5,1);
		$st->bindValue(6,0);
		$st->bindValue(7,jry_wb_get_time());
		$st->bindValue(8,0);
		$st->bindValue(9,1);
		$st->execute();	
		return $conn->lastInsertId();		
	}
?>