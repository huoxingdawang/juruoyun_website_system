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
			($share_mode&&$jry_wb_login_user['id']!=-1&&$_GET['fast']==1&&$jry_wb_login_user['jry_wb_nd_extern_information']['fast_size']>$data[0]['size'])||
			((!$share_mode)&&$jry_wb_login_user['jry_wb_nd_extern_information']['fast_size']>$data[0]['size']&&$_GET['fast']==1))//可以高速下载
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
					if((($onearea['size']-$onearea['used'])>$min_use)&&($onearea['samearea']||(!$jry_wb_login_user['jry_wb_nd_extern_information']['sameareaonly'])))
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
			catch(jry_nd_exception $e)
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
	if($login=jry_wb_print_head("",true,true,true,array('use','usenetdisk'),false)!='ok')
	{
		echo json_encode(array('login'=>false,'reasion'=>$login));
		exit();			
	}
	if($action=='pre_check')
	{
		if($jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_allow_type']==-1||in_array($_POST['type'],$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_allow_type']))
			if(($jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_size_total']-$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_uploading_size']-((int)$_POST['total_size'])-$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_size_used'])>=0)
			{
				if($_POST['size']==''||$_POST['father']==''||$_POST['name']=='')
				{
					echo json_encode(array('login'=>true,'code'=>false,'reason'=>7));
					exit();
				}
				$conn=jry_wb_connect_database();
				if($_POST['father']!=0)
				{
					$st = $conn->prepare('SELECT file_id FROM '.constant('jry_wb_netdisk').'file_list WHERE `id`=? AND `file_id`=? AND `isdir`=1 AND `delete`=0');
					$st->bindValue(1,$jry_wb_login_user['id']);
					$st->bindValue(2,$_POST['father']);
					$st->execute();
					$data=$st->fetchAll();
					if(count($data)==0)
					{
						echo json_encode(array('login'=>true,'code'=>false,'reason'=>4));
						exit();
					}	
				}				
				$st = $conn->prepare('SELECT file_id FROM '.constant('jry_wb_netdisk').'file_list WHERE `id`=? AND `father`=? AND `name`=? AND `type`=? AND `delete`=0');
				$st->bindValue(1,$jry_wb_login_user['id']);
				$st->bindValue(2,$_POST['father']);
				$st->bindValue(3,str_replace("&","/37",$_POST['name']));
				$st->bindValue(4,$_POST['type']);
				$st->execute();
				$data=$st->fetchAll();
				if(count($data)!=0)
				{
					echo json_encode(array('login'=>true,'code'=>false,'reason'=>11));
					exit();
				}
				$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'users SET jry_nd_uploading_size=jry_nd_uploading_size+? , lasttime=? WHERE `id`=?;');
				$st->bindValue(1,$_POST['size']);
				$st->bindValue(2,jry_wb_get_time());
				$st->bindValue(3,$jry_wb_login_user['id']);
				$st->execute();
				
				$method=0;
				
				
				$areas=jry_nd_get_area_by_type($method);
				$area=$areas[0];
				$min_use=$areas[0]['size']-$areas[0]['used'];
				foreach($areas as $onearea)
				{
					if($onearea['upload']==0)
						continue;
					if((($onearea['size']-$onearea['used'])>$min_use)&&($onearea['samearea']||(!$jry_wb_login_user['jry_wb_nd_extern_information']['sameareaonly'])))
					{
						$min_use=$onearea['size']-$onearea['used'];
						$area=$onearea;
					}
				}
				$area['config_message']=json_decode($area['config_message']);
				if($min_use<$_POST['size'])
				{
					echo json_encode(array('login'=>true,'code'=>false,'reason'=>3));
					exit();
				}	
				$st = $conn->prepare('INSERT INTO '.constant('jry_wb_netdisk').'file_list (`id`,`father`,`name`,`type`,`area`,`size`,`lasttime`,`uploading`) VALUES (?,?,?,?,?,?,?,?)');
				$st->bindValue(1,$jry_wb_login_user['id']);
				$st->bindValue(2,$_POST['father']);
				$st->bindValue(3,$_POST['name']);
				$st->bindValue(4,$_POST['type']);
				$st->bindValue(5,$area['area_id']);
				$st->bindValue(6,$_POST['size']);
				$st->bindValue(7,jry_wb_get_time());
				$st->bindValue(8,1);
				$st->execute();
				$file_id=$conn->lastInsertId();
				if($method==0)
					$extern_message=[];
				else if($method==1)//阿里云STS签名
				{
					try
					{
						$extern_message=jry_nd_aly_upload_sign($area,$file_id);
					}
					catch (jry_nd_exception $e)
					{
						echo json_encode($e->getMessage());
						exit();
					}
				}
				echo json_encode(array('login'=>true,'code'=>true,'area'=>$area['area_id'],'file_id'=>$file_id,'method'=>$method,'extern_message'=>$extern_message));
			}
			else
				echo json_encode(array('login'=>true,'code'=>false,'reason'=>2));
		else
			echo json_encode(array('login'=>true,'code'=>false,'reason'=>1));	
	}
	else if($action=='upload')
	{			
		if($jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_allow_type']==-1||in_array($type,$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_allow_type']))
		{
			$conn=jry_wb_connect_database();
			$st = $conn->prepare('SELECT file_id,area FROM '.constant('jry_wb_netdisk').'file_list WHERE `id`=? AND `father`=? AND `name`=? AND `type`=? AND `size`=? AND `uploading`=?');
			$st->bindValue(1,$jry_wb_login_user['id']);
			$st->bindValue(2,$_POST['father']);
			$st->bindValue(3,str_replace("&","/37",$_POST['name']));
			$st->bindValue(4,$_POST['type']);
			$st->bindValue(5,$_POST['size']);
			$st->bindValue(6,1);
			$st->execute();
			$data=$st->fetchAll();
			if(count($data)==0)
			{
				echo json_encode(array('login'=>true,'code'=>false,'reason'=>4));
				exit();
			}
			if($data[0]['file_id']!=$_POST['file_id'])
			{
				echo json_encode(array('login'=>true,'code'=>false,'reason'=>5));
				exit();
			}
			$area=jry_nd_get_area_by_area_id($data[0]['area']);
			if($area==null)
			{
				echo json_encode(array('login'=>true,'code'=>false,'reason'=>6,'test'=>$data[0]['area']));
				exit();
			}
			if($area['type']!=0)
			{
				echo json_encode(array('login'=>true,'code'=>false,'reason'=>7));
				exit();
			}
			move_uploaded_file($_FILES['file']['tmp_name'],$area['config_message']->dir. constant('jry_nd_upload_file_prefix').$data[0]['file_id'].'-'.$_POST['index']);
			echo json_encode(array('login'=>true,'code'=>true));
		}
		else
			echo json_encode(array('login'=>true,'code'=>false,'reason'=>1));
	}
	else if($action=='merge')
	{
		if($jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_allow_type']==-1||in_array($type,$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_allow_type']))
		{
			$conn=jry_wb_connect_database();
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'file_list WHERE `id`=? AND `father`=? AND `name`=? AND `type`=? AND `size`=? AND `uploading`=?');
			$st->bindValue(1,$jry_wb_login_user['id']);
			$st->bindValue(2,$_POST['father']);
			$st->bindValue(3,str_replace("&","/37",$_POST['name']));
			$st->bindValue(4,$_POST['type']);
			$st->bindValue(5,$_POST['size']);
			$st->bindValue(6,1);
			$st->execute();
			$data=$st->fetchAll();
			if(count($data)==0)
			{
				echo json_encode(array('login'=>true,'code'=>false,'reason'=>4));
				exit();
			}
			if($data[0]['file_id']!=$_POST['file_id'])
			{
				echo json_encode(array('login'=>true,'code'=>false,'reason'=>5));
				exit();
			}
			$area=jry_nd_get_area_by_area_id($data[0]['area']);
			if($area==null)
			{
				echo json_encode(array('login'=>true,'code'=>false,'reason'=>6));
				exit();
			}
			
			$size=0;
			if($area['type']==0)
			{
				$target=$area['config_message']->dir.constant('jry_nd_upload_file_prefix').$data[0]['file_id'].'-';
				$dst=fopen($area['config_message']->dir.constant('jry_nd_upload_file_prefix').$data[0]['file_id'].'_jryupload','wb');
				for($i=0;$i<$_POST['index'];$i++) 
				{
					$slice=$target.$i;
					$src=fopen($slice, 'rb');
					stream_copy_to_stream($src, $dst);
					fclose($src);
					unlink($slice);
				}
				fclose($dst);
				$size=filesize($area['config_message']->dir.constant('jry_nd_upload_file_prefix').$data[0]['file_id'].'_jryupload');				
			}
			else if($area['type']==1)
			{
				
				$ossclient=jry_nd_aly_connect_out_by_area($area);
				if(!jry_nd_aly_check_file_exist($ossclient,$area,$area['config_message']->dir.constant('jry_nd_upload_file_prefix').$data[0]['file_id'].'_jryupload'))
				{
					echo json_encode(array('code'=>false,'reason'=>220002,'file'=>__FILE__,'line'=>__LINE__));
					exit();
				}
				$objectmeta=$ossclient->getObjectMeta($area['config_message']->bucket,$area['config_message']->dir.constant('jry_nd_upload_file_prefix').$data[0]['file_id'].'_jryupload');					
				$size=$objectmeta['content-length'];
			}
			if(abs(ceil($size/1024)-$_POST['size'])>10)
			{
				
				if($area['type']==0)
					unlink($area['config_message']->dir.constant('jry_nd_upload_file_prefix').$data[0]['file_id'].'_jryupload');
				else if($area['type']==1)
				{
					$ossclient = new OssClient($area['config_message']->accesskeyid,$area['config_message']->accesskeysecret,$area['config_message']->endpoint,false);
					$ossclient->deleteObject($area['config_message']->bucket,$area['config_message']->dir.constant('jry_nd_upload_file_prefix').$data['file_id'].'_jryupload');					
				}
				$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'users SET jry_nd_uploading_size=jry_nd_uploading_size-? , lasttime=? WHERE `id`=?;');
				$st->bindValue(1,$_POST['size']);
				$st->bindValue(2,jry_wb_get_time());
				$st->bindValue(3,$jry_wb_login_user['id']);
				$st->execute();
				$st = $conn->prepare('DELETE FROM '.constant('jry_wb_netdisk').'file_list WHERE  `file_id`=?;');
				$st->bindValue(1,$data[0]['file_id']);
				$st->execute();
				echo json_encode(array('login'=>true,'code'=>false,'reason'=>8));
				exit();
			}
			$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'users SET jry_nd_uploading_size=jry_nd_uploading_size-?,jry_nd_size_used=jry_nd_size_used+? , lasttime=? WHERE `id`=?;');
			$st->bindValue(1,$_POST['size']);
			$st->bindValue(2,ceil($size/1024));
			$st->bindValue(3,$lasttime=jry_wb_get_time());
			$st->bindValue(4,$jry_wb_login_user['id']);	
			$st->execute();
			$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET uploading=? , size=? WHERE `file_id`=? AND id=?');
			$st->bindValue(1,0);
			$st->bindValue(2,ceil($size/1024));
			$st->bindValue(3,$data[0]['file_id']);
			$st->bindValue(4,$jry_wb_login_user['id']);
			$st->execute();
			$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'area SET used=used+? , lasttime=? WHERE `area_id`=?;');
			$st->bindValue(1,ceil($size/1024));
			$st->bindValue(2,jry_wb_get_time());
			$st->bindValue(3,$data[0]['area']);	
			$st->execute();
			if(($file=fopen('jry_nd.fast_save_message','r'))==false)
			{
				$st = $conn->prepare('SELECT lasttime FROM '.constant('jry_wb_netdisk').'group ORDER BY lasttime DESC LIMIT 1;');	$st->execute();		$data['group']=$st->fetchAll()[0]['lasttime'];
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
			jry_wb_get_netdisk_information();
			echo json_encode(array('login'=>true,'lasttime'=>$lasttime,'code'=>true,'jry_nd_size_total'=>$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_size_total'],'jry_nd_size_used'=>$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_size_used']));
		}
		else
			echo json_encode(array('login'=>true,'code'=>false,'reason'=>1));
	}
	else if($action=='new_dir')
	{
		$conn=jry_wb_connect_database();	
		$st = $conn->prepare('INSERT INTO '.constant('jry_wb_netdisk').'file_list (`id`,`father`,`name`,`type`,`area`,`size`,`lasttime`,`uploading`,`isdir`) VALUES (?,?,?,?,?,?,?,?,?)');
		$st->bindValue(1,$jry_wb_login_user['id']);
		$st->bindValue(2,$_POST['father']);
		$st->bindValue(3,'新建文件夹'.jry_wb_get_time());
		$st->bindValue(4,'');
		$st->bindValue(5,1);
		$st->bindValue(6,0);
		$st->bindValue(7,$lasttime=jry_wb_get_time());
		$st->bindValue(8,0);
		$st->bindValue(9,1);
		$st->execute();
		$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'users SET lasttime=? WHERE `id`=?;');
		$st->bindValue(1,jry_wb_get_time());
		$st->bindValue(2,$lasttime=jry_wb_get_time());
		$st->execute();
		echo json_encode(array('login'=>true,'code'=>true,'lasttime'=>$lasttime));
	}
	else if($action=='rename')
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'file_list WHERE `id`=? AND `file_id`=? AND `delete`=0 LIMIT 1');
		$st->bindValue(1,$jry_wb_login_user['id']);
		$st->bindValue(2,$_POST['file_id']);
		$st->execute();
		$data=$st->fetchAll();
		if(count($data)==0)
		{
			echo json_encode(array('login'=>true,'code'=>false,'reason'=>11));
			exit();
		}
		$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET type=? , name=? ,lasttime=? WHERE `file_id`=? AND id=? LIMIT 1');
		$st->bindValue(1,$_POST['type']);
		$st->bindValue(2,str_replace("&","/37",$_POST['name']));
		$st->bindValue(3,$lasttime=jry_wb_get_time());
		$st->bindValue(4,$_POST['file_id']);
		$st->bindValue(5,$jry_wb_login_user['id']);
		$st->execute();
		$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'users SET lasttime=? WHERE `id`=? LIMIT 1;');
		$st->bindValue(1,$lasttime=jry_wb_get_time());
		$st->bindValue(2,$jry_wb_login_user['id']);	
		$st->execute();
		echo json_encode(array('login'=>true,'code'=>true,'lasttime'=>$lasttime));
	}
	else if($action=='move')
	{
		$files=json_decode($_POST['file_id']);
		$conn=jry_wb_connect_database();
		foreach($files as $file)
		{
			$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET `father`=? , lasttime=? WHERE `file_id`=? AND id=?');
			$st->bindValue(1,$_POST['to']);
			$st->bindValue(2,$lasttime=jry_wb_get_time());
			$st->bindValue(3,$file);
			$st->bindValue(4,$jry_wb_login_user['id']);
			$st->execute();			
		}
		$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'users SET lasttime=? WHERE `id`=?;');
		$st->bindValue(1,$lasttime=jry_wb_get_time());
		$st->bindValue(2,$jry_wb_login_user['id']);
		$st->execute();
		echo json_encode(array('login'=>true,'lasttime'=>$lasttime,'code'=>true));
	}
	else if($action=='delete')
	{
		function delete_one($file_id,$data)
		{
			global $jry_wb_login_user;
			$conn=jry_wb_connect_database();
			if($file_id!=0)
			{
				$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'file_list WHERE `id`=? AND `file_id`=? limit 1');
				$st->bindValue(1,$jry_wb_login_user['id']);
				$st->bindValue(2,$file_id);
				$st->execute();
				$data=$st->fetchAll();
				if(count($data)==0)
				{
					return;
				}
				$data=$data[0];
			}
			if($data['delete'])
				return 0;
			$area=jry_nd_get_area_by_area_id($data['area']);
			if($data['share'])
			{
				$st = $conn->prepare('DELETE FROM '.constant('jry_wb_netdisk').'share WHERE id=? AND file_id=?');
				$st->bindValue(1,$jry_wb_login_user['id']);
				$st->bindValue(2,$data['file_id']);
				$st->execute();
			}
			if($data['isdir'])
			{
				$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET size=? , `delete`=? ,lasttime=? WHERE `file_id`=? AND id=?');
				$st->bindValue(1,0);
				$st->bindValue(2,1);
				$st->bindValue(3,$lasttime=jry_wb_get_time());
				$st->bindValue(4,$data['file_id']);
				$st->bindValue(5,$jry_wb_login_user['id']);
				$st->execute();				
				$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'file_list WHERE `id`=? AND `father`=?');
				$st->bindValue(1,$jry_wb_login_user['id']);
				$st->bindValue(2,$data['file_id']);
				$st->execute();
				$data=$st->fetchAll();
				$total=0;
				foreach($data as $one)
					$total+=delete_one(0,$one);
				return $total;
			}
			else
			{
				if($area['type']==0)
					unlink($area['config_message']->dir.constant('jry_nd_upload_file_prefix').$data['file_id'].'_jryupload');
				else if($area['type']==1)
				{
					$ossclient = new OssClient($area['config_message']->accesskeyid,$area['config_message']->accesskeysecret,$area['config_message']->endpoint,false);
					$ossclient->deleteObject($area['config_message']->bucket,$area['config_message']->dir.constant('jry_nd_upload_file_prefix').$data['file_id'].'_jryupload');
				}
				$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET size=? , `delete`=? ,lasttime=? WHERE `file_id`=? AND id=?');
				$st->bindValue(1,0);
				$st->bindValue(2,1);
				$st->bindValue(3,$lasttime=jry_wb_get_time());
				$st->bindValue(4,$data['file_id']);
				$st->bindValue(5,$jry_wb_login_user['id']);
				$st->execute();
				if($data['uploading'])
				{
					$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'users SET lasttime=?,jry_nd_uploading_size=jry_nd_uploading_size-? WHERE `id`=?;');
					$st->bindValue(1,$lasttime=jry_wb_get_time());
					$st->bindValue(2,$data['size']);
					$st->bindValue(3,$jry_wb_login_user['id']);	
					$st->execute();					
					return 0;
				}
				$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'area SET used=used-? , lasttime=? WHERE `area_id`=?;');
				$st->bindValue(1,$data['size']);
				$st->bindValue(2,jry_wb_get_time());
				$st->bindValue(3,$data['area']);	
				$st->execute();				
				return $data['size'];
			}
		}
		$files=json_decode($_POST['file_id']);
		$conn=jry_wb_connect_database();
		foreach($files as $file)
		{
			$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'users SET lasttime=?,jry_nd_size_used=jry_nd_size_used-? WHERE `id`=?;');
			$st->bindValue(1,$lasttime=jry_wb_get_time());
			$st->bindValue(2,delete_one($file,1));
			$st->bindValue(3,$jry_wb_login_user['id']);	
			$st->execute();
		}
		jry_wb_get_netdisk_information();
		echo json_encode(array('login'=>true,'lasttime'=>$lasttime,'code'=>true,'jry_nd_size_total'=>$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_size_total'],'jry_nd_size_used'=>$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_size_used']));
		
	}
	else if($action=='share'||$action=='unshare')
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'file_list WHERE `id`=? AND `file_id`=? AND `delete`=0 limit 1');
		$st->bindValue(1,$jry_wb_login_user['id']);
		$st->bindValue(2,$_POST['file_id']);
		$st->execute();
		$data=$st->fetchAll();
		if(count($data)==0)
		{
			echo json_encode(array('login'=>true,'code'=>false,'reason'=>12));
			exit();
		}
		if($action=='share')
		{
			$srcstr = '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
			$code='';
			mt_srand();
			for ($i=0;$i<64; $i++) 
				$code.=$srcstr[mt_rand(0,strlen($srcstr)-1)];	
			$st = $conn->prepare('INSERT INTO '.constant('jry_wb_netdisk').'share (`id`,`key`,`file_id`,`lasttime`) VALUES (?,?,?,?)');
			$st->bindValue(1,$jry_wb_login_user['id']);
			$st->bindValue(2,$code);
			$st->bindValue(3,$data[0]['file_id']);
			$st->bindValue(4,jry_wb_get_time());
			$st->execute();			
		}
		else
		{
			$st = $conn->prepare('DELETE FROM '.constant('jry_wb_netdisk').'share WHERE  id=? AND file_id=?');
			$st->bindValue(1,$jry_wb_login_user['id']);
			$st->bindValue(2,$data[0]['file_id']);
			$st->execute();			
		}
		$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET `share`=?,`lasttime`=? WHERE `file_id`=? AND id=?');
		$st->bindValue(1,($action=='share')?1:0);
		$st->bindValue(2,$lasttime=jry_wb_get_time());
		$st->bindValue(3,$data[0]['file_id']);
		$st->bindValue(4,$jry_wb_login_user['id']);
		$st->execute();
		$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'users SET lasttime=? WHERE `id`=?;');
		$st->bindValue(1,$lasttime=jry_wb_get_time());
		$st->bindValue(2,$jry_wb_login_user['id']);	
		$st->execute();
		echo json_encode(array('login'=>true,'code'=>true,'lasttime'=>$lasttime));
	}
	else
	{
		echo json_encode(array('login'=>true,'code'=>false,'reason'=>9));
	}
?>