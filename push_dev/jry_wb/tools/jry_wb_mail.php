<?php
	include_once("jry_wb_includes.php");
	require_once( dirname(__FILE__).'/phpemailer/PHPMailerAutoload.php');
	function jry_wb_send_mail($to,$subject,$text)
	{
		if($to==''||$subject==''||$text=='')
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100016,'file'=>__FILE__,'line'=>__LINE__)));
		$mailer=new PHPMailer();
		$mailer->CharSet = 'UTF-8';
		$mailer->isSMTP();
		$mailer->Host=constant('jry_wb_mail_phpmailer_host');
		$mailer->SMTPAuth=true;
		$mailer->Username=constant('jry_wb_mail_phpmailer_user');
		$mailer->Password=constant('jry_wb_mail_phpmailer_password');
		$mailer->SMTPSecure='ssl';
		$mailer->Port = 465;
		$mailer->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
			)
		);		
		$mailer->setFrom(constant('jry_wb_mail_phpmailer_user'),constant('jry_wb_name'));
		$mailer->addAddress($to,constant('jry_wb_mail_phpmailer_to'));
		$mailer->addReplyTo(constant('jry_wb_mail_phpmailer_replay'),constant('jry_wb_mail_phpmailer_replay_name'));

		$mailer->isHTML(true); 

		$mailer->Subject = $subject;
		$mailer->Body = $text;
		if($mailer->send())
			return true;
		throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100016,'file'=>__FILE__,'line'=>__LINE__)));
	}
	function jry_wb_send_mail_code($mail,$url)
	{
		$code=jry_wb_get_random_string(100);
		$code=md5($mail.$code.jry_wb_get_time());
		$conn=jry_wb_connect_database();
		$q = "INSERT INTO ".JRY_WB_DATABASE_GENERAL."mail_code (mail,code,time) VALUES (?,?,?)";
		$st = $conn->prepare($q);
		$st->bindParam(1,$mail);
		$st->bindParam(2,$code);
		$st->bindParam(3,jry_wb_get_time());
		$st->execute();	
		return jry_wb_send_mail($mail,constant('jry_wb_name').'邮箱验证','点击以下链接完成邮箱验证<a href="'.constant('jry_wb_host').$url.'code='.$code.'">'.constant('jry_wb_host').$url.'code='.$code.'</a>');
	}
	function jry_wb_send_mail_code6($mail)
	{
		$code=jry_wb_get_random_string(6);
		$conn=jry_wb_connect_database();
		$q = "INSERT INTO ".JRY_WB_DATABASE_GENERAL."mail_code (mail,code,time) VALUES (?,?,?)";
		$st = $conn->prepare($q);
		$st->bindParam(1,$mail);
		$st->bindParam(2,$code);
		$st->bindParam(3,jry_wb_get_time());
		$st->execute();	
		return jry_wb_send_mail($mail,constant('jry_wb_name').'邮箱验证','这是您的验证码:<br>'.$code.'<br>注意区分大小写');
	}	
?>