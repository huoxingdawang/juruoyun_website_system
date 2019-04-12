<?php 
	include_once("../tools/jry_wb_includes.php");
?>
<?php if($_GET['action']==''){
		jry_wb_print_head("校园管理",true,true,true);?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>
	<?php jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_href('school','active');?>
</div>
<h1>新功能开发中</h1>
<a target="_blank" href="Providence_changing_seat_system.zip"><h56>天意换座位系统（离线版）点此下载</h56></a><br>
<a target="_blank" href="Providence_changing_seat_system_big.zip"><h56>天意换座位系统（离线加大版）点此下载</h56></a><br>
<a target="" href="huanzuo.php"><h56>天意换座位系统在线版</h56></a>
<script language="javascript" src="school_showallschool.js"></script>
<script language="javascript">
jry_wb_add_load(function()
{
	school_all=new school_showallschool_function(document.getElementById("schoolall"));
	jry_wb_ajax_load_data('school_getinformation.php?action=schoolall',function (data){school_all.dofordata(data);school_all.show(false,false)});
})
</script>
<div id="schoolall" style="width:100%"></div>
<?php }else if($_GET['action']=='add'){
		jry_wb_print_head("校园管理",true,true,false);
		$conn2=jry_wb_connect_database();
		$q='SELECT * FROM '.constant('schooldb').'list where schoolid=? LIMIT 1';
		$st = $conn2->prepare($q);
		$st->bindParam(1,$_GET['schoolid']);
		$st->execute();	
		$data=$st->fetchAll();	
		$data=$data[0];
?>
<table border="4" width="100%">
<form method="post" action="school_do.php?action=add">
	<tr>
		<td class="h55" width="400px">申请人</td>
		<td width="*"><?php jry_wb_show_user($jry_wb_login_user,"",4);?></td>
	</tr>
	<tr>
		<td class="h55">目标学校</td>
		<td class="h56"><?php echo $data['schoolname']?><input type="hidden" value="<?php echo $_GET['schoolid']?>" id="schoolid" name="schoolid"></td>
	</tr>	
	<tr>
		<td class="h55">真实姓名</td>
		<td class="h55"><input type="text" value="<?php echo $jry_wb_login_user['name']?>" id="truename" name="truename" class="h56"></td>
	</tr>	
	<tr>
		<td class="h55">学号</td>
		<td class="h55"><input type="text" value="" id="schoolselfid" name="schoolselfid" class="h56"></td>
	</tr>	
	<tr>
		<td class="h55">真实性别</td>
		<td class="h55"><input name="sex"id="sex" type="radio" value=1 <?php if($jry_wb_login_user[sex]==1)echo "checked" ?>>
	      	男
	        <input type="radio" id="sex"name="sex" value=0	<?php if($jry_wb_login_user[sex]==0)echo "checked" ?> >
	        女
		</td>
	</tr>	
	<tr>
		<td><h55>验证码</h55></td>
		<td>
		<input name="vcode" type="text" id="vcode" class="h56" size="4"
		onclick="document.getElementById('vcodesrc').src=jry_wb_message.jry_wb_host+'tools/verificationcode.php?r=+Math.random()"
		class="h56"
		/>
		<img id="vcodesrc" src=""/>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div align="center"><input type="submit" name="Submit" value="提交"  onclick="" class="button button1"/></div>
		</td>
	</tr>
</form>
</table>
<?php }?>
<?php jry_wb_print_tail()?>