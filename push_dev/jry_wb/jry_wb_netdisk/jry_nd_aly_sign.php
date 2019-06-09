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
		if($area['type']!=1)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200000,'file'=>__FILE__,'line'=>__LINE__)));		
		DefaultProfile::addEndpoint($area['config_message']->region,$area['config_message']->region,"Sts",'sts'.$area['config_message']->region.'.aliyuncs.com');
		$iclientprofile = DefaultProfile::getProfile($area['config_message']->region,JRY_ND_ALY_STS_ACCESS_KEY_ID,JRY_ND_ALY_STS_ACCESS_KEY_SECRET);
		$client = new DefaultAcsClient($iclientprofile);
		$request = new Sts\AssumeRoleRequest();
		$request->setRoleSessionName("jry".$jry_wb_login_user['id'].$file_id);
		$request->setRoleArn(JRY_ND_ALY_STS_ROLEARN);
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
						'name'=>$area['config_message']->dir.JRY_ND_UPLOAD_FILE_PREFIX.$file_id.'_jryupload'
		);
	}
	function jry_nd_aly_download_sign($connect,$area,$file,$relocation=false)
	{
		if($area['type']!=1)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200000,'file'=>__FILE__,'line'=>__LINE__)));		
		if(!jry_nd_aly_check_file_exist($connect,$area,$file))
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>220002,'file'=>__FILE__,'line'=>__LINE__)));			
		$sign=$connect->signUrl($area['config_message']->bucket,$file,JRY_ND_OSS_SIGN_MAX_TIME);
		if($relocation)
			header("Location:".$sign);
		return $sign;
	}
?>