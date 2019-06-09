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
		$mailer->Host=JRY_WB_MAIL_PHPMAILER_HOST;
		$mailer->SMTPAuth=true;
		$mailer->Username=JRY_WB_MAIL_PHPMAILER_USER;
		$mailer->Password=JRY_WB_MAIL_PHPMAILER_PASSWORD;
		$mailer->SMTPSecure='ssl';
		$mailer->Port = 465;
		$mailer->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
			)
		);		
		$mailer->setFrom(JRY_WB_MAIL_PHPMAILER_USER,JRY_WB_NAME);
		$mailer->addAddress($to,JRY_WB_MAIL_PHPMAILER_TO);
		$mailer->addReplyTo(JRY_WB_MAIL_PHPMAILER_REPLAY,JRY_WB_MAIL_PHPMAILER_REPLAY_NAME);

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
		return jry_wb_send_mail($mail,JRY_WB_NAME.'邮箱验证','点击以下链接完成邮箱验证<a href="'.JRY_WB_HOST.$url.'code='.$code.'">'.JRY_WB_HOST.$url.'code='.$code.'</a>');
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
		return jry_wb_send_mail($mail,JRY_WB_NAME.'邮箱验证','这是您的验证码:<br>'.$code.'<br>注意区分大小写');
	}	
?>