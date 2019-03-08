<?php
	include_once("jry_wb_includes.php");
	function jry_wb_get_ip_address($ip)
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'ip WHERE ip=? LIMIT 1');
		$st->bindParam(1,$ip);
		$st->execute();
		$data=$st->fetchAll();
		if(count($data)!=0)
			return json_decode($data[0]['data']);
		$json=file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip='.$ip);
		if($json=='')
			$json='{"code":0,"data":{"country":"XX","area":"","region":"XX","city":"unknow","county":"unknow","isp":"unknow","country_id":"xx","area_id":"","region_id":"xx","city_id":"local","county_id":"local","isp_id":"local"}}';
		else
		{
			if(!(json_decode($json)->code))
			{
				$st = $conn->prepare('INSERT INTO '.constant('jry_wb_database_general').'ip (`ip`,`data`) VALUES (?,?)');
				$st->bindParam(1,$ip);
				$st->bindParam(2,$json);
				$st->execute();
			}
		}
		return json_decode($json);
	}
	if(($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'])==__FILE__)
		echo json_encode(jry_wb_get_ip_address($_GET['ip']));
?>