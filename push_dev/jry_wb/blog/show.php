<?php 
	include_once("../tools/jry_wb_includes.php");
	$action=$_GET['action'];
	jry_wb_print_head("博客",false,true,true);
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>
	<?php if($jry_wb_login_user[id]!=-1)jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_href('blog');?>
	<?php if($jry_wb_login_user[id]!=-1)jry_wb_print_href('blog_draft');?>
	<?php jry_wb_print_href('blog_show','active');?>
</div>
<script type="text/javascript">
jry_wb_add_onresize(function(){var all=document.getElementById("result");var width=document.documentElement.clientWidth;if(width>800){all_width=width-Math.min(width*0.3,width-800);all.style.width=all_width;all.style.margin="0px "+(width-all_width)/2+"px"}else{all.style.width=width,all.style.margin="0px 0px"}})
jry_wb_set_shortcut([jry_wb_keycode_alt,jry_wb_keycode_l],function(){jry_wb_beautiful_right_alert.alert("已打开草稿箱列表",3000,'auto','ok');window.open('<?php echo jry_wb_print_href('blog_draft','','',true);?>');});
jry_wb_set_shortcut([jry_wb_keycode_alt,jry_wb_keycode_b],function(){jry_wb_beautiful_right_alert.alert("已打开博客列表",3000,'auto','ok');window.open('<?php echo jry_wb_print_href('blog','','',true);?>');});

jry_wb_add_load(function(){
	jry_wb_ajax_load_data("blog_getinformation.php?action=get_blog_one&blog_id=<?php echo $_GET['blog_id']?>",function(data){
		jry_wb_loading_off();
		data=JSON.parse(data);
		if(!data.ifshow||data.delete||data.data==null)
		{
			jry_wb_ajax_load_data("http://<?php echo $_SERVER['HTTP_HOST'];?>/404.php?url=http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>",function(data){document.write(data);});
			return;
		}
		return_data=markdown(document.getElementById("result"),data.id,data.lasttime,(data.data));
		document.title+='|'+return_data.title;
		window.onresize();
	});
});
</script>
<div id="result"></div>
<?php jry_wb_print_tail();?>