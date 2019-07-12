<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_check_type($user,$type)
	{
		if($user['nd_ei']==NULL)
			$user['nd_ei']=jry_wb_get_netdisk_information_by_id($user['id']);
		return ($user['nd_ei']['allow_type']==-1||in_array($type,$user['nd_ei']['allow_type']));
	}		
?>