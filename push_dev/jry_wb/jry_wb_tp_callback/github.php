<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_tp_github_oauth_config.php");	
	$login=jry_wb_print_head("",true,false,false,array(),false,false);
	$code=$_GET['code'];
	$ch=curl_init('https://github.com/login/oauth/access_token?client_id='.constant('jry_wb_tp_github_oauth_config_client_id').'&client_secret='.constant('jry_wb_tp_github_oauth_config_client_secret').'&code='.$code);
	curl_setopt($ch,CURLOPT_HEADER, 0);    
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch,CURLOPT_POST,1);	
	$access_token=curl_exec($ch);
	curl_close($ch);
	$ch=curl_init('https://api.github.com/user?access_token='.$access_token);
	curl_setopt($ch,CURLOPT_USERAGENT,constant('jry_wb_tp_github_oauth_config_name'));
	curl_setopt($ch,CURLOPT_HEADER, 0);    
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
	$data=curl_exec($ch);
	curl_close($ch);
	$data=json_decode($data);
	if($data->message=='Bad credentials')
	{
		jry_wb_print_head("gayhub错误",false,false,true,array('use'),true,false);
		?>
		<script>
			jry_wb_beautiful_alert.alert("gayhub错误",'Bad credentials',function(){window.close();});
		</script>
		<?php
		jry_wb_print_tail();
		exit();
	}
	if($login!='ok')//登录部分
	{
		$type=5;
		$github_id=$data->id;
		require(constant('jry_wb_local_dir')."/jry_wb_mainpages/do_login.php");
	}
	else
	{
		$conn=jry_wb_connect_database();
		$q ="update ".constant('jry_wb_database_general')."users set oauth_github=?,lasttime=? where id=? ";
		$st = $conn->prepare($q);
		$st->bindParam(1,json_encode($data));		
		$st->bindParam(2,jry_wb_get_time());
		$st->bindParam(3,$jry_wb_login_user[id]);
		$st->execute();			
		jry_wb_print_head("绑定",false,false,true,array('use'),true,false);
		?>
		<script>
			jry_wb_cache.set('oauth_github','<?php  echo json_encode($data);?>');
			jry_wb_beautiful_alert.alert("绑定成功",'<?php  echo $data->name . $data->login?>',function(){window.close();});
		</script>
		<?php
	}	
?>