<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	try{jry_wb_check_compentence(NULL,array('use','manage','managecompentence'));}catch(jry_wb_exception $e){echo $e->getMessage();exit();}
	$conn2=jry_wb_connect_database();
	$st = $conn2->prepare("SHOW FULL COLUMNS FROM ".JRY_WB_DATABASE_MANAGE_SYSTEM."competence");
	$st->execute();
	$names=$st->fetchAll();
	$st = $conn2->prepare("select * from ".JRY_WB_DATABASE_MANAGE_SYSTEM."competence");
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
	echo json_encode(array('code'=>true,'data'=>$json));
?>