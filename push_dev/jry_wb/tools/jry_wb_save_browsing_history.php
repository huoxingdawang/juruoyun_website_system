<?php
	include_once('jry_wb_database.php'); 
	include_once('jry_wb_get_time.php');
	include_once('jry_wb_get_device.php');
	global $jry_wb_login_user; 	
	$st=jry_wb_connect_database()->prepare('INSERT INTO '.JRY_WB_DATABASE_LOG.'browsing_history (`id`,`time`,`from`,`now`,`device`,`browser`,`ip`) VALUES (?,?,?,?,?,?,?);');
	$st->bindValue(1,$jry_wb_login_user['id']);
	$st->bindValue(2,jry_wb_get_time());
	$st->bindValue(3,$_SERVER['HTTP_REFERER']);
	$st->bindValue(4,$_SERVER['REQUEST_URI']);
	$st->bindValue(5,jry_wb_get_device(true));
	$st->bindValue(6,jry_wb_get_browser(true));
	$st->bindValue(7,$_SERVER['REMOTE_ADDR']);
	$st->execute();
?>