<?php
	include_once("jry_nd_aly_includes.php");	
	use OSS\OssClient;
	use OSS\Core\OssException;
	function jry_nd_aly_connect_in_by_area($area)
	{
		if($area['type']!=1)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200000,'file'=>__FILE__,'line'=>__LINE__)));
		try
		{
			if($area['samearea'])
				return new OssClient($area['config_message']->accesskeyid,$area['config_message']->accesskeysecret,'oss-'.$area['config_message']->region.'-internal.aliyuncs.com',false);
			else
				return jry_nd_aly_connect_out_by_area($area);
		}
		catch(OssException $e)
		{
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>220001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>"Message:".$e->getMessage())));
		}	
	}
	function jry_nd_aly_connect_out_by_area($area)
	{
		if($area['type']!=1)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200000,'file'=>__FILE__,'line'=>__LINE__)));
		try
		{
			return (new OssClient($area['config_message']->accesskeyid,$area['config_message']->accesskeysecret,'oss-'.$area['config_message']->region.'.aliyuncs.com',false));
		}
		catch(OssException $e)
		{
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>220001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>"Message:".$e->getMessage())));
		}
	}
?>