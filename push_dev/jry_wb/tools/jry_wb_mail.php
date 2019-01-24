<?php
	include_once("jry_wb_includes.php");
	require '../tools/phpemailer/PHPMailerAutoload.php';
	function jry_wb_send_mail($to,$subject,$text)
	{
		if($to==''||$subject==''||$text=='')
			return false;
		$mailer=new PHPMailer();
		$mailer->CharSet = 'UTF-8';
		$mailer->isSMTP();
		$mailer->Host=constant('jry_wb_mail_host');
		$mailer->SMTPAuth=true;
		$mailer->Username=constant('jry_wb_mail_user');
		$mailer->Password=constant('jry_wb_mail_pas');
		$mailer->SMTPSecure='ssl';
		$mailer->Port = 465;
		$mailer->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
			)
		);		
		$mailer->setFrom(constant('jry_wb_mail_user'),constant('jry_wb_mail_from'));
		$mailer->addAddress($to,constant('jry_wb_mail_to'));
		$mailer->addReplyTo(constant('jry_wb_mail_replay'),constant('jry_wb_mail_replay_name'));

		$mailer->isHTML(true); 

		$mailer->Subject = $subject;
		$mailer->Body = $text;
		if($mailer->send())
			return true;
		exit();
	}
	function jry_wb_send_mail_code($mail,$url)
	{
		$srcstr = '23456789abcdefghijklmnpqrstwyzABCDEFGHJKLMNPQRSTWYZ';
		$code='';
		mt_srand();
		for ($i = 0; $i < 100; $i++) 
			$code.=$srcstr[mt_rand(0, 50)];
		$code=md5($mail.$code.jry_wb_get_time());
		$conn=jry_wb_connect_database();
		$q = "INSERT INTO ".constant('jry_wb_database_general')."mail_code (mail,code,time) VALUES (?,?,?)";
		$st = $conn->prepare($q);
		$st->bindParam(1,$mail);
		$st->bindParam(2,$code);
		$st->bindParam(3,jry_wb_get_time());
		$st->execute();	
		return jry_wb_send_mail($mail,'蒟蒻云邮箱验证','点击以下链接完成邮箱验证<a href="'.constant('jry_wb_host').$url.'code='.$code.'">'.constant('jry_wb_host').$url.'code='.$code.'</a>');
	}
?>