<?php 
	include_once("../jry_wb_tools/jry_wb_includes.php");
	$st =jry_wb_connect_database()->prepare("DELETE FROM ".JRY_WB_DATABASE_BLOG."text where lasttime<? AND `delete` =1");
	$st->bindParam(1,date("Y-m-d H;i:s",time()-JRY_WB_LOGIN_TIME));
	$st->execute();	
	try{jry_wb_check_compentence(NULL,array('use','editorblog'));}catch(jry_wb_exception $e){echo $e->getMessage();exit();}
	$action=$_GET['action'];
	if($action=='save_as_draft')
	{
		$conn=jry_wb_connect_database();
		$q ="UPDATE ".JRY_WB_DATABASE_BLOG."text SET `data` = ? , lasttime = ?, last_modify_time = ? ,title=? where blog_id = ? and id=?";
		$st = $conn->prepare($q);
		$st->bindParam(1,$_POST['data']);
 		$st->bindParam(2,jry_wb_get_time());
 		$st->bindParam(3,jry_wb_get_time());
		$st->bindParam(4,urldecode($_GET['title']));
		$st->bindParam(5,intval($_GET['blog_id']));
		$st->bindParam(6,$jry_wb_login_user['id']);
		$st->execute();
		echo json_encode(array('login'=>true,'message'=>intval($_GET['blog_id']).':'.urldecode($_GET['title'])));
	}
	if($action=='push')
	{
		$conn=jry_wb_connect_database();
		$q ="UPDATE ".JRY_WB_DATABASE_BLOG."text SET ifshow=1 , lasttime = ? where blog_id = ? and id=?";
		$st = $conn->prepare($q);
		$st->bindParam(1,jry_wb_get_time());
		$st->bindParam(2,intval($_GET['blog_id']));
		$st->bindParam(3,$jry_wb_login_user['id']);
		$st->execute();
		echo json_encode(array('login'=>true,'message'=>intval($_GET['blog_id'])));
	}
	if($action=='pull')
	{
		$conn=jry_wb_connect_database();
		$q ="UPDATE ".JRY_WB_DATABASE_BLOG."text SET ifshow=0 , lasttime = ? where blog_id = ? and id=?";
		$st = $conn->prepare($q);
		$st->bindParam(1,jry_wb_get_time());
		$st->bindParam(2,intval($_GET['blog_id']));
		$st->bindParam(3,$jry_wb_login_user['id']);
		$st->execute();
		echo json_encode(array('login'=>true,'message'=>intval($_GET['blog_id'])));
	}
	if($action=='new')
	{
		$conn=jry_wb_connect_database();
		$q ="INSERT INTO ".JRY_WB_DATABASE_BLOG."text (`id`,`lasttime`,`last_modify_time`) VALUES (?,?,?)";
		$st = $conn->prepare($q);
		$st->bindParam(1,$jry_wb_login_user['id']); 
		$st->bindParam(2,jry_wb_get_time());
		$st->bindParam(3,jry_wb_get_time());
		$st->execute();
		echo json_encode(array('login'=>true,'message'=>'OK'));
	}
	if($action=='delete')
	{
		$conn=jry_wb_connect_database();
		$q ="UPDATE ".JRY_WB_DATABASE_BLOG."text SET `data` =NULL , lasttime = ? ,title=NULL,`delete`=1 where blog_id = ? and id=?";
		$st = $conn->prepare($q);
 		$st->bindParam(1,jry_wb_get_time());
		$st->bindParam(2,intval($_GET['blog_id']));
		$st->bindParam(3,$jry_wb_login_user['id']);
		$st->execute();
		echo json_encode(array('login'=>true,'message'=>intval($_GET['blog_id']).':'.urldecode($_GET['title'])));
	}
?>