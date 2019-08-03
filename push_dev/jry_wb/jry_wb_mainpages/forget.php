<?php
	include_once('../jry_wb_tools/jry_wb_includes.php');
	session_start();
	jry_wb_print_head('重置密码',false,false,true);
?>
<?php echo jry_wb_include_css($jry_wb_login_user['style'],'mainpages/forget'); ?>
<div class='jry_wb_top_toolbar'>
	<?php jry_wb_print_logo(false);?>	
	<?php jry_wb_print_href('login','');?>
	<?php jry_wb_print_href('add_user','');?>
	<?php jry_wb_print_href('forget','active');?>
</div>
<div id='mainbody'></div>
<script language="javascript">jry_wb_include_once_script('jry_wb_mainpages_forget.js.php',function(){jry_wb_mainpages_forget(document.getElementById('mainbody'))});</script>
<?php jry_wb_print_tail();?>