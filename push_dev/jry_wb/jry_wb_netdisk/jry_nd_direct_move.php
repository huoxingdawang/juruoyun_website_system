<?php
	include_once('jry_nd_direct_include.php');
	function jry_nd_direct_move($conn,$user,$file,$to)
	{
		if($file['file_id']==$to['file_id'])
			return;
		jry_nd_database_operate_user_used_uploading($conn,$user,0,0);
		$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET `father`=? , lasttime=? WHERE `file_id`=? AND id=?');
		$st->bindValue(1,$to['file_id']);
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$file['file_id']);
		$st->bindValue(4,$user['id']);
		$st->execute();
	}
	function jry_nd_direct_move_file_id($conn,$user,$file_id,$to)
	{
		if(($file=jry_nd_database_get_file($conn,$user,$file_id))===null)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200008,'file'=>__FILE__,'line'=>__LINE__)));
		jry_nd_direct_move($conn,$user,$file,$to);
	}
?>