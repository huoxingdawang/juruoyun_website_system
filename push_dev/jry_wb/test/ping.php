<?php
	include_once("../jry_wb_tools/jry_wb_database.php");
	$conn=jry_wb_connect_database();
	while(1)
	{
		$st=$conn->prepare('SELECT `ip` FROM '.JRY_WB_DATABASE_GENERAL.'login');
		$st->execute();
		foreach($st->fetchAll() as $one)
			system("ping -c 1 ".$one['ip']);
		sleep(60);
	}
?>
