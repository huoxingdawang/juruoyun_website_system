<?php 
	include_once("../tools/jry_wb_includes.php");
	jry_wb_print_head("我的图床",true,true,true,(($_GET['mode']=='admin')?array('use','usepicturebed','managepicturebed'):array('use','usepicturebed')));
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>
	<?php jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_href('picturebed');?>	
	<?php jry_wb_print_href('mypicturebed',(($_GET['mode']=='admin')?'':'active'));?>
</div>
<div id='show' class="picturebed_all"></div>
<script language="javascript">
var rightmeau;
jry_wb_add_load(function ()
{
	<?php if($_SERVER['HTTP_HOST']=='juruoyun.top'||$_SERVER['HTTP_HOST']=='www.juruoyun.top'){ ?>
		jry_wb_beautiful_right_alert.alert('由于站主"家境贫寒",服务器配置比较辣鸡<br>暂时无法生成尺寸较大的图片的缩略图<br>希望大家前往开发组简介页积极打赏<br>开发组对此深表歉意<br>点击以关闭此通知',20000,'auto','warn');
	<?php } ?>
});
mode="<?php echo $_GET['mode']?>";
</script>
<script language="javascript" src="picturebed.js"></script>



<?php jry_wb_print_tail()?>