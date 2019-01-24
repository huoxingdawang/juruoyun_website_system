<?php 
	include_once("../tools/head.php");
	$ojclassid		=$_GET[ojclassid];
	$action			=$_GET[action];
	@$conn2=connect_sql();
	if($action=='show')
	{
		print_head("题库添加",true,true,false,array('use','manage','manageoj','manageojclass'));
?>
<table width="100%" border="2">
	<tr>
		<td width="10%"><h56>科目编号</h56></td>
		<td width="20%"><h56>创建人</h56></td>
		<td width="60%"><h56>科目名称</h56></td>
	</tr>
<?php
	@$conn2=connect_sql();
	$q='SELECT * FROM '.constant('ojdb').'list 
		INNER JOIN '.constant('generaldb').'users  ON ('.constant('generalpro').'users.id = '.constant('ojpro').'list.ojclassaddid)
		INNER JOIN '.constant('managedb').'competence  ON ('.constant('generalpro').'users.type = '.constant('managepro').'competence.type)
	';
	//echo $q;
	$st = $conn2->prepare($q);
	$st->execute();
	foreach($st->fetchAll()as $ojlist)
	{
	?>
	<tr>
		<td><h56>#<?php echo $ojlist[ojclassid]?></h56></td>
		<td><h56><?php show_user($ojlist,"",2)?></h56></td>
		<td><h56><?php echo $ojlist[ojclassname]?></h56></td>
	</tr>	
<?php } ?>
	<tr>
		<td><h56>#?</h56></td>
		<td><h56><?php show_user($_USER,"",2)?></h56></td>
		<td>
			<form action="oj_manage_do_add_class.php?action=add" method="post">
				<input type="text" class="h56" id="name" name="name"/>
				<input type="submit" value="提交" class="button button1"/>
			</form>
		</td>		
	</tr>
</table>
<?php } print_tail(false);?>