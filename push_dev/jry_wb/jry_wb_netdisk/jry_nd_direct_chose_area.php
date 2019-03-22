<?php
	include_once('jry_nd_direct_include.php');
	function jry_nd_direct_chose_area($conn,$user,$size)
	{
		if($size<1000)
			$method=0;
		else
			$method=1;
		$st = $conn->prepare('SELECT *FROM '.constant('jry_wb_netdisk').'area WHERE  type=? AND `use`=1');
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
?>