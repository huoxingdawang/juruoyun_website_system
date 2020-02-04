<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	try{jry_wb_check_compentence(NULL,array('use','manage','useping'));}catch(jry_wb_exception $e){echo $e->getMessage();exit();}
	$action=$_GET['action'];
	if($action=='get')
	{
		$myfile=fopen("/tf/ips", "r");
		$ans=fread($myfile,filesize("/tf/ips"));
		fclose($myfile);
		echo json_encode(array('code'=>true,'data'=>$ans));
	}
	else if($action=='result')
	{
		$myfile=fopen("/tf/ping", "r");
		$ans=fread($myfile,filesize("/tf/ping"));
		fclose($myfile);
		echo json_encode(array('code'=>true,'data'=>$ans));
	}	
?>