<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_netdisk.php");
	include_once("jry_wb_nd_tools.php");
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
				$ans[$i]=array(	'group_id'=>$data[$i]['group_id'],
								'group_name'=>$data[$i]['group_name'],
								'jry_nd_group_type'=>$data[$i]['jry_nd_group_type'],
								'lasttime'=>$data[$i]['lasttime']);
			echo json_encode($ans);
		}
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
							'father'=>$data[$i]['father'],
							'name'=>$data[$i]['name'],
							'type'=>$data[$i]['type'],
							'area'=>$data[$i]['area'],
							'size'=>$data[$i]['size'],
							'download_times'=>$data[$i]['download_times'],
							'uploading'=>$data[$i]['uploading'],
							'trust'=>$data[$i]['trust'],
							'toll_flow'=>$data[$i]['toll_flow'],
							'delete'=>$data[$i]['delete'],
							'isdir'=>$data[$i]['isdir'],
							'share'=>$data[$i]['share'],
							'self_share'=>$data[$i]['self_share'],
							'share_list'=>json_decode($data[$i]['share_list']),
							'lasttime'=>$data[$i]['lasttime']);
		echo json_encode($ans);		
		exit();
	}
	if($action=='share')
	{
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'share WHERE id=? AND file_id=?;');
		$st->bindValue(1,$jry_wb_login_user['id']);
		$st->bindValue(2,$_POST['file_id']);
		$st->execute();
		$ans=[];
		foreach($st->fetchAll() as $data)
			$ans[]=array(	'file_id'=>$data['file_id'],
							'share_id'=>$data['share_id'],
							'key'=>$data['key'],
							'fastdownload'=>$data['fastdownload'],
							'requesturl'=>$data['requesturl'],
							'lasttime'=>$data['lasttime']);
		echo json_encode($ans);		
		exit();
	}
	if($action=='share_list')
	{
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_netdisk').'share WHERE id=?;');
		$st->bindValue(1,$jry_wb_login_user['id']);
		$st->execute();
		$ans=[];
		foreach($st->fetchAll() as $data)
			$ans[]=array(	'file_id'=>$data['file_id'],
							'share_id'=>$data['share_id'],
							'key'=>$data['key'],
							'fastdownload'=>$data['fastdownload'],
							'requesturl'=>$data['requesturl'],
							'lasttime'=>$data['lasttime']);
		echo json_encode($ans);		
		exit();
	}	
?>