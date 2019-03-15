<?php
	include_once("jry_nd_aly_includes.php");	
	use OSS\OssClient;
	use OSS\Core\OssException;
	function jry_nd_aly_connect_in_by_area($area)
	{
		if($area['type']!=1)
		{
			throw new jry_nd_exception(json_encode(array('code'=>false,'reason'=>200000,'file'=>__FILE__,'line'=>__LINE__)));
			return ;
		}
		try
		{
			return new OssClient($area['config_message']->accesskeyid,$area['config_message']->accesskeysecret,$area['config_message']->endpoint_in,false);
		}
		catch(OssException $e)
		{
			throw new jry_nd_exception(json_encode(array('code'=>false,'reason'=>220001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>"Message:".$e->getMessage())));
		}	
	}
	function jry_nd_aly_connect_out_by_area($area)
	{
		if($area['type']!=1)
		{
			throw new jry_nd_exception(json_encode(array('code'=>false,'reason'=>200000,'file'=>__FILE__,'line'=>__LINE__)));
			return ;
		}
		try
		{
			return (new OssClient($area['config_message']->accesskeyid,$area['config_message']->accesskeysecret,$area['config_message']->endpoint,false));
		}
		catch(OssException $e)
		{
			throw new jry_nd_exception(json_encode(array('code'=>false,'reason'=>220001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>"Message:".$e->getMessage())));
		}
	}
?>