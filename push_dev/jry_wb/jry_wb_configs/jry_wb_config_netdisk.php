<?php
//上传文件前缀
	define('JRY_ND_UPLOAD_FILE_PREFIX'					,'');
//oss加速签名时间(s做单位)
	define('JRY_ND_OSS_SIGN_MAX_TIME'					,5);
//aly sts ak id
	define('JRY_ND_ALY_STS_ACCESS_KEY_ID'				,'');
//aly sts ak secret
	define('JRY_ND_ALY_STS_ACCESS_KEY_SECRET'			,'');
//aly sts ak角色
	define('JRY_ND_ALY_STS_ROLEARN'						,'');
//下载最低速度(用于估计加速副本有效时间)
	define('JRY_ND_MIN_SPEED'							,100);
//存储空间价格(KB/绿币/月)
	define('JRY_ND_PRICE_SIZE'							,10*1024);
//高速流量价格(KB/绿币)
	define('JRY_ND_PRICE_FAST_SIZE'						,2*1024);
//方法0(服务器)上最大值(-1是不限制)
	define('JRY_ND_UPLOAD_METHOD_0_MAX_SIZE'			,-1);
//方法0(服务器)上单分片最大值
	define('JRY_ND_UPLOAD_METHOD_0_MAX_SIZE_PRE_CHUNK'	,1024*10);
//方法0(服务器)同时上传分片最大值
	define('JRY_ND_UPLOAD_METHOD_0_MAX_SIZE_PRE_TIME'	,2);
//方法0(服务器)下载最大值(-1是不限制)
	define('JRY_ND_DOWNLOAD_METHOD_0_MAX_SIZE'			,-1);
?>