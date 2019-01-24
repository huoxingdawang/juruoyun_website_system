<?php 
	include_once("../tools/jry_wb_includes.php");
	$ojclassid=$_GET[ojclassid];
	$ojclassname=$_GET[ojclassname];
	jry_wb_print_head('在线测评',true,true,true)
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>
	<?php jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_href('ojall');?> 
	<?php jry_wb_print_href('ojlogs');?>
	<?php jry_wb_print_href('oj','active');?>
</div>
<link rel="stylesheet" type="text/css" href="oj_showquestion.css">
<script language="javascript" src="oj_showquestion.js"></script>
<div id='question'>
</div>
<script language="javascript">
jry_wb_add_load(function (){showquestion = new showquestion_function(document.getElementById('question'),'<?php echo jry_wb_print_href("login",0,"",1)?>');jry_wb_ajax_load_data('oj_checkquestion.php',function (data){showquestion.dofordata(data);},[{'name':'ansid','value':'no'},{'name':'ojquestionid','value':(JSON.parse(decodeURI(location.hash.slice(1))).ojquestionid==null?'rand':JSON.parse(decodeURI(location.hash.slice(1))).ojquestionid)},{'name':'ojclassid','value':'<?php echo $ojclassid?>'},{'name':'ans','value':''},{'name':'isoption','value':''}]);});
jry_wb_add_onresize(function(){var all=document.getElementById('question');var width=document.documentElement.clientWidth;if(width>800){all_width=width-Math.min(width*0.2,width-800);all.style.width=all_width;all.style.margin="0px "+(width-all_width)/2+"px"}else{all.style.width="100%",all.style.margin="0px 0px"}});
document.onkeydown = function (event) 
{
	var e = event || window.event || arguments.callee.caller.arguments[0];
	if (e && e.keyCode == 13) 
	{
        document.getElementById("button").click();   
	}
};
</script>
<?php jry_wb_print_tail()?>