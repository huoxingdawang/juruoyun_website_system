<?php
	include_once("jry_wb_cheat_includes.php");
	function jry_wb_cheat_get_cheat_room($conn,$room_id)
	{
		$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_CHEAT.'rooms WHERE cheat_room_id=? LIMIT 1');
		$st->bindValue(1,$room_id,PDO::PARAM_INT);
		$st->execute();
		$all=$st->fetchAll();
		if(count($all)===0)
			return null;
		$all[0]['users']=json_decode($all[0]['users']);
		return $all[0];
	}
