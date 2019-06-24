<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_tp_gitee_oauth_config.php");
	Header("HTTP/1.1 302 Found");
	Header("Location: https://gitee.com/oauth/authorize?response_type=code&client_id=".JRY_WB_TP_GITEE_OAUTH_CLIENT_ID."&redirect_uri=".JRY_WB_HOST ."jry_wb_tp_callback/gitee.php");
?>