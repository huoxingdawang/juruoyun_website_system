<?php
	include_once('jry_nd_direct_include.php');
	function jry_nd_direct_move($conn,$user,$file,$to)
	{
		if($file['file_id']==$to['file_id'])
			return;
		if($file['father']==$to['file_id'])
			return;		
		$father=jry_nd_database_get_father($conn,$user,$file);
		jry_nd_database_operate_user_used_uploading($conn,$user,0,0);
		$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'file_list SET `father`=? , lasttime=? WHERE `file_id`=? AND id=?');
		$st->bindValue(1,$to['file_id']);
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$file['file_id']);
		$st->bindValue(4,$user['id']);
		$st->execute();
		if($file['share']||$to['share']||$father['share'])
		{
			if(is_string($father['share_list']))
				$father['share_list']=json_decode($father['share_list']);
			if(is_string($file['share_list']))
				$file['share_list']=json_decode($file['share_list']);
			if(is_string($to['share_list']))
				$to['share_list']=json_decode($to['share_list']);	
			$delete=[];
			$add=[];
			if($father['share_list']===NULL)
				$father['share_list']=[];
			if($file['share_list']===NULL)
				$file['share_list']=[];
			if($to['share_list']===NULL)
				$to['share_list']=[];			
			foreach($father['share_list'] as $one)
				if(array_search($one,$to['share_list'])===false)
					$delete[]=$one;
			foreach($to['share_list'] as $one)
				if(array_search($one,$file['share_list'])===false)
					$add[]=$one;
			function update($conn,$user,$file,$add,$delete)
			{
				if(is_string($file['share_list']))
					$file['share_list']=json_decode($file['share_list']);					
				if($file['share_list']===NULL)
					$file['share_list']=[];
				foreach($delete as $one)
					array_splice($file['share_list'],array_search($one,$file['share_list']),1);
				$file['share_list']=array_merge($file['share_list'],$add);
				$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'file_list SET `lasttime`=?,`share_list`=? WHERE `file_id`=? AND id=? LIMIT 1');
				$st->bindValue(1,jry_wb_get_time());
				$st->bindValue(2,json_encode($file['share_list']));
				$st->bindValue(3,$file['file_id']);
				$st->bindValue(4,$user['id']);
				$st->execute();
				if($file['isdir']==1)
					foreach(jry_nd_database_get_file_by_father($conn,$user,$file) as $child)
						update($conn,$user,$child,$file['share_list'],$delete);
			}
			update($conn,$user,$file,$add,$delete);
			$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'file_list SET `share`=0 , self_share=0 ,share_list=NULL WHERE JSON_LENGTH(share_list)=0');
			$st->execute();
			$st = $conn->prepare('UPDATE '.JRY_WB_DATABASE_NETDISK.'file_list SET `share`=1 WHERE JSON_LENGTH(share_list)!=0');
			$st->execute();
		}
	}
	function jry_nd_direct_move_file_id($conn,$user,$file_id,$to)
	{
		if(($file=jry_nd_database_get_file($conn,$user,$file_id))===null)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200008,'file'=>__FILE__,'line'=>__LINE__)));
		jry_nd_direct_move($conn,$user,$file,$to);
	}
?>