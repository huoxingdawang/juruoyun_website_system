<?php
	include_once("../tools/jry_wb_includes.php");
	include_once(constant('jry_wb_local_dir')."/jry_wb_tp_sdk/qq/oauth/qqConnectAPI.php");
	$qc = new QC();
	$data=(array('access_token'=>$qc->qq_callback(),'openid'=>$qc->get_openid()));
	$qc = new QC($data['access_token'],$data['openid']);
	$data['message']=$qc->get_user_info();
	$login=jry_wb_print_head("",true,false,false,array(),false,false);
	if($login!='ok')//登录部分
	{
		$type=4;
		$open_id=$data['openid'];
		$access_token=$data['access_token'];
		require(constant('jry_wb_local_dir')."/jry_wb_mainpages/do_login.php");
	}
	else
	{
		$conn=jry_wb_connect_database();
		$q ="update ".constant('jry_wb_database_general')."users set oauth_qq=?,lasttime=? where id=? ";
		$st = $conn->prepare($q);
		$st->bindParam(1,json_encode($data));		
		$st->bindParam(2,jry_wb_get_time());
		$st->bindParam(3,$jry_wb_login_user[id]);
		$st->execute();			
		jry_wb_print_head("绑定",false,false,true,array('use'),true,false);
		?>
		<script>
			jry_wb_cache.set('oauth_qq','<?php  echo json_encode($data['message']);?>')
			jry_wb_beautiful_alert.alert("绑定成功",'<?php  echo $data['message']['nickname']?>',function(){window.close();});
		</script>
		<?php
	}
?>