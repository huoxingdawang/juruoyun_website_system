<?php 
	include_once("../jry_wb_tools/jry_wb_includes.php");
	jry_wb_print_head("校园管理",true,true,false,array('use','manageschool','manageschoolschool'));
?>
<script language="javascript" src="school_showallschool.js"></script>
<script language="javascript">
jry_wb_add_onload(function()
{
	school_all=new school_showallschool_function(document.getElementById("schoolall"));
	jry_wb_ajax_load_data('school_getinformation.php?action=schoolall',function (data){school_all.dofordata(data);school_all.show(true,true)});
})
</script>
<div id="schoolall" style="width:100%"></div>
<?php jry_wb_print_tail() ?>