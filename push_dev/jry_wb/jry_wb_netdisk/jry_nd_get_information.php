<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_netdisk.php");
	include_once("jry_wb_nd_tools.php");
	if(jry_wb_print_head("",true,true,true,array('use','usenetdisk'),false)!='ok')
	{
		echo json_encode(array('login'=>false,'reasion'=>$login));
		exit();			
	}
	$action=$_GET['action'];	
	jry_wb_get_netdisk_information();
	$conn=jry_wb_connect_database();
	if(($file=fopen('jry_nd.fast_save_message','r'))==false)
	{
		$st = $conn->prepare('SELECT lasttime FROM '.constant('jry_wb_netdisk').'area ORDER BY lasttime DESC LIMIT 1;');	$st->execute();		$data['area']=$st->fetchAll()[0]['lasttime'];
		$st = $conn->prepare('SELECT lasttime FROM '.constant('jry_wb_netdisk').'group ORDER BY lasttime DESC LIMIT 1;');	$st->execute();		$data['group']=$st->fetchAll()[0]['lasttime'];
		$file=fopen('jry_nd.fast_save_message','w');
		fwrite($file,json_encode($data));
		fclose($file);
		$data['new']=true;
	}
	else
	{
		$data=json_decode(fread($file,filesize('jry_nd.fast_save_message')));
		fclose($file);
	}
	if($action=='area')
	{
		if((urldecode($_GET['lasttime']))>($data->area))
			echo json_encode(null);
		else
		{
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'area WHERE lasttime>?;');
			$st->bindParam(1,urldecode($_GET['lasttime']));
			$st->execute();
			$ans=[];
			$data=$st->fetchAll();
			$n=count($data);			
			for($i=0;$i<$n;$i++)
				$ans[$i]=array(	'area_id'=>$data[$i]['area_id'],
								'id'=>$data[$i]['id'],
								'name'=>$data[$i]['name'],
								'fast'=>$data[$i]['fast'],
								'type'=>$data[$i]['type'],
								'lasttime'=>$data[$i]['lasttime']);
			echo json_encode($ans);
		}
		exit();
	}
	if($action=='group')
	{
		if((urldecode($_GET['lasttime']))>($data->group))
			echo json_encode(null);
		else
		{
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'group WHERE lasttime>?;');
			$st->bindParam(1,urldecode($_GET['lasttime']));
			$st->execute();
			$ans=[];
			$data=$st->fetchAll();
			$n=count($data);
			for($i=0;$i<$n;$i++)
				$ans[$i]=array(	'jry_nd_group_id'=>$data[$i]['jry_nd_group_id'],
								'jry_nd_group_name'=>$data[$i]['jry_nd_group_name'],
								'jry_nd_group_type'=>$data[$i]['jry_nd_group_type'],
								'lasttime'=>$data[$i]['lasttime']);
			echo json_encode($ans);
		}
		exit();
	}
	if($action=='file_list')
	{
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'file_list WHERE lasttime>? AND id=?;');
		$st->bindValue(1,date('Y-m-d H:i:s',strtotime(urldecode($_GET['lasttime']))));
		$st->bindValue(2,$jry_wb_login_user['id']);
		$st->execute();
		$ans=[];
		$data=$st->fetchAll();
		$n=count($data);
		if($n==0)
		{
			echo json_encode(null);		
			exit();
		}
		for($i=0;$i<$n;$i++)
			$ans[$i]=array(	'file_id'=>$data[$i]['file_id'],
							'id'=>$data[$i]['id'],
							'dir'=>$data[$i]['dir'],
							'name'=>$data[$i]['name'],
							'type'=>$data[$i]['type'],
							'area'=>$data[$i]['area'],
							'size'=>$data[$i]['size'],
							'download_times'=>$data[$i]['download_times'],
							'toll_flow'=>$data[$i]['toll_flow'],
							'lasttime'=>$data[$i]['lasttime']);
		echo json_encode($ans);		
		exit();
	}
	
?>