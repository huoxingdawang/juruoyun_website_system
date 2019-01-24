<?php
	include_once("../tools/jry_wb_includes.php");
//	exit();
	if(jry_wb_print_head('',true,true,true,array('use'),false)!='ok')
	{
		echo json_encode(array('login'=>false));
		exit();
	}
	global $ansid,$ojclassid,$conn2;
	$ansid			=$_POST['ansid'];
	$ojclassid		=json_decode(preg_replace('/(^\d\[\]\,)/i','',urldecode($_POST['ojclassid'])));
	$ans=$_POST['ans'];
	$isoption=$_POST['isoption'];
	$conn2=jry_wb_connect_database();
	function get_next_time($data,$error)
	{
		if($error)
			$minute=0.5;
		else if($data['times']>3)
			$minute=120;
		else if($data['times']>0)
			$minute=10;
		else if($data['times']>-2)//小于2次
			$minute=8;
		else if($data['times']>-4)//小于4次
			$minute=4;
		else if($data['times']>-6)//小于6次
			$minute=2;
		return date('Y-m-d H:i:s',time()+$minute*60);
	}
	function check_do_error_question()
	{
		global $ansid,$ojclassid,$conn2,$jry_wb_login_user;
		$q='SELECT '.constant('ojpro').'link.ojquestionid
		FROM '.constant('ojdb').'link , '.constant('ojdb').'error
		WHERE '.constant('ojpro').'link.ojclassid IN ('.implode(',',$ojclassid).')
		AND '.constant('ojpro').'error.ojquestionid = '.constant('ojpro').'link.ojquestionid
		AND '.constant('ojpro').'error.id = ?
		AND '.constant('ojpro').'error.nexttime < ?
		AND '.constant('ojpro').'error.times < 0
		AND '.constant('ojpro').'link.ojquestionid!=?			
		ORDER BY '.constant('ojpro').'error.times ASC
		';
		$st = $conn2->prepare($q);
		$st->bindParam(1,$jry_wb_login_user['id']);		
		$st->bindParam(2,jry_wb_get_time());
		$st->bindParam(3,$ansid);		
		$st->execute();
		return ($st->rowCount()==0)?false:array('ojquestionid'=>$st->fetchAll()[0]['ojquestionid'],'count'=>$st->rowCount());
	}
	function check_do_notdo_question()
	{
		global $ansid,$ojclassid,$conn2,$jry_wb_login_user;
		$q='SELECT '.constant('ojpro').'link.ojquestionid
		FROM '.constant('ojdb').'link
		WHERE '.constant('ojpro').'link.ojclassid IN ('.implode(',',$ojclassid).')
		AND '.constant('ojpro').'link.ojquestionid NOT IN ( 
			SELECT ojquestionid FROM 
			'.constant('ojdb').'error 
			WHERE '.constant('ojdb').'error.id=?
			)			
		AND '.constant('ojpro').'link.ojquestionid!=?			
		ORDER BY rand()
		';			
		$st = $conn2->prepare($q);	
		$st->bindParam(1,$jry_wb_login_user['id']);	
		$st->bindParam(2,$ansid);
		$st->execute();
		return ($st->rowCount()==0)?false:array('ojquestionid'=>$st->fetchAll()[0]['ojquestionid'],'count'=>$st->rowCount());
	}		
	function check_do_max_question()
	{
		global $ansid,$ojclassid,$conn2,$jry_wb_login_user;
		$q='SELECT '.constant('ojpro').'link.ojquestionid
		FROM '.constant('ojdb').'link , '.constant('ojdb').'error	
		WHERE '.constant('ojpro').'link.ojquestionid!=?
		AND '.constant('ojpro').'error.ojquestionid = '.constant('ojpro').'link.ojquestionid
		AND '.constant('ojpro').'error.id = ?
		AND '.constant('ojpro').'error.nexttime <?
		AND '.constant('ojpro').'link.ojclassid IN ('.implode(',',$ojclassid).')
		ORDER BY '.constant('ojpro').'error.maxtimes ASC
		';		
		$st = $conn2->prepare($q);					
		$st->bindParam(1,$ansid);	
		$st->bindParam(2,$jry_wb_login_user['id']);		
		$st->bindParam(3,jry_wb_get_time());
		$st->execute();
		return ($st->rowCount()==0)?false:array('ojquestionid'=>$st->fetchAll()[0]['ojquestionid'],'count'=>$st->rowCount());
	}
	$check='no';
	if($ansid!='no')
	{
		//检验正误
		$st = $conn2->prepare('SELECT ans,questiontype FROM '.constant('ojdb').'questionlist	WHERE '.constant('ojpro').'questionlist.ojquestionid=? LIMIT 1;');
		$st->bindParam(1,$ansid);$st->execute();foreach($st->fetchAll() as $questionlist);
		switch ($questionlist['questiontype'])
		{
			case 1://单选	
				$check=($ans==$questionlist['ans']);
				$mode=2;
				break;
			case 3://填空
				$check=($ans==$questionlist['ans']);
				$mode=1;
				break;
			case 2://单词
				if($isoption=="true")
				{
					$check=($ans==$questionlist['ans']);
					$mode=2;
				}
				else
				{
					$check=($ans==$questionlist['ans']);
					$mode=1;
				}
				break;
			case 4://编译
				include_once("compling_judge.php");
				compling_judge($ansid,$ans,$questionlist['ans']);
				$check=60;
				$mode=3;	
				exit();
				break;
		}
		$st = $conn2->prepare("SELECT times FROM ".constant("ojdb")."error WHERE id=? AND ojquestionid=?");
		$st->bindParam(1,$jry_wb_login_user['id']);$st->bindParam(2,$ansid);$st->execute();foreach($st->fetchAll() as $erroryuan);
		if($check===true)
			if($st->rowCount()==0)
				$q="INSERT INTO ".constant("ojdb")."error (times,maxtimes,nexttime,lasttime,id,ojquestionid) VALUES (0,0,:nexttime,:lasttime,:id,:ojquestionid);";
			else
				$q="UPDATE ".constant("ojdb")."error SET times=times+1,nexttime=:nexttime,lasttime=:lasttime WHERE id=:id AND ojquestionid=:ojquestionid;";
		else
			if($st->rowCount()==0)
				$q="INSERT INTO ".constant("ojdb")."error (times,maxtimes,nexttime,lasttime,id,ojquestionid) VALUES (-1,-1,:nexttime,:lasttime,:id,:ojquestionid);";
			else
				$q="UPDATE ".constant("ojdb")."error SET times=times-1,nexttime=:nexttime,lasttime=:lasttime,maxtimes= (case when maxtimes > times then times else maxtimes end)  WHERE id=:id AND ojquestionid=:ojquestionid;";
		$st = $conn2->prepare($q);
		$st->bindValue(':id',$jry_wb_login_user['id']);$st->bindValue(':ojquestionid',$ansid);$st->bindValue(':nexttime',get_next_time($erroryuan,$check));$st->bindValue(':lasttime',jry_wb_get_time());	$st->bindValue(':logans',$ans);
		$st->execute();
		//记录正误
		$q="INSERT INTO ".constant("ojdb")."logs (id,ojquestionid,`time`,logans,result,testmode) VALUES (:id,:ojquestionid,:lasttime,:logans,:result,:testmode);";
		$st = $conn2->prepare($q);
		$st->bindValue(':id',$jry_wb_login_user['id']);$st->bindValue(':ojquestionid',$ansid);$st->bindValue(':lasttime',jry_wb_get_time());$st->bindValue(':logans',$ans);$st->bindValue(':testmode',$mode);
		
		if($check===true)$st->bindValue(':result','right');
		else if($check===false)$st->bindValue(':result','error');
		else $st->bindValue(':result',$check);
		
		$st->execute();
		if($check===true)
			$q="UPDATE ".constant("ojdb")."questionlist SET submit=submit+1,`right`=`right`+1,lasttime=:lasttime WHERE ojquestionid=:ojquestionid;";
		else
			$q="UPDATE ".constant("ojdb")."questionlist SET submit=submit+1,lasttime=:lasttime WHERE ojquestionid=:ojquestionid;";
		$st = $conn2->prepare($q);
		$st->bindValue(':ojquestionid',$ansid);$st->bindValue(':lasttime',jry_wb_get_time());
		$st->execute();
	}
	$questionid=0;
	$count='N/A';
	if($check==false)
	{
		$reason='lasterror';
		$questionid=$ansid;
		$count='N/A';
	}else if($_POST['ojquestionid']!='rand')
	{
		$reason='point';
		$questionid=$_POST['ojquestionid'];
		$count='N/A';
	}else if(($questionid=check_do_error_question()))
	{
		$count=$questionid['count'];
		$questionid=$questionid['ojquestionid'];	
		$reason='error';
	}else if(($questionid=check_do_notdo_question()))
	{
		$count=$questionid['count'];
		$questionid=$questionid['ojquestionid'];		
		$reason='notdo';
	}else if(($questionid=check_do_max_question()))
	{
		$count=$questionid['count'];
		$questionid=$questionid['ojquestionid'];		
		$reason='max';
	}else
	{
		$count='N/A';
		$reason='rand';
	}	
	if($reason=='rand')
		$q='SELECT *
		FROM '.constant('ojdb').'link,'.constant('ojdb').'questionlist,'.constant('ojdb').'error
		WHERE '.constant('ojpro').'link.ojquestionid!=:ansid
		AND '.constant('ojpro').'link.ojclassid IN ('.implode(',',$ojclassid).') 
		AND '.constant('ojpro').'questionlist.ojquestionid = '.constant('ojpro').'link.ojquestionid
		AND '.constant('ojpro').'error.id = :id
		AND '.constant('ojpro').'error.ojquestionid = '.constant('ojpro').'link.ojquestionid
		ORDER BY rand()
		LIMIT 1
		';	
	else
		$q='SELECT * FROM '.constant('ojdb').'link,'.constant('ojdb').'questionlist
			LEFT JOIN '.constant('ojdb').'error ON(
				'.constant('ojpro').'error.id = :id
				AND '.constant('ojpro').'error.ojquestionid = '.constant('ojpro').'questionlist.ojquestionid
			)
			WHERE '.constant('ojpro').'questionlist.ojquestionid=:ojquestionid 
			AND '.constant('ojpro').'link.ojquestionid ='.constant('ojpro').'questionlist.ojquestionid
			LIMIT 1
		';
	$st = $conn2->prepare($q);
	$st->bindValue(':ansid',$ansid);$st->bindValue(':ojquestionid',$questionid);$st->bindValue(':id',$jry_wb_login_user['id']);
	$st->execute();
	foreach($st->fetchAll() as $questionlist);
	$questionlist['times']=$questionlist['times']!=NULL?$questionlist['times']:0;
	$questionlist['maxtimes']=$questionlist['maxtimes']!=NULL?$questionlist['maxtimes']:0;
	$questionlist['option']=json_decode($questionlist['option']);
	//特殊处理单词题
	if($questionlist['questiontype']==2&&$questionlist['times']<1)
	{
		$q='SELECT ans
		FROM '.constant('ojdb').'link,'.constant('ojdb').'questionlist
		WHERE '.constant('ojpro').'link.ojquestionid!=:ansid
		AND '.constant('ojpro').'link.ojclassid IN ('.implode(',',$ojclassid).') 
		AND '.constant('ojpro').'questionlist.ojquestionid = '.constant('ojpro').'link.ojquestionid
		ORDER BY rand()
		LIMIT 3
		';
		$st = $conn2->prepare($q);
		$st->bindValue(':ansid',$questionid);
		$st->bindValue(':id',$jry_wb_login_user['id']);
		$st->execute();
		$for_word=$st->fetchAll();
		$total=count($for_word);
		for($i=0;$i<$total;$i++)
			$questionlist['option'][$i]=array('ans'=>$for_word[$i]['ans']);
		$questionlist['option'][$total]=array('ans'=>$questionlist['ans']);
		shuffle($questionlist['option']); //随机排序数组 
	}
	
	
	echo json_encode(array( 'check'=>$check,
							'reason'=>$reason,
							'count'=>$count,
							
							'ojquestionid'=>$questionid,
							'ojquestionaddid'=>$questionlist['ojquestionaddid'],
							'question'=>$questionlist['question'],
							'ojclassid'=>json_encode($ojclassid),
							'ojclassid_self'=>$questionlist['ojclassid'],
							'questiontype'=>$questionlist['questiontype'],
							'option'=>$questionlist['option'],
							'source'=>$questionlist['source'],
							'ans'=>($check==false)?$questionlist['ans']:NULL,
							
							'times'=>$questionlist['times'],
							'maxtimes'=>$questionlist['maxtimes'],

							'submit'=>$questionlist['submit'],
							'right'=>$questionlist['right']
							)
					);
?>