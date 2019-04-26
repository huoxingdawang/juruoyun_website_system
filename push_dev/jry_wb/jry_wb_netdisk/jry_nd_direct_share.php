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
		$share_id=$conn->lastInsertId();
		function update($conn,$user,$file,$share_id,$first=0)
		{
			if($file['sharelist']!='')
				$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET `share`=?,`lasttime`=?,`sharelist`=JSON_MERGE_PRESERVE(sharelist,?),self_share=self_share|? WHERE `file_id`=? AND id=? LIMIT 1');
			else
				$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET `share`=?,`lasttime`=?,`sharelist`=?,self_share=self_share|? WHERE `file_id`=? AND id=? LIMIT 1');
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
					if(is_string($file['sharelist']))
						$file['sharelist']=json_decode($file['sharelist']);
					$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET `share`=?,`lasttime`=?,`sharelist`=JSON_MERGE_PRESERVE(sharelist,?) WHERE id=? AND file_id!=? AND JSON_CONTAINS(sharelist,?)');
					$st->bindValue(1,1);
					$st->bindValue(2,jry_wb_get_time());
					$st->bindValue(3,json_encode(array((string)$share_id)));
					$st->bindValue(4,$user['id']);
					$st->bindValue(5,$file['file_id']);
					$st->bindValue(6,json_encode($file['sharelist']));
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
		$st = $conn->prepare('SELECT share_id FROM '.constant('jry_wb_netdisk').'share WHERE `file_id`=? AND id=?');
		$st->bindValue(1,$file['file_id']);
		$st->bindValue(2,$user['id']);			
		$st->execute();
		foreach($st->fetchAll() as $one)
		{
			$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET `lasttime`=? , sharelist=JSON_REMOVE(sharelist,JSON_UNQUOTE(JSON_SEARCH(sharelist,\'one\',?))) WHERE JSON_CONTAINS(sharelist,?);');
			$st->bindValue(1,jry_wb_get_time());
			$st->bindValue(2,(string)$one['share_id']);
			$st->bindValue(3,json_encode(array((string)$one['share_id'])));
			$st->execute();
		}
		$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET self_share=0 WHERE `file_id`=? AND id=?');
		$st->bindValue(1,$file['file_id']);
		$st->bindValue(2,$user['id']);			
		$st->execute();
		$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET `share`=0 , self_share=0 ,sharelist=NULL WHERE JSON_LENGTH(sharelist)=0');
		$st->execute();
		$file['share']=0;
		$st = $conn->prepare('DELETE FROM '.constant('jry_wb_netdisk').'share WHERE  id=? AND file_id=?');
		$st->bindValue(1,$user['id']);
		$st->bindValue(2,$file['file_id']);
		$st->execute();
		jry_nd_database_operate_user_used_uploading($conn,$user,0,0);
	}
	//UPDATE netdisk_file_list SET sharelist=JSON_ARRAY_APPEND(sharelist,'$',2) WHERE JSON_CONTAINS(sharelist, '1');
	//UPDATE netdisk_file_list SET sharelist=JSON_REMOVE(sharelist,JSON_UNQUOTE(JSON_SEARCH(sharelist,'one','1'))) WHERE JSON_CONTAINS(sharelist, '["1"]');
	//UPDATE netdisk_file_list SET sharelist=JSON_MERGE_PRESERVE(JSON_REMOVE(sharelist,JSON_UNQUOTE(JSON_SEARCH(sharelist,'one','1'))),'["5","6"]') WHERE JSON_CONTAINS(sharelist, '["1"]');
/*
TRUNCATE `netdisk_share`;
UPDATE `juruoyun_dev`.`netdisk_file_list` SET `sharelist`=NULL,share=0,self_share=0,lasttime=NOW() WHERE  `file_id`=60;
UPDATE `juruoyun_dev`.`netdisk_file_list` SET `sharelist`=NULL,share=0,self_share=0,lasttime=NOW()  WHERE  `file_id` >=62 AND `file_id` <=84;
UPDATE `juruoyun_dev`.`netdisk_file_list` SET `sharelist`=NULL,share=0,self_share=0,lasttime=NOW()  WHERE  `file_id` >=97 AND `file_id` <=98;
*/
?>