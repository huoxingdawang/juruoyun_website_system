<?php
	include_once("jry_wb_includes.php");
	require_once '../jry_wb_tp_sdk/ip2region/Ip2Region.php';
	function jry_wb_get_ip_address($ip)
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'ip WHERE ip=? LIMIT 1');
		$st->bindParam(1,$ip);
		$st->execute();
		$data=$st->fetchAll();
		if(count($data)!=0)
		{
			$json=json_decode($data[0]['data']);
			$json->by='db';
			return $json;
		}
		$ip2region = new Ip2Region();
		$json=explode('|',$ip2region->btreeSearch($ip)['region']);
		if(!($json[4]==''&&$json[3]==''&&$json[2]==''&&$json[1]==''&&$json[0]==''))
			return json_decode('{"by":"ip2region","code":0,"data":{"ip":"'.$ip.'","isp":"'.$json[4].'","area":"","city":"'.$json[3].'","county":"XX","isp_id":"","region":"'.$json[2].'","area_id":"","city_id":"","country":"'.$json[0].'","county_id":"xx","region_id":"","country_id":""}}');
/*		$json=file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip='.$ip);
		if($json=='')
			$json='{"code":0,"data":{"country":"XX","area":"","region":"XX","city":"unknow","county":"unknow","isp":"unknow","country_id":"xx","area_id":"","region_id":"xx","city_id":"local","county_id":"local","isp_id":"local"}}';
		else
		{
			if(!(json_decode($json)->code))
			{
				$json=json_decode($json);
				$json['by']='tb';
				$json=json_encode($json);				
				$st = $conn->prepare('INSERT INTO '.constant('jry_wb_database_general').'ip (`ip`,`data`) VALUES (?,?)');
				$st->bindParam(1,$ip);
				$st->bindParam(2,$json);
				$st->execute();
			}
		}
		return json_decode($json);
		$json=json_decode(file_get_contents('http://freeapi.ipip.net/'.$ip));
		if($json[4]==''&&$json[3]==''&&$json[2]==''&&$json[1]==''&&$json[0]=='')
			return null;
		$json='{"by":"ipip","code":0,"data":{"ip":"'.$ip.'","isp":"'.$json[4].'","area":"","city":"'.$json[2].'","county":"XX","isp_id":"","region":"'.$json[1].'","area_id":"","city_id":"","country":"'.$json[0].'","county_id":"xx","region_id":"","country_id":""}}';
		$st = $conn->prepare('INSERT INTO '.constant('jry_wb_database_general').'ip (`ip`,`data`) VALUES (?,?)');
		$st->bindParam(1,$ip);
		$st->bindParam(2,$json);
		$st->execute();		
		return json_decode($json);*/
	}
	if(($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'])==__FILE__)
		echo json_encode(jry_wb_get_ip_address($_GET['ip']));
?>