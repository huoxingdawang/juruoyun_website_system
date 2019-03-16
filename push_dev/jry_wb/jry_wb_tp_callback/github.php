<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_tp_github_oauth_config.php");	
	$code=$_GET['code'];
	$ch=curl_init('https://github.com/login/oauth/access_token?client_id='.constant('jry_wb_tp_github_oauth_config_client_id').'&client_secret='.constant('jry_wb_tp_github_oauth_config_client_secret').'&code='.$code.'&redirect_uri='.constant('jry_wb_host') ."jry_wb_tp_callback/github.php");
	curl_setopt($ch,CURLOPT_HEADER, 0);    
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch,CURLOPT_POST,1);	
	$access_token=curl_exec($ch);
	curl_close($ch);
	$ch=curl_init('https://api.github.com/user?'.explode('&',$access_token)[0]);
	curl_setopt($ch,CURLOPT_USERAGENT,constant('jry_wb_tp_github_oauth_config_name'));
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
	try
	{
		jry_wb_print_head("",true,false,false,array(),false,false);
	}
	catch(jry_wb_exception $e)
	{
		$type=5;
		require(constant('jry_wb_local_dir')."/jry_wb_mainpages/do_login.php");
		$conn=jry_wb_connect_database();
		$q ="update ".constant('jry_wb_database_general')."users set oauth_github=?,lasttime=? where id=? ";
		$st = $conn->prepare($q);
		$st->bindParam(1,json_encode($data));		
		$st->bindParam(2,jry_wb_get_time());
		$st->bindParam(3,$users[id]);
		$st->execute();	
		exit();
	}
	$conn=jry_wb_connect_database();
	$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general')."users WHERE oauth_github->'$.message.node_id'=? LIMIT 1");
	$st->bindParam(1,$github_id);
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
	$q ="update ".constant('jry_wb_database_general')."users set oauth_github=?,lasttime=? where id=? ";
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
		jry_wb_cache.set('oauth_github','<?php  echo json_encode($data['message']);?>');
		jry_wb_beautiful_alert.alert("绑定成功",'<?php  echo $data['message']->name . $data['message']->login?>',function(){window.close();});
	</script>