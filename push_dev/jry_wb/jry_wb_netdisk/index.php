<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_netdisk.php");
	include_once("jry_wb_nd_tools.php");
	jry_wb_print_head("网盘",true,true,true,array('use','usenetdisk'));
	if(!jry_wb_get_netdisk_information())
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
		$st = $conn->prepare('SELECT lasttime FROM '.constant('jry_wb_database_netdisk').'area ORDER BY lasttime DESC LIMIT 1;');	$st->execute();		$data['area']=$st->fetchAll()[0]['lasttime'];
		$st = $conn->prepare('SELECT lasttime FROM '.constant('jry_wb_database_netdisk').'group ORDER BY lasttime DESC LIMIT 1;');	$st->execute();		$data['group']=$st->fetchAll()[0]['lasttime'];
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
<div class='jry_wb_top_toolbar'></div>
<script language="javascript" src="jry_wb_nd_index.js"></script>
<script>
	jry_wb_add_load(function()
	{
		document.getElementsByClassName('jry_wb_top_toolbar')[0].style.display='none';
		document.getElementById('buttom_message').style.display='none';
	});
	var jry_nd_share_mode_flag=false;
	var jry_nd_fast_save_message=JSON.parse('<?php  echo json_encode($data);?>');
	var jry_nd_price_fast_size=JSON.parse('<?php  echo constant('jry_nd_price_fast_size');?>');
	var jry_nd_price_size=JSON.parse('<?php  echo constant('jry_nd_price_size');?>');
</script>
<div class="jry_wb_netdisk_body" id="body">

</div>
<?php jry_wb_print_tail();?>