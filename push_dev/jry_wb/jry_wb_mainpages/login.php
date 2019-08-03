<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_tp_github_oauth_config.php");
	include_once("../jry_wb_configs/jry_wb_tp_gitee_oauth_config.php");
	include_once("../jry_wb_configs/jry_wb_tp_mi_oauth_config.php");	
	include_once("../jry_wb_configs/jry_wb_tp_qq_oauth_config.php");
	session_start();
	if((!JRY_WB_HOST_SWITCH)&&$_COOKIE['password']!=NULL&&$_COOKIE['id']!=NULL&&(!$_GET['debug']))
	{
		$conn=jry_wb_connect_database();
		$host_conn=jry_wb_connect_host_database();
		$q='SELECT * FROM '.constant('jry_wb_host_database_general').'users
			LEFT JOIN '.constant('jry_wb_host_database_general').'login  ON ('.constant('jry_wb_host_database_general_prefix').'users.id = '.constant('jry_wb_host_database_general_prefix')."login.id)
			where ".constant('jry_wb_host_database_general_prefix')."users.id =? AND device=? LIMIT 1";
		$st = $host_conn->prepare($q);
		$st->bindParam(1,intval((isset($_COOKIE['id'])?$_COOKIE['id']:-1)));
		$st->bindParam(2,jry_wb_get_device(true));
		$st->execute();
		$data=$st->fetchAll();
		if(count($data)!=0)
		{
			$user=$data[0];
			if($user['password']==$_COOKIE['password'])
			{
				$q="SELECT * FROM ".JRY_WB_DATABASE_GENERAL."users where id=?;";
				$st = $conn->prepare($q);
				$st->bindParam(1,$user['id']);
				$st->execute();
				if(count($jry_wb_login_user=$st->fetchAll())==0)
				{
					jry_wb_print_head("登录",false,false,true);		
					?><script language=javascript>
						jry_wb_beautiful_alert.alert("请联系开发组","");
					</script>
					<h1>请联系开发组</h1>
					<h2>QQ:1176402460</h2>
					<h2>邮箱:lijunyandeyouxiang@163.com</h2><?php
					exit();
				}
				$jry_wb_login_user=$jry_wb_login_user[0];
				$type=8;
				require(JRY_WB_LOCAL_DIR."/jry_wb_mainpages/do_login.php");
				exit();
			}
		}
	}	
	jry_wb_print_head("登录",false,false,true);
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>	
	<?php jry_wb_print_href('login','active');?>
	<?php jry_wb_print_href('add_user','');?>
	<?php jry_wb_print_href('forget','');?>	
</div>
<div id='mainbody'></div>
<script language="javascript">jry_wb_include_once_script('jry_wb_mainpages_login.js.php',function(){jry_wb_mainpages_login(document.getElementById('mainbody'))});</script>
<?php
	jry_wb_print_tail();
?>
