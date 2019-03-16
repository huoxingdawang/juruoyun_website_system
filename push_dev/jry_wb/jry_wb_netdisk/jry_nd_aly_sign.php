<?php
	include_once("jry_nd_aly_includes.php");	
	use Sts\Request\V20150401 as Sts;
	use OSS\OssClient;
	use OSS\Core\OssException;		
	function jry_nd_aly_upload_sign($area,$file_id)
	{
		global $jry_wb_login_user;
		define('ENABLE_HTTP_PROXY', FALSE);
		define('HTTP_PROXY_IP', '127.0.0.1');
		define('HTTP_PROXY_PORT', '8888');
		DefaultProfile::addEndpoint($area['config_message']->sts_region_id,$area['config_message']->sts_region_id,"Sts",$area['config_message']->sts_endpoint);
		$iclientprofile = DefaultProfile::getProfile($area['config_message']->sts_region_id,constant('jry_nd_aly_sts_accesskeyid'),constant('jry_nd_aly_sts_accesskeysecret'));
		$client = new DefaultAcsClient($iclientprofile);
		$request = new Sts\AssumeRoleRequest();
		$request->setRoleSessionName("jry".$jry_wb_login_user['id'].$file_id);
		$request->setRoleArn(constant('jry_nd_aly_sts_rolearn'));
		$request->setDurationSeconds(60*60);
		try
		{
			$response=$client->getAcsResponse($request);	
		}
		catch(ServerException $e)
		{
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>220003,'file'=>__FILE__,'line'=>__LINE__,'extern'=>"Error:".$e->getErrorCode()."Message:".$e->getMessage())));
		}
		catch(ClientException $e)
		{
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>220003,'file'=>__FILE__,'line'=>__LINE__,'extern'=>"Error:".$e->getErrorCode()."Message:".$e->getMessage())));
		}
		catch(OssException $e)
		{
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>220001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>"Message:".$e->getMessage())));
		}	
		return array(	'response'=>$response,
						'region'=>$area['config_message']->region,
						'bucket'=>$area['config_message']->bucket,
						'name'=>$area['config_message']->dir.constant('jry_nd_upload_file_prefix').$file_id.'_jryupload'
		);
	}
	function jry_nd_aly_download_sign($connect,$area,$file,$relocation=false)
	{
		if(!jry_nd_aly_check_file_exist($connect,$area,$file))
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>220002,'file'=>__FILE__,'line'=>__LINE__)));			
		$sign=$connect->signUrl($area['config_message']->bucket,$file,constant('jry_nd_oss_max_time'));
		if($relocation)
			header("Location:".$sign);
		return $sign;
	}
?>