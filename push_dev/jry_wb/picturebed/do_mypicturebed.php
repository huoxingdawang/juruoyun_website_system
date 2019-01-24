<?php 
	include_once("../tools/jry_wb_includes.php");
	if(jry_wb_print_head("",true,true,true,array('use','usepicturebed'),false)!='ok')
	{
		echo json_encode(array('login'=>false,'reasion'=>$login));
		exit();			
	}
	$action=$_GET['action'];
	$mode=($jry_wb_login_user['managepicturebed'])?$_GET['mode']:'';
	$conn=jry_wb_connect_database();
	if($action=='get')
	{	
		if($mode!='admin')
		{
			$st=$conn->prepare('SELECT pictureid,time,id FROM '.constant('picturebeddb').'map WHERE id=?');
			$st->bindParam(1,$jry_wb_login_user['id']);
		}
		else
			$st=$conn->prepare('SELECT pictureid,time,id FROM '.constant('picturebeddb').'map');
		$st->execute(); 
		$data=$st->fetchAll();
		$total=count($data);
		for($i=0;$i<$total;$i++)
		{
			$json[$i]=	array(	'pictureid'=>$data[$i]['pictureid'],
								'time'=>$data[$i]['time'],
								'id'=>$data[$i]['id']
								);
		}
		echo json_encode($json);
	}
	if($action=='delate')
	{
		if($mode!='admin')
		{
			$st = $conn->prepare('SELECT * FROM '.constant('picturebeddb').'map where pictureid=? AND id=? LIMIT 1');
			$st->bindParam(1,$_GET['pictureid']);
			$st->bindParam(2,$jry_wb_login_user['id']);
		}
		else
		{
			$st = $conn->prepare('SELECT * FROM '.constant('picturebeddb').'map where pictureid=? LIMIT 1');
			$st->bindParam(1,$_GET['pictureid']);
		}
		$st->execute(); 
		foreach($st->fetchAll()as $photo);
		if($photo!=null)
		{			
			$filename = "../../data/uploads/".$photo['pictureid'].'.'.$photo['type']."_jryupload";
			unlink($filename);
			$q='DELETE FROM '.constant('picturebeddb').'map WHERE pictureid=?';
			$st=$conn->prepare($q);
			$st->bindParam(1,$photo['pictureid']);
			$st->execute();
			echo json_encode(array('state'=>true));
		}
		else
			echo json_encode(array('state'=>false,'reason'=>'您不是创建人'));
	}
?>