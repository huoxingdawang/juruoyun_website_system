<?php
		require_once 'aly_oss_sdk/autoload.php';
	use OSS\OssClient;
	use OSS\Core\OssException;

	$accessKeyId = "LTAI1vFnk9zUMtMa";
	$accessKeySecret = "d6AWev9B9PNFRi4zQuj6Tsv4nwv7mi";
	$endpoint = "http://oss-cn-beijing-internal.aliyuncs.com";
	$bucket= "juruoyun";
	
	// 文件名称
	ob_clean();
	$object = "1.png";
	header("content-type:".getimagesize($object)['mime']); 
try {
	$ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
	$content = $ossClient->getObject($bucket, $object);
    print( $content);
} catch (OssException $e) {
	print $e->getMessage();
}