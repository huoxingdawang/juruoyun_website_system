<?php
	include_once("../tools/jry_wb_includes.php");
	jry_wb_print_head('BUGreport',false,false,true,array());
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>	
	<?php jry_wb_print_href("bug");?>
	<?php jry_wb_print_href("showbug","active");?>
	<?php if($jry_wb_login_user[id]!=-1)jry_wb_show_user($jry_wb_login_user);?>
</div>
<table border='1' width='100%'>
<tr>
<td width='200px'>位置</td>
<td width='20px'>设备</td>
<td width='20px'>ID</td>
<td width='300px'>内容</td>
<td width='100px'>时间</td>
<td width='20px'>状态</td>
<td width='100px'>回复时间</td>
<td width='*'>回复内容</td>
</tr>
<?php
	$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'bug');
	$st->execute();
	foreach ($st->fetchAll() as $one)
	{
?>
<tr>
<td width='200px'><?php echo $one['url']; ?></td>
<td width='20px'><?php echo $one['device']; ?></td>
<td width='20px'><?php echo $one['id']; ?></td>
<td width='300px'><?php echo $one['bug']; ?></td>
<td width='100px'><?php echo $one['time']; ?></td>
<td width='20px'><?php echo $one['status']; ?></td>
<td width='100px'><?php echo $one['updatetime']; ?></td>
<td width='*'><?php echo $one['reply']; ?></td>
</tr>
<?php		
	}
?>
</table>
<?php jry_wb_print_tail(); ?>