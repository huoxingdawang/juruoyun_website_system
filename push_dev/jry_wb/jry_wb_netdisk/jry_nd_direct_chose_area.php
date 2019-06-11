<?php
	include_once('jry_nd_direct_include.php');
	function jry_nd_direct_chose_area($conn,$user,$size)
	{
		if($size<JRY_ND_UPLOAD_METHOD_0_MAX_SIZE||JRY_ND_UPLOAD_METHOD_0_MAX_SIZE==-1)
			$method=0;
		else
			$method=1;
		$st = $conn->prepare('SELECT *FROM '.JRY_WB_DATABASE_NETDISK.'area WHERE  type=? AND `use`=1 AND `upload`=1');
		$st->bindParam(1,$method);
		$st->execute();
		if(count($areas=$st->fetchAll())==0)
			return null;
		$area=$areas[0];
		$min_use=$areas[0]['size']-$areas[0]['used'];
		foreach($areas as $onearea)
		{
			if($onearea['upload']==0)
				continue;
			if((($onearea['size']-$onearea['used'])>$min_use)&&($onearea['samearea']||(!$user['nd_ei']['sameareaonly'])))
			{
				$min_use=$onearea['size']-$onearea['used'];
				$area=$onearea;
			}
		}	
		if($min_use<$size)
			$area=null;
		$area['config_message']=json_decode($area['config_message']);
		$area['rest_size']=$area['size']-$area['used'];
		return $area;		
	}
	function jry_nd_get_area_by_area_id($area_id)
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT *FROM '.JRY_WB_DATABASE_NETDISK.'area WHERE area_id=? AND `use`=1 LIMIT 1');
		$st->bindParam(1,$area_id);
		$st->execute();
		if(count($data=$st->fetchAll())==0)
			return null;
		$data[0]['config_message']=json_decode($data[0]['config_message']);
		return $data[0];
	}
	function jry_nd_get_area_by_type($type)
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT *FROM '.JRY_WB_DATABASE_NETDISK.'area WHERE  type=? AND `use`=1');
		$st->bindParam(1,$type);
		$st->execute();
		return $st->fetchAll();		
	}
?>