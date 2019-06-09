<?php
	include_once("../tools/jry_wb_includes.php");
	try{jry_wb_check_compentence(NULL,array('use','manage','usemailsender'));}catch(jry_wb_exception $e){echo $e->getMessage();exit();}
	$conn=jry_wb_connect_database();
	$st = $conn->prepare("SELECT mail,name,id FROM ".JRY_WB_DATABASE_GENERAL."users ORDER BY id DESC;");
	$st->execute();
	$dataall=$st->fetchAll();
	$ans=[];
	foreach($dataall as $data)
	{
		if($data['mail']!='')
		{
			if(jry_wb_send_mail($data['mail'],
			$_POST['title'],
			'尊敬的'.JRY_WB_NAME.'用户'.$data['id'].'('.$data['name'].')，您好：<br>'.
			$_POST['data'].'<br>'.
			JRY_WB_NAME.'开发组，'.JRY_WB_NAME.'管理组 '.jry_wb_get_time().
			'<br> powered by juruoyun web system '.JRY_WB_VERSION
			))
				$ans[]=(array('id'=>$data['id'],'data'=>'OK'));
			else
				$ans[]=(array('id'=>$data['id'],'data'=>'notok'));
		}
		else
			$ans[]=(array('id'=>$data['id'],'data'=>'nomail'));
	}
	echo json_encode($ans);
?>