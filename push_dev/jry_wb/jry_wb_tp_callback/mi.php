<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_tp_mi_oauth_config.php");
	require_once(constant('jry_wb_local_dir').'/jry_wb_tp_sdk/mi/utils/XMUtil.php');
	require_once(constant('jry_wb_local_dir').'/jry_wb_tp_sdk/mi/utils/AccessToken.php');
	require_once(constant('jry_wb_local_dir').'/jry_wb_tp_sdk/mi/httpclient/XMHttpClient.php');
	require_once(constant('jry_wb_local_dir').'/jry_wb_tp_sdk/mi/httpclient/XMOAuthClient.php');
	require_once(constant('jry_wb_local_dir').'/jry_wb_tp_sdk/mi/httpclient/XMApiClient.php');
	$code=$_GET["code"];
	if($code)
	{
		$oauthClient=new XMOAuthClient(constant('jry_wb_tp_mi_oauth_config_client_id'),constant('jry_wb_tp_mi_oauth_config_client_secret'));
		$oauthClient->setRedirectUri(constant('jry_wb_host').'/jry_wb_tp_callback/mi.php');	
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
				$xmApiClient=new XMApiClient(constant('jry_wb_tp_mi_oauth_config_client_id'),$tokenId);
				$nonce=XMUtil::getNonce();
				$path='/user/profile';
				$method="GET";
				$params=array('token'=>$tokenId,"clientId"=>constant('jry_wb_tp_mi_oauth_config_client_id'));
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
	$login=jry_wb_print_head("",true,false,false,array(),false,false);
	if($login!='ok')//登录部分
	{
		$type=6;
		$unionId=$data['unionId'];
		require(constant('jry_wb_local_dir')."/jry_wb_mainpages/do_login.php");
	}
	else
	{
		$conn=jry_wb_connect_database();
		$q ="update ".constant('jry_wb_database_general')."users set oauth_mi=?,lasttime=? where id=? ";
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
			jry_wb_cache.set('oauth_mi','<?php  echo json_encode($data);?>')
			jry_wb_beautiful_alert.alert("绑定成功",'<?php  echo $data['miliaoNick']?>',function(){window.close();});
		</script>
		<?php
	}	
	

?>