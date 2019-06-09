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
<link rel="stylesheet" type="text/css" href="jry_wb_online_judge_show_question.css">
<script language="javascript" src="jry_wb_online_judge_show_question.js"></script>
<div id='question'>
</div>
<script language="javascript">
sq=new jry_wb_online_judge_show_question_function(document.getElementById('question'));
/*jry_wb_add_load(function (){showquestion = new showquestion_function(document.getElementById('question'),'<?php echo jry_wb_print_href("login",0,"",1)?>');jry_wb_ajax_load_data('jry_wb_online_judge_checkquestion.php',function (data){showquestion.dofordata(data);},[{'name':'ansid','value':'no'},{'name':'ojquestionid','value':(JSON.parse(decodeURI(location.hash.slice(1))).ojquestionid==null?'rand':JSON.parse(decodeURI(location.hash.slice(1))).ojquestionid)},{'name':'ojclassid','value':'<?php echo $ojclassid?>'},{'name':'ans','value':''},{'name':'isoption','value':''}]);});
jry_wb_add_onresize(function(){var all=document.getElementById('question');var width=document.documentElement.clientWidth;if(width>800){all_width=width-Math.min(width*0.2,width-800);all.style.width=all_width;all.style.margin="0px "+(width-all_width)/2+"px"}else{all.style.width="100%",all.style.margin="0px 0px"}});
document.onkeydown = function (event) 
{
	var e = event || window.event || arguments.callee.caller.arguments[0];
	if (e && e.keyCode == 13) 
	{
        document.getElementById("button").click();   
	}
};*/
</script>
<?php jry_wb_print_tail()?>