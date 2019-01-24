<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_netdisk.php");	
	function jry_wb_get_netdisk_information()
	{
		global $jry_wb_login_user;
		$conn=jry_wb_connect_database();
		$q='SELECT * FROM '.constant('jry_wb_netdisk').'users 
		LEFT JOIN '.constant('jry_wb_netdisk').'group  ON ('.constant('jry_wb_netdisk_prefix').'users.jry_nd_group_id = '.constant('jry_wb_netdisk_prefix')."group.jry_nd_group_id)
		where ".constant('jry_wb_netdisk_prefix')."users.id =? LIMIT 1";
		$st = $conn->prepare($q);
		$st->bindParam(1,$jry_wb_login_user['id']);
		$st->execute();
		if(count($data=$st->fetchAll())==0)
			return false;
		$jry_wb_login_user['jry_wb_nd_extern_information']=$data[0];
		$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_allow_type']=json_decode($jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_allow_type']);
		return true;
	}
	function jry_wb_create_netdisk_account()
	{
		global $jry_wb_login_user;
		$conn=jry_wb_connect_database();
		$q='INSERT INTO '.constant('jry_wb_netdisk').'users (`id`,`lasttime`) VALUES (?,?)';
		$st = $conn->prepare($q);
		$st->bindParam(1,$jry_wb_login_user['id']);
		$st->bindParam(2,jry_wb_get_time());
		$st->execute();
	}
	function jry_wb_netdisk_connect_to_javascript()
	{
		global $jry_wb_login_user;
		echo '<script language="javascript">
		jry_wb_login_user["jry_wb_nd_extern_information"]=JSON.parse(\''.json_encode(array(
		'jry_nd_size_total'=>$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_size_total'],
		'jry_nd_size_used'=>$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_size_used'],
		'jry_nd_group_id'=>$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_group_id'],
		'jry_nd_group_name'=>$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_group_name'],
		'lasttime'=>$jry_wb_login_user['jry_wb_nd_extern_information']['lasttime'],
		'jry_nd_allow_type'=>$jry_wb_login_user['jry_wb_nd_extern_information']['jry_nd_allow_type']
		)).'\');	
		</script>';
	}
?>