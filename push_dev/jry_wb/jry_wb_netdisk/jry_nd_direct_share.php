<?php
	include_once('jry_nd_direct_include.php');
	function jry_nd_direct_share($conn,$user,$file)
	{
		$code=jry_wb_get_random_string(30);
		$st = $conn->prepare('INSERT INTO '.constant('jry_wb_netdisk').'share (`id`,`key`,`file_id`,`lasttime`) VALUES (?,?,?,?)');
		$st->bindValue(1,$user['id']);
		$st->bindValue(2,$code);
		$st->bindValue(3,$file['file_id']);
		$st->bindValue(4,jry_wb_get_time());
		$st->execute();
		if($file['share']==0)
		{
			$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET `share`=?,`lasttime`=? WHERE `file_id`=? AND id=?');
			$st->bindValue(1,1);
			$st->bindValue(2,jry_wb_get_time());
			$st->bindValue(3,$file['file_id']);
			$st->bindValue(4,$user['id']);
			$st->execute();
		}
		$file['share']=1;
		jry_nd_database_operate_user_used_uploading($conn,$user,0,0);
	}
	function jry_nd_direct_unshare($conn,$user,$file)
	{
		if($file['share']==0)
			return;
		$file['share']=0;
		$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET `share`=?,`lasttime`=? WHERE `file_id`=? AND id=?');
		$st->bindValue(1,0);
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$file['file_id']);
		$st->bindValue(4,$user['id']);			
		$st->execute();
		$st = $conn->prepare('DELETE FROM '.constant('jry_wb_netdisk').'share WHERE  id=? AND file_id=?');
		$st->bindValue(1,$user['id']);
		$st->bindValue(2,$file['file_id']);
		$st->execute();
		jry_nd_database_operate_user_used_uploading($conn,$user,0,0);
	}
?>