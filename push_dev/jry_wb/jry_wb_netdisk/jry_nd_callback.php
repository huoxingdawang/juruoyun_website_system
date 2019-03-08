<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_netdisk.php");
	include_once("jry_wb_nd_tools.php");	
	include_once("jry_nd_file_type.php");	
	use OSS\OssClient;
	use OSS\Core\OssException;
	if($_GET['action']=='aly_download')
	{
		$receive_data=json_decode(base64_decode(file_get_contents("php://input")));
		$conn=jry_wb_connect_database();
		
		/*$st = $conn->prepare('INSERT INTO '.constant('jry_wb_netdisk').'test (`data`,`lasttime`) VALUES (?,?)');
		$st->bindValue(1,json_encode($receive_data));
		$st->bindValue(2,jry_wb_get_time());
		$st->execute();
		*/
		
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'area WHERE `type`=1 AND config_message->"$.bucket"=? AND `use`=1');
		$st->bindValue(1,$receive_data->events[0]->oss->bucket->name);
		$st->execute();
		$data=$st->fetchAll();
		if(count($data)==0)
			exit();
		$data[0]['config_message']=json_decode($data[0]['config_message']);
		$dir=$receive_data->events[0]->oss->object->key;
		
		
		$ossclient_in	=new OssClient($data[0]['config_message']->accesskeyid,$data[0]['config_message']->accesskeysecret,$data[0]['config_message']->endpoint_in,false);			
		$ossclient_in->deleteObject($data[0]['config_message']->bucket,$dir);
		
		
		
		
		$qianzhui=$data[0]['config_message']->dir.constant('jry_nd_upload_file_prefix');
		$b=mb_strpos($dir,$qianzhui)+mb_strlen($qianzhui);
		$e=mb_strpos($dir,"_jryupload")-$b;
		$file_id=mb_substr($dir,$b,$e);
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'file_list WHERE `area`=? AND file_id=? AND use=1');
		$st->bindValue(1,$data[0]['area_id']);
		$st->bindValue(2,$file_id);
		$st->execute();
		$data2=$st->fetchAll();
		if(count($data2)==0)	
			exit();
		
		$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'users SET fast_size=fast_size-? , lasttime=? WHERE `id`=?;');
		$st->bindValue(1,$data2[0]['size']);
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$data2[0]['id']);	
		$st->execute();	
	}
?>
