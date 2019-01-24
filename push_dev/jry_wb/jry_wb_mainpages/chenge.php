<?php
	include_once("../tools/jry_wb_includes.php");
	jry_wb_print_head("用户管理",true,true,true,array(),true,false);
	$st=jry_wb_connect_database()->prepare('SELECT * FROM '.constant('jry_wb_database_general').'style');
	$st->execute();
	$i=0;
	foreach($st->fetchAll() as $one)
	{
		$ans[$i]['name']=$one['name'];
		$ans[$i]['style_id']=$one['style_id'];
		$i++;
	}
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>	
	<?php jry_wb_show_user($jry_wb_login_user,true);?>
</div>
<table width="100%" class="jry_wb_left_toolbar">
	<tr>
		<td class="jry_wb_left_toolbar_left" valign="top">
			<div class="jry_wb_left_toolbar_left_list_1" onClick="show();window.onresize();">基本信息查看</div>
			<div class="jry_wb_left_toolbar_left_list_2" onClick="showchenge();window.onresize();">个人信息修改</div>
			<div class="jry_wb_left_toolbar_left_list_1" onClick="show_ip();window.onresize();">登录/登出</div>
			<div class="jry_wb_left_toolbar_left_list_2" onClick="showtel();window.onresize();">电话</div>
			<div class="jry_wb_left_toolbar_left_list_1" onClick="showmail();window.onresize();">邮箱</div>
			<div class="jry_wb_left_toolbar_left_list_2" onClick="showpas();window.onresize();">修改密码</div>
			<div class="jry_wb_left_toolbar_left_list_1" onClick="showshow();window.onresize();">隐私设置</div>
			<div class="jry_wb_left_toolbar_left_list_2" onClick="showspecialfact();window.onresize();">特效设置</div>
			<div class="jry_wb_left_toolbar_left_list_1" onClick="showcache();window.onresize();">缓存查看</div>
			<div class="jry_wb_left_toolbar_left_list_2" onClick="showmusiclist();window.onresize();">歌单管理</div>
			<?php if(!$jry_wb_login_user['use']){ ?><div class="leftlist_default" onClick="unlock()">申请解封</div><?php } ?>
	  </td>
		<td id="show"  valign="top">
		</td>
	</tr>
</table>
<script language="javascript">
	var style=JSON.parse('<?php  echo json_encode($ans);?>');
	var jry_wb_mainpages_chenge_prelook_styles='<?php echo jry_wb_print_href('jry_wb_style_control','','',true);?>'
</script>
<script language="javascript" src="jry_wb_mainpages_chenge.js"></script>
<?php jry_wb_print_tail();?>
