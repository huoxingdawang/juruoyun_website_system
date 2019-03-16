<?php
	include_once("../tools/jry_wb_includes.php");
	include_once(constant('jry_wb_local_dir')."/jry_wb_tp_sdk/qq/oauth/qqConnectAPI.php");
	$qc = new QC();
	$data=(array('access_token'=>$qc->qq_callback(),'openid'=>$qc->get_openid()));
	$qc = new QC($data['access_token'],$data['openid']);
	$data['message']=$qc->get_user_info();
	$data['lasttime']=jry_wb_get_time();
	$open_id=$data['openid'];
	$access_token=$data['access_token'];
	try
	{
		jry_wb_print_head("",true,false,false,array(),false,false);
	}
	catch(jry_wb_exception $e)
	{
		$type=4;
		require(constant('jry_wb_local_dir')."/jry_wb_mainpages/do_login.php");
		$conn=jry_wb_connect_database();
		$q ="update ".constant('jry_wb_database_general')."users set oauth_qq=?,lasttime=? where id=? ";
		$st = $conn->prepare($q);
		$st->bindParam(1,json_encode($data));		
		$st->bindParam(2,jry_wb_get_time());
		$st->bindParam(3,$users['id']);
		$st->execute();			
		exit();
	}
	$conn=jry_wb_connect_database();
	$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general')."users WHERE oauth_qq->'$.openid'=? AND oauth_qq->'$.access_token'=? LIMIT 1");
	$st->bindParam(1,$open_id);
	$st->bindParam(2,$access_token);
	$st->execute();
	foreach($st->fetchAll()as $users);
	if($users!=NULL)
	{
		jry_wb_print_head("绑定失败",false,false,false,array('use'),true,false);
		?>
		<script>
			jry_wb_loading_off();
			jry_wb_word_special_fact.switch=false;		
			jry_wb_beautiful_alert.alert("绑定失败",'绑定过了',function(){window.close();});
		</script>
		<?php
		exit();
	}
	$conn=jry_wb_connect_database();
	$q ="update ".constant('jry_wb_database_general')."users set oauth_qq=?,lasttime=? where id=? ";
	$st = $conn->prepare($q);
	$st->bindParam(1,json_encode($data));		
	$st->bindParam(2,jry_wb_get_time());
	$st->bindParam(3,$jry_wb_login_user['id']);
	$st->execute();			
	jry_wb_print_head("绑定",false,false,false,array('use'),true,false);
	?>
	<script>
		jry_wb_loading_off();
		jry_wb_word_special_fact.switch=false;
		jry_wb_cache.set('oauth_qq','<?php  echo json_encode($data['message']);?>')
		jry_wb_beautiful_alert.alert("绑定成功",'<?php  echo $data['message']['nickname']?>',function(){window.close();});
	</script>