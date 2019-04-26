<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_get_share($conn,$share_id,$key,$file_id)
	{
		if($share_id=='')
			return null;
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'share WHERE share_id=? AND `key`=? LIMIT 1;');
		$st->bindValue(1,$share_id);
		$st->bindValue(2,$key);
		$st->execute();
		$share=$st->fetchAll();
		if(count($share)==0)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>230000,'file'=>__FILE__,'line'=>__LINE__)));
		$share=$share[0];
		$user=array('id'=>$share['id']);
		$user['nd_ei']=jry_wb_get_netdisk_information_by_id($user['id']);
		if(($file=jry_nd_database_get_file($conn,$user,$file_id))===null)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200008,'file'=>__FILE__,'line'=>__LINE__)));
		if(!$file['trust'])
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>230001,'file'=>__FILE__,'line'=>__LINE__)));
		if(is_string($file['share_list']))
			$file['share_list']=json_decode($file['share_list']);					
		if($file['share_list']===NULL)
			$file['share_list']=[];
		if(array_search((string)$share['share_id'],$file['share_list'])===false)
			return false;
		if(!jry_nd_database_check_type($user,$file))
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200001,'file'=>__FILE__,'line'=>__LINE__)));
		$father=jry_nd_database_get_file($conn,$user,$share['file_id']);
		if(($father['file_id']!=$share['file_id']||$father['file_id']==0)&&$file['file_id']!=$share['file_id'])
			return false;
		return array('user'=>$user,'share'=>$share,'father'=>$father,'file'=>$file);
	}		
?>