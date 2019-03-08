<?php 
	include_once("../tools/jry_wb_includes.php");
	$st =jry_wb_connect_database()->prepare("DELETE FROM ".constant('blogdb')."text where lasttime<? AND `delete` =1");
	$st->bindParam(1,date("Y-m-d H;i:s",time()-constant('logintime')));
	$st->execute();		
	$action=$_GET['action'];
	if($action=='get_blog_list')
	{
		$conn=jry_wb_connect_database();
		$q ="SELECT * FROM ".constant('blogdb')."text where lasttime>? ORDER BY lasttime DESC"; 
		$st = $conn->prepare($q);
		$st->bindParam(1,urldecode($_GET['lasttime']));
		$st->execute();				
		$data=$st->fetchAll();
		$total=count($data);
		for($i=0;$i<$total;$i++)
		{
			if($data[$i]['ifshow'])
			{
				$json[$i]=	array(	'blog_id'=>$data[$i]['blog_id'],
									'title'=>$data[$i]['title'],
									'lasttime'=>$data[$i]['lasttime'],
									'show'=>$data[$i]['ifshow'],
									'delete'=>$data[$i]['delete'],
									'id'=>$data[$i]['id']
									);
			}
			else
			{
				$json[$i]=	array(	'blog_id'=>$data[$i]['blog_id'],
									'lasttime'=>$data[$i]['lasttime'],
									'delete'=>$data[$i]['delete'],
									'show'=>$data[$i]['ifshow']
									);				
			}
		}
		echo json_encode($json);	
		exit();
	} 
	if($action=='get_blog_one')
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare("SELECT * FROM ".constant('blogdb')."text where blog_id=?");
		$st->bindParam(1,$_GET['blog_id']);
		$st->execute();			
		foreach($st->fetchAll() as $data);
		if($data['ifshow'])
		{
			echo json_encode(array(	'blog_id'=>$data['blog_id'],
									'data'=>json_decode($data['data']),
									'lasttime'=>$data['lasttime'],
									'ifshow'=>$data['ifshow'],
									'delete'=>$data['delete'],
									'id'=>$data['id']
									));
			$st = $conn->prepare('INSERT INTO '.constant('jry_wb_database_log').'blog_reading (`id`,`blog_id`,`time`,`ip`,`device`,`browser`) VALUES (?,?,?,?,?,?);');
			$st->bindValue(1,$jry_wb_login_user['id']);
			$st->bindValue(2,$_GET['blog_id']);
			$st->bindValue(3,jry_wb_get_time());
			$st->bindValue(4,$_SERVER['REMOTE_ADDR']);
			$st->bindValue(5,jry_wb_get_device(true));
			$st->bindValue(6,jry_wb_get_browser(true));
			$st->execute();
			$st = $conn->prepare("UPDATE ".constant('blogdb')."text SET readingcount = readingcount+1 ,lasttime=? where blog_id = ?");
			$st->bindParam(1,jry_wb_get_time());
			$st->bindParam(2,intval($_GET['blog_id']));
			$st->execute();			
		}
		else
			echo json_encode(array('blog_id'=>$data['blog_id'],'ifshow'=>$data['ifshow']));
		exit();
	}	
	$login=	jry_wb_print_head("",true,true,false,array('use'),false);
	if($login!='ok')
	{
		echo json_encode(array('login'=>false,'reasion'=>$login));
		exit();			
	}
	if($action=='get_draft_list')
	{
		$conn=jry_wb_connect_database();
		$q ="SELECT * FROM ".constant('blogdb')."text where id=? AND lasttime>? ORDER BY lasttime DESC"; 
		$st = $conn->prepare($q);
		$st->bindParam(1,$jry_wb_login_user['id']);
		$st->bindParam(2,urldecode($_GET['lasttime']));
		$st->execute();				
		$data=$st->fetchAll();
		$total=count($data);
		for($i=0;$i<$total;$i++)
		{
			$json[$i]=	array(	'blog_id'=>$data[$i]['blog_id'],
								'title'=>$data[$i]['title'],
								'lasttime'=>$data[$i]['lasttime'],
								'delete'=>$data[$i]['delete'],
								'show'=>$data[$i]['ifshow']
								);
		}
		echo json_encode($json);	
		exit();
	} 	
	if($action=='get_draft_one')
	{
		$conn=jry_wb_connect_database();
		$q ="SELECT * FROM ".constant('blogdb')."text where id=? AND blog_id=? ORDER BY lasttime DESC";
		$st = $conn->prepare($q);
		$st->bindParam(1,$jry_wb_login_user['id']);
		$st->bindParam(2,$_GET['blog_id']);
		$st->execute();			
		foreach($st->fetchAll() as $data);		
		echo json_encode(array(	'blog_id'=>$data['blog_id'],
								'title'=>$data['title'],
								'delete'=>$data['delete'],
								'data'=>json_decode($data['data']),
								'lasttime'=>$data['lasttime'],
								'show'=>$data['ifshow']
								));			
	}
?>