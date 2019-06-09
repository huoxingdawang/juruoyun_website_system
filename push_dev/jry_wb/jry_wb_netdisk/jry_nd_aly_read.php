<?php
	include_once("jry_nd_aly_includes.php");	
	function jry_nd_aly_read_file($connect,$area,$file)
	{
		if($area['type']!=1)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200000,'file'=>__FILE__,'line'=>__LINE__)));			
		return ($connect->getObject($area['config_message']->bucket,$area['config_message']->dir.JRY_ND_UPLOAD_FILE_PREFIX.$file['file_id'].'_jryupload'));
	}
?>