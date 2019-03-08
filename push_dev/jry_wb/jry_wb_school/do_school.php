<?php
	include_once("../tools/jry_wb_includes.php");
	if($login=jry_wb_print_head("",true,true,true,array('use','useschool'),false)!='ok')
	{
		echo json_encode(array('login'=>false,'reasion'=>$login));
		exit();			
	}
	$action=$_GET['action'];
	if($action=='join')
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_school').'list WHERE school_id=? LIMIT 1');
		$st->bindParam(1,$_POST['school_id']);
		$st->execute();	
		$data=$st->fetchAll();
		if(count($data)==0)
		{
			echo json_encode(array('login'=>true,'result'=>false,'reason'=>'1'));
			exit();
		}
		$data=$data[0];
		if($data['allow']==0)
		{
			echo json_encode(array('login'=>true,'result'=>false,'reason'=>'2'));
			exit();
		}
		if($data['check']==1)
		{
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_school').'join WHERE school_id=? AND id=? LIMIT 1');
			$st = $conn->prepare($q);
			$st->bindParam(1,$_POST['school_id']);
			$st->bindParam(2,$jry_wb_login_user['id']);
			$st->execute();	
			$data=$st->fetchAll();
			if(count($data)!=0)
			{
				echo json_encode(array('login'=>true,'result'=>false,'reason'=>'3'));
				exit();
			}
			$st = $conn->prepare("INSERT INTO ".constant('jry_wb_school')."join (school_id,id,sex,time,extern,name) VALUES (?,?,?,?,?,?)");
			$st->bindParam(1,$_POST['school_id']);
			$st->bindParam(2,$jry_wb_login_user['id']);
			$st->bindParam(3,$_POST['sex']);
			$st->bindParam(4,jry_wb_get_time());
			$st->bindParam(5,$_POST['extern']);
			$st->bindParam(6,$_POST['name']);
			$st->execute();
			$st = $conn->prepare("UPDATE ".constant('jry_wb_school')."list SET waiting=waiting+1 WHERE school_id=?");
			$st->bindParam(1,$_POST['school_id']);
			$st->execute();			
			echo json_encode(array('login'=>true,'result'=>true,'reason'=>1));
		}
		else
		{
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_school').'student WHERE school_id=? AND id=? LIMIT 1');
			$st = $conn->prepare($q);
			$st->bindParam(1,$_POST['school_id']);
			$st->bindParam(2,$jry_wb_login_user['id']);
			$st->execute();	
			$data=$st->fetchAll();
			if(count($data)!=0)
			{
				echo json_encode(array('login'=>true,'result'=>false,'reason'=>'3'));
				exit();
			}			
			$st = $conn->prepare("INSERT INTO ".constant('jry_wb_school')."student (school_id,id,sex,time,name) VALUES (?,?,?,?,?)");
			$st->bindParam(1,$_POST['school_id']);
			$st->bindParam(2,$jry_wb_login_user['id']);
			$st->bindParam(3,$_POST['sex']);
			$st->bindParam(4,jry_wb_get_time());
			$st->bindParam(5,$_POST['name']);
			$st->execute();
			$st = $conn->prepare("UPDATE ".constant('jry_wb_school')."list SET number=number+1 WHERE school_id=?");
			$st->bindParam(1,$_POST['school_id']);
			$st->execute();
			echo json_encode(array('login'=>true,'result'=>true,'reason'=>2));
		}	
	}
?>