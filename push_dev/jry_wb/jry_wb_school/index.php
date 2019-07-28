<?php 
	include_once("../jry_wb_tools/jry_wb_includes.php");
	jry_wb_print_head("校园管理",true,true,true,array('use','useschool'));
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>
	<?php jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_href('school','active');?>
</div>
<script language="javascript" src="school_showallschool.js"></script>
<script language="javascript">
jry_wb_add_onload(function()
{
	school_all=new school_showallschool_function(document.getElementById("schoolall"));
	jry_wb_ajax_load_data('school_getinformation.php?action=schoolall',function (data){school_all.dofordata(data);school_all.show(false,false);jry_wb_loading_off();});
})
</script>
<h1>新功能开发中</h1>
<div id="schoolall" style="width:100%"></div>
<?php jry_wb_print_tail()?>