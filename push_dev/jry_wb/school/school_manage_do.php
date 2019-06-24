<?php	
	include_once("../jry_wb_tools/jry_wb_includes.php");
	$action=$_GET['action'];
	$conn2=jry_wb_connect_database();
	try{jry_wb_check_compentence(NULL,array('use','manage','manageschool','manageschoolschool'));}catch(jry_wb_exception $e){echo $e->getMessage();exit();}	
	if($action=='addschool')
	{
		$q='INSERT INTO '.constant('schooldb')."list (`schoolname`) VALUES(?)";
		$st = $conn2->prepare($q);
		$st->bindParam(1,$_POST['name']);
		$st->execute();	
	}
	if($action=='chengeschool')
	{
		$q="UPDATE ".constant('schooldb')."list SET schoolname=? WHERE schoolid=?";
		$st = $conn2->prepare($q);
		$st->bindParam(1,$_POST['name']);
		$st->bindParam(2,$_GET['id']);
		$st->execute();	
	}	

?>
<script language="javascript">jry_wb_beautiful_alert.alert("修改成功","","window.location.href = document.referrer;")</script>