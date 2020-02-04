<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	try{jry_wb_check_compentence(NULL,array('use','manage','useping'));}catch(jry_wb_exception $e){echo $e->getMessage();exit();}
	$myfile=fopen("/tf/ips", "w");
	fwrite($myfile,base64_decode($_POST['data']));
	fclose($myfile);	
	$myfile=fopen("/tf/ips", "r");
	$ans=fread($myfile,filesize("/tf/ips"));
	fclose($myfile);
	echo json_encode($ans);
?>