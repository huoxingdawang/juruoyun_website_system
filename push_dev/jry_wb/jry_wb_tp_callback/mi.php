<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_tp_mi_oauth_config.php");
	require_once(JRY_WB_LOCAL_DIR.'/jry_wb_tp_sdk/mi/utils/XMUtil.php');
	require_once(JRY_WB_LOCAL_DIR.'/jry_wb_tp_sdk/mi/utils/AccessToken.php');
	require_once(JRY_WB_LOCAL_DIR.'/jry_wb_tp_sdk/mi/httpclient/XMHttpClient.php');
	require_once(JRY_WB_LOCAL_DIR.'/jry_wb_tp_sdk/mi/httpclient/XMOAuthClient.php');
	require_once(JRY_WB_LOCAL_DIR.'/jry_wb_tp_sdk/mi/httpclient/XMApiClient.php');
	$code=$_GET["code"];
	if($code)
	{
		$oauthClient=new XMOAuthClient(JRY_WB_TP_MI_OAUTH_CLIENT_ID,JRY_WB_TP_MI_OAUTH_CLIENT_SECRET);
		$oauthClient->setRedirectUri(JRY_WB_HOST.'/jry_wb_tp_callback/mi.php');	
		$token=$oauthClient->getAccessTokenByAuthorizationCode($code);
		if($token)
		{
			if($token->isError())
			{
				$errorNo=$token->getError();
				$errordes=$token->getErrorDescription();
				echo "errorno:".$errorNo."errordescription:".$errordes."<br>";
				exit();
			}
			else
			{
				$tokenId=$token->getAccessTokenId();
				$refreshtoken=$token->getRefreshToken();
				$xmApiClient=new XMApiClient(JRY_WB_TP_MI_OAUTH_CLIENT_ID,$tokenId);
				$nonce=XMUtil::getNonce();
				$path='/user/profile';
				$method="GET";
				$params=array('token'=>$tokenId,"clientId"=>JRY_WB_TP_MI_OAUTH_CLIENT_ID);
				$sign=XMUtil::buildSignature($nonce,$method,$xmApiClient->getApiHost(),$path,$params,$token->getMacKey());
				$head=XMUtil::buildMacRequestHead($tokenId,$nonce,$sign);
				$data=$xmApiClient->callApi($path,$params,false,$head)['data'];
			}
		}
		else
		{
			echo "GettokenError";
			exit();
		}
	}
	else
	{
		echo "Getcodeerror:".$_GET["error"]."errordescription:".$_GET["error_description"];
		exit();
	}	
	$data=(array('access_token'=>$tokenId,'refreshtoken'=>$refreshtoken,'lasttime'=>jry_wb_get_time(),'message'=>$data));		
	$unionId=$data['message']['unionId'];
	$conn=jry_wb_connect_database();		
	if($jry_wb_login_user['id']==-1)
	{
		$type=6;
		require(JRY_WB_LOCAL_DIR."/jry_wb_mainpages/do_login.php");
		$st=$conn->prepare('UPDATE '.JRY_WB_DATABASE_GENERAL.'users SET oauth=JSON_REPLACE(IF(ISNULL(oauth->\'$.mi\'),JSON_INSERT(IFNULL(oauth,JSON_OBJECT()),\'$.mi\',NULL),oauth),\'$.mi\',CONVERT(?,JSON)),lasttime=? WHERE id=? ');
		$st->bindValue(1,json_encode($data));		
		$st->bindValue(2,jry_wb_get_time());
		$st->bindValue(3,$jry_wb_login_user['id']);
		$st->execute();	
		exit();
	}
	$st=$conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL."users WHERE oauth->'$.mi.message.unionId'=? LIMIT 1");
	$st->bindValue(1,$unionId);
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
	$st=$conn->prepare('UPDATE '.JRY_WB_DATABASE_GENERAL.'users SET oauth=JSON_REPLACE(IF(ISNULL(oauth->\'$.mi\'),JSON_INSERT(IFNULL(oauth,JSON_OBJECT()),\'$.mi\',NULL),oauth),\'$.mi\',CONVERT(?,JSON)),lasttime=? WHERE id=? ');
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
		jry_wb_cache.set('oauth_mi','<?php  echo json_encode($data['message']);?>')
		jry_wb_beautiful_alert.alert("绑定成功",'<?php  echo $data['message']['miliaoNick']?>',function(){window.close();});
	</script>