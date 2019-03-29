<?php
	include_once("jry_nd_aly_includes.php");	
	use Sts\Request\V20150401 as Sts;
	use OSS\OssClient;
	use OSS\Core\OssException;
	function jry_nd_aly_check_file_exist($connect,$area,$file_name)
	{
		if($area['type']!=1)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200000,'file'=>__FILE__,'line'=>__LINE__)));		
		try
		{
			return $connect->doesObjectExist($area['config_message']->bucket,$file_name);
		}
		catch(OssException $e)
		{
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>220001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>"Message:".$e->getMessage())));
		}
	}
?>