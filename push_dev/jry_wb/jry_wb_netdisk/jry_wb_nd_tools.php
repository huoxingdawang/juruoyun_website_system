<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_netdisk.php");	
	function jry_wb_get_netdisk_information($conn)
	{
		global $jry_wb_login_user;
		$q='SELECT *,'.JRY_WB_DATABASE_NETDISK_PREFIX.'users.lasttime as lasttime FROM '.JRY_WB_DATABASE_NETDISK.'users 
		LEFT JOIN '.JRY_WB_DATABASE_NETDISK.'group  ON ('.JRY_WB_DATABASE_NETDISK_PREFIX.'users.group_id = '.JRY_WB_DATABASE_NETDISK_PREFIX."group.group_id)
		where ".JRY_WB_DATABASE_NETDISK_PREFIX."users.id =? LIMIT 1";
		$st = $conn->prepare($q);
		$st->bindParam(1,$jry_wb_login_user['id']);
		$st->execute();
		if(count($data=$st->fetchAll())==0)
		{
			if(jry_wb_create_netdisk_account($conn)===false)
				return false;
			jry_wb_get_netdisk_information($conn);
			return false;
		}
		$jry_wb_login_user['nd_ei']=$data[0];
		$jry_wb_login_user['nd_ei']['allow_type']=json_decode($jry_wb_login_user['nd_ei']['allow_type']);
		return true;
	}
	function jry_wb_get_netdisk_information_by_id($id)
	{
		$conn=jry_wb_connect_database();
		$q='SELECT *,'.JRY_WB_DATABASE_NETDISK_PREFIX.'users.lasttime as lasttime  FROM '.JRY_WB_DATABASE_NETDISK.'users 
		LEFT JOIN '.JRY_WB_DATABASE_NETDISK.'group  ON ('.JRY_WB_DATABASE_NETDISK_PREFIX.'users.group_id = '.JRY_WB_DATABASE_NETDISK_PREFIX."group.group_id)
		where ".JRY_WB_DATABASE_NETDISK_PREFIX."users.id =? LIMIT 1";
		$st = $conn->prepare($q);
		$st->bindParam(1,$id);
		$st->execute();
		if(count($data=$st->fetchAll())==0)
			return null;
		$data[0]['allow_type']=json_decode($data[0]['allow_type']);
		return $data[0];	
	}
	function jry_wb_create_netdisk_account($conn)
	{
		global $jry_wb_login_user;
		if($jry_wb_login_user['id']==-1)
			return false;
		$st = $conn->prepare('INSERT INTO '.JRY_WB_DATABASE_NETDISK.'users (`id`,`lasttime`) VALUES (?,?)');
		$st->bindParam(1,$jry_wb_login_user['id']);
		$st->bindParam(2,jry_wb_get_time());
		$st->execute();
		return true;
	}
	function jry_wb_netdisk_connect_to_javascript()
	{
		global $jry_wb_login_user;
		echo '<script language="javascript">
		jry_wb_login_user["nd_ei"]=JSON.parse(\''.json_encode(array(
		'size_total'=>$jry_wb_login_user['nd_ei']['size_total'],
		'size_used'=>$jry_wb_login_user['nd_ei']['size_used'],
		'group_id'=>$jry_wb_login_user['nd_ei']['group_id'],
		'group_name'=>$jry_wb_login_user['nd_ei']['group_name'],
		'lasttime'=>$jry_wb_login_user['nd_ei']['lasttime'],
		'fast_size'=>$jry_wb_login_user['nd_ei']['fast_size'],
		'allow_type'=>$jry_wb_login_user['nd_ei']['allow_type']
		)).'\');	
		</script>';
	}
?>