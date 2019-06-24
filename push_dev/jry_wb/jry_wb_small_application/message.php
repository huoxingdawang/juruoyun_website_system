<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	echo "HTTP_USER_AGENT:".$_SERVER["HTTP_USER_AGENT"].'<br><br><br>';
	echo "browser:".jry_wb_get_browser(true).':'.jry_wb_get_browser(false).'<br><br><br>';
	echo "device:".jry_wb_get_device(true).':'.jry_wb_get_device(false).'<br><br><br>';
	echo "ip:".$_SERVER['REMOTE_ADDR'].'<br><br><br>';
	echo "HTTP_REFERER:".$_SERVER['HTTP_REFERER'].'<br><br><br>';
	echo "REQUEST_URI:".$_SERVER['REQUEST_URI'].'<br><br><br>';
?>