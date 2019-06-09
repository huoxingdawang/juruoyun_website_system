<?php
	include_once("jry_nd_aly_includes.php");	
	use Sts\Request\V20150401 as Sts;
	use OSS\OssClient;
	use OSS\Core\OssException;
	function jry_nd_aly_upload($connect,$old_area,$area,$file,$new_file_name)
	{
		if($area['type']!=1)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200000,'file'=>__FILE__,'line'=>__LINE__)));			
		$file_name=$old_area['config_message']->dir.JRY_ND_UPLOAD_FILE_PREFIX.$file['file_id'].'_jryupload';
		$connect->multiuploadFile($area['config_message']->bucket,$new_file_name,$file_name,array(OssClient::OSS_CHECK_MD5 => true,OssClient::OSS_PART_SIZE => 1));
	}
	function jry_nd_aly_upload_fast_buf($connect,$area,$file)
	{
		if($area['faster_area']==null||$area['faster_area']['fast']==0)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200010,'file'=>__FILE__,'line'=>__LINE__)));
		if($area['faster_area']['type']!=1)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200000,'file'=>__FILE__,'line'=>__LINE__)));		

		$code=jry_wb_get_random_string(30);		
		$new_file_name=$area['faster_area']['config_message']->dir.JRY_ND_UPLOAD_FILE_PREFIX.$file['file_id'].'_jryupload'.$code;		
		jry_nd_aly_upload($connect,$area,$area['faster_area'],$file,$new_file_name);
		return $code;
	}
	
?>