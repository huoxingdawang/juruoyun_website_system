<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_tp_github_oauth_config.php");
	include_once("../jry_wb_configs/jry_wb_tp_mi_oauth_config.php");	
	include_once("../jry_wb_configs/jry_wb_tp_qq_oauth_config.php");	
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
			<div class="jry_wb_left_toolbar_left_list_1" onClick="tp_in();window.onresize();">第三方接入</div>
			<?php if(!$jry_wb_login_user['use']){ ?><div class="leftlist_default" onClick="unlock()">申请解封</div><?php } ?>
	  </td>
		<td id="show"  valign="top">
		</td>
	</tr>
</table>
<script language="javascript">
	var jry_wb_tp_github_oauth_config_enable=<?php echo(constant ('jry_wb_tp_github_oauth_config_client_id')==''?'false':'true'); ?>;
	var jry_wb_tp_qq_oauth_config_enable=<?php echo($jry_wb_tp_qq_oauth_config==null?'false':'true'); ?>;
	var jry_wb_tp_mi_oauth_config_enable=<?php echo(constant ('jry_wb_tp_mi_oauth_config_client_id')==''?'false':'true'); ?>;
	var jry_wb_default_user_head='<?php  if($jry_wb_login_user['sex']==0)echo constant('jry_wb_defult_woman_picture');else echo constant('jry_wb_defult_man_picture');?>';
	var jry_wb_gravatar_user_head=<?php	$uri = 'http://www.gravatar.com/avatar/' .md5($jry_wb_login_user['mail']). '?d=404';$headers = @get_headers($uri);if (preg_match("|200|", $headers[0])) echo "'".$uri."'";else echo 'null';?>;
	var jry_wb_qq_user_head=<?php if(strtolower(array_pop(explode("@",$jry_wb_login_user['mail'])))=='qq.com')echo "'"."https://q2.qlogo.cn/headimg_dl?dst_uin=".(explode("@",$jry_wb_login_user['mail'])[0])."&spec=100"."'";else if($jry_wb_login_user['oauth_qq']!='') echo '"'.$jry_wb_login_user['oauth_qq']->message->figureurl_qq_2.'"';else echo 'null';?>;
	var jry_wb_github_user_head='<?php if($jry_wb_login_user['oauth_github']!='')echo $jry_wb_login_user['oauth_github']->avatar_url;?>';
	var jry_wb_mi_user_head='<?php if($jry_wb_login_user['oauth_mi']!='')echo $jry_wb_login_user['oauth_mi']->miliaoIcon_orig;?>';
	var style=JSON.parse('<?php  echo json_encode($ans);?>');
	var jry_wb_mainpages_chenge_prelook_styles='<?php echo jry_wb_print_href('jry_wb_style_control','','',true);?>'
</script>
<script language="javascript" src="jry_wb_mainpages_chenge.js"></script>
<?php jry_wb_print_tail();?>
