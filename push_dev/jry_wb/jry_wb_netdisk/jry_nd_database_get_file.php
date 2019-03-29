<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_get_file($conn,$user,$file_id)
	{
		if($file_id==0)
			return true;
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'file_list WHERE file_id=? AND id=? AND `delete`=0 LIMIT 1');
		$st->bindValue(1,$file_id);
		$st->bindValue(2,$user['id']);
		$st->execute();
		$root=$st->fetchAll();
		if(count($root)!=0)
			return $root[0];
		return null;	
	}			
	function jry_nd_database_get_file_by_father_name_type($conn,$user,$father,$name,$type)
	{
		$st = $conn->prepare('SELECT file_id FROM '.constant('jry_wb_netdisk').'file_list WHERE `id`=? AND `father`=? AND `name`=? AND `type`=? AND `delete`=0');
		$st->bindValue(1,$user['id']);
		$st->bindValue(2,$father);
		$st->bindValue(3,str_replace("&","/37",$name));
		$st->bindValue(4,str_replace("&","/37",$type));
		$st->execute();
		$data=$st->fetchAll();
		if(count($data)!=0)
			return $data[0];
		return null;
	}
	function jry_nd_database_get_file_by_father($conn,$user,$father)
	{
		if($father['isdir']==0)
			return null;		
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'file_list WHERE father=? AND id=? AND `delete`=0 LIMIT 1');
		$st->bindValue(1,$father['file_id']);
		$st->bindValue(2,$user['id']);
		$st->execute();
		return $st->fetchAll();		
	}
?>