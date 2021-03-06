<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_get_area($conn,$area_id)
	{
		$st = $conn->prepare('SELECT *FROM '.JRY_WB_DATABASE_NETDISK.'area WHERE area_id=? AND `use`=1 LIMIT 1');
		$st->bindParam(1,$area_id);
		$st->execute();
		if(count($data=$st->fetchAll())==0)
			return null;
		$data[0]['config_message']=json_decode($data[0]['config_message']);
		if($data[0]['fast']==0)
			if($data[0]['faster']!='')							//有加速器
				$data[0]['faster_area']=jry_nd_database_get_area($conn,$data[0]['faster']);
		return $data[0];	
	}
?>