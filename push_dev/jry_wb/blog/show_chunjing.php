<?php
	if($_GET['blog_id']==3)
	{
		$title='开发组简介';
		$name='introduction';
	}
	else if($_GET['blog_id']==13)
	{
		$title='使用指南';
		$name='zhinan';
	}
	else if($_GET['blog_id']==11)
	{
		$title='用户协议';
		$name='xieyi';
	}		
	else
	{
		include('../../404.php');
		exit();
	}
	include_once("../tools/jry_wb_includes.php");
	jry_wb_print_head($title,false,true,true);
?>
<div class="jry_wb_top_toolbar">
	<?php if($jry_wb_login_user['id']!=-1)jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_logo(false);?>	
	<?php jry_wb_print_href($name,"active");?>
</div>
<script type="text/javascript">
jry_wb_add_onresize(function(){var all=document.getElementById("result");var width=document.documentElement.clientWidth;if(width>800){all_width=width-Math.min(width*0.3,width-800);all.style.width=all_width;all.style.margin="0px "+(width-all_width)/2+"px"}else{all.style.width=width,all.style.margin="0px 0px"}})
jry_wb_add_load(function ()
{
	jry_wb_ajax_load_data("../blog/blog_getinformation.php?action=get_blog_one&blog_id=<?php echo $_GET['blog_id']; ?>",function(data){
		jry_wb_loading_off();
		data=JSON.parse(data);
		if(!data.ifshow||data.delete||data.data==null)
		{
			jry_wb_ajax_load_data("http://<?php echo $_SERVER['HTTP_HOST'];?>/404.php?url=http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>",function(data){document.write(data);});
			return;
		}		
		new jry_wb_markdown(document.getElementById("result"),data.id,data.lasttime,(data.data));
		window.onresize();
	});
});
</script>
<div id="result"></div>
<?php jry_wb_print_tail()?>