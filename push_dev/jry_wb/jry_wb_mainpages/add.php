<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_user_extern_message.php");
	jry_wb_print_head("注册",false,false,true);
	if(!JRY_WB_HOST_SWITCH&&$_GET['debug']!=1)
	{
		?><script>window.location="<?php echo JRY_WB_HOST_ADDRESS?>jry_wb_mainpages/add.php"</script><?php
		exit();
	}
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>	
	<?php jry_wb_print_href("login","");?>
	<?php jry_wb_print_href('add_user',"active");?>
</div>
<div id='area'></div>
<script language="javascript">jry_wb_include_once_script('jry_wb_mainpages_add.js.php',function(){jry_wb_register(document.getElementById('area'))});</script>
<?php jry_wb_print_tail()?>
