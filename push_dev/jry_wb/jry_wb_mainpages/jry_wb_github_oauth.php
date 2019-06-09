<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_tp_github_oauth_config.php");
	Header("HTTP/1.1 302 Found");
	Header("Location: https://github.com/login/oauth/authorize?client_id=".JRY_WB_TP_GITHUB_OAUTH_CLIENT_ID."&redirect_uri=".JRY_WB_HOST ."jry_wb_tp_callback/github.php");
?>