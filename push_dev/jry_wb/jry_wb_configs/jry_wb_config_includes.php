<?php
	include_once("jry_wb_config_database_user.php");
	include_once("jry_wb_config_database_system.php");
	include_once("jry_wb_config_default_user.php");
	include_once("jry_wb_config_default_system.php");
	include_once("jry_wb_config_mail_user.php");

	include_once("jry_wb_config_short_message_user.php");
	if(constant('jry_wb_short_message_switch')=='aly')
		include_once("jry_wb_config_short_message_aly.php");
	if(constant('jry_wb_mail_switch')=='phpmailer')
		include_once("jry_wb_config_mail_phpmailer.php");
		
?>