<?php	
	include_once("../tools/jry_wb_includes.php");
	$action=$_GET['action'];
	$conn2=jry_wb_connect_database();
	try{jry_wb_check_compentence();}catch(jry_wb_exception $e){echo $e->getMessage();exit();}
	if($action=='schoolall')
	{
		if($login)
		{
			
		}
		$q='SELECT * FROM '.JRY_WB_DATABASE_SCHOOL.'list ';
		$st = $conn2->prepare($q);
		$st->execute();	
		$data=[];
		foreach($st->fetchAll() as $one)
		{
			$data[]=array(	'school_id'=>$one['school_id'],
							'school_name'=>$one['school_name'],
							'waiting'=>$one['waiting'],
							'number'=>$one['number'],
							'enter_config'=>json_decode($one['enter_config'])
			);
		}
		echo json_encode($data);
		exit();
	} 

?>