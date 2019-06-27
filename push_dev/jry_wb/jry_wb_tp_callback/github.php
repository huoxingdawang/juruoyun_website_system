<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_tp_github_oauth_config.php");	
	$code=$_GET['code'];
	$ch=curl_init('https://github.com/login/oauth/access_token?client_id='.JRY_WB_TP_GITHUB_OAUTH_CLIENT_ID.'&client_secret='.JRY_WB_TP_GITHUB_OAUTH_CLIENT_SECRET.'&code='.$code.'&redirect_uri='.JRY_WB_HOST ."jry_wb_tp_callback/github.php");
	curl_setopt($ch,CURLOPT_HEADER, 0);    
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch,CURLOPT_POST,1);	
	$access_token=curl_exec($ch);
	curl_close($ch);
	$ch=curl_init('https://api.github.com/user?'.explode('&',$access_token)[0]);
	curl_setopt($ch,CURLOPT_USERAGENT,JRY_WB_TP_GITHUB_OAUTH_NAME);
	curl_setopt($ch,CURLOPT_HEADER, 0);    
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
	$data=curl_exec($ch);
	curl_close($ch);
	$data=json_decode($data);
	if($data->message=='Bad credentials'||$data->message=='Not Found')
	{
		jry_wb_print_head("gayhub错误",false,false,false,array('use'),true,false);
		?>
		<script>
			jry_wb_loading_off();
			jry_wb_word_special_fact.switch=false;
			jry_wb_beautiful_alert.alert("gayhub错误",'<?php  echo $data->message; ?>',function(){window.close();});
		</script>
		<?php
		exit();
	}
	$data=(array('access_token'=>explode('=',explode('&',$access_token)[0])[1],'lasttime'=>jry_wb_get_time(),'message'=>$data));	
	$github_id=$data['message']->node_id;
	$conn=jry_wb_connect_database();
	if($jry_wb_login_user['id']==-1)
	{
		$type=5;
		require(JRY_WB_LOCAL_DIR."/jry_wb_mainpages/do_login.php");
		$st=$conn->prepare('UPDATE '.JRY_WB_DATABASE_GENERAL.'users SET oauth=JSON_REPLACE(IF(ISNULL(oauth->\'$.github\'),JSON_INSERT(IFNULL(oauth,JSON_OBJECT()),\'$.github\',NULL),oauth),\'$.github\',CONVERT(?,JSON)),lasttime=? WHERE id=? ');
		$st->bindValue(1,json_encode($data));		
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$jry_wb_login_user['id']);
		$st->execute();	
		exit();
	}
	$st=$conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL."users WHERE oauth->'$.github.message.node_id'=? LIMIT 1");
	$st->bindValue(1,$github_id);
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
	$st=$conn->prepare('UPDATE '.JRY_WB_DATABASE_GENERAL.'users SET oauth=JSON_REPLACE(IF(ISNULL(oauth->\'$.github\'),JSON_INSERT(IFNULL(oauth,JSON_OBJECT()),\'$.github\',NULL),oauth),\'$.github\',CONVERT(?,JSON)),lasttime=? WHERE id=? ');
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
		jry_wb_cache.set('oauth_github','<?php  echo json_encode($data['message']);?>');
		jry_wb_beautiful_alert.alert("绑定成功",'<?php  echo $data['message']->name . $data['message']->login?>',function(){window.close();});
	</script>