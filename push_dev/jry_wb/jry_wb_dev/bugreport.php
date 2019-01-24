<?php
	include_once("../tools/jry_wb_includes.php");
	if($_GET['action']!='save')
	{
		jry_wb_print_head('BUGreport',false,false,true,array());?>
		<div class="jry_wb_top_toolbar">
			<?php jry_wb_print_logo(false);?>	
			<?php jry_wb_print_href("bug","active");?>
			<?php if($jry_wb_login_user[id]!=-1)jry_wb_show_user($jry_wb_login_user);?>
		</div>
		<form method="post" action="bugreport.php?action=save">
		<table>
			<tr><td><h55>出现BUG的位置</h55></td><td><input name="url" id="url" value="" class="h56" style="width:1000px;"></td></tr>
			<tr><td valign="top"><h55>出现BUG的现象</h55></td><td><textarea id="bugmeaasge" name="bugmeaasge" class="h56" rows="20"></textarea></td></tr>
			<tr><td colspan="2" align="center"><input id="submit" name="submit" class="jry_wb_button jry_wb_button_size_big jry_wb_color_ok" type="submit" value="提交"></td></tr>
		</table>
		</form>
		<script language="javascript">
			jry_wb_add_load(function(){document.getElementById('url').value=document.referrer});
		</script>
<?php }
	else
	{
		jry_wb_print_head('',false,false,false);
		$st = $conn->prepare('INSERT INTO '.constant('jry_wb_database_general')."bug (id,url,bug,device) VALUES(?,?,?,?)");
		$st->bindParam(1,$jry_wb_login_user['id']); 
		$st->bindParam(2,$_POST['url']);
		$st->bindParam(3,$_POST['bugmeaasge']);	
		$st->bindParam(4,jry_wb_get_device(true));				
		$st->execute();
		jry_wb_send_mail("lijunyandeyouxiang@163.com",
		"BUGreport",
		'你的代码在 '.jry_wb_get_time().' 又双叒叕 BUG了<br>'.
		'信息为:<br>'.
		'ID :'.$jry_wb_login_user['id'].'<br>'.
		'url :'.$_POST['url'].'<br>'.
		'device :'.jry_wb_get_device().'<br>'.
		'bugmeaasge :'.$_POST['bugmeaasge'].'<br>'.
		'webserve :'.jry_wb_get_browser().'<br>'.
		'请及时处理<br>'
		);
		?>
		<script language="javascript">
			jry_wb_beautiful_alert.alert("感谢您的反馈","",function(){window.location.href='<?php echo $_POST['url'];?>'});
		</script>
		<?php
	}
	jry_wb_print_tail();
?>