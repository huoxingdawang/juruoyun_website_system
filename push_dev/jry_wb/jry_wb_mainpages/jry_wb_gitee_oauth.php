<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_tp_gitee_oauth_config.php");
	Header("HTTP/1.1 302 Found");
	Header("Location: https://gitee.com/oauth/authorize?response_type=code&client_id=".constant ('jry_wb_tp_gitee_oauth_config_client_id')."&redirect_uri=".constant('jry_wb_host') ."jry_wb_tp_callback/gitee.php");
?>