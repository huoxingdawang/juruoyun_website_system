<?php
	include_once("../tools/jry_wb_includes.php");
	include_once(constant('jry_wb_local_dir')."/jry_wb_tp_sdk/qq/oauth/qqConnectAPI.php");
	$qc = new QC();
	$qc->qq_login();
?>