<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_tp_mi_oauth_config.php");
	require_once(constant('jry_wb_local_dir').'/jry_wb_tp_sdk/mi/utils/XMUtil.php');
	require_once(constant('jry_wb_local_dir').'/jry_wb_tp_sdk/mi/utils/AccessToken.php');
	require_once(constant('jry_wb_local_dir').'/jry_wb_tp_sdk/mi/httpclient/XMHttpClient.php');
	require_once(constant('jry_wb_local_dir').'/jry_wb_tp_sdk/mi/httpclient/XMOAuthClient.php');
	require_once(constant('jry_wb_local_dir').'/jry_wb_tp_sdk/mi/httpclient/XMApiClient.php');
	$oauthClient = new XMOAuthClient(constant('jry_wb_tp_mi_oauth_config_client_id'),constant('jry_wb_tp_mi_oauth_config_client_secret'));
	$oauthClient->setRedirectUri(constant('jry_wb_host').'/jry_wb_tp_callback/mi.php');
	$url = $oauthClient->getAuthorizeUrl('code','state');
	Header("HTTP/1.1 302 Found");
	Header("Location: $url");
?>