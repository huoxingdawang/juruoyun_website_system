<?php
	include_once("jry_wb_online_judge_includes.php");
	$q='SELECT 	'.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'question_list.question_id
		FROM 	'.JRY_WB_DATABASE_ONLINE_JUDGE.'question_list , '.JRY_WB_DATABASE_ONLINE_JUDGE.'error 
		WHERE  	'.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'question_list.question_id='.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'error.question_id
		AND		'.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'error.id=?
		AND		'.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'error.times<0
		AND		'.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'error.question_id!=?
		AND		'.JRY_WB_DATABASE_ONLINE_JUDGE_PREFIX.'error.extern->\'$.nexttime\' < ?		
		';	
	$a=0;
	$class=array(1);
	foreach($class as $c)
		($q.=((($a++)==0?' AND (':' OR ').' JSON_CONTAINS(class,?) '));
	if(count($class)!=0)
		$q.=')';
	echo $q;
	$st = $conn->prepare($q);
	$st->bindValue(1,1);		
	$st->bindValue(2,1);
	$st->bindValue(3,jry_wb_get_time());
	$i=4;
	foreach($class as $c)
		$st->bindParam($i++,json_encode($c));
	$st->execute();	
	echo json_encode($st->fetchAll());
?>