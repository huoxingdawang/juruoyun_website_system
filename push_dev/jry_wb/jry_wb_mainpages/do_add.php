<?php
	include_once("../tools/jry_wb_includes.php");
	if(!constant('jry_wb_host_switch'))
	{
		?><script>window.location="<?php echo constant('jry_wb_host_addr')?>mainpages/add.php"</script><?php
		exit();
	}	
	if($_GET['action']=='send_tel')
	{
		if(!constant('jry_wb_check_tel_switch'))
			exit();
		jry_wb_print_head("",true,false,false,array(),false,false);
		if($_POST['vcode']!=$_SESSION['vcode']||$_POST['vcode']=='')
		{
			echo "验证码错误";
			exit();
		}
		require_once "../tools/SignatureHelper.php";
		if(($code=gettelsmscode($_POST['tel']))==-1)
		{
			echo "提交过于频繁";
			exit();
		}
		sendsms($_POST['tel'],Array ("code"=>$code),constant('jry_wb_short_message_aly_add_user'));	
		echo 'OK';
		exit();		
	}
	jry_wb_print_head("注册",false,false,false);
	$psw1=$_POST["password1"];
	$psw2=$_POST["password2"];
	$sex=$_POST["sex"];
	$tel=$_POST["tel"];
	$name=$_POST["name"];
	$vcode=$_POST["vcode"];
	if(constant('jry_wb_check_tel_switch'))
	{
		$st = $conn->prepare('DELETE FROM '.constant('jry_wb_database_general').'tel_code where time<?');
		$st->bindParam(1,date("Y-m-d H:i:s",time()-5*60));
		$st->execute();
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'tel_code where tel=?');
		$st->bindParam(1,$_POST['tel']);
		$st->execute();	
		foreach($st->fetchAll()as $tels);		
		if($_POST['phonecode']!=$tels['code']||$_POST['phonecode']==''){?><script language=javascript>jry_wb_beautiful_alert.alert('请填写正确信息','手机验证码错误','self.location=document.referrer;');</script>		<?php	exit();}					
		if(!jry_wb_test_phone_number($tel)){?><script language=javascript>jry_wb_beautiful_alert.alert('请填写正确信息','电话错误','self.location=document.referrer;');</script>		<?php	exit();}
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users where tel=?');
		$st->bindParam(1,$tel);
		$st->execute();
		foreach($st->fetchAll()as $users)if($users[id]!=''&&$users[id]!=$jry_wb_login_user[id])	{?><script language=javascript>jry_wb_beautiful_alert.alert('请填写非重复信息','电话重复'	,'self.location=document.referrer;');</script>		<?php	exit();}
		
		$st = $conn->prepare('DELETE FROM '.constant('jry_wb_database_general').'tel_code where tel=?');
		$st->bindParam(1,$tel);
		$st->execute();
	}
	
	if($name=="")								{?><script language=javascript>jry_wb_beautiful_alert.alert('请填写完整信息','名字为空'		,'self.location=document.referrer;');</script>		<?php	exit();}
	if($vcode!= $_SESSION['vcode']||$vcode=='')	{?><script language=javascript>jry_wb_beautiful_alert.alert('请填写正确信息','验证码错误'	,'self.location=document.referrer;');</script>		<?php	exit();}
	if($psw1!=$psw2)							{?><script language=javascript>jry_wb_beautiful_alert.alert('请填写正确信息','两次密码不同'	,'self.location=document.referrer;');</script>		<?php 	exit();}
	if(strlen($psw1)<8)							{?><script language=javascript>jry_wb_beautiful_alert.alert('请填写正确信息','密码太短'		,'self.location=document.referrer;');</script>		<?php 	exit();}
	$psw1=md5($psw1);
	$conn=jry_wb_connect_database();
	$now=jry_wb_get_time();//时间
	if($sex==0)
		$q = "INSERT INTO ".constant('jry_wb_database_general')."users (name,password,sex,enroldate,head,tel,lasttime) VALUES (?,?,?,?,'default_head_woman',?,?)";
	else
		$q = "INSERT INTO ".constant('jry_wb_database_general')."users (name,password,sex,enroldate,tel,lasttime) VALUES (?,?,?,?,?,?)";
	$st = $conn->prepare($q);
	$st->bindParam(1,$name);
	$st->bindParam(2,$psw1);
	$st->bindParam(3,$sex);
	$st->bindParam(4,$now);
	$st->bindParam(5,$tel);
	$st->bindParam(6,$now);
	$st->execute();
	$jry_wb_login_user['id']=$conn->lastInsertId();
	jry_wb_echo_log(constant('jry_wb_log_type_add'));
	echo "<script language=javascript> id=".$jry_wb_login_user['id'].";url='".jry_wb_print_href("login",0,"",1)."'</script>";
?>
<script language=javascript>
	function _alert()	{jry_wb_beautiful_alert.alert('注册成功 第三遍提示','您的<h56>ID='+id+'</h56><br>ID是您登录本网站的<h56>唯一</h56>凭证<br>请牢记',"window.location.href='"+url+"'");}
	function __alert()	{jry_wb_beautiful_alert.alert('注册成功 第二遍提示','您的<h56>ID='+id+'</h56><br>ID是您登录本网站的<h56>唯一</h56>凭证<br>请牢记',"_alert()");}
	function ___alert()	{jry_wb_beautiful_alert.alert('注册成功 第一遍提示','您的<h56>ID='+id+'</h56><br>ID是您登录本网站的<h56>唯一</h56>凭证<br>请牢记',"__alert()");}
	window.onload=___alert();	
</script>
<?php		jry_wb_print_tail();?>