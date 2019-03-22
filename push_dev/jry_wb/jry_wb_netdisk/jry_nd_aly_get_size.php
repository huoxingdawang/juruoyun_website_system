<?php
	include_once("jry_nd_aly_includes.php");	
	include_once("jry_nd_aly_includes.php");	
	use Sts\Request\V20150401 as Sts;
	use OSS\OssClient;
	use OSS\Core\OssException;
	function jry_nd_aly_get_size($connect,$area,$file)
	{
		try
		{
			return $connect->getObjectMeta($area['config_message']->bucket,$area['config_message']->dir.constant('jry_nd_upload_file_prefix').$file['file_id'].'_jryupload')['content-length'];
		}
		catch(OssException $e)
		{
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>220001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>"Message:".$e->getMessage())));
		}
	}
?>