<?php
	include_once("../tools/jry_wb_includes.php");
	try{jry_wb_check_compentence(NULL,array('use','manage','managecompentence'));}catch(jry_wb_exception $e){echo $e->getMessage();exit();}
	$conn2=jry_wb_connect_database();
	$st = $conn2->prepare("SHOW FULL COLUMNS FROM ".constant('jry_wb_database_manage_system')."competence");
	$st->execute();
	$names=$st->fetchAll();
	$st = $conn2->prepare("select * from ".constant('jry_wb_database_manage_system')."competence");
	$st->execute();
	$json=[];
	$competences=$st->fetchAll();
	foreach($competences as $competence)
	{
		$one=[];
		foreach($names as $name)
		{
			if($name['Field']!='type')
				$one[]=array('name'=>$name['Field'],'value'=>$competence[$name['Field']]);
			else
				$type=$competence[$name['Field']];
		}
		$json[]=array('type'=>$type,'data'=>$one);
	}
	echo json_encode($json);
?>