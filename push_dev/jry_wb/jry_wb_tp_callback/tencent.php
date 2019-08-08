<?php
	session_start();
	$_SESSION['QC_userData']['state'] = $_GET['state'];	
	include_once("../jry_wb_tools/jry_wb_includes.php");
	include_once(JRY_WB_LOCAL_DIR."/jry_wb_tp_sdk/qq/oauth/qqConnectAPI.php");
	$qc = new QC();
	$data=(array('access_token'=>$qc->qq_callback(),'openid'=>$qc->get_openid()));
	$qc = new QC($data['access_token'],$data['openid']);
	$data['message']=$qc->get_user_info();
	$data['lasttime']=jry_wb_get_time();
	$open_id=$data['openid'];
	$access_token=$data['access_token'];
	$conn=jry_wb_connect_database();
	if($jry_wb_login_user['id']==-1)
	{
		$type=4;
		require(JRY_WB_LOCAL_DIR."/jry_wb_mainpages/do_login.php");
		$st=$conn->prepare('UPDATE '.JRY_WB_DATABASE_GENERAL.'users SET oauth=JSON_REPLACE(IF(ISNULL(oauth->\'$.qq\'),JSON_INSERT(IFNULL(oauth,JSON_OBJECT()),\'$.qq\',NULL),oauth),\'$.qq\',CONVERT(?,JSON)),lasttime=? WHERE id=? ');
		$st->bindValue(1,json_encode($data));		
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$jry_wb_login_user['id']);
		$st->execute();			
		exit();
	}
	$st=$conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'users WHERE oauth->\'$.qq.openid\'=? AND oauth->\'$.qq.access_token\'=? LIMIT 1');
	$st->bindValue(1,$open_id);
	$st->bindValue(2,$access_token);
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
	$st=$conn->prepare('UPDATE '.JRY_WB_DATABASE_GENERAL.'users SET oauth=JSON_REPLACE(IF(ISNULL(oauth->\'$.qq\'),JSON_INSERT(IFNULL(oauth,JSON_OBJECT()),\'$.qq\',NULL),oauth),\'$.qq\',CONVERT(?,JSON)),lasttime=? WHERE id=? ');
	$st->bindValue(1,json_encode($data));		
	$st->bindValue(2,jry_wb_get_time());
	$st->bindValue(3,$jry_wb_login_user['id']);
	$st->execute();			
	jry_wb_print_head("绑定",false,false,false,array('use'),true,false);
	?>
	<script>
		jry_wb_loading_off();
		jry_wb_word_special_fact.switch=false;
		jry_wb_js_session.close=true;
		jry_wb_cache.set('oauth_qq','<?php  echo json_encode($data['message']);?>')
		jry_wb_beautiful_alert.alert("绑定成功",'<?php  echo $data['message']['nickname']?>',function(){window.close();});
	</script>