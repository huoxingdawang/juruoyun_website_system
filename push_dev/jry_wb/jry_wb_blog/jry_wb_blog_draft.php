<?php 
	include_once("../tools/jry_wb_includes.php");
	$action=$_GET['action'];
	jry_wb_print_head("博客",true,true,true,array('use','editorblog'));
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>
	<?php jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_href('blog');?>	
	<?php jry_wb_print_href('blog_draft','active');?>	
</div>
<link rel="stylesheet" type="text/css" href="jry_wb_blog_draft.css">
<script language="javascript" src="jry_wb_blog_draft.js"></script>
<div id='result'></div>
<script language="javascript">
jry_wb_add_onresize(function()
{
	var all=document.getElementById("result");
	var width=document.documentElement.clientWidth;
	if(width>800)
	{
		all_width=width-Math.min(width*0.3,width-800);
		all.style.width=all_width;
		all.style.margin="0px "+(width-all_width)/2+"px";
	}
	else
	{
		all.style.width=width;
		all.style.margin="0px 0px";
	}
});
jry_wb_add_load(function()
{
	blog_draft=new jry_wb_blog_draft_function(document.getElementById("result"));
	blog_draft.data_get();
	window.onresize();
});	
</script>
<?php jry_wb_print_tail();?>