<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_user_extern_message.php");	
	try
	{
		jry_wb_print_head("控制系统",true,false,false,array('use','manage','manageusers'),false);
	}
	catch(jry_wb_exception $e)
	{
		echo $e->getMessage();
		exit();
	}
	if($_GET['action']=='')
	{
		$id=(int)$_GET['id'];
		if($_POST==null)
		{
			echo json_encode(array('code'=>false,'reason'=>400000));
			exit();
		}
		$cmd="UPDATE ".constant('jry_wb_database_general')."users SET ";
		foreach ($_POST as $key => $value) 
			$cmd.='`'.(preg_replace('/[^a-zA-Z]/','',urldecode($key))."`=?,");
		$cmd.=" lasttime=? WHERE id=? LIMIT 1;";
		$conn2=jry_wb_connect_database();
		$st = $conn2->prepare($cmd);
		$i=1;
		foreach ($_POST as $key => &$value) 
		{
			$st->bindParam($i,$value);
			$i++;
		}
		$st->bindParam($i,jry_wb_get_time());
		$st->bindParam($i+1,$id);
		$st->execute();
		echo json_encode(array('code'=>true));
	}else if($_GET['action']=='name_not_ok')
	{
		$id=(int)$_GET['id'];		
		$cmd="UPDATE ".constant('jry_wb_database_general')."users SET `use`=0 , `lasttime`=? WHERE id=? LIMIT 1;";
		$conn2=jry_wb_connect_database();
		$st = $conn2->prepare($cmd);
		$st->bindParam(1,jry_wb_get_time());
		$st->bindParam(2,$id);
		$st->execute();
		$cmd="SELECT mail,name,id FROM ".constant('jry_wb_database_general')."users WHERE id=? LIMIT 1;";
		$st = $conn2->prepare($cmd);
		$st->bindParam(1,$id);		
		$st->execute();
		$data=$st->fetchAll()[0];
		if(jry_wb_send_mail($data['mail'],
		'昵称不合法被禁用通知',
		'尊敬的'.constant('jry_wb_name').'用户'.$data['id'].'('.$data['name'].')，您好：<br>'.
		'您的账号 '.$data['id'].' 的昵称 "'.$data['name'].'"在 '.jry_wb_get_time().' 被管理员认为不合法<br>'.
		'可能的原因是违反了<a href="'.constant('jry_wb_host').'mainpages/xieyi.php">蒟蒻云用户协议</a>，或相关法律法规，当然不排除您的昵称使管理员恶心呕吐导致管理员电脑损坏的可能性<br>'.
		'请您及时前往<a href="'.constant('jry_wb_host').'mainpages/chenge.php">蒟蒻云用户中心</a>进行修改<br>'.
		'蒟蒻云管理组感谢您的配合以及对国家相关法律法规的遵守<br>'.
		constant('jry_wb_name').'开发组，'.constant('jry_wb_name').'管理组 '.jry_wb_get_time()
		))
			echo json_encode(array('code'=>true));
		else
			echo json_encode(array('code'=>false,'reason'=>300001));			
	}else if($_GET['action']=='bangyouxiang')
	{
		$q='SELECT tel,name FROM '.constant('jry_wb_database_general').'users WHERE id = ?';
		$st = $conn->prepare($q);
		$st->bindParam(1,$_GET['id']);
		$st->execute();	
		$user=$st->fetchAll()[0];
		require_once "../tools/jry_wb_short_message.php";
		jry_wb_send_short_message($user['tel'],Array ("name"=>$user['name']),constant('jry_wb_short_message_aly_connect_mail')); 	
		echo json_encode(array('code'=>true));
		exit();
	}
	else if($_GET['action']=='unlock')
	{
		$conn2=jry_wb_connect_database();		
		$id=(int)$_GET['id'];
		$cmd="SELECT mail,name,id FROM ".constant('jry_wb_database_general')."users WHERE id=? LIMIT 1;";
		$st = $conn2->prepare($cmd);
		$st->bindParam(1,$id);		
		$st->execute();
		$data=$st->fetchAll()[0];
		if(jry_wb_send_mail($data['mail'],
		'解封通知',
		'尊敬的用户，您好：<br>您的账号 '.$data['id'].' 在 '.jry_wb_get_time().' 被管理员认为合法,已被解封<br>'.
		'蒟蒻云管理组感谢您的耐心等待以及对国家相关法律法规的遵守<br>'.
		'蒟蒻云开发组，蒟蒻云管理组 '.jry_wb_get_time()
		))
			echo json_encode(array('code'=>true));
		else
			echo json_encode(array('code'=>false,'reason'=>300001));
	}
	else if($_GET['action']=='print')
	{
		header("Content-Type: application/vnd.ms-excel");
		Header("Accept-Ranges:bytes");
		Header("Content-Disposition:attachment;filename=".constant('jry_wb_name')."用户列表".jry_wb_get_time().".xls");
		header("Pragma: no-cache");
		header("Expires: 0");		
		echo '
			<html xmlns:o="urn:schemas-microsoft-com:office:office"
			xmlns:x="urn:schemas-microsoft-com:office:excel"
			xmlns="http://www.w3.org/TR/REC-html40">
			<head>
			<meta http-equiv="expires" content="Mon, 06 Jan 1999 00:00:01 GMT">
			<meta http-equiv=Content-Type content="text/html; charset=gb2312">
			<!--[if gte mso 9]><xml>
			<x:ExcelWorkbook>
			<x:ExcelWorksheets>
			<x:ExcelWorksheet>
			<x:Name></x:Name>
			<x:WorksheetOptions>
			<x:DisplayGridlines/>
			</x:WorksheetOptions>
			</x:ExcelWorksheet>
			</x:ExcelWorksheets>
			</x:ExcelWorkbook>
			</xml><![endif]-->
			</head>';

		echo "<table>";
		$keys=['id','tel','mail','name','sex','enroldate'];
		$extern_keys=['school','qq','china_id','parent_china_id','parent_tel','parent_name','guanxi','zhiyuan1','zhiyuan2','zhiyuan3','chifan','zhusu'];
		echo '<tr>';
		foreach($keys as $key)
			echo '<td>'.iconv('utf-8','gbk',$jry_wb_config_user_name[$key]).'</td>';
		foreach($jry_wb_config_user_extern_message as $one)
			foreach($extern_keys as $key)
				if($key==$one['key'])
					echo '<td>'.iconv('utf-8','gbk',$one['name']).'</td>';
		echo '</tr>';
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users WHERE `type`=?');
		$st->bindValue(1,4);
		$st->execute();			
		foreach($st->fetchAll() as $user)
		{
			$user['extern']=json_decode($user['extern'],true);
			echo '<tr>';
			foreach($keys as $key)
				if($key=='sex')
					echo '<td style="vnd.ms-excel.numberformat:@">'.iconv('utf-8','gbk',($user['sex']==0)?'女':($user['sex']==1?'男':'女装大佬')).'</td>';
				else if($key=='lasttime'||$key=='enroldate'||$key=='logdate'||$key=='greendate')
					echo '<td style="vnd.ms-excel.numberformat:@">'.iconv('utf-8','gbk',$user[$key]).'</td>';
				else if($key=='use')
					echo '<td style="vnd.ms-excel.numberformat:@">'.iconv('utf-8','gbk',($user['use']==0)?'禁止':'允许').'</td>';
				else if(is_string($user[$key]))
					echo '<td style="vnd.ms-excel.numberformat:@">'.iconv('utf-8','gbk',$user[$key]).'</td>';
				else
					echo '<td style="vnd.ms-excel.numberformat:@">'.$user[$key].'</td>';
			foreach($jry_wb_config_user_extern_message as $one)
				foreach($extern_keys as $key)
					if($key==$one['key'])
					{
						if($one['type']=='word'||$one['type']=='tel'||$one['type']=='mail'||$one['type']=='china_id')
							echo '<td style="vnd.ms-excel.numberformat:@">'.iconv('utf-8','gbk',$user['extern'][$key]).'</td>';
						else if($one['type']=='check')
							echo '<td style="vnd.ms-excel.numberformat:@">'.iconv('utf-8','gbk',($user['extern'][$key]==1?'是':'否')).'</td>';
						else if($one['type']=='select')
							foreach($one['select'] as $select)
								if(is_array($select))
								{
									if($user['extern'][$key]==$select['value'])
										echo '<td style="vnd.ms-excel.numberformat:@">'.iconv('utf-8','gbk',$select['name']).'</td>';
								}
								else
								{
									if($user['extern'][$key]==$select)
										echo '<td style="vnd.ms-excel.numberformat:@">'.iconv('utf-8','gbk',$select).'</td>';
								}
						else
							echo '<td>undefined</td>';
					}						
			echo '</tr>';			
		}
		echo "</table>";
		exit ();		
	}
?>