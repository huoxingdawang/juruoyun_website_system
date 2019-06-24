<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_netdisk.php");
	include_once("jry_wb_nd_tools.php");	
	include_once("jry_nd_file_type.php");
	$conn=jry_wb_connect_database();	
	$st=$conn->prepare('SELECT * FROM '.constant('picturebeddb').'map');
	$st->execute(); 
	$data=$st->fetchAll();
	$area=jry_nd_get_area_by_type(0)[0];
	$area['config_message']=json_decode($area['config_message']);
//	print_r($area);
	exit();
	foreach($data as $photo)
	{
		$filename=constant('jry_wb_upload_file_address').$photo['pictureid'].'.'.$photo['type']."_jryupload";
		$size=ceil(filesize($filename)/1024);
		$st = $conn->prepare('INSERT INTO '.JRY_WB_DATABASE_NETDISK.'file_list (`id`,`dir`,`name`,`type`,`area`,`size`,`lasttime`,`uploading`) VALUES (?,?,?,?,?,?,?,?)');
		$st->bindValue(1,$photo['id']);
		$st->bindValue(2,'/picturebed/');
		$st->bindValue(3,$photo['pictureid']);
		$st->bindValue(4,$photo['type']);
		$st->bindValue(5,$area['area_id']);
		$st->bindValue(6,$size);
		$st->bindValue(7,jry_wb_get_time());
		$st->bindValue(8,0);
		$st->execute();
		$file_id=$conn->lastInsertId();		
		$file_name=$area['config_message']->dir.JRY_ND_UPLOAD_FILE_PREFIX.$file_id.'_jryupload';
		echo $filename.'&nbsp;&nbsp;&nbsp;&nbsp;to&nbsp;&nbsp;&nbsp;&nbsp;'.$file_name.'<br>';
		$dst=fopen($file_name, 'wb');
		$src=fopen($filename, 'rb');
		stream_copy_to_stream($src, $dst);
		fclose($src);		
		fclose($dst);		
		$st = $conn->prepare('INSERT INTO '.JRY_WB_DATABASE_NETDISK.'share (`id`,`key`,`file_id`,`lasttime`,`share_id`) VALUES (?,?,?,?,?)');
		$st->bindValue(1,$photo['id']);
		$st->bindValue(2,'');
		$st->bindValue(3,$file_id);
		$st->bindValue(4,jry_wb_get_time());
		$st->bindValue(5,$photo['pictureid']);
		$st->execute();
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'users SET size_used=size_used+? , lasttime=? WHERE `id`=?;');
		$st->bindValue(1,($size));
		$st->bindValue(2,$lasttime=jry_wb_get_time());
		$st->bindValue(3,$photo['id']);	
		$st->execute();
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'area SET used=used+? , lasttime=? WHERE `area_id`=?;');
		$st->bindValue(1,$size);
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$area['area_id']);	
		$st->execute();
		if(($file=fopen('jry_nd.fast_save_message','r'))==false)
		{
			$st = $conn->prepare('SELECT lasttime FROM '.JRY_WB_DATABASE_NETDISK.'group ORDER BY lasttime DESC LIMIT 1;');	$st->execute();		$data['group']=$st->fetchAll()[0]['lasttime'];
			$data->area=jry_wb_get_time();
			$file2=fopen('jry_nd.fast_save_message','w');
			fwrite($file2,json_encode($data));
			fclose($file2);
			$data['new']=true;
		}
		else
		{
			$data=json_decode(fread($file,filesize('jry_nd.fast_save_message')));
			$data->area=jry_wb_get_time();
			$file2=fopen('jry_nd.fast_save_message','w');
			fwrite($file2,json_encode($data));
			fclose($file2);
		}
		fclose($file);		
	}
?>