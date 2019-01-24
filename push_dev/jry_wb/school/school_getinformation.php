<?php	
	include_once("../tools/jry_wb_includes.php");
	$action=$_GET['action'];
	$conn2=jry_wb_connect_database();
	if($action=='schoolall')
	{
		$login=	jry_wb_print_head("",true,true,false,array('use'),false);
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