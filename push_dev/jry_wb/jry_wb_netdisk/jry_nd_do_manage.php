<?php
	include_once("jry_nd_includes.php");
	use Sts\Request\V20150401 as Sts;	
	use OSS\OssClient;
	use OSS\Core\OssException;
	try
	{
		jry_wb_print_head("",true,true,true,array('use','usenetdisk','manage','managenetdisk'),false);
	}
	catch(jry_wb_exception $e)
	{
		echo $e->getMessage();
		exit();
	}
	jry_wb_get_netdisk_information();
	$action=$_GET['action'];
	if($action=='delete_buf')
	{
		try
		{
			$area=jry_nd_get_area_by_area_id($_GET['area_id']);
			$ossclient_in	=jry_nd_aly_connect_in_by_area($area);
			try
			{
				$listobjectinfo=$ossclient_in->listobjects($area['config_message']->bucket,$options=array('prefix'=>$area['config_message']->dir));
			}
			catch(OssException $e)
			{
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>220000,'extern'=>$e->getMessage(),'file'=>__FILE__,'line'=>__LINE__)));
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
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>220000,'file'=>__FILE__,'line'=>__LINE__)));
			}
		}
		catch (jry_wb_exception $e)
		{
			echo $e->getMessage();
			exit();
		}
		echo json_encode(array('data'=>$data,'code'=>true));
	}
	else if($action=='sync')
	{
		$delete_log=array();
		$file_log=array();
		$area_log=array();
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_netdisk').'file_list WHERE `delete`=1');
		$st->execute();
		$files=$st->fetchAll();
		$st = $conn->prepare('DELETE FROM '.constant('jry_wb_database_netdisk').'file_list WHERE `delete`=1');
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
		$q='SELECT * FROM '.constant('jry_wb_database_netdisk').'users 
		LEFT JOIN '.constant('jry_wb_database_netdisk').'group  ON ('.constant('jry_wb_database_netdisk_prefix').'users.group_id = '.constant('jry_wb_database_netdisk_prefix')."group.group_id)";
		$st = $conn->prepare($q);
		$st->execute();
		$users=array();
		foreach($st->fetchAll() as $user)
			$users[]=array( 'id'=>$user['id'],
							'size_uploading'=>0,
							'size_used'=>0
			);
		$users_id=array_column($users,'id');
		$st = $conn->prepare('SELECT *FROM '.constant('jry_wb_database_netdisk').'area WHERE `use`=1');
		$st->execute();
		$areas=$st->fetchAll();
		
		foreach($areas as $area)
		{
			$area['config_message']=json_decode($area['config_message']);
			$area_size=0;
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_netdisk').'file_list WHERE area=? and `delete`=0');
			$st->bindValue(1,$area['area_id']);
			$st->execute();
			$files=$st->fetchAll();
			foreach($files as $file)
			{
				$file['extern']=json_decode($file['extern']);
				$result=array_search($file['id'],$users_id);
				if($result!==false)
				{
					if(!$file['isdir'])
					{
						if(jry_nd_direct_check_file_exist($area,$file))
						{
							if($file['uploading'])
								jry_nd_database_set_file_ok($conn,$users[$result],$file['file_id'],$file['size']);
						}
						else 
							$result=false;
					}
					if(jry_nd_database_get_father($conn,$users[$result],$file)===null)
						$result=false;
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
									'delete'=>$file['delete'],
									'isdir'=>$file['isdir'],
									'lasttime'=>$file['lasttime']);
					$st = $conn->prepare('DELETE FROM '.constant('jry_wb_database_netdisk').'file_list WHERE file_id=?');
					$st->bindValue(1,$file['file_id']);
					$st->execute();
					jry_nd_direct_delete($conn,$users[$result],$file);
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
						$users[$result]['size_uploading']+=$file['size'];
					else
						$users[$result]['size_used']+=$file['size'];
				}
			}
			jry_nd_database_set_area_size($conn,$area,$area_size);
			$area_log[]=array('used'=>$area_size);
		}
		foreach($users as $user)
			jry_nd_database_set_user_used_uploading($conn,$user,$user['size_used'],$user['size_uploading']);
		echo json_encode(array('delete_log'=>$delete_log,'file_log'=>$file_log,'area_log'=>$area_log,'users'=>$users,'code'=>true));
	}