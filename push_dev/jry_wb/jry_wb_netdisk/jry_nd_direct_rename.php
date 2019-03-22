<?php
	include_once('jry_nd_direct_include.php');
	function jry_nd_direct_rename($conn,$user,$file,$to_name,$to_type)
	{
		if($to_name==$file['name']&&$to_type==$file['type'])
			return;
		jry_nd_database_operate_user_used_uploading($conn,$user,0,0);		
		$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET type=? , name=? ,lasttime=? WHERE `file_id`=? AND id=? LIMIT 1');
		$st->bindValue(1,str_replace("&","/37",$to_type));
		$st->bindValue(2,str_replace("&","/37",$to_name));
		$st->bindValue(3,jry_wb_get_time());
		$st->bindValue(4,$file['file_id']);
		$st->bindValue(5,$user['id']);
		$st->execute();
	}
	function jry_nd_direct_rename_file_id($conn,$user,$file_id,$to_name,$to_type)
	{
		if(($file=jry_nd_database_get_file($conn,$user,$file_id))===null)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200008,'file'=>__FILE__,'line'=>__LINE__)));
		jry_nd_direct_rename($conn,$user,$file,$to_name,$to_type);
	}
?>