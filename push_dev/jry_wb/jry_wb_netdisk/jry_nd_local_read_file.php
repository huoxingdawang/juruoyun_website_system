<?php
	include_once("jry_wb_local_include.php");
	function jry_nd_local_read_file($area,$file)
	{
		if($area['type']!=0)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200000,'file'=>__FILE__,'line'=>__LINE__)));			
		$file_name=$area['config_message']->dir.JRY_ND_UPLOAD_FILE_PREFIX.$file['file_id'].'_jryupload';
		return fread(fopen($file_name,'rb'),filesize($file_name));		
	}
	function jry_nd_local_echo_file($area,$file)
	{
		if($area['type']!=0)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200000,'file'=>__FILE__,'line'=>__LINE__)));
		@readfile($area['config_message']->dir.JRY_ND_UPLOAD_FILE_PREFIX.$file['file_id'].'_jryupload');
	}
?>
