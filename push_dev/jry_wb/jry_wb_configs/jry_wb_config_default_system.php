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
	define('JRY_WB_OAUTH_SWITCH'		,!(constant('jry_wb_tp_gitee_oauth_config_client_id')==''&&constant('jry_wb_tp_github_oauth_config_client_id')==''&&constant('jry_wb_tp_mi_oauth_config_client_id')==''&&$jry_wb_tp_qq_oauth_config==NULL));
?>