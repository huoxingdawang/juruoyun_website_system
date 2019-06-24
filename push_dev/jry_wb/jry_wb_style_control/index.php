<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	$get_try=$_GET['try'];
	if($get_try!='')
		$jry_wb_login_user['style']=jry_wb_load_style($_GET['try']);
	else
		$get_try=$jry_wb_login_user['style_id'];
	if($get_try!=$jry_wb_login_user['style']['style_id'])
	{
		include('../../404.php');
		exit();
	}
	jry_wb_print_head("主题查看与试用",false,true,true);
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>
	<?php if($jry_wb_login_user[id]!=-1)jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_href('jry_wb_style_control','active');?>
</div>
<div id="main_body">
	<div align="center" class="jry_wb_font_big_size jry_wb_color_normal jry_wb_color_normal_prevent">
		主题:<?php echo $jry_wb_login_user['style']['name']?>
	</div>
	<div align="right" class="jry_wb_font_normal_size jry_wb_color_normal jry_wb_color_normal_prevent" style="">
		<span id="by_id" style="right: 100px;position: relative;">by:</span>
		<script language="javascript">jry_wb_get_and_show_user(document.getElementById('by_id'),<?php echo $jry_wb_login_user['style']['id'];?>,null,null,true);</script>
	</div>	
	<table>
	<?php
		for($i=count($jry_wb_login_user['style']['update'])-1;$i>=0;$i--)
			echo '<tr><td class="h56">'.$jry_wb_login_user['style']['update'][$i]['time'].'</td><td class="h56">'.$jry_wb_login_user['style']['update'][$i]['data'].'</td></tr>';
	?>
	</table>
</div>
<script language="javascript">jry_wb_add_onresize(function(){var all=document.getElementById("main_body");var width=document.documentElement.clientWidth;if(width>800){all_width=width-Math.min(width*0.3,width-800);all.style.width=all_width;all.style.margin="0px "+(width-all_width)/2+"px"}else{all.style.width=width,all.style.margin="0px 0px"}})</script>
<?php jry_wb_print_tail();?>