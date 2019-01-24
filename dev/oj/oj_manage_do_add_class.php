<?php
	include("../tools/head.php");
	print_head("题库添加",true,true,false,array('use','manage','manageoj','manageojclass'));
	/*echo $_POST[question].'<br>';
	echo $_POST[questiontype].'<br>';
	echo $_POST[option].'<br>';
	echo $_POST[source].'<br>';
	echo $_POST[ojclassid].'<br>';
	echo $_POST[ojquestionid].'<br>';
	echo $_POST[ans].'<br>';*/
	$action=$_GET[action];
	$conn2=connect_sql();
	if($action=='add')
	{
		$q="INSERT INTO ".constant('ojdb')."list 
			(`ojclassname`,`ojclassaddid`,`lasttime`)
			VALUES('".$_POST['name']."','".$_USER['id']."','".get_time()."')
		";		
		//echo $q;
		$st = $conn2->prepare($q);
		$st->execute();
	}
		
?>
<script language="javascript">win.alert("修改成功","","window.location.href = document.referrer;")</script>
<?php print_tail();?>
