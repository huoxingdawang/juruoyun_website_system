<?php
	include_once('jry_nd_direct_include.php');
	function jry_nd_direct_delete($conn,&$user,$file)
	{
		if($file['delete'])
			return ;
		jry_nd_direct_unshare($conn,$user,$file);
		if($file['isdir'])
		{
			$files=jry_nd_database_get_file_by_father($conn,$user,$file);
			foreach($files as $child)
				jry_nd_direct_delete($conn,$user,$child);
			jry_nd_database_delete_file($conn,$user,$file);
		}
		else
		{
			if(($area=jry_nd_database_get_area($conn,$file['area']))===null)
				return;
			try
			{
				if($area['type']==0)
					jry_nd_local_delete_file($area,$file);
				else if($area['type']==1)
					jry_nd_aly_delete_file(jry_nd_aly_connect_in_by_area($area),$area,$file);
				jry_nd_database_delete_file($conn,$user,$file,$area);
			}catch (jry_wb_exception $e){}
		}
	}
	function jry_nd_direct_delete_file_id($conn,&$user,$file_id)
	{
		if(($file=jry_nd_database_get_file($conn,$user,$file_id))===null)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200008,'file'=>__FILE__,'line'=>__LINE__)));
		jry_nd_direct_delete($conn,$user,$file);
	}
?>