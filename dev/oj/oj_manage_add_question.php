<?php 
	include_once("../tools/head.php");
	$ojclassid		=$_GET[ojclassid];
	$action			=$_GET[action];
	@$conn2=connect_sql();
	if($action=='show')
	{
		print_head("题库管理",true,true,false,array('use','manage','manageoj','manageojquestion'));
	}
?>

<?php print_tail();?>