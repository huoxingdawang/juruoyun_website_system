<?php	
	include_once("../tools/jry_wb_includes.php");
	$ojclassid		=$_GET[ojclassid];
	$action			=$_GET[action];
	$conn2=jry_wb_connect_database();
	$start_time = microtime(true);
	if($action=='error')
	{
		$login=	jry_wb_print_head("",true,true,false,array('use'),false);
		if($login!='ok')
		{
			echo json_encode(array('login'=>false,'reasion'=>$login));
			exit();			
		}	
		$q='SELECT ojerrorid,id,ojquestionid,times,nexttime,maxtimes,lasttime
		FROM '.constant('ojdb').'error
		WHERE '.constant('ojpro').'error.lasttime>?
		AND '.constant('ojpro').'error.id=?
		ORDER BY ojerrorid ASC
		';
		$st = $conn2->prepare($q);
		$st->bindParam(1,urldecode($_GET['lasttime']));
		$st->bindParam(2,$jry_wb_login_user['id']);
		$st->execute();
		$data=$st->fetchAll();	
		$total=count($data);
		$json=array();		
		for($i=0;$i<$total;$i++)
		{
			$json[$i]=	array(	'ojerrorid'=>$data[$i]['ojerrorid'],
								'id'=>$data[$i]['id'],
								'ojquestionid'=>$data[$i]['ojquestionid'],
								'times'=>$data[$i]['times'],
								'nexttime'=>$data[$i]['nexttime'],
								'maxtimes'=>$data[$i]['maxtimes'],
								'lasttime'=>$data[$i]['lasttime']
								);
		}
		echo json_encode($json);
		//echo '循环执行时间为：'.(microtime(true)-$start_time).' s';	
		
	}
	else if($action=='list')
	{
		$login=	jry_wb_print_head("",true,true,false,array('use'),false);
		if($login!='ok')
		{
			echo json_encode(array('login'=>false,'reasion'=>$login));
			exit();			
		}	
		function dfs($conn2,$father,$ceng)
		{
			if($ceng>4)
				return null;
			$q='SELECT *
			FROM '.constant('ojdb').'list
			WHERE '.constant('ojpro').'list.lasttime>?
			AND '.constant('ojpro').'list.father=?
			ORDER BY ojclassid ASC
			';
			$st = $conn2->prepare($q);
			$st->bindParam(1,urldecode($_GET['lasttime']));
			$st->bindParam(2,$father);
			$st->execute();
			$data=$st->fetchAll();
			$total=count($data);
			for($i=0;$i<$total;$i++)
			{
				$json[$i]=	array(	'ojclassid'=>$data[$i]['ojclassid'],
									'ojclassname'=>$data[$i]['ojclassname'],
									'ojclassaddid'=>$data[$i]['ojclassaddid'],
									'father'=>$data[$i]['father'],
									'lasttime'=>$data[$i]['lasttime'],
									'children'=>dfs($conn2,$data[$i]['ojclassid'],$ceng+1)
									);
			}
			return $json;
		}
		echo json_encode(dfs($conn2,0,0));
	}	
	else if($action=='logs')
	{
		$login=	jry_wb_print_head("",true,true,false,array('use'),false);
		if($login!='ok')
		{
			echo json_encode(array('login'=>false,'reasion'=>$login));
			exit();			
		}
		$q='SELECT ojlogid,time,logans,result,id,ojquestionid
		FROM '.constant('ojdb').'logs
		where '.constant('ojpro').'logs.time>?
		ORDER BY time DESC
		';
		$st = $conn2->prepare($q);
		$st->bindParam(1,urldecode($_GET['lasttime']));
		$st->execute();
		$data=$st->fetchAll();
		$total=count($data);
		$json=array();		
		for($i=0;$i<$total;$i++)
		{
			$json[$i]=	array(	'ojlogid'=>$data[$i]['ojlogid'],
								'time'=>$data[$i]['time'],
								'logans'=>$data[$i]['logans'],
								'result'=>$data[$i]['result'],
								'ojquestionid'=>$data[$i]['ojquestionid'],
								"id"=>$data[$i]['id']
								);
		}
		echo json_encode($json);
	}else if($action=='questionlist')
	{
		$login=	jry_wb_print_head("",true,true,false,array('use'),false);
		if($login!='ok')
		{
			echo json_encode(array('login'=>false,'reasion'=>$login));
			exit();			
		}
		$q='SELECT ojquestionid,ojquestionaddid,questiontype,question,lasttime
		FROM '.constant('ojdb').'questionlist
		WHERE '.constant('ojpro').'questionlist.lasttime>?
		ORDER BY '.constant('ojdb').'questionlist.ojquestionid ASC
		';
		$st = $conn2->prepare($q);
		$st->bindParam(1,urldecode($_GET['lasttime']));
		$st->execute();
		$data=$st->fetchAll();	
		$total=count($data);
		$json=array();		
		for($i=0;$i<$total;$i++)
		{
			$json[$i]=array(	'ojquestionid'=>$data[$i]['ojquestionid'],
								'question'=>($data[$i]['question']),
								'questiontype'=>$data[$i]['questiontype'],
								"ojquestionaddid"=>$data[$i]['ojquestionaddid'],
								"lasttime"=>$data[$i]['lasttime']
								);
		}
		echo json_encode($json);
	}
	else if($action=='link')
	{
		$login=	jry_wb_print_head("",true,true,false,array('use'),false);
		if($login!='ok')
		{
			echo json_encode(array('login'=>false,'reasion'=>$login));
			exit();			
		}
		$q='SELECT *
		FROM '.constant('ojdb').'link
		WHERE '.constant('ojpro').'link.lasttime>?
		ORDER BY '.constant('ojdb').'link.ojquestionid ASC
		';
		$st = $conn2->prepare($q);
		$st->bindParam(1,urldecode($_GET['lasttime']));
		$st->execute();
		$data=$st->fetchAll();	
		$total=count($data);
		$json=array();		
		for($i=0;$i<$total;$i++)
		{
			$json[$i]=array(	'ojquestionid'=>$data[$i]['ojquestionid'],
								'ojclassid'=>$data[$i]['ojclassid'],
								'lasttime'=>$data[$i]['lasttime']
								);
		}
		echo json_encode($json);
	}	
	else if($action=='manager')
	{
		$login=	jry_wb_print_head("",true,true,false,array('use','manage','manageoj'),false);
		if($login!='ok')
		{
			echo json_encode(array('login'=>false,'reasion'=>$login));
			exit();			
		}
		$q='SELECT id,ojclassid,lasttime
		FROM '.constant('ojdb').'manager
		WHERE '.constant('ojpro').'manager.lasttime>?
		AND '.constant('ojpro').'manager.id=?
		ORDER BY '.constant('ojdb').'manager.lasttime ASC
		';
		$st = $conn2->prepare($q);
		$st->bindParam(1,urldecode($_GET['lasttime']));
		$st->bindParam(2,$jry_wb_login_user['id']);
		$st->execute();
		$data=$st->fetchAll();
		$total=count($data);
		$json=array();		
		for($i=0;$i<$total;$i++)
		{
			$json[$i]=array(	'id'=>$data[$i]['id'],
								'ojclassid'=>$data[$i]['ojclassid'],
								'lasttime'=>$data[$i]['lasttime']
								);
		}
		echo json_encode($json);		
	}
?>