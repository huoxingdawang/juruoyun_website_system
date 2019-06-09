<?php
	include_once("jry_wb_cli_includes.php");
	$timeer=0;
	$sleep=$argv[1]==''?10*60:((int)$argv[1]);
	$sleep=max(0,$sleep-3);
	echo ("\n".jry_wb_php_cli_color('OK','green')."\nfor ".$sleep."\nby ".jry_wb_php_cli_color('juruoyun web system '.JRY_WB_VERSION,'light_green')."\n");	
	while(1)
	{
		$data=jry_wb_cli_get_machine();
		$st = jry_wb_connect_database()->prepare("INSERT INTO ".JRY_WB_DATABASE_LOG."machine (`data`) VALUES(?)");
		$st->bindParam(1,json_encode($data));
		$st->execute();	
		$timeer=time();
		echo jry_wb_get_time()." save\n";
		sleep($sleep);
	}
	
?>