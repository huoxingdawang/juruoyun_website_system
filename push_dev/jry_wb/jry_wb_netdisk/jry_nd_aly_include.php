<?php
	include_once("jry_wb_nd_nd_include.php");
	include_once("jry_wb_nd_aly_copy.php");
	include_once("jry_wb_nd_aly_delete.php");
	include_once("jry_wb_nd_aly_download.php");
	include_once("jry_wb_nd_aly_get_size.php");
	include_once("jry_wb_nd_aly_sign.php");
	include_once("jry_wb_nd_aly_upload.php");
	include_once((dirname(__DIR__)."/jry_wb_tp_sdk/aly/aliyun-php-sdk-core/Regions/EndpointConfig.php"));
	use Sts\Request\V20150401 as Sts;	
	use OSS\OssClient;
	use OSS\Core\OssException;
?>