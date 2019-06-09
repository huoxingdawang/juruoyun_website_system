<?php
	error_reporting(E_ALL^E_NOTICE^E_WARNING);
	include_once("jry_wb_config_database_user.php");
	include_once("jry_wb_config_database_system.php");
	include_once("jry_wb_config_default_user.php");
	include_once("jry_wb_config_default_system.php");
	include_once("jry_wb_config_mail_user.php");

	include_once("jry_wb_config_short_message_user.php");
	if(JRY_WB_SHORT_MESSAGE_SWITCH=='aly')
		include_once("jry_wb_config_short_message_aly.php");
	if(JRY_WB_MAIL_SWITCH=='phpmailer')
		include_once("jry_wb_config_mail_phpmailer.php");
		
?>