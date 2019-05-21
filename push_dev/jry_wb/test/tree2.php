<?php
	include_once("../tools/jry_wb_includes.php");
	$conn=jry_wb_connect_database();
	$st = $conn->prepare("SELECT JSON_SEARCH(chat_rooms,'one',?) FROM  juruoyun_dev.chat_users WHERE id=1");
	$st->bindValue(1,1,PDO::PARAM_INT);
	$st->execute();
	var_dump($st->fetchAll());	
?>
