<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_tp_mi_oauth_config.php");
	require_once(JRY_WB_LOCAL_DIR.'/jry_wb_tp_sdk/mi/utils/XMUtil.php');
	require_once(JRY_WB_LOCAL_DIR.'/jry_wb_tp_sdk/mi/utils/AccessToken.php');
	require_once(JRY_WB_LOCAL_DIR.'/jry_wb_tp_sdk/mi/httpclient/XMHttpClient.php');
	require_once(JRY_WB_LOCAL_DIR.'/jry_wb_tp_sdk/mi/httpclient/XMOAuthClient.php');
	require_once(JRY_WB_LOCAL_DIR.'/jry_wb_tp_sdk/mi/httpclient/XMApiClient.php');
	$oauthClient = new XMOAuthClient(JRY_WB_TP_MI_OAUTH_CLIENT_ID,JRY_WB_TP_MI_OAUTH_CLIENT_SECRET);
	$oauthClient->setRedirectUri(JRY_WB_HOST.'jry_wb_tp_callback/mi.php');
	$url = $oauthClient->getAuthorizeUrl('code','state');
	Header("HTTP/1.1 302 Found");
	Header("Location: $url");
?>