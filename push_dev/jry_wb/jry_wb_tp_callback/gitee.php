<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_tp_gitee_oauth_config.php");	
	$code=$_GET['code'];
	$ch=curl_init('https://gitee.com/oauth/token?grant_type=authorization_code&code='.$code.'&client_id='.JRY_WB_TP_GITEE_OAUTH_CLIENT_ID.'&redirect_uri='.JRY_WB_HOST .'jry_wb_tp_callback/gitee.php'.'&client_secret='.JRY_WB_TP_GITEE_OAUTH_CLIENT_SECRET);
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
	$conn=jry_wb_connect_database();
	if($jry_wb_login_user['id']==-1)
	{
		$type=7;
		require(JRY_WB_LOCAL_DIR."/jry_wb_mainpages/do_login.php");
		$st=$conn->prepare('UPDATE '.JRY_WB_DATABASE_GENERAL.'users SET oauth=JSON_REPLACE(IF(ISNULL(oauth->\'$.gitee\'),JSON_INSERT(IFNULL(oauth,JSON_OBJECT()),\'$.gitee\',NULL),oauth),\'$.gitee\',CONVERT(?,JSON)),lasttime=? WHERE id=? ');
		$st = $conn->prepare($q);
		$st->bindValue(1,json_encode($data));		
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$jry_wb_login_user['id']);
		$st->execute();
		exit();
	}
	$st=$conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL."users WHERE oauth->'$.gitee.message.id'=? LIMIT 1");
	$st->bindValue(1,$gitee_id,PDO::PARAM_INT);
	$st->execute();
	foreach($st->fetchAll()as $users);
	if($users!=NULL)
	{
		jry_wb_print_head("绑定失败",false,false,false,array('use'),true,false);
		?>
		<script>
			jry_wb_loading_off();
			jry_wb_word_special_fact.switch=false;
			jry_wb_js_session.close=true;
			jry_wb_beautiful_alert.alert("绑定失败",'绑定过了',function(){window.close();});
		</script>
		<?php
		exit();
	}
	$st=$conn->prepare('UPDATE '.JRY_WB_DATABASE_GENERAL.'users SET oauth=JSON_REPLACE(IF(ISNULL(oauth->\'$.gitee\'),JSON_INSERT(IFNULL(oauth,JSON_OBJECT()),\'$.gitee\',NULL),oauth),\'$.gitee\',CONVERT(?,JSON)),lasttime=? WHERE id=? ');
	$st->bindValue(1,json_encode($data));		
	$st->bindValue(2,jry_wb_get_time());
	$st->bindValue(3,$jry_wb_login_user[id]);
	$st->execute();			
	jry_wb_print_head("绑定",false,false,false,array('use'),true,false);
	?>
	<script>
		jry_wb_loading_off();
		jry_wb_word_special_fact.switch=false;
		jry_wb_js_session.close=true;
		jry_wb_cache.set('oauth_gitee','<?php  echo json_encode($data['message']);?>');
		jry_wb_beautiful_alert.alert("绑定成功",'<?php  echo $data['message']->name . $data['message']->login?>',function(){window.close();});
	</script>