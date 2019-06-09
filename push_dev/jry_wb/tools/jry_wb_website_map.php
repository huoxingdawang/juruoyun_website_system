<?php
	include_once("jry_wb_includes.php");
	global $jry_wb_website_map;
	$jry_wb_website_map=NULL;
	function jry_wb_load_website_map()
	{
		global $jry_wb_website_map;
		if($jry_wb_website_map!=NULL)
			return;
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL."website");
		$st->execute();
		$jry_wb_website_map=$st->fetchAll();		
	}
?>