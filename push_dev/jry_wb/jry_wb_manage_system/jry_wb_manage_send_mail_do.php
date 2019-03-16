<?php
	include_once("../tools/jry_wb_includes.php");
	try
	{
		jry_wb_print_head("发邮件的",true,false,false,array('use','manage','usemailsender'),false);
	}
	catch(jry_wb_exception $e)
	{
		echo $e->getMessage();
		exit();
	}
	$conn=jry_wb_connect_database();
	$st = $conn->prepare("SELECT mail,name,id FROM ".constant('jry_wb_database_general')."users ORDER BY id DESC;");
	$st->execute();
	$dataall=$st->fetchAll();
	$ans=[];
	foreach($dataall as $data)
	{
		if($data['mail']!='')
		{
			if(jry_wb_send_mail($data['mail'],
			$_POST['title'],
			'尊敬的'.constant('jry_wb_name').'用户'.$data['id'].'('.$data['name'].')，您好：<br>'.
			$_POST['data'].'<br>'.
			constant('jry_wb_name').'开发组，'.constant('jry_wb_name').'管理组 '.jry_wb_get_time().
			'<br> powered by juruoyun web system '.constant('jry_wb_version')
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