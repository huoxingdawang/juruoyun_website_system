<?php
	include_once("jry_wb_includes.php");
	if($_POST['now']=='')
	{
		echo 'false';
		exit();
	}
	$st=jry_wb_connect_database()->prepare('INSERT INTO '.JRY_WB_DATABASE_LOG.'browsing_history (`id`,`time`,`from`,`now`,`device`,`browser`,`ip`) VALUES (?,?,?,?,?,?,?);');
	$st->bindValue(1,$jry_wb_login_user['id']);
	$st->bindValue(2,jry_wb_get_time());
	$st->bindValue(3,$_POST['from']);
	$st->bindValue(4,$_POST['now']);
	$st->bindValue(5,jry_wb_get_device(true));
	$st->bindValue(6,jry_wb_get_browser(true));
	$st->bindValue(7,$_SERVER['REMOTE_ADDR']);
	$st->execute();
	echo 'true';
?>