<?php
	include_once("jry_wb_config_default_user.php");
	define('JRY_WB_LOCAL_DIR'			,dirname(dirname(__FILE__)));
	define('JRY_WB_HOST'				,'http://'.JRY_WB_DOMIN.(JRY_WB_PORT==''?'':(':'.JRY_WB_PORT)).'/jry_wb/');
	define('JRY_WB_DATA_HOST'			,'http://'.JRY_WB_DOMIN.(JRY_WB_PORT==''?'':(':'.JRY_WB_PORT)).'/data/');
	define('JRY_WB_VERSION'				,'3.0dev');
	include_once('jry_wb_tp_gitee_oauth_config.php');
	include_once('jry_wb_tp_github_oauth_config.php');
	include_once('jry_wb_tp_mi_oauth_config.php');
	include_once('jry_wb_tp_qq_oauth_config.php');
	define('JRY_WB_OAUTH_SWITCH'		,!(JRY_WB_TP_GITEE_OAUTH_CLIENT_ID==''&&JRY_WB_TP_GITHUB_OAUTH_CLIENT_ID==''&&JRY_WB_TP_MI_OAUTH_CLIENT_ID==''&&$JRY_WB_TP_QQ_OAUTH_CONFIG==NULL));
?>