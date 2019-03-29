<?php
	include_once("jry_wb_local_include.php");
	function jry_nd_local_read_file($area,$file)
	{
		if($area['type']!=0)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200000,'file'=>__FILE__,'line'=>__LINE__)));			
		$file_name=$area['config_message']->dir.constant('jry_nd_upload_file_prefix').$file['file_id'].'_jryupload';
		return fread(fopen($file_name,'rb'),filesize($file_name));		
	}
?>
