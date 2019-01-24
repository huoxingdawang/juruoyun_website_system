<?php
		require_once 'aly_oss_sdk/autoload.php';

use OSS\OssClient;
use OSS\Core\OssException;

	$accessKeyId = "LTAI1vFnk9zUMtMa";
	$accessKeySecret = "d6AWev9B9PNFRi4zQuj6Tsv4nwv7mi";
	$endpoint = "http://oss-cn-beijing-internal.aliyuncs.com";
	$bucket= "juruoyun";
	$object = "1.png";
$acl = "public-read";
try {
    $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

    $ossClient->putObjectAcl($bucket, $object, $acl);
} catch (OssException $e) {
    printf(__FUNCTION__ . ": FAILED\n");
    printf($e->getMessage() . "\n");
    return;
}
print(__FUNCTION__ . ": OK" . "\n");
echo '<br>'.'http://juruoyun.oss-cn-beijing.aliyuncs.com/'.$object;