<?php
	include_once('jry_nd_direct_include.php');
	function jry_nd_direct_share($conn,$user,$file)
	{
		$code=jry_wb_get_random_string(30);
		$st = $conn->prepare('INSERT INTO '.JRY_WB_DATABASE_NETDISK.'share (`id`,`key`,`file_id`,`lasttime`) VALUES (?,?,?,?)');
		$st->bindValue(1,$user['id']);
		$st->bindValue(2,$code);
		$st->bindValue(3,$file['file_id']);
		$st->bindValue(4,jry_wb_get_time());
		$st->execute();
		$share_id=$conn->lastInsertId();
		function update($conn,$user,$file,$share_id,$first=0)
		{
			if($file['share_list']!='')
				$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'file_list SET `share`=?,`lasttime`=?,`share_list`=JSON_MERGE_PRESERVE(share_list,?),self_share=self_share|? WHERE `file_id`=? AND id=? LIMIT 1');
			else
				$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'file_list SET `share`=?,`lasttime`=?,`share_list`=?,self_share=self_share|? WHERE `file_id`=? AND id=? LIMIT 1');
			$st->bindValue(1,1);
			$st->bindValue(2,jry_wb_get_time());
			$st->bindValue(3,json_encode(array((string)$share_id)));
			$st->bindValue(4,$first);
			$st->bindValue(5,$file['file_id']);
			$st->bindValue(6,$user['id']);
			$st->execute();
			if($file['isdir']==1)
			{
				if($file['self_share']==1&&$file['isdir']==1)
				{
					if(is_string($file['share_list']))
						$file['share_list']=json_decode($file['share_list']);
					$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'file_list SET `share`=?,`lasttime`=?,`share_list`=JSON_MERGE_PRESERVE(share_list,?) WHERE id=? AND file_id!=? AND JSON_CONTAINS(share_list,?)');
					$st->bindValue(1,1);
					$st->bindValue(2,jry_wb_get_time());
					$st->bindValue(3,json_encode(array((string)$share_id)));
					$st->bindValue(4,$user['id']);
					$st->bindValue(5,$file['file_id']);
					$st->bindValue(6,json_encode($file['share_list']));
					$st->execute();
				}
				else
					foreach(jry_nd_database_get_file_by_father($conn,$user,$file) as $child)
						update($conn,$user,$child,$share_id);
			}
		}
		update($conn,$user,$file,$share_id,1);
		$file['share']=1;
		jry_nd_database_operate_user_used_uploading($conn,$user,0,0);
	}
	function jry_nd_direct_unshare($conn,$user,$file)
	{
		if($file['share']==0)
			return;
		$st = $conn->prepare('SELECT share_id FROM '.JRY_WB_DATABASE_NETDISK.'share WHERE `file_id`=? AND id=?');
		$st->bindValue(1,$file['file_id']);
		$st->bindValue(2,$user['id']);			
		$st->execute();
		foreach($st->fetchAll() as $one)
		{
			$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'file_list SET `lasttime`=? , share_list=JSON_REMOVE(share_list,JSON_UNQUOTE(JSON_SEARCH(share_list,\'one\',?))) WHERE JSON_CONTAINS(share_list,?);');
			$st->bindValue(1,jry_wb_get_time());
			$st->bindValue(2,(string)$one['share_id']);
			$st->bindValue(3,json_encode(array((string)$one['share_id'])));
			$st->execute();
		}
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'file_list SET self_share=0 WHERE `file_id`=? AND id=?');
		$st->bindValue(1,$file['file_id']);
		$st->bindValue(2,$user['id']);			
		$st->execute();
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'file_list SET `share`=0 , self_share=0 ,share_list=NULL WHERE JSON_LENGTH(share_list)=0');
		$st->execute();
		$file['share']=0;
		$st = $conn->prepare('DELETE FROM '.JRY_WB_DATABASE_NETDISK.'share WHERE  id=? AND file_id=?');
		$st->bindValue(1,$user['id']);
		$st->bindValue(2,$file['file_id']);
		$st->execute();
		jry_nd_database_operate_user_used_uploading($conn,$user,0,0);
	}
?>