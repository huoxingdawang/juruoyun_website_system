<?php 
	include_once("jry_wb_online_judge_includes.php");
	$ojclassid=$_GET[ojclassid];
	$ojclassname=$_GET[ojclassname];
	jry_wb_print_head('在线测评',true,true,true)
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>
	<?php jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_href('online_judge_all','','',false,'top_toolbar_all');?>
	<?php jry_wb_print_href('online_judge_logs','','',false,'top_toolbar_logs');?>		
	<?php jry_wb_print_href('online_judge_show_question','active');?>
</div>
<?php echo jry_wb_include_css($jry_wb_login_user['style'],'online_judge/show_question'); ?>
<script language="javascript" src="jry_wb_online_judge_show_question.js"></script>
<div id='question'></div>
<script language="javascript">sq=new jry_wb_online_judge_show_question_function(document.getElementById('question'));</script>
<?php jry_wb_print_tail()?>