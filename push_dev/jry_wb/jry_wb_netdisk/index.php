<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_netdisk.php");
	include_once("jry_wb_nd_tools.php");
	jry_wb_print_head("网盘",true,true,true,array('use','usenetdisk'));
	if(!jry_wb_get_netdisk_information($conn))
	{
		?><script>var jry_wb_netdisk_first_time_use=true;</script><?php
	}
	else
	{
		?><script>var jry_wb_netdisk_first_time_use=false;</script><?php
	}
	jry_wb_netdisk_connect_to_javascript();
	$conn=jry_wb_connect_database();
	if(($file=fopen('jry_nd.fast_save_message','r'))==false)
	{
		$st = $conn->prepare('SELECT lasttime FROM '.JRY_WB_DATABASE_NETDISK.'area ORDER BY lasttime DESC LIMIT 1;');	$st->execute();		$data['area']=$st->fetchAll()[0]['lasttime'];
		$st = $conn->prepare('SELECT lasttime FROM '.JRY_WB_DATABASE_NETDISK.'group ORDER BY lasttime DESC LIMIT 1;');	$st->execute();		$data['group']=$st->fetchAll()[0]['lasttime'];
		$file=fopen('jry_nd.fast_save_message','w');
		fwrite($file,json_encode($data));
		fclose($file);
		$data['new']=true;
	}
	else
	{
		$data=json_decode(fread($file,filesize('jry_nd.fast_save_message')));
		fclose($file);
	}
?>
<?php echo jry_wb_include_css($jry_wb_login_user['style'],'netdisk/index'); ?>
<?php echo jry_wb_include_css($jry_wb_login_user['style'],'netdisk/file'); ?>
<div class='jry_wb_top_toolbar'></div>
<script>
	jry_wb_include_once_script('jry_wb_nd_index.js.php',function(){jry_wb_netdisk_init(document.getElementById('body'));});
	var jry_nd_fast_save_message=JSON.parse('<?php  echo json_encode($data);?>');
</script>
<div class="jry_wb_netdisk_body" id="body">

</div>
<?php jry_wb_print_tail();?>