<?php
	include_once("../tools/jry_wb_includes.php");
	try{jry_wb_check_compentence();}catch(jry_wb_exception $e){echo $e->getMessage();exit();}		
	if($_GET['action']=='add')
	{
	
	}
?>