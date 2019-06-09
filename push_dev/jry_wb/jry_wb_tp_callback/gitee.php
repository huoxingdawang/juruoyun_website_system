<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_tp_gitee_oauth_config.php");	
	$code=$_GET['code'];
	$ch=curl_init('https://gitee.com/oauth/token?grant_type=authorization_code&code='.$code.'&client_id='.constant('jry_wb_tp_gitee_oauth_config_client_id').'&redirect_uri='.constant('jry_wb_host') .'jry_wb_tp_callback/gitee.php'.'&client_secret='.constant('jry_wb_tp_gitee_oauth_config_client_secret'));
	curl_setopt($ch,CURLOPT_HEADER, 0);    
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch,CURLOPT_POST,1);	
	$access_token=json_decode(curl_exec($ch));
	curl_close($ch);	
	$ch=curl_init('https://gitee.com/api/v5/user?access_token='.$access_token->access_token);
	curl_setopt($ch,CURLOPT_HEADER, 0);    
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
	$data=curl_exec($ch);
	curl_close($ch);
	$data=json_decode($data);
	$data=(array('access_token'=>$access_token,'lasttime'=>jry_wb_get_time(),'message'=>$data));	
	$gitee_id=$data['message']->id;
	if($jry_wb_login_user['id']==-1)
	{
		$type=7;
		require(JRY_WB_LOCAL_DIR."/jry_wb_mainpages/do_login.php");
		$conn=jry_wb_connect_database();
		$q ="update ".JRY_WB_DATABASE_GENERAL."users set oauth_gitee=?,lasttime=? where id=? ";
		$st = $conn->prepare($q);
		$st->bindParam(1,json_encode($data));		
		$st->bindParam(2,jry_wb_get_time());
		$st->bindParam(3,$jry_wb_login_user['id']);
		$st->execute();
		exit();
	}
	$conn=jry_wb_connect_database();
	$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL."users WHERE oauth_gitee->'$.message.id'=? LIMIT 1");
	$st->bindParam(1,$gitee_id,PDO::PARAM_INT);
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
	$q ="update ".JRY_WB_DATABASE_GENERAL."users set oauth_gitee=?,lasttime=? where id=? ";
	$st = $conn->prepare($q);
	$st->bindParam(1,json_encode($data));		
	$st->bindParam(2,jry_wb_get_time());
	$st->bindParam(3,$jry_wb_login_user[id]);
	$st->execute();			
	jry_wb_print_head("绑定",false,false,false,array('use'),true,false);
	?>
	<script>
		jry_wb_loading_off();
		jry_wb_word_special_fact.switch=false;		
		jry_wb_cache.set('oauth_gitee','<?php  echo json_encode($data['message']);?>');
		jry_wb_beautiful_alert.alert("绑定成功",'<?php  echo $data['message']->name . $data['message']->login?>',function(){window.close();});
	</script>