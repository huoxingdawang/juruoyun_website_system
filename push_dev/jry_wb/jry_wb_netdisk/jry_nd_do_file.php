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
	include_once("jry_nd_includes.php");
	use Sts\Request\V20150401 as Sts;
	use OSS\OssClient;
	use OSS\Core\OssException;
	if($jry_wb_login_user['id']!=-1)
		jry_wb_get_netdisk_information();
	$action=$_GET['action'];
	$conn=jry_wb_connect_database();
	if($action=='open'||$action=='download')
	{
		try
		{
			$share_mode=false;
			if(($share=jry_nd_database_get_share($conn,$_GET['share_id'],$_GET['key']==''?'':$_GET['key'],$_GET['file_id']))!=false)
			{
				$share_mode=true;
				$file=$share['file'];
				$area=$share['area'];
			}
			if(!$share_mode)
			{
				if(($file=jry_nd_database_get_file($conn,$jry_wb_login_user,$_GET['file_id']))===null)
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200008,'file'=>__FILE__,'line'=>__LINE__)));
				if(($area=jry_nd_database_get_area($conn,$file['area']))===null)
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200000,'file'=>__FILE__,'line'=>__LINE__)));
			}
			$by='';
			$fast_mode=jry_nd_database_check_fast($conn,$area,$share_mode,$jry_wb_login_user,$file,$by,($share_mode?$share['user']:null));
			if($share_mode)
				if($by=='user')
					$kou_user=$jry_wb_login_user;
				else
					$kou_user=$share['user'];
			else
				$kou_user=$jry_wb_login_user;			
			$file['extern']=json_decode($file['extern']);
//			var_dump($share_mode);var_dump($fast_mode);var_dump($by);print_r($file);print_r($area);	print_r($kou_user);
			if(!$fast_mode&&$file['size']>1000)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200009,'file'=>__FILE__,'line'=>__LINE__)));
			if($area['type']==0)
			{
				if(!$fast_mode)
				{
					jry_nd_direct_set_head($action,$file);
					echo jry_nd_local_read_file($area,$file);
				}
				else
				{
					$ossclient_in	=jry_nd_aly_connect_in_by_area($area['faster_area']);
					$ossclient		=jry_nd_aly_connect_out_by_area($area['faster_area']);
					if(($new=jry_nd_direct_check_new_fast($area,$file,$action))!==true)
						$tobject=$area['faster_area']['config_message']->dir.constant('jry_nd_upload_file_prefix').$file['file_id'].'_jryupload'.$new;
					if($new===true)
					{
						$code=jry_nd_aly_upload_fast_buf($ossclient_in,$area,$file);
						jry_nd_database_set_file_extern($conn,$file,$action,$code);
						$tobject=$area['faster_area']['config_message']->dir.constant('jry_nd_upload_file_prefix').$file['file_id'].'_jryupload'.$code;					
					}
					$time=$file['size']/constant('jry_nd_min_speed');
					$time=max($time,60*5);
					if($time<60)
						$time='+'.$time.' seconds';
					else if($time<60*60)
						$time='+'.ceil($time/60).' minutes';
					else if($time<60*60*24)
						$time='+'.ceil($time/60/60).' hours';
					else
						$time='+'.ceil($time/60/60/24).' days';
					if($action=='open')
						$copyoptions=array(OssClient::OSS_HEADERS=>array('Expires'=>date("Y-m-d H:i:s",strtotime($time)),'Content-Type'=>jry_nd_get_content_type($file['type']),'Content-Disposition'=>'filename="'.urlencode($file['name']).'.'.$file['type'].'"'));
					if($action=='download')
						$copyoptions=array(OssClient::OSS_HEADERS=>array('Expires'=>date("Y-m-d H:i:s",strtotime($time)),'Content-Type'=>jry_nd_get_content_type($file['type']),'Content-Disposition'=>'attachment; filename="'.urlencode($file['name']).'.'.$file['type'].'"'));
					$ossclient_in->copyObject($area['faster_area']['config_message']->bucket,$tobject,$area['faster_area']['config_message']->bucket, $tobject,$copyoptions);						
					jry_nd_database_operate_user_fast($conn,$kou_user,-$file['size']);
					jry_nd_aly_download_sign($ossclient,$area['faster_area'],$tobject,true);						
				}
			}
			else if($area['type']==1)
			{				
				if(!$fast_mode)
				{
					$ossclient_in=jry_nd_aly_connect_in_by_area($area);					
					jry_nd_direct_set_head($action,$file);	
					print(jry_nd_aly_read_file($ossclient_in,$area,$file));
				}
				else
				{
					$ossclient_in	=jry_nd_aly_connect_in_by_area($area);
					$ossclient		=jry_nd_aly_connect_out_by_area($area);
					$fromobject=$area['config_message']->dir.constant('jry_nd_upload_file_prefix').$file['file_id'].'_jryupload';
					if(($new=jry_nd_direct_check_new_fast($area,$file,$action))!==true)
						$tobject=$fromobject.$new;
					$time=$file['size']/constant('jry_nd_min_speed');
					$time=max($time,60*5);
					if($time<60)
						$time='+'.$time.' seconds';
					else if($time<60*60)
						$time='+'.ceil($time/60).' minutes';
					else if($time<60*60*24)
						$time='+'.ceil($time/60/60).' hours';
					else
						$time='+'.ceil($time/60/60/24).' days';
					if($action=='open')
						$copyoptions=array(OssClient::OSS_HEADERS=>array('Expires'=>date("Y-m-d H:i:s",strtotime($time)),'Content-Type'=>jry_nd_get_content_type($file['type']),'Content-Disposition'=>'filename="'.urlencode($file['name']).'.'.$file['type'].'"'));
					if($action=='download')
						$copyoptions=array(OssClient::OSS_HEADERS=>array('Expires'=>date("Y-m-d H:i:s",strtotime($time)),'Content-Type'=>jry_nd_get_content_type($file['type']),'Content-Disposition'=>'attachment; filename="'.urlencode($file['name']).'.'.$file['type'].'"'));
					if($new===true)
					{
						$code=jry_wb_get_random_string(30);
						$tobject=$fromobject.$code;
						$ossclient_in->copyObject($area['config_message']->bucket,$fromobject,$area['config_message']->bucket,$tobject,$copyoptions);
						jry_nd_database_set_file_extern($conn,$file,$action,$code);
					}
					$ossclient_in->copyObject($area['config_message']->bucket,$tobject,$area['config_message']->bucket, $tobject,$copyoptions);	
					jry_nd_database_operate_user_fast($conn,$kou_user,-$file['size']);
					jry_nd_aly_download_sign($ossclient,$area,$tobject,true);			
				}
				
			}
			exit();
		}
		catch(jry_wb_exception $e)
		{
			$data=json_decode($error=$e->getMessage());
			switch($data->reason)
			{
				case 230000:
				case 200008:
				case 200001:
					header('HTTP/1.1 404 Not Found'); 
					header("status: 404 Not Found"); 
					include('../../404.php');
					break;
				case 200009:
					echo $error;
					break;
				default	:
					echo $error;
			}
			exit();
		}		
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
	else if($action=='add_size')
	{
		$size=(int)$_GET['size'];
		$size=max(0,$size);
		if($ok=jry_wb_set_green_money($conn,$jry_wb_login_user,-($size/constant('jry_nd_price_size')),constant('jry_wb_log_type_green_money_pay_nd_size')))
			jry_nd_database_operate_user_size($conn,$jry_wb_login_user,$size);
		echo json_encode(array('code'=>$ok,'reason'=>300002,'lasttime'=>jry_wb_get_time(),'size_total'=>$jry_wb_login_user['nd_ei']['size_total'],'green_money'=>$jry_wb_login_user['green_money']));
	}
	else if($action=='add_fast_size')
	{
		$size=(int)$_GET['size'];
		$size=max(0,$size);
		if($ok=jry_wb_set_green_money($conn,$jry_wb_login_user,-($size/constant('jry_nd_price_fast_size')),constant('jry_wb_log_type_green_money_pay_nd_size')))
			jry_nd_database_operate_user_fast($conn,$jry_wb_login_user,$size);
		echo json_encode(array('code'=>$ok,'reason'=>300002,'lasttime'=>jry_wb_get_time(),'fast_size'=>$jry_wb_login_user['nd_ei']['fast_size'],'green_money'=>$jry_wb_login_user['green_money']));		
	}	
	else
	{
		echo json_encode(array('login'=>true,'code'=>false,'reason'=>000000));
	}
?>