<?php
	//文件包含
	$jry_wb_start_time=microtime(true);	
	class jry_wb_exception extends Exception{};		
	include_once(dirname(dirname(__FILE__)).'/jry_wb_configs/jry_wb_config_includes.php');
	include_once('jry_wb_database.php');
	include_once('jry_wb_check_compentence.php');
	include_once('jry_wb_user.php');

	include_once('jry_wb_set_green_money.php');
	
	include_once('jry_wb_print_logo.php');
	include_once('jry_wb_print_href.php');
	include_once('jry_wb_print_tail.php');
	include_once('jry_wb_print_head.php');
	
	include_once('jry_wb_get_time.php');
	include_once('jry_wb_get_ip_address.php');
	include_once('jry_wb_get_device.php');
	include_once('jry_wb_get_domain.php');
	include_once('jry_wb_get_random_string.php');
	
	include_once('jry_wb_test_phone_number.php');
	include_once('jry_wb_test_mail.php');
	include_once('jry_wb_test_device.php');
	include_once('jry_wb_test_domain_is_ip.php');
	include_once('jry_wb_test_china_id_card.php');
	include_once('jry_wb_test_is_cli_mode.php');

	include_once('jry_wb_aes.php');
	
	if(constant('jry_wb_mail_switch')!='')
		include_once('jry_wb_mail.php');
//	if(constant('jry_wb_short_message_switch'))
//		include_once('');
	include_once('SignatureHelper.php');
	
	include_once('jry_wb_load_style.php');
	include_once('jry_wb_pretreatment.php');
	
	include_once('jry_wb_log.php');
	include_once('jry_wb_website_map.php');
	
	include_once('jry_wb_autoload.php');
?>
