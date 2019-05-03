<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_netdisk.php");
	include_once("../jry_wb_netdisk/jry_wb_nd_tools.php");
	jry_wb_print_head("网盘",true,true,true,array('use','usenetdisk'));
	if(!jry_wb_get_netdisk_information())
	{
		?><script>var jry_wb_netdisk_first_time_use=true;</script><?php
		jry_wb_create_netdisk_account();
		jry_wb_get_netdisk_information();
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
<div class="jry_wb_netdisk_body" id="body">

</div>
<script>
document.getElementById('body').innerHTML+=jry_wb_compare_time(jry_wb_cache.get_last_time('nd_file_list').split(/ /)[0],'<?php  echo jry_wb_get_time();?>');
document.getElementById('body').innerHTML+=new Date(jry_wb_cache.get_last_time('nd_file_list').split(/ /)[0]);
document.getElementById('body').innerHTML+=new Date('<?php  echo jry_wb_get_time();?>'.replace(/\-/g, "/"));
</script>
<?php jry_wb_print_tail();?>