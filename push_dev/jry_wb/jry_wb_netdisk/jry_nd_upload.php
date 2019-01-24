<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_netdisk.php");
	include_once("jry_wb_nd_tools.php");	
	if(jry_wb_print_head("",true,true,true,array('use','usenetdisk'),false)!='ok')
	{
		echo json_encode(array('login'=>false,'reasion'=>$login));
		exit();			
	}
	jry_wb_get_netdisk_information();
	$action=$_GET['action'];
	if($action=='pre_check')
	{
		if($jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_size_used']+((int)$_POST['total_size'])>=$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_size_total'])
		{
			echo json_encode(array('login'=>true,'code'=>false,'reason'=>1));
			exit();
		}
		if($jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_allow_type']!=-1)
		{
			$all_type=json_decode($_POST['all_type']);
			foreach($all_type as $type)
				if(!in_array($type,$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_allow_type']))
				{
					echo json_encode(array('login'=>true,'code'=>false,'reason'=>2));
					exit();
				}				
		}
		echo json_encode(array('login'=>true,'code'=>true));
	}
	else if($action=='upload')
	{
		$type=strtolower(trim(strrchr($_POST["name"], '.'),'.'));
		if($jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_allow_type']==-1||in_array($type,$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_allow_type']))
			move_uploaded_file($_FILES['file']['tmp_name'],constant('jry_nd_upload_file_address').constant('jry_nd_upload_file_prefix').iconv("utf-8","gbk",$_POST["name"]).'-'.$_POST['index']);
		else
			echo -1;
	}
	else if($action='merge')
	{
		$type=strtolower(trim(strrchr($_POST["name"], '.'),'.'));
		if($jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_allow_type']==-1||in_array($type,$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_allow_type']))
		{
			$target=constant('jry_nd_upload_file_address').constant('jry_nd_upload_file_prefix').iconv("utf-8","gbk",$_POST["name"]);
			$dst=fopen(constant('jry_nd_upload_file_address').constant('jry_nd_upload_file_prefix').iconv("utf-8","gbk",$name.'.'.$type).'_jryupload','wb');
			for($i=0;$i<$_POST['index'];$i++) 
			{
				$slice=$target.'-'.$i;
				$src=fopen($slice, 'rb');
				stream_copy_to_stream($src, $dst);
				fclose($src);
				unlink($slice);
			}
			fclose($dst);
		}
		else
			echo -1;	
	}
?>