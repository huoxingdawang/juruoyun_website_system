<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	echo json_encode(jry_wb_get_ip_address($_SERVER['REMOTE_ADDR']))."\n".'<br><br><br>';
	echo "HTTP_USER_AGENT:".$_SERVER["HTTP_USER_AGENT"].'<br><br><br>'."\n";
	echo "browser:".jry_wb_get_browser(true).':'.jry_wb_get_browser(false).'<br><br><br>'."\n";
	echo "device:".jry_wb_get_device(true).':'.jry_wb_get_device(false).'<br><br><br>'."\n";
	echo "ip:".$_SERVER['REMOTE_ADDR'].'<br><br><br>'."\n";
	echo "area:".jry_wb_get_ip_address_string($_SERVER['REMOTE_ADDR']).'<br><br><br>'."\n";
	echo "HTTP_REFERER:".$_SERVER['HTTP_REFERER'].'<br><br><br>'."\n";
	echo "REQUEST_URI:".$_SERVER['REQUEST_URI'].'<br><br><br>'."\n";
?>