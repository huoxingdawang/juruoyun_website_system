<?php	
	include_once("../tools/jry_wb_includes.php");
	$action=$_GET['action'];
	$conn2=jry_wb_connect_database();
	if($action=='addschool')
	{
		jry_wb_print_head("校园管理",true,true,false,array('use','manageschool','manageschoolschool'));
		$q='INSERT INTO '.constant('schooldb')."list (`schoolname`) VALUES(?)";
		$st = $conn2->prepare($q);
		$st->bindParam(1,$_POST['name']);
		$st->execute();	
	}
	if($action=='chengeschool')
	{
		jry_wb_print_head("校园管理",true,true,false,array('use','manageschool','manageschoolschool'));
		$q="UPDATE ".constant('schooldb')."list SET schoolname=? WHERE schoolid=?";
		$st = $conn2->prepare($q);
		$st->bindParam(1,$_POST['name']);
		$st->bindParam(2,$_GET['id']);
		$st->execute();	
	}	

?>
<script language="javascript">jry_wb_beautiful_alert.alert("修改成功","","window.location.href = document.referrer;")</script>