<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	$jry_wb_keywords='';$jry_wb_description='';
	jry_wb_print_head("控制系统",true,true,true,array('use','manage'));
?>
<div class='jry_wb_top_toolbar'>
	<?php jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_logo(false);?>
	<?php jry_wb_print_href('jry_wb_manage_system',"active");?>
	<a style="background-color:#0066FF" id='hash'></a>
</div>
<div id="_top"></div>
<script language="javascript" src="jry_wb_manage_system.js"></script>
<div width="100%" height="450px" id="body" border="0" class="jry_wb_left_toolbar"></div>
<?php jry_wb_print_tail();?>