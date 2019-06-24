<?php 
	include_once("../jry_wb_tools/jry_wb_includes.php");
	$question=$_GET['question'];
	jry_wb_print_head('help|'.$question,false,false,true);
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>
	<?php jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_href('help','active');?>
	<a href="<?php echo ($_GET['return']==''?$_SESSION['url']:$_GET['return'])?>" target="_parent">返回上一页</a>	
</div>


<?php jry_wb_print_tail();?>