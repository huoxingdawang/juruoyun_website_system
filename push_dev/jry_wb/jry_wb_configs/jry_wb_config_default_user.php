<?php
//工作域名或ip
	define('JRY_WB_DOMIN'								,'');
//工作端口号(留空使用80端口)
	define('JRY_WB_PORT'								,'');
	include_once("jry_wb_config_default_system.php");
//登录时间
	define('JRY_WB_LOGIN_TIME'							,60*60*24);
//网站名称
	define('JRY_WB_NAME'								,'');
//默认语言
	define('JRY_WB_DEFAULT_LANGUAGE'					,'zh-CN');
//网站logo图标(.ico格式,16*16)
	define('JRY_WB_LOGO_ICO_ADDRESS'					,'');
//网站logo图片
	define('JRY_WB_LOGO_PICTURE_ADDRESS'				,'');
//默认女用户头
	define('JRY_WB_DEFULT_WOMAN_PICTURE'				,'');
//默认男用户头
	define('JRY_WB_DEFULT_MAN_PICTURE'					,'');
//控制台字符画js地址
	define('JRY_WB_CHARACTER_DRAWING_LOGO_ADDRESS'		,'');
//鼠标点击出字特效
	define('JRY_WB_WORD_SPECIAL_FACT_SWITCH'			,false);
//鼠标跟随特效
	define('JRY_WB_FOLLOW_MOUTH_SPECIAL_FACT_SWITCH'	,false);
//播放宣传视频
	define('JRY_WB_SHOW_VIDEO_SWITCH'					,false);
//换title
	define('JRY_WB_CHENGE_TITLE_SWITCH'					,false);	
//背景音乐
	define('JRY_WB_BACKGROUND_MUSIC_SWITCH'				,false);
//注册检测邮箱
	define('JRY_WB_CHECK_MAIL_SWITCH'					,false);
//注册检测电话
	define('JRY_WB_CHECK_TEL_SWITCH'					,true);
//验证码字体位置
	define('JRY_WB_VCODE_FONT_DIR'						,JRY_WB_LOCAL_DATA_DIR.'font/simhei.ttf');
//验证码斜度
	define('JRY_WB_VCODE_FONT_SLOPE'					,[-45,45]);
//验证码颜色
	define('JRY_WB_VCODE_COLOR'							,[['back'=>['r'=>0xFF,'g'=>0xFF,'b'=>0xFF],'pix'=>['r'=>187,'g'=>230,'b'=>247],'font'=>['r'=>41,'g'=>163,'b'=>238]]]);		
//是否是主站
	define('JRY_WB_HOST_SWITCH'							,false);
//主站地址
	if(JRY_WB_HOST_SWITCH)
		define('JRY_WB_HOST_ADDRESS'					,'');
//描述
	define('JRY_WB_DESCRIPTION'							,'');
//关键字
	define('JRY_WB_KEYWORDS'							,'');
//时区
	date_default_timezone_set('Asia/Shanghai');
//debug模式
	define('JRY_WB_DEBUG_MODE'							,true);
//socket模式	
	define('JRY_WB_SOCKET_SWITCH'						,false);
//默认歌单
	if(JRY_WB_BACKGROUND_MUSIC_SWITCH)
		define('JRY_WB_BACKGROUND_MUSIC_DEFAULT_LIST',
			[
			]);
?>