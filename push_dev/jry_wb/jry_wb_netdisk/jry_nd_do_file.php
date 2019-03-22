<?php
/*错误对照表
1	:不允许的文件类型
2	:用户空间不足
3	:当前存储区域不足
4	:分片上传数据发送错误
5	:分片上传ID错误
6	:分片上传存储区不存在
7	:分片上传存储区上传方法错误
8	:文件大小误差过大
9	:未知操作
10	:OSS文件错误
11	:文件重复
12	:文件不存在或已删除
13	:STS签名错误
14	:OSS连接错误
15	:存储区连接错误
*/
	include_once("../tools/jry_wb_includes.php");
	include_once("jry_nd_includes.php");
	use Sts\Request\V20150401 as Sts;
	use OSS\OssClient;
	use OSS\Core\OssException;
	ini_set("display_errors", "On"); 
	if($jry_wb_login_user['id']!=-1)
		jry_wb_get_netdisk_information();
	$action=$_GET['action'];
	if($action=='open'||$action=='download')
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'share WHERE share_id=? AND `key`=? LIMIT 1;');
		$st->bindValue(1,$_GET['share_id']);
		$st->bindValue(2,$_GET['key']==''?'':$_GET['key']);
		$st->execute();
		$share=$st->fetchAll();
		$share_mode=false;
		if(count($share)!=0)
		{
			function getf($file)
			{
				global $conn;
				global $share;
				$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'file_list WHERE file_id=? AND id=? AND `delete`=0 LIMIT 1');
				$st->bindValue(1,$file['father']);
				$st->bindValue(2,$share[0]['id']);
				$st->execute();
				$root=$st->fetchAll();
				if(count($root)!=0)
					return $root;
				else
					return null;
			}
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'file_list WHERE file_id=? AND id=? AND `delete`=0 LIMIT 1');
			$st->bindValue(1,$_GET['file_id']);
			$st->bindValue(2,$share[0]['id']);
			$st->execute();
			$root=$st->fetchAll();
			if(count($root)!=0)
				if(($share_user=jry_wb_get_netdisk_information_by_id($share[0]['id']))!=null)
					if($_GET['file_id']==$share[0]['file_id'])
						$share_mode=true;
					else
						while(($root=getf($root[0]))!=null)
							if($share_mode=($root[0]['file_id']==$share[0]['file_id']))
								break;
		}
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'file_list WHERE `id`=? AND `file_id`=? AND `delete`=0 limit 1');
		if($share_mode)
			$st->bindValue(1,$share[0]['id']);
		else
			$st->bindValue(1,$jry_wb_login_user['id']);
		$st->bindValue(2,$_GET['file_id']);
		$st->execute();
		$data=$st->fetchAll();
		if(count($data)==0)
		{
			if($action=='open')
				header("Location:".constant('jry_nd_404'));	
			else
				include('../../404.php');
			exit();
		}
		$area=jry_nd_get_area_by_area_id($data[0]['area']);
		if($area==null)
		{
			echo json_encode(array('login'=>true,'code'=>false,'reason'=>6));
			exit();
		}
		$fast_mode=false;
		if(	($share_mode&&$share[0]['fastdownload']&&$_GET['fast']==1&&$share_user['fast_size']>$data[0]['size'])||
			(!$area['samearea'])||
			($share_mode&&$jry_wb_login_user['id']!=-1&&$_GET['fast']==1&&$jry_wb_login_user['nd_ei']['fast_size']>$data[0]['size'])||
			((!$share_mode)&&$jry_wb_login_user['nd_ei']['fast_size']>$data[0]['size']&&$_GET['fast']==1))//可以高速下载
		{
			$fast_mode=true;
			if($area['type']==0)//上传
			{
				$old_area=$area;
				$areas=jry_nd_get_area_by_type(1);
				$area=$areas[0];
				$min_use=$areas[0]['size']-$areas[0]['used'];
				foreach($areas as $onearea)
				{
					if($onearea['upload']==0)
						continue;
					if((($onearea['size']-$onearea['used'])>$min_use)&&($onearea['samearea']||(!$jry_wb_login_user['nd_ei']['sameareaonly'])))
					{
						$min_use=$onearea['size']-$onearea['used'];
						$area=$onearea;
					}
				}
				if($min_use<$data[0]['size'])
				{
					echo json_encode(array('login'=>true,'code'=>false,'reason'=>3,'min_use'=>$min_use,'size'=>$data[0]['size']));
					exit();
				}
				$area['config_message']=json_decode($area['config_message']);
				$file_name=$old_area['config_message']->dir.constant('jry_nd_upload_file_prefix').$data[0]['file_id'].'_jryupload';
				$new_file_name=$area['config_message']->dir.constant('jry_nd_upload_file_prefix').$data[0]['file_id'].'_jryupload';
				$ossclient = new OssClient($area['config_message']->accesskeyid,$area['config_message']->accesskeysecret,$area['config_message']->endpoint_in,false);			
				$ossclient->multiuploadFile($area['config_message']->bucket,$new_file_name,$file_name,array(OssClient::OSS_CHECK_MD5 => true,OssClient::OSS_PART_SIZE => 1));
				$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET  area=? WHERE `file_id`=? AND id=?');
				$st->bindValue(1,$area['area_id']);
				$st->bindValue(2,$data[0]['file_id']);
				if($share_mode)
					$st->bindValue(3,$share[0]['id']);
				else
					$st->bindValue(3,$jry_wb_login_user['id']);						
				$st->execute();
				jry_nd_database_operate_area_size($conn,$old_area,-$data[0]['size']);	//原区域减少
				jry_nd_database_operate_area_size($conn,$area,$data[0]['size']);		//新区域增加
				unlink($old_area['config_message']->dir.constant('jry_nd_upload_file_prefix').$data[0]['file_id'].'_jryupload');
			}
		}
		if($fast_mode||$area['type']==1)
		{
			try
			{
				$ossclient_in	=jry_nd_aly_connect_in_by_area($area);
				$ossclient		=jry_nd_aly_connect_out_by_area($area);
			}
			catch (jry_nd_aly_exception $e)
			{
				echo $e->getMessage();
				exit();
			}
			$data[0]['extern']=json_decode($data[0]['extern']);
			$new=false;
			$fromobject=$area['config_message']->dir.constant('jry_nd_upload_file_prefix').$data[0]['file_id'].'_jryupload';
			if($data[0]['extern']==NULL)
				$new=true;
			else
				if($action=='open')
					if($data[0]['extern']->open!=''&&$ossclient_in->doesObjectExist($area['config_message']->bucket,$fromobject.$data[0]['extern']->open))
						$tobject=$fromobject.$data[0]['extern']->open;
					else
						$new=true;	
				else
					if($data[0]['extern']->download!=''&&$ossclient_in->doesObjectExist($area['config_message']->bucket,$fromobject.$data[0]['extern']->download))
						$tobject=$fromobject.$data[0]['extern']->download;
					else
						$new=true;
			$time=$data[0]['size']/constant('jry_nd_min_speed');
			$time=max($time,60*15);
			if($time<60)
				$time='+'.$time.' seconds';
			else if($time<60*60)
				$time='+'.ceil($time/60).' minutes';
			else if($time<60*60*24)
				$time='+'.ceil($time/60/60).' hours';
			else
				$time='+'.ceil($time/60/60/24).' days';
			if($action=='open')
				$copyoptions=array(OssClient::OSS_HEADERS=>array('Expires'=>date("Y-m-d H:i:s",strtotime($time)),'Content-Type'=>jry_nd_get_content_type($data[0]['type']),'Content-Disposition'=>'filename="'.urlencode($data[0]['name']).'.'.$data[0]['type'].'"'));
			if($action=='download')
				$copyoptions=array(OssClient::OSS_HEADERS=>array('Expires'=>date("Y-m-d H:i:s",strtotime($time)),'Content-Type'=>jry_nd_get_content_type($data[0]['type']),'Content-Disposition'=>'attachment; filename="'.urlencode($data[0]['name']).'.'.$data[0]['type'].'"'));
			if($new)
			{
				$code=jry_wb_get_random_string(30);
				$tobject=$fromobject.$code;
				$ossclient_in->copyObject($area['config_message']->bucket,$fromobject,$area['config_message']->bucket, $tobject,$copyoptions);
				$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET extern=? , lasttime=? WHERE `file_id`=?;');
				if($action=='open')
					$st->bindValue(1,json_encode(array('open'=>$code,'download'=>$data[0]['extern']->download)));
				else
					$st->bindValue(1,json_encode(array('download'=>$code,'open'=>$data[0]['extern']->open)));
				$st->bindValue(2,jry_wb_get_time());
				$st->bindValue(3,$data[0]['file_id']);
				$st->execute();	
			}
			$ossclient_in->copyObject($area['config_message']->bucket,$tobject,$area['config_message']->bucket, $tobject,$copyoptions);	
		}
		if($fast_mode)
		{
			$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'users SET fast_size=fast_size-? , lasttime=? WHERE `id`=?;');
			$st->bindValue(1,$data[0]['size']);
			$st->bindValue(2,jry_wb_get_time());
			if($jry_wb_login_user['id']!=-1)
				$st->bindValue(3,$jry_wb_login_user['id']);	
			else
				$st->bindValue(3,$$share[0]['id']);
			$st->execute();
			try
			{
				jry_nd_aly_download_sign($ossclient,$area,$tobject,true);
			}
			catch(jry_wb_exception $e)
			{
				echo $e->getMessage();
				exit();
			}			
		}
		else
		{
			if($action=='open')
			{
				header("content-type: ".jry_nd_get_content_type($data[0]['type']));
				header("Accept-Ranges: bytes");
			}
			else
			{
				header("Accept-Ranges: bytes");		
				header("Content-Disposition: attachment; filename=".$data[0]['name'].'.'.$data[0]['type']);
				header("content-type: ".jry_nd_get_content_type($data[0]['type']));
			}
			if($area['type']==0)
			{
				$file_name=$area['config_message']->dir.constant('jry_nd_upload_file_prefix').$data[0]['file_id'].'_jryupload';
				$dst=fopen($file_name,'rb');
				echo fread($dst,filesize($file_name));
			}
			else if($area['type']==1)
			{		
				print($ossclient_in->getObject($area['config_message']->bucket,$tobject));
			}
		}
		$st=$conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET download_times=download_times+1 WHERE `file_id`=?');
		$st->bindValue(1,$data[0]['file_id']);
		$st->execute();
		exit();
	}
	try
	{
		jry_wb_print_head("",true,true,true,array('use','usenetdisk'),false);
	}
	catch(jry_wb_exception $e)
	{
		echo $e->getMessage();
		exit();
	}
	if($action=='pre_check')
	{
 		try
		{			
			if($_POST['size']==''||$_POST['name']==''||$_POST['father']=='')
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200004,'file'=>__FILE__,'line'=>__LINE__)));
			if(!jry_nd_database_check_type($jry_wb_login_user,$_POST['type']))
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200001,'file'=>__FILE__,'line'=>__LINE__)));
			if(!jry_nd_database_check_size($jry_wb_login_user,$_POST['size']))
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200002,'file'=>__FILE__,'line'=>__LINE__)));	
			if(jry_nd_database_get_file($conn,$jry_wb_login_user,$_POST['father'])===null)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200006,'file'=>__FILE__,'line'=>__LINE__)));
			if(jry_nd_database_get_file_by_father_name_type($conn,$jry_wb_login_user,$_POST['father'],$_POST['name'],$_POST['type'])!=null)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200005,'file'=>__FILE__,'line'=>__LINE__)));
			if((($area=jry_nd_direct_chose_area($conn,$jry_wb_login_user,$_POST['size']))===null))
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200003,'file'=>__FILE__,'line'=>__LINE__)));
			$file_id=jry_nd_database_new_file($conn,$jry_wb_login_user,$_POST['father'],$_POST['name'],$_POST['type'],$area,$_POST['size']);
			if($area['type']==0)
				$extern_message=[];
			else if($area['type']==1)//阿里云STS签名
			{
				try
				{
					$extern_message=jry_nd_aly_upload_sign($area,$file_id);
				}
				catch (jry_wb_exception $e)
				{
					jry_nd_database_delete_file_file_id($conn,$file_id);
					throw new jry_wb_exception($e->getMessage());
				}			
			}
			jry_nd_database_operate_user_used_uploading($conn,$jry_wb_login_user,0,$_POST['size']);
			jry_nd_database_operate_area_size($conn,$area,$_POST['size']);	
		}
		catch (jry_wb_exception $e)
		{
			echo $e->getMessage();
			exit();
		}
		echo json_encode(array('login'=>true,'code'=>true,'area'=>$area['area_id'],'file_id'=>$file_id,'method'=>$area['type'],'extern_message'=>$extern_message));
	}
	else if($action=='upload')
	{
		try
		{
			if(jry_nd_database_check_type($jry_wb_login_user,$_POST['type']))
			{
				$conn=jry_wb_connect_database();
				if(	(($file=jry_nd_database_get_file($conn,$jry_wb_login_user,$_POST['file_id']))===null)||
					($file['father']	!=$_POST['father'])||
					($file['size']		!=$_POST['size'])||
					($file['type']		!=str_replace("&","/37",$_POST['type']))||
					($file['name']		!=str_replace("&","/37",$_POST['name']))||
					(($area=jry_nd_database_get_area($conn,$file['area']))===null)||
					($area['type']!=0)
				)
				{
					if($file!=null)
					{
						jry_nd_database_delete_file_file_id($conn,$file['file_id']);
						jry_nd_database_operate_user_used_uploading($conn,$jry_wb_login_user,0,-$file['size']);	
						unlink($area['config_message']->dir. constant('jry_nd_upload_file_prefix').$file['file_id'].'-'.$_POST['index']);
					}
					if($area!=null)
						jry_nd_database_operate_area_size($conn,$area,-$file['size']);
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200004,'file'=>__FILE__,'line'=>__LINE__)));
				}
				move_uploaded_file($_FILES['file']['tmp_name'],$area['config_message']->dir. constant('jry_nd_upload_file_prefix').$file['file_id'].'-'.$_POST['index']);
			}
			else
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200001,'file'=>__FILE__,'line'=>__LINE__)));
		}
		catch (jry_wb_exception $e)
		{
			echo $e->getMessage();
			exit();
		}	
		echo json_encode(array('code'=>true));
	}
	else if($action=='merge')
	{
		try
		{
			if(jry_nd_database_check_type($jry_wb_login_user,$_POST['type']))
			{
				$conn=jry_wb_connect_database();
				if(	(($file=jry_nd_database_get_file($conn,$jry_wb_login_user,$_POST['file_id']))===null)||
					($file['father']	!=$_POST['father'])||
					($file['size']		!=$_POST['size'])||
					($file['type']		!=str_replace("&","/37",$_POST['type']))||
					($file['name']		!=str_replace("&","/37",$_POST['name']))||
					(($area=jry_nd_database_get_area($conn,$file['area']))===null)
				)
				{
					if($file!=null)
					{
						jry_nd_database_delete_file_file_id($conn,$file['file_id']);
						jry_nd_database_operate_user_used_uploading($conn,$jry_wb_login_user,0,-$file['size']);
						if($area!=null)
						{
							if($area['type']==0)
							{
								$target=$area['config_message']->dir.constant('jry_nd_upload_file_prefix').$data[0]['file_id'].'-';
								for($i=0;$i<$_POST['index'];$i++) 
									unlink($target.$i);
							}
						}
					}
					if($area!=null)
						jry_nd_database_operate_area_size($conn,$area,-$file['size']);
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200004,'file'=>__FILE__,'line'=>__LINE__)));
					exit();
				}
				$size=0;
				if($area['type']==0)
				{
					$target=$area['config_message']->dir.constant('jry_nd_upload_file_prefix').$file['file_id'].'-';
					$dst=fopen($area['config_message']->dir.constant('jry_nd_upload_file_prefix').$file['file_id'].'_jryupload','wb');
					for($i=0;$i<$_POST['index'];$i++) 
					{
						$slice=$target.$i;
						$src=fopen($slice, 'rb');
						stream_copy_to_stream($src, $dst);
						fclose($src);
						unlink($slice);
					}
					fclose($dst);
					$size=jry_nd_local_get_size($area,$file);				
				}
				else if($area['type']==1)
				{
					
					$ossclient=jry_nd_aly_connect_in_by_area($area);
					if(!jry_nd_aly_check_file_exist($ossclient,$area,$area['config_message']->dir.constant('jry_nd_upload_file_prefix').$file['file_id'].'_jryupload'))
						throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>220002,'file'=>__FILE__,'line'=>__LINE__)));
					$size=jry_nd_aly_get_size($ossclient,$area,$file);
				}
				if(abs(ceil($size/1024)-$_POST['size'])>10||abs(ceil($size/1024)-$file['size'])>10)
				{
					
					if($area['type']==0)
						jry_nd_local_delete_file($area,$file);
					else if($area['type']==1)
						jry_nd_aly_delete_file(jry_nd_aly_connect_in_by_area($area),$area,$file);
					jry_nd_database_operate_user_used_uploading($conn,$jry_wb_login_user,0,-$file['size']);
					jry_nd_database_operate_area_size($conn,$area,-$file['size']);
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200007,'file'=>__FILE__,'line'=>__LINE__)));
					exit();
				}
				jry_nd_database_operate_user_used_uploading($conn,$jry_wb_login_user,ceil($size/1024),-$file['size']);
				jry_nd_database_set_file_ok($conn,$jry_wb_login_user,$file['file_id'],ceil($size/1024));
				jry_nd_database_operate_fast_save('area',jry_wb_get_time());
				jry_wb_get_netdisk_information();
			}
			else
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200001,'file'=>__FILE__,'line'=>__LINE__)));
		}
		catch (jry_wb_exception $e)
		{
			echo $e->getMessage();
			exit();
		}		
		echo json_encode(array('code'=>true,'lasttime'=>jry_wb_get_time(),'size_total'=>$jry_wb_login_user['nd_ei']['size_total'],'size_used'=>$jry_wb_login_user['nd_ei']['size_used']));
	}
	else if($action=='new_dir')
	{
		try
		{
			jry_nd_direct_new_dir($conn,$jry_wb_login_user,$_POST['father']);
		}
		catch (jry_wb_exception $e)
		{
			echo $e->getMessage();
			exit();
		}			
		echo json_encode(array('code'=>true,'lasttime'=>jry_wb_get_time()));
	}
	else if($action=='rename')
	{
		try
		{
			jry_nd_direct_rename_file_id($conn,$jry_wb_login_user,$_POST['file_id'],$_POST['name'],$_POST['type']);
		}
		catch (jry_wb_exception $e)
		{
			echo $e->getMessage();
			exit();
		}			
		echo json_encode(array('code'=>true,'lasttime'=>jry_wb_get_time()));
	}
	else if($action=='move')
	{
		if(($to=jry_nd_database_get_file($conn,$jry_wb_login_user,$_POST['to']))===null)
		{
			echo (json_encode(array('code'=>false,'reason'=>200006,'file'=>__FILE__,'line'=>__LINE__)));
			exit();
		}			
		$files=json_decode($_POST['file_id']);
		foreach($files as $file_id)
		{
			try
			{
				jry_nd_direct_move_file_id($conn,$jry_wb_login_user,$file_id,$to);
			}catch (jry_wb_exception $e){}
		}
		echo json_encode(array('code'=>true,'lasttime'=>jry_wb_get_time()));
	}
	else if($action=='delete')
	{
		$files=json_decode($_POST['file_id']);
		$conn=jry_wb_connect_database();
		foreach($files as $file)
		{
			try
			{
				jry_nd_direct_delete_file_id($conn,$jry_wb_login_user,$file);
			}catch (jry_wb_exception $e){}
		}
		echo json_encode(array('login'=>true,'lasttime'=>jry_wb_get_time(),'code'=>true,'size_total'=>$jry_wb_login_user['nd_ei']['size_total'],'size_used'=>$jry_wb_login_user['nd_ei']['size_used']));	
	}
	else if($action=='share')
	{
		if(($file=jry_nd_database_get_file($conn,$jry_wb_login_user,$_POST['file_id']))===null)
		{
			echo (json_encode(array('code'=>false,'reason'=>200008,'file'=>__FILE__,'line'=>__LINE__)));
			exit();
		}
		jry_nd_direct_share($conn,$jry_wb_login_user,$file);
		echo json_encode(array('code'=>true,'lasttime'=>jry_wb_get_time()));
	}
	else if($action=='unshare')
	{
		if(($file=jry_nd_database_get_file($conn,$jry_wb_login_user,$_POST['file_id']))===null)
		{
			echo (json_encode(array('code'=>false,'reason'=>200008,'file'=>__FILE__,'line'=>__LINE__)));
			exit();
		}
		jry_nd_direct_unshare($conn,$jry_wb_login_user,$file);
		echo json_encode(array('code'=>true,'lasttime'=>jry_wb_get_time()));
	}
	else
	{
		echo json_encode(array('login'=>true,'code'=>false,'reason'=>000000));
	}
?>