<?php
		require_once 'aly_oss_sdk/autoload.php';
	use OSS\OssClient;
	use OSS\Core\OssException;

	$accessKeyId = "LTAI1vFnk9zUMtMa";
	$accessKeySecret = "d6AWev9B9PNFRi4zQuj6Tsv4nwv7mi";
	// Endpoint以杭州为例，其它Region请按实际情况填写。
	$endpoint = "http://oss-cn-beijing-internal.aliyuncs.com";
	// 存储空间名称
	$bucket= "juruoyun";
	// 文件名称
	$object = "test.txt";
	$content = "Hi, OSS.";

	try {
		$ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
		$ossClient->putObject($bucket, $object, $content);
	} catch (OssException $e) {
		print $e->getMessage();
	}	
?>