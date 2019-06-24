<?php	
	include_once("../jry_wb_tools/jry_wb_includes.php");
	$action=$_GET['action'];
	try{jry_wb_check_compentence();}catch(jry_wb_exception $e){echo $e->getMessage();exit();}			
	$conn2=jry_wb_connect_database();
	if($action=='schoolall')
	{
		if($login!='ok')
		{
			echo json_encode(array('login'=>false,'reasion'=>$login));
			exit();			
		}
		$q='SELECT * FROM '.constant('schooldb').'list ';
		$st = $conn2->prepare($q);
		$st->execute();	
		print(urldecode (json_encode($st->fetchAll())));	
		exit();
	} 

?>