<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_operate_area_size($conn,$area,$size)
	{
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'area SET used=used+? , lasttime=? WHERE `area_id`=?;');
		$st->bindValue(1,$size);
		$st->bindValue(2,$time=jry_wb_get_time());
		$st->bindValue(3,$area['area_id']);	
		$st->execute();
		jry_nd_database_operate_fast_save('area',$time);
	}
	function jry_nd_database_set_area_size($conn,$area,$size)
	{
		if($size<0)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>230001,'file'=>__FILE__,'line'=>__LINE__)));
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'area SET used=? , lasttime=? WHERE `area_id`=?;');
		$st->bindValue(1,$size);
		$st->bindValue(2,$time=jry_wb_get_time());
		$st->bindValue(3,$area['area_id']);	
		$st->execute();
		jry_nd_database_operate_fast_save('area',$time);
	}		
?>