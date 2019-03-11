<?php
	include_once('jry_wb_config_default_system.php');
	$jry_wb_tp_qq_oauth_config=(object)array(									//qq oAuth 配置
		'appid'=>'',
		'appkey'=>'',
		'callback'=>constant('jry_wb_host').'jry_wb_tp_callback/tencent.php',
		'scope'=>'get_user_info',
		'errorReport'=>true,
		'storageType'=>'file',
		'host'=>'localhost',
		'user'=>'root',
		'password'=>'root',
		'database'=>'test'
	);
?>