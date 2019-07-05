<?php 
	include_once("jry_wb_online_judge_includes.php");
	$action=$_GET['action'];
	$jry_wb_keywords='在线测评';
	jry_wb_print_head("在线测评",false,true,true);
?>
<div class="jry_wb_top_toolbar" id='jry_wb_top_toolbar'>
	<?php jry_wb_print_logo(false);?>
	<?php if($jry_wb_login_user['id']!=-1)jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_href('online_judge_all','','',false,'top_toolbar_all');?>
	<?php jry_wb_print_href('online_judge_logs','','',false,'top_toolbar_logs');?>	
</div>
<script language="javascript" src="jry_wb_online_judge.js"></script>
<script language="javascript" src="jry_wb_online_judge_show_all.js"></script>
<script language="javascript" src="jry_wb_online_judge_show_logs.js"></script>
<script language="javascript" src="jry_wb_online_judge_show_class.js"></script>
<?php echo jry_wb_include_css($jry_wb_login_user['style'],'online_judge/index'); ?>
<div id='area'></div>
<script language="javascript">
	oj=new jry_wb_online_judge_function(document.getElementById('area'),undefined,JSON.parse('<?php echo json_encode(jry_wb_online_judge_operate_fast_save('get')); ?>'));
</script>
<?php jry_wb_print_tail();?>