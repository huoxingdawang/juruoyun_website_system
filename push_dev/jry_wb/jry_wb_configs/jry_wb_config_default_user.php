<?php
//工作域名或ip
	define('JRY_WB_DOMIN'								,'dev.juruoyun.top');
//工作端口号(留空使用80端口)
	define('JRY_WB_PORT'								,'');
	include_once("jry_wb_config_default_system.php");
//登录时间
	define('JRY_WB_LOGIN_TIME'							,60*60*24);
//网站名称
	define('JRY_WB_NAME'								,'蒟蒻云内测');
//默认语言
	define('JRY_WB_DEFAULT_LANGUAGE'					,'zh-CN');
//网站logo图标(.ico格式,16*16)
	define('JRY_WB_LOGO_ICO_ADDRESS'					,JRY_WB_DATA_HOST	.'general/picture/logo.ico');
//网站logo图片
	define('JRY_WB_LOGO_PICTURE_ADDRESS'				,JRY_WB_DATA_HOST	.'general/picture/LOGO.jpg');
//默认女用户头
	define('JRY_WB_DEFULT_WOMAN_PICTURE'				,JRY_WB_DATA_HOST	.'general/picture/default_head_woman.jpg');
//默认男用户头
	define('JRY_WB_DEFULT_MAN_PICTURE'					,JRY_WB_DATA_HOST	.'general/picture/default_head_man.jpg');
//控制台字符画js地址
	define('JRY_WB_CHARACTER_DRAWING_LOGO_ADDRESS'		,JRY_WB_HOST		.'jry_wb_configs/I_am_so_vegetable.js');
//鼠标点击出字特效
	define('JRY_WB_WORD_SPECIAL_FACT_SWITCH'			,true);
//鼠标跟随特效
	define('JRY_WB_FOLLOW_MOUTH_SPECIAL_FACT_SWITCH'	,true);
//播放宣传视频
	define('JRY_WB_SHOW_VIDEO_SWITCH'					,false);
//换title
	define('JRY_WB_CHENGE_TITLE_SWITCH'					,true);	
//背景音乐
	define('JRY_WB_BACKGROUND_MUSIC_SWITCH'				,true);
//注册检测邮箱
	define('JRY_WB_CHECK_MAIL_SWITCH'					,false);
//注册检测电话
	define('JRY_WB_CHECK_TEL_SWITCH'					,true);
//是否是主站
	define('JRY_WB_HOST_SWITCH'							,false);
//主站地址
	if(JRY_WB_HOST_SWITCH)
		define('JRY_WB_HOST_ADDRESS'					,'http://juruoyun.top/mywork/');
//描述
	define('JRY_WB_DESCRIPTION'							,'李俊彦的个人站,本站主要演示了蒟蒻云网站系统,如有bug请及时联系我,本人是蒟蒻云开发组,目前正在独立开发蒟蒻云网站系统,蒟蒻云开发组现在稀缺美工,欢迎联系我');
//关键字
	define('JRY_WB_KEYWORDS'							,'李俊彦的个人站,蒟蒻云开发组的个人站','蒟蒻云网站系统开发组个人站');
//时区
	date_default_timezone_set('Asia/Shanghai');
//debug模式
	define('JRY_WB_DEBUG_MODE'			,true);
//socket模式
	define('JRY_WB_SOCKET_SWITCH'		,true);
//默认歌单
	if(JRY_WB_BACKGROUND_MUSIC_SWITCH)
		define('JRY_WB_BACKGROUND_MUSIC_DEFAULT_LIST',
			[
			array('mid'=>'574566207','type'=>'163'),
			array('mid'=>'459434585','type'=>'163'),
			array('mid'=>'003O7cLi18hkBm','type'=>'qq'),
			array('mid'=>'000idahy2pT761','type'=>'qq'),
			array('mid'=>'004L3vFm0vErA3','type'=>'qq'),
			array('mid'=>'002o9DKP3d9XIH','type'=>'qq'),
			array('mid'=>'003StCNV01trk2','type'=>'qq')
			]);
?>