<?php
	include_once("../tools/jry_wb_includes.php");
	include_once(JRY_WB_LOCAL_DIR."/jry_wb_tp_sdk/qq/oauth/qqConnectAPI.php");
	$qc = new QC();
	$qc->qq_login();
?>