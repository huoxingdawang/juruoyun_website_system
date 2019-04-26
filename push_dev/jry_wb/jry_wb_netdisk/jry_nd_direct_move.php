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
		$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET `father`=? , lasttime=? WHERE `file_id`=? AND id=?');
		$st->bindValue(1,$to['file_id']);
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$file['file_id']);
		$st->bindValue(4,$user['id']);
		$st->execute();
		if($file['share']||$to['share']||$father['share'])
		{
			if(is_string($father['sharelist']))
				$father['sharelist']=json_decode($father['sharelist']);
			if(is_string($file['sharelist']))
				$file['sharelist']=json_decode($file['sharelist']);
			if(is_string($to['sharelist']))
				$to['sharelist']=json_decode($to['sharelist']);	
			$delete=[];
			$add=[];
			if($father['sharelist']===NULL)
				$father['sharelist']=[];
			if($file['sharelist']===NULL)
				$file['sharelist']=[];
			if($to['sharelist']===NULL)
				$to['sharelist']=[];			
			foreach($father['sharelist'] as $one)
				if(array_search($one,$to['sharelist'])===false)
					$delete[]=$one;
			foreach($to['sharelist'] as $one)
				if(array_search($one,$file['sharelist'])===false)
					$add[]=$one;
			function update($conn,$user,$file,$add,$delete)
			{
				if(is_string($file['sharelist']))
					$file['sharelist']=json_decode($file['sharelist']);					
				if($file['sharelist']===NULL)
					$file['sharelist']=[];
				foreach($delete as $one)
					array_splice($file['sharelist'],array_search($one,$file['sharelist']),1);
				$file['sharelist']=array_merge($file['sharelist'],$add);
				$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET `lasttime`=?,`sharelist`=? WHERE `file_id`=? AND id=? LIMIT 1');
				$st->bindValue(1,jry_wb_get_time());
				$st->bindValue(2,json_encode($file['sharelist']));
				$st->bindValue(3,$file['file_id']);
				$st->bindValue(4,$user['id']);
				$st->execute();
				if($file['isdir']==1)
					foreach(jry_nd_database_get_file_by_father($conn,$user,$file) as $child)
						update($conn,$user,$child,$file['sharelist'],$delete);
			}
			update($conn,$user,$file,$add,$delete);
			$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET `share`=0 , self_share=0 ,sharelist=NULL WHERE JSON_LENGTH(sharelist)=0');
			$st->execute();
			$st = $conn->prepare('UPDATE '.constant('jry_wb_netdisk').'file_list SET `share`=1 WHERE JSON_LENGTH(sharelist)!=0');
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