<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_user_extern_message.php");
	include_once("../jry_wb_configs/jry_wb_tp_github_oauth_config.php");
	include_once("../jry_wb_configs/jry_wb_tp_gitee_oauth_config.php");
	include_once("../jry_wb_configs/jry_wb_tp_mi_oauth_config.php");	
	include_once("../jry_wb_configs/jry_wb_tp_qq_oauth_config.php");
	jry_wb_print_head("用户管理",true,true,true,array(),true,false);
	$st=jry_wb_connect_database()->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'style');
	$st->execute();
	$i=0;
	foreach($st->fetchAll() as $one)
	{
		$ans[$i]['name']=$one['name'];
		$ans[$i]['style_id']=$one['style_id'];
		$i++;
	}
?>
<?php echo jry_wb_include_css($jry_wb_login_user['style'],'mainpages/chenge'); ?>
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
			<?php $i=0;if(JRY_WB_BACKGROUND_MUSIC_SWITCH){ ?><div class="jry_wb_left_toolbar_left_list_<?php echo $i%2+1;$i++; ?>" onClick="showmusiclist();window.onresize();">歌单管理</div><?php } ?>
			<?php if(JRY_WB_OAUTH_SWITCH){ ?><div class="jry_wb_left_toolbar_left_list_<?php echo $i%2+1;$i++; ?>" onClick="showtpin();window.onresize();">第三方接入</div><?php } ?>
			<?php if($JRY_WB_CONFIG_USER_EXTERN_MESSAGE!=NULL){ ?><div class="jry_wb_left_toolbar_left_list_<?php echo $i%2+1;$i++; ?>" onClick="showextern();window.onresize();">扩展信息</div><?php } ?>
			<?php if(JRY_WB_INVITE_CODE){ ?><div class="jry_wb_left_toolbar_left_list_<?php echo $i%2+1;$i++; ?>" onClick="showinvitecode();window.onresize();">邀请码</div><?php } ?>
			<div class="jry_wb_left_toolbar_left_list_<?php echo $i%2+1;$i++; ?>" onClick="showlog();window.onresize();">操作记录</div>
			<?php if(!$jry_wb_login_user['use']){ ?><div class="leftlist_default" onClick="unlock()">申请解封</div><?php } ?>
	  </td>
		<td id="show"  valign="top"></td>
	</tr>
</table>
<script language="javascript">
	var jry_wb_gravatar_user_head=<?php	$uri = 'http://www.gravatar.com/avatar/' .md5($jry_wb_login_user['mail']). '?d=404';if(JRY_WB_CHECK_GRAVATAR)$headers=@get_headers($uri);if((!JRY_WB_CHECK_GRAVATAR)||preg_match("|200|", $headers[0])) echo "'".$uri."'";else echo 'null';?>;
	var style=JSON.parse('<?php  echo json_encode($ans);?>');
</script>
<script language="javascript" src="jry_wb_mainpages_chenge.js.php"></script>
<?php jry_wb_print_tail();?>
