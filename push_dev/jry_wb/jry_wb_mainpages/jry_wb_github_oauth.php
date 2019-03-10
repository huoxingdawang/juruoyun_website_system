<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_tp_github_oauth_config.php");
	Header("HTTP/1.1 302 Found");
	Header("Location: https://github.com/login/oauth/authorize?client_id=".constant ('jry_wb_tp_github_oauth_config_client_id')."&redirect_uri=".constant('jry_wb_host') ."/jry_wb_tp_callback/github.php");
?>