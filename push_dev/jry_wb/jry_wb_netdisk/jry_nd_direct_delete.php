<?php
	include_once('jry_nd_direct_include.php');
	function jry_nd_direct_delete($conn,$user,$file)
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
				if(is_string($file['extern']))
					$file['extern']=json_decode($file['extern']);
				if($file['extern']->open!='')
				{
					if($area['fast'])
					{
						if($area['type']==1)
							jry_nd_aly_connect_in_by_area($area['faster_area'])->deleteObject($area['config_message']->bucket,$area['config_message']->dir.constant('jry_nd_upload_file_prefix').$file['file_id'].'_jryupload'.$file['extern']->open);
					}
					else
					{
						if($area['faster_area']['type']==1)
							jry_nd_aly_connect_in_by_area($area['faster_area'])->deleteObject($area['faster_area']['config_message']->bucket,$area['faster_area']['config_message']->dir.constant('jry_nd_upload_file_prefix').$file['file_id'].'_jryupload'.$file['extern']->open);
					}
				}
				if($file['extern']->download!='')
				{
					if($area['fast'])
					{
						if($area['type']==1)
							jry_nd_aly_connect_in_by_area($area['faster_area'])->deleteObject($area['config_message']->bucket,$area['config_message']->dir.constant('jry_nd_upload_file_prefix').$file['file_id'].'_jryupload'.$file['extern']->download);
					}
					else
					{
						if($area['faster_area']['type']==1)
							jry_nd_aly_connect_in_by_area($area['faster_area'])->deleteObject($area['faster_area']['config_message']->bucket,$area['faster_area']['config_message']->dir.constant('jry_nd_upload_file_prefix').$file['file_id'].'_jryupload'.$file['extern']->download);
					}					
				}				
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