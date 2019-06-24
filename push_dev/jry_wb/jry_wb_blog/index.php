<?php 
	include_once("../tools/jry_wb_includes.php");
	$action=$_GET['action'];
	jry_wb_print_head("博客",false,true,true);
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>
	<?php if($jry_wb_login_user[id]!=-1)jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_href('blog','active');?>
	<?php if($jry_wb_login_user[id]!=-1&&$jry_wb_login_user['compentence']['editorblog'])jry_wb_print_href('blog_draft');?>
</div>
<link rel="stylesheet" type="text/css" href="jry_wb_blog_index.css">
<script language="javascript" src="jry_wb_blog_index.js"></script>
<div id='all'></div>
<script language="javascript">
jry_wb_add_onresize(function()
{
	var all=document.getElementById('all');
	var width=document.documentElement.clientWidth;
	if(width>1000)
	{
		all_width=width-Math.min(width*0.2,width-1000);
		all.style.width=all_width;
		all.style.margin="0px "+(width-all_width)/2+"px";
	}else
	{
		all.style.width="100%";
		all.style.margin="0px 0px";
	}
});
jry_wb_add_load(function()
{
	blog_index=new jry_wb_blog_index_function(document.getElementById("all"));
	blog_index.get_cache();
});	
</script>
<?php jry_wb_print_tail();?>