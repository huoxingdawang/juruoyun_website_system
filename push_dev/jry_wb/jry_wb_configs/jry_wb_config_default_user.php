<?php
	define('jry_wb_domin'								,'dev.juruoyun.top');
	define('jry_wb_port','');
	include_once("jry_wb_config_default_system.php");
	
	define('sessiontime'								,3600);//s
	define('logintime'									,60*60*24);//s
	define('jry_wb_name'								,'蒟蒻云内测');
	define('jry_wb_default_language'					,'zh-CN');
	define('jry_wb_logo_ico_address'					,constant('jry_wb_data_host').'general/picture/logo.ico');
	define('jry_wb_logo_picture_address'				,constant('jry_wb_data_host').'general/picture/LOGO.jpg');
	define('jry_wb_defult_woman_picture'				,constant('jry_wb_data_host').'general/picture/default_head_woman.jpg');
	define('jry_wb_defult_man_picture'					,constant('jry_wb_data_host').'general/picture/default_head_man.jpg');
	define('jry_wb_character_drawing_logo_address'		,constant('jry_wb_host').'jry_wb_configs/I_am_so_vegetable.js');
	
	define('jry_wb_word_special_fact_switch'			,true);
	define('jry_wb_follow_mouth_special_fact_switch'	,true);
	define('jry_wb_show_video_switch'					,false);
	define('jry_wb_chenge_title_switch'					,true);	
	define('jry_wb_background_music_switch'				,true);
	define('jry_wb_check_mail_switch'					,false);
	define('jry_wb_check_tel_switch'					,true);
	define('jry_wb_host_switch'							,false);//主站？
	define('jry_wb_host_addr'							,'http://juruoyun.top/mywork/');//主站？
	define('jry_wb_description'							,'李俊彦的个人站,本站主要演示了蒟蒻云网站系统,如有bug请及时联系我,本人是蒟蒻云开发组,目前正在独立开发蒟蒻云网站系统,蒟蒻云开发组现在稀缺美工,欢迎联系我');
	define('jry_wb_keywords'							,'李俊彦的个人站,蒟蒻云开发组的个人站','蒟蒻云网站系统开发组个人站');
	define('jry_wb_upload_file_address','/var/www/dev_html_upload_data/');

	date_default_timezone_set('Asia/Shanghai');
	
	define('jry_wb_debug_mode'			,true);
	define('jry_wb_socket_switch'		,true);

	if(constant('jry_wb_background_music_switch'))
		define('jry_wb_background_music_default_list',
			[
			array('mid'=>'574566207','type'=>'163'),
			array('mid'=>'459434585','type'=>'163'),
			array('mid'=>'003O7cLi18hkBm','type'=>'qq'),
			array('mid'=>'000idahy2pT761','type'=>'qq'),
			array('mid'=>'004L3vFm0vErA3','type'=>'qq'),
			array('mid'=>'0038pLAN0JFpaU','type'=>'qq'),
			array('mid'=>'002WDzh20GpMWQ','type'=>'qq'),
			array('mid'=>'002o9DKP3d9XIH','type'=>'qq'),
			array('mid'=>'003StCNV01trk2','type'=>'qq'),
			array('mid'=>'000oxiWq0t7aZ2','type'=>'qq'),
			array('mid'=>'004TRcLI4IguSE','type'=>'qq')
			]);
?>