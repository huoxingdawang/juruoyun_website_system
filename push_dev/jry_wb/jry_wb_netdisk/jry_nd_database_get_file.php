<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_get_file($conn,$user,$file_id)
	{
		if($file_id==0)
			return array(	'file_id'			=>0,
							'id'				=>$user['id'],
							'father'			=>0,
							'name'				=>'/',
							'type'				=>'',
							'size'				=>0,
							'lasttime'			=>'1926-08-17 00:00:00',
							'uploading'			=>0,
							'area'				=>1,
							'download_times'	=>0,
							'share'				=>0,
							'self_share'		=>0,
							'share_list'		=>[],
							'delete'			=>0,
							'isdir'				=>1,
							'trust'				=>1,
							'extern'			=>(object)[]
			);
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_NETDISK.'file_list WHERE file_id=? AND id=? AND `delete`=0 LIMIT 1');
		$st->bindValue(1,$file_id);
		$st->bindValue(2,$user['id']);
		$st->execute();
		$root=$st->fetchAll();
		if(count($root)!=0)
			return $root[0];
		return null;	
	}			
	function jry_nd_database_get_file_by_father_name_type($conn,$user,$father,$name,$type,$isdir=false)
	{
		if(is_array($father))
			$father=$father['file_id'];
		if(is_object($father))
			$father=$father->file_id;
		$st = $conn->prepare('SELECT file_id FROM '.JRY_WB_DATABASE_NETDISK.'file_list WHERE `id`=? AND `father`=? AND `name`=? AND `type`=? AND isdir=? AND `delete`=0');
		$st->bindValue(1,$user['id']);
		$st->bindValue(2,$father);
		$st->bindValue(3,str_replace("&","/37",$name));
		$st->bindValue(4,str_replace("&","/37",$type));
		$st->bindValue(5,$isdir);
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
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_NETDISK.'file_list WHERE father=? AND id=? AND `delete`=0');
		$st->bindValue(1,$father['file_id']);
		$st->bindValue(2,$user['id']);
		$st->execute();
		return $st->fetchAll();		
	}
?>