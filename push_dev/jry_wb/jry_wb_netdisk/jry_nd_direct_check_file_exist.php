<?php
	include_once("jry_nd_aly_includes.php");	
	use Sts\Request\V20150401 as Sts;
	use OSS\OssClient;
	use OSS\Core\OssException;
	function jry_nd_direct_check_file_exist($area,$file,$action='')
	{		
		$file_name=$area['config_message']->dir.constant('jry_nd_upload_file_prefix').$file['file_id'].'_jryupload';
		if($action=='open')
			$file_name.=$file['extern']->open;
		else if($action=='download')
			$file_name.=$file['extern']->download;
		else 
			$file_name.=$action;
		if($area['type']==0)
			return jry_nd_local_check_file_exist($area,$file_name);
		else if($area['type']==1)
			return jry_nd_aly_check_file_exist(jry_nd_aly_connect_in_by_area($area),$area,$file_name);
			
	}
?>