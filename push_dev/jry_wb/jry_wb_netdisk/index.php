<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_netdisk.php");
	include_once("jry_wb_nd_tools.php");
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
		$st = $conn->prepare('SELECT lasttime FROM '.constant('jry_wb_netdisk').'area ORDER BY lasttime DESC LIMIT 1;');	$st->execute();		$data['area']=$st->fetchAll()[0]['lasttime'];
		$st = $conn->prepare('SELECT lasttime FROM '.constant('jry_wb_netdisk').'group ORDER BY lasttime DESC LIMIT 1;');	$st->execute();		$data['group']=$st->fetchAll()[0]['lasttime'];
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
<div class='jry_wb_top_toolbar'>
	<?php jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_logo(false);?>
	<?php jry_wb_print_href('jry_wb_netdisk',"active");?>
</div>
<link rel='stylesheet' type='text/css' href='jry_wb_nd.css'>
<link rel='stylesheet' type='text/css' href='jry_wb_nd_file.css'>
<script language="javascript" src="jry_wb_nd_index.js"></script>
<script language="javascript" src="jry_wb_nd_file.js"></script>
<script>var jry_nd_fast_save_message=JSON.parse('<?php  echo json_encode($data);?>');</script>
<div class="jry_wb_netdisk_body" id="body">

</div>
<?php jry_wb_print_tail();?>