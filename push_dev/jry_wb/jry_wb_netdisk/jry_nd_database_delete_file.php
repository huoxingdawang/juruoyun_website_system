<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_delete_file_file_id($conn,$file_id)
	{
		$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET size=? , `delete`=? ,lasttime=? ,extern=? WHERE `file_id`=?');
		$st->bindValue(1,0);
		$st->bindValue(2,1);
		$st->bindValue(3,jry_wb_get_time());
		$st->bindValue(4,NULL);
		$st->bindValue(5,$file_id);
		$st->execute();	
	}	
	function jry_nd_database_delete_file($conn,&$user,$file,$area=null)
	{
		jry_nd_database_delete_file_file_id($conn,$file['file_id']);
		if($file['isdir'])
			return;
		if($area==null)
			if(($area=jry_nd_database_get_area($conn,$file['area']))===null)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200000,'file'=>__FILE__,'line'=>__LINE__)));
		jry_nd_database_operate_area_size($conn,$area,-$file['size']);
		jry_nd_database_operate_fast_save('area',jry_wb_get_time());
		if($file['uploading'])
			jry_nd_database_operate_user_used_uploading($conn,$user,0,-$file['size']);
		else
			jry_nd_database_operate_user_used_uploading($conn,$user,-$file['size'],0);
	}
?>