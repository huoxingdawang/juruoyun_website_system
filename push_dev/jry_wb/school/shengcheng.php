<?php 
	include_once("../tools/jry_wb_includes.php");
	jry_wb_print_head("内测",false,true,true);
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>
	<?php jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_href('school_manage','active');?>
</div>
<?php
	if($_GET[action]=='')
	{
?>
<h56>该系统用于生成离线版数据</h56>
<form action="shengcheng.php?action=show" method="post" name="getdata">
<table>
<tr><td valign="top"><h56>列</h56></td><td valign="top"><input name="lie" type="text" value="8" class="h56"></td></tr>
<tr><td valign="top"><h56>走廊所处的位置（换行以输入多个走廊）</h56></td><td valign="top"><textarea class="h56" name="huan" cols="10" rows="5"></textarea></td></tr>
<tr><td valign="top"><h56>学生的姓名（换行以输入多个学生，学生学号由系统按输入顺序生成）</h56></td><td valign="top"><textarea class="h56" name="data" cols="100" rows="100"></textarea></td></tr>
</table>
<input name="" type="submit" value="提交"class="button button1">
</form>
<?php }
	else
	{
		$lie=$_POST[lie];
		$huan=explode("\n",$_POST[huan]);
		$data=explode("\n",$_POST[data]);
		$data_count=count ($data);
		$huan_count=count ($huan);
		echo "{";
		echo '"num":'.$data_count.',';
		echo '"seat":{"lie":'.$lie.',"break":[';
		$i=1;
		foreach ($huan as $seat)
		{
			echo $seat;
			if($i!=$huan_count)echo ',';
			$i++;
		}
		echo ']},"stu":[';
		$i=1;
		foreach ($data as $stu)
		{
			/*sscanf($stu,"%s%s%s",$jasdfhkjas,$name,$$jasdfhkjas);*/
			/*"1":{"id":1,	"name":"毕震明",	"position":1},*/
			echo '{"id":'.$i.',"name":"'.$stu.'","position":'.$i.'}';
			if($i!=$data_count)echo ',';
			$i++;
		}
		
		echo "]}";
	}	
?>
<?php jry_wb_print_tail()?>