<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_netdisk.php");
	include_once("jry_wb_nd_tools.php");	
	include_once("jry_nd_file_type.php");
	include_once((dirname(__DIR__)."/jry_tp_sdk/aly/aliyun-php-sdk-core/Regions/EndpointConfig.php"));
	use Sts\Request\V20150401 as Sts;	
	use OSS\OssClient;
	use OSS\Core\OssException;
	if(jry_wb_print_head("",true,true,true,array('use','usenetdisk','manage','managenetdisk'),false)!='ok')
	{
		echo json_encode(array('login'=>false,'reasion'=>$login));
		exit();			
	}	
	jry_wb_get_netdisk_information();
	$action=$_GET['action'];
	if($action=='delete_buf')
	{
		$area=jry_nd_get_area_by_area_id($_GET['area_id']);
		if($area==null||$area['type']!=1)
		{
			echo json_encode(array('login'=>true,'code'=>false,'reason'=>6));
			exit();
		}
		$ossclient_in=new OssClient($area['config_message']->accesskeyid,$area['config_message']->accesskeysecret,$area['config_message']->endpoint_in,false);
		try
		{
			$listobjectinfo=$ossclient_in->listobjects($area['config_message']->bucket,$options=array('prefix'=>$area['config_message']->dir));
		}
		catch(OssException $e)
		{
			echo json_encode(array('login'=>true,'code'=>false,'reason'=>10,'message'=>$e->getMessage() . "\n"));
			return;
		}
		$data=array();
		$nextmarker=$listobjectinfo->getNextMarker();
		$listobject=$listobjectinfo->getObjectList();
		if (!empty($listobject))
			foreach ($listobject as $objectinfo)
			{
				$time=$ossclient_in->getObjectMeta($area['config_message']->bucket,$objectinfo->getKey())['expires'];
				if($time!=''&&strtotime(jry_wb_get_time())>strtotime($time))				
					$data[]=$objectinfo->getKey();
			}
		try
		{
			if(count($data)!=0)
				$ossclient_in->deleteObjects($area['config_message']->bucket,$data);
		}
		catch(OssException $e)
		{
			echo json_encode(array('login'=>true,'code'=>false,'reason'=>10,'message'=>$e->getMessage() . "\n"));
			return;
		}
		echo json_encode(array('data'=>$data,'login'=>true,'code'=>true));
	}
	else if($action=='sync')
	{
		$delete_log=array();
		$file_log=array();
		$area_log=array();
		
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'file_list WHERE `delete`=1');
		$st->execute();
		$files=$st->fetchAll();
		$st = $conn->prepare('DELETE FROM '.constant('jry_wb_netdisk').'file_list WHERE `delete`=1');
		$st->execute();
		foreach($files as $file)
			$delete_log[]=array('file_id'=>$file['file_id'],
								'id'=>$file['id'],
								'name'=>$file['name'],
								'type'=>$file['type'],
								'area'=>$file['area'],
								'size'=>$file['size'],
								'uploading'=>$file['uploading'],
								'isdir'=>$file['isdir'],
								'lasttime'=>$file['lasttime']);
		$q='SELECT * FROM '.constant('jry_wb_netdisk').'users 
		LEFT JOIN '.constant('jry_wb_netdisk').'group  ON ('.constant('jry_wb_netdisk_prefix').'users.jry_nd_group_id = '.constant('jry_wb_netdisk_prefix')."group.jry_nd_group_id)";
		$st = $conn->prepare($q);
		$st->execute();
		$users=array();
		foreach($st->fetchAll() as $user)
			$users[]=array( 'id'=>$user['id'],
							'jry_nd_uploading_size'=>0,
							'jry_nd_size_used'=>0
			);
		$users_id=array_column($users,'id');
		$st = $conn->prepare('SELECT *FROM '.constant('jry_wb_netdisk').'area WHERE `use`=1');
		$st->execute();
		$areas=$st->fetchAll();
		
		foreach($areas as $area)
		{
			if($area['type']==1)
				$ossclient_in=new OssClient($area['config_message']->accesskeyid,$area['config_message']->accesskeysecret,$area['config_message']->endpoint_in,false);
			$area_size=0;
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'file_list WHERE area=? and `delete`=0');
			$st->bindValue(1,$area['area_id']);
			$st->execute();
			$files=$st->fetchAll();
			foreach($files as $file)
			{
				$result=array_search($file['id'],$users_id);
				if(!$file['is_dir'])
				{
					//判断文件存在
					
					//存在uploading更新flag
					
					//不存在$result=false;
				}
				if($result===false)
				{
					$delete_log[]=array('file_id'=>$file['file_id'],
									'id'=>$file['id'],
									'name'=>$file['name'],
									'type'=>$file['type'],
									'area'=>$file['area'],
									'size'=>$file['size'],
									'uploading'=>$file['uploading'],
									'isdir'=>$file['isdir'],
									'lasttime'=>$file['lasttime']);
				/*	$st = $conn->prepare('DELETE FROM '.constant('jry_wb_netdisk').'file_list WHERE file_id=?');
					$st->bindValue(1,$file['file_id']);
					$st->execute();*/
				}
				else
				{
					$file_log[]=array('file_id'=>$file['file_id'],
									'id'=>$file['id'],
									'name'=>$file['name'],
									'type'=>$file['type'],
									'area'=>$file['area'],
									'size'=>$file['size'],
									'uploading'=>$file['uploading'],
									'isdir'=>$file['isdir'],
									'lasttime'=>$file['lasttime']);
					$area_size+=$file['size'];
					if($file['uploading'])
						$users[$result]['jry_nd_uploading_size']+=$file['size'];
					else
						$users[$result]['jry_nd_size_used']+=$file['size'];
				}
			}
			$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'area SET used=? , lasttime=? WHERE `area_id`=?;');
			$st->bindValue(1,$area_size);
			$st->bindValue(2,jry_wb_get_time());
			$st->bindValue(3,$area['area_id']);	
			$st->execute();
			$area_log[]=array('used'=>$area_size);
		}
		foreach($users as $user)
		{
			$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'users SET lasttime=?,jry_nd_uploading_size=?,jry_nd_size_used=? WHERE `id`=?;');
			$st->bindValue(1,$lasttime=jry_wb_get_time());
			$st->bindValue(2,$user['jry_nd_uploading_size']);
			$st->bindValue(3,$user['jry_nd_size_used']);
			$st->bindValue(4,$user['id']);	
			$st->execute();	
		}
		echo json_encode(array('delete_log'=>$delete_log,'file_log'=>$file_log,'area_log'=>$area_log,'users'=>$users,'login'=>true,'code'=>true));
	}
?>