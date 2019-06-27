CREATE DATABASE IF NOT EXISTS `juruoyun_rel`;
USE `juruoyun_rel`;

DROP TABLE IF EXISTS `blog_text`;
CREATE TABLE IF NOT EXISTS `blog_text` (
  `blog_id` int(32) NOT NULL AUTO_INCREMENT,
  `ifshow` tinyint(1) NOT NULL DEFAULT '0',
  `id` int(32) NOT NULL,
  `data` longtext,
  `lasttime` datetime NOT NULL,
  `title` varchar(512) DEFAULT NULL,
  `delete` int(1) NOT NULL DEFAULT '0',
  `readingcount` int(32) NOT NULL DEFAULT '0',
  PRIMARY KEY (`blog_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `chat_message`;
CREATE TABLE IF NOT EXISTS `chat_message` (
  `chat_text_id` int(32) NOT NULL AUTO_INCREMENT,
  `id` int(32) NOT NULL,
  `chat_room_id` int(32) NOT NULL,
  `message` longtext COLLATE utf8_bin NOT NULL,
  `send_time` datetime NOT NULL,
  PRIMARY KEY (`chat_text_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `chat_rooms`;
CREATE TABLE IF NOT EXISTS `chat_rooms` (
  `chat_room_id` int(32) NOT NULL AUTO_INCREMENT,
  `id` int(32) NOT NULL,
  `name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '聊天室',
  `head` json DEFAULT NULL,
  `cream_time` datetime NOT NULL,
  `lasttime` datetime NOT NULL,
  `last_add_time` datetime DEFAULT '1926-08-17 00:00:00',
  `last_say_time` datetime DEFAULT '1926-08-17 00:00:00',
  `say_count` int(32) NOT NULL DEFAULT '0',
  `users` json DEFAULT NULL,
  `delete` tinyint(1) NOT NULL DEFAULT '0',
  `big` tinyint(1) NOT NULL,
  PRIMARY KEY (`chat_room_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `chat_users`;
CREATE TABLE IF NOT EXISTS `chat_users` (
  `id` int(32) NOT NULL,
  `say_count` int(32) NOT NULL DEFAULT '0',
  `chat_rooms` json DEFAULT NULL,
  `lasttime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `general_invite_code`;
CREATE TABLE IF NOT EXISTS `general_invite_code` (
  `incite_code_id` int(32) NOT NULL AUTO_INCREMENT,
  `id` int(32) NOT NULL,
  `code` varchar(8) COLLATE utf8_bin NOT NULL,
  `creattime` datetime NOT NULL,
  `lasttime` datetime NOT NULL,
  `use` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`incite_code_id`,`code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `general_ip`;
CREATE TABLE IF NOT EXISTS `general_ip` (
  `ip` varchar(16) NOT NULL,
  `data` json DEFAULT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `general_login`;
CREATE TABLE IF NOT EXISTS `general_login` (
  `login_id` int(32) NOT NULL AUTO_INCREMENT,
  `id` int(32) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `device` int(8) unsigned DEFAULT '0' COMMENT '0:网页,1:iPad,2:IPHONE,3:android',
  `code` varchar(256) DEFAULT NULL,
  `browser` int(8) unsigned DEFAULT NULL,
  `trust` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`login_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `general_mail_code`;
CREATE TABLE IF NOT EXISTS `general_mail_code` (
  `mail` varchar(1024) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `general_style`;
CREATE TABLE IF NOT EXISTS `general_style` (
  `style_id` int(32) NOT NULL AUTO_INCREMENT,
  `id` int(32) NOT NULL DEFAULT '0',
  `name` varchar(128) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `note` varchar(1024) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `data` longtext COLLATE utf8_bin NOT NULL,
  `update` longtext COLLATE utf8_bin NOT NULL,
  `lasttime` datetime NOT NULL,
  PRIMARY KEY (`style_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
DELETE FROM `general_style`;
INSERT INTO `general_style` (`style_id`, `id`, `name`, `note`, `data`, `update`, `lasttime`) VALUES
	(1, 1, '蒟蒻云灰色主题', '蒟蒻云默认主题，支持最好，更新最及时', '{\r\n	"desktop_css_address":"default/desktop.css","desktop_css_type":0,\r\n	"general_css_address":"default/general.css","general_css_type":0,\r\n	"mobile_css_address":"default/mobile.css","mobile_css_type":0,\r\n	"mainpages_index_css_address":"default/mainpages/index.css","mainpages_index_css_type":0\r\n}', '[\r\n	{"time":"2018-10-28 16:08:00","data":"随新版主题管理器发布"},\r\n	{"time":"2018-10-29 10:21:00","data":"测试系统更新"}\r\n]', '2019-02-01 10:20:38');

DROP TABLE IF EXISTS `general_tel_code`;
CREATE TABLE IF NOT EXISTS `general_tel_code` (
  `time` datetime NOT NULL,
  `tel` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `general_users`;
CREATE TABLE IF NOT EXISTS `general_users` (
  `id` bigint(32) NOT NULL AUTO_INCREMENT,
  `type` json NOT NULL,
  `invite_id` int(32) NOT NULL DEFAULT '0',
  `order` varchar(16) NOT NULL DEFAULT '19260817',
  `tel` varchar(16) NOT NULL DEFAULT '',
  `mail` varchar(128) NOT NULL DEFAULT '',
  `lasttime` datetime NOT NULL DEFAULT '1926-08-17 00:00:00',
  `name` varchar(128) DEFAULT NULL,
  `password` varchar(1024) DEFAULT NULL,
  `enroldate` datetime DEFAULT NULL,
  `logdate` datetime NOT NULL DEFAULT '1926-08-17 00:00:00',
  `greendate` datetime NOT NULL DEFAULT '1926-08-17 00:00:00',
  `language` char(32) NOT NULL DEFAULT 'zh-CN',
  `use` int(1) NOT NULL DEFAULT '1',
  `sex` tinyint(2) NOT NULL DEFAULT '1',
  `zhushi` varchar(2048) DEFAULT '',
  `green_money` double unsigned NOT NULL DEFAULT '0',
  `head` json DEFAULT NULL,
  `style_id` int(32) NOT NULL DEFAULT '1',
  `tel_show` int(2) NOT NULL DEFAULT '0',
  `ip_show` tinyint(1) NOT NULL DEFAULT '1',
  `oauth_show` tinyint(1) NOT NULL DEFAULT '1',
  `head_special` json DEFAULT NULL,
  `mail_show` int(2) NOT NULL DEFAULT '0',
  `word_special_fact` int(1) NOT NULL DEFAULT '1',
  `follow_mouth` int(1) NOT NULL DEFAULT '1',
  `background_music_list` json DEFAULT NULL,
  `oauth` json DEFAULT NULL,
  `extern` json DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `general_website`;
CREATE TABLE IF NOT EXISTS `general_website` (
  `url` varchar(256) COLLATE utf8_bin NOT NULL,
  `name` varchar(256) COLLATE utf8_bin NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `show_name` varchar(128) COLLATE utf8_bin NOT NULL,
  `show_at_mainpage` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
DELETE FROM `general_website`;
INSERT INTO `general_website` (`url`, `name`, `type`, `show_name`, `show_at_mainpage`) VALUES
	('http://dev.juruoyun.top/jry_wb/jry_wb_dev/bugreport.php', 'bug', 1, 'BUGreport', 0),
	('http://dev.juruoyun.top/jry_wb/aboutus/index.php', 'aboutus', 1, '关于我们', 0),
	('jry_wb_mainpages/index.php', 'home', 0, '', 0),
	('jry_wb_mainpages/chenge.php', 'users', 0, '用户管理', 0),
	('jry_wb_mainpages/login.php', 'login', 0, '登录', 0),
	('jry_wb_mainpages/add.php', 'add_user', 0, '注册', 0),
	('jry_wb_mainpages/forget.php', 'forget', 0, '重置密码', 0),
	('jry_wb_manage_system/index.php', 'jry_wb_manage_system', 0, '管理员中心', 0),
	('jry_wb_small_application/index.php', 'smallapp', 0, '小程序', 1),
	('jry_wb_online_judge/oj_showquestion.php', 'online_judge_show_question', 0, '展示题目', 0),
	('jry_wb_online_judge/index.php#{"action":"logs"}', 'online_judge_logs', 0, '提交记录', 0),
	('jry_wb_online_judge/index.php#{"action":"ql"}', 'online_judge_all', 0, '题目总览', 0),
	('jry_wb_blog/jry_wb_blog_draft.php', 'blog_draft', 0, '博客草稿箱', 0),
	('jry_wb_blog/jry_wb_blog_editor.php', 'blog_editor', 0, '博客编辑器', 0),
	('jry_wb_blog/index.php', 'blog', 0, '博客', 1),
	('jry_wb_blog/jry_wb_blog_show.php', 'blog_show', 0, '博客展示', 0),
	('jry_wb_netdisk/index.php', 'jry_wb_netdisk', 0, '网盘', 1),
	('jry_wb_style_control/index.php', 'jry_wb_style_control', 0, '主题管理器', 0),
	('http://dev.juruoyun.top/jry_wb/jry_wb_dev/showbug.php', 'showbug', 2, 'BUG展示', 0),
	('jry_wb_chat/index.php', 'chat', 0, '聊天室', 1),
	('jry_wb_online_judge/index.php#{"action":"ql"}', 'online_judge_all', 0, '在线测评', 1);

DROP TABLE IF EXISTS `log_blog_reading`;
CREATE TABLE IF NOT EXISTS `log_blog_reading` (
  `logid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `ip` varchar(20) COLLATE utf8_bin NOT NULL,
  `device` int(11) DEFAULT NULL,
  `browser` int(11) DEFAULT NULL,
  PRIMARY KEY (`logid`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `log_browsing_history`;
CREATE TABLE IF NOT EXISTS `log_browsing_history` (
  `cnt` int(32) NOT NULL AUTO_INCREMENT,
  `id` int(32) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `from` varchar(1024) COLLATE utf8_bin DEFAULT NULL,
  `now` varchar(512) COLLATE utf8_bin DEFAULT NULL,
  `device` int(11) DEFAULT NULL,
  `browser` int(11) DEFAULT NULL,
  `ip` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`cnt`)
) ENGINE=InnoDB AUTO_INCREMENT=292 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `log_data`;
CREATE TABLE IF NOT EXISTS `log_data` (
  `log_id` int(32) NOT NULL AUTO_INCREMENT,
  `id` int(32) NOT NULL DEFAULT '0',
  `time` datetime DEFAULT NULL,
  `type` int(32) DEFAULT NULL,
  `data` longtext COLLATE utf8_bin,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `log_machine`;
CREATE TABLE IF NOT EXISTS `log_machine` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `data` json NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `log_socket`;
CREATE TABLE IF NOT EXISTS `log_socket` (
  `log_socket_id` int(32) NOT NULL AUTO_INCREMENT,
  `data` longtext COLLATE utf8_bin,
  PRIMARY KEY (`log_socket_id`)
) ENGINE=InnoDB AUTO_INCREMENT=301 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `mainpage_bigdeal`;
CREATE TABLE IF NOT EXISTS `mainpage_bigdeal` (
  `bigdeal_id` int(32) NOT NULL AUTO_INCREMENT,
  `id` int(32) NOT NULL,
  `time` datetime NOT NULL DEFAULT '1926-08-17 00:00:00',
  `name` char(50) NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`bigdeal_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `mainpage_hengfu`;
CREATE TABLE IF NOT EXISTS `mainpage_hengfu` (
  `hengfu_id` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id` int(32) NOT NULL,
  `words` varchar(1024) NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`hengfu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `mainpage_tanmu`;
CREATE TABLE IF NOT EXISTS `mainpage_tanmu` (
  `id` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `words` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `manage_competence`;
CREATE TABLE IF NOT EXISTS `manage_competence` (
  `type` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `competencename` varchar(64) DEFAULT NULL,
  `color` varchar(50) DEFAULT '0',
  `order` int(10) unsigned NOT NULL DEFAULT '0',
  `or` tinyint(1) NOT NULL DEFAULT '1',
  `manage` tinyint(1) NOT NULL DEFAULT '0',
  `manageusers` tinyint(1) NOT NULL DEFAULT '0',
  `manageadmin` tinyint(1) NOT NULL DEFAULT '0',
  `managecompentence` tinyint(1) NOT NULL DEFAULT '0',
  `managehengfu` tinyint(1) NOT NULL DEFAULT '0',
  `managebigdeal` tinyint(1) NOT NULL DEFAULT '0',
  `managetanmu` tinyint(1) NOT NULL DEFAULT '0',
  `manageonlinejudge` tinyint(1) NOT NULL DEFAULT '0',
  `manageonlinejudgequestion` tinyint(1) NOT NULL DEFAULT '0',
  `manageonlinejudgeclasses` tinyint(1) NOT NULL DEFAULT '0',
  `manageonlinejudgeaddquestion` tinyint(1) NOT NULL DEFAULT '0',
  `editorblog` tinyint(1) NOT NULL DEFAULT '0',
  `usenetdisk` tinyint(1) NOT NULL DEFAULT '0',
  `checknetdisk` tinyint(1) NOT NULL DEFAULT '0',
  `managenetdisk` tinyint(1) NOT NULL DEFAULT '0',
  `usemailsender` tinyint(1) NOT NULL DEFAULT '0',
  `useschool` tinyint(1) NOT NULL DEFAULT '0',
  `usechat` tinyint(1) NOT NULL DEFAULT '0',
  `addchatroom` tinyint(1) NOT NULL DEFAULT '0',
  `deletechatroom` tinyint(1) NOT NULL DEFAULT '0',
  `renamechatroom` tinyint(1) NOT NULL DEFAULT '0',
  `setchatroomhead` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
DELETE FROM `manage_competence`;
INSERT INTO `manage_competence` (`type`, `competencename`, `color`, `order`, `or`, `manage`, `manageusers`, `manageadmin`, `managecompentence`, `managehengfu`, `managebigdeal`, `managetanmu`, `manageonlinejudge`, `manageonlinejudgequestion`, `manageonlinejudgeclasses`, `manageonlinejudgeaddquestion`, `editorblog`, `usenetdisk`, `checknetdisk`, `managenetdisk`, `usemailsender`, `useschool`, `usechat`, `addchatroom`, `deletechatroom`, `renamechatroom`, `setchatroomhead`) VALUES
	(1, '网站主', '00FF00', 1, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0),
	(2, '测试者', 'ffe000', 3, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(3, '管理员', '458B00', 2, 1, 1, 1, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(4, '用户', '66CCFF', 3, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 1, 1, 0, 1, 1),
	(5, '题库管理员', '458B00', 2, 1, 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(6, '高级题库管理员', '458B00', 2, 1, 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

DROP TABLE IF EXISTS `manage_list`;
CREATE TABLE IF NOT EXISTS `manage_list` (
  `order` int(11) NOT NULL,
  `father` varchar(64) NOT NULL DEFAULT 'root',
  `url` varchar(256) NOT NULL,
  `name` varchar(64) NOT NULL,
  `competence` json DEFAULT NULL,
  `hash` varchar(128) NOT NULL DEFAULT '',
  `next` varchar(64) DEFAULT NULL,
  `is_script` tinyint(1) NOT NULL DEFAULT '0',
  `init_script` varchar(512) DEFAULT NULL,
  `run_script` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`order`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
DELETE FROM `manage_list`;
INSERT INTO `manage_list` (`order`, `father`, `url`, `name`, `competence`, `hash`, `next`, `is_script`, `init_script`, `run_script`) VALUES
	(1, 'aboutuser', 'jry_wb_manage_system/jry_wb_manage_user.js.php', '用户管理', '["manageusers"]', 'user', NULL, 1, 'jry_wb_manage_user_init', 'jry_wb_manage_user_run'),
	(2, 'mainpage', 'jry_wb_manage_system/jry_wb_manage_hengfu.js', '横幅', '["managehengfu"]', 'hengfu', NULL, 1, 'jry_wb_manage_hengfu_init', 'jry_wb_manage_hengfu_run'),
	(3, 'mainpage', 'jry_wb_manage_system/jry_wb_manage_bigdeal.js', '大事件', '["managebigdeal"]', 'bigdeal', NULL, 1, 'jry_wb_manage_bigdeal_init', 'jry_wb_manage_bigdeal_run'),
	(4, 'aboutuser', 'jry_wb_manage_system/jry_wb_manage_competence.js', '权限组', '["managecompentence"]', 'compentence', '', 1, 'jry_wb_manage_competence_init', 'jry_wb_manage_competence_run'),
	(5, 'aboutuser', 'jry_wb_manage_system/jry_wb_manage_send_mail.js', '邮件发送', '["usemailsender"]', 'mailsender', NULL, 1, 'jry_wb_manage_send_mail_init', 'jry_wb_manage_send_mail_run'),
	(6, 'root', ' ', '用户相关', NULL, 'users', 'aboutuser', 0, NULL, NULL),
	(7, 'root', ' ', '在线测评', '["manageonlinejudge"]', 'online_judge', 'online_judge', 0, NULL, NULL),
	(8, 'mainpage', 'jry_wb_manage_system/jry_wb_manage_tanmu.js', '弹幕', '["managetanmu"]', 'tanmu', NULL, 1, 'jry_wb_manage_tanmu_init', 'jry_wb_manage_tanmu_run'),
	(9, 'root', ' ', '首页相关', NULL, 'mainpages', 'mainpage', 0, NULL, NULL),
	(10, 'online_judge', 'jry_wb_online_judge/jry_wb_online_judge_manage.js', '刷新', '["manageonlinejudge"]', '', NULL, 1, 'jry_wb_online_judge_sync_init', 'jry_wb_online_judge_sync_run'),
	(11, 'online_judge', 'jry_wb_online_judge/jry_wb_online_judge_manage.js', '清空缓存', '["manageonlinejudge"]', '', NULL, 1, 'jry_wb_online_judge_clean_init', 'jry_wb_online_judge_clean_run'),
	(12, 'online_judge', 'jry_wb_online_judge/jry_wb_online_judge_manage.js', '题目管理', '["manageonlinejudge", "manageonlinejudgequestion"]', 'question', NULL, 1, 'jry_wb_online_judge_manage_question_init', 'jry_wb_online_judge_manage_question_run');

DROP TABLE IF EXISTS `netdisk_area`;
CREATE TABLE IF NOT EXISTS `netdisk_area` (
  `area_id` int(32) NOT NULL AUTO_INCREMENT,
  `id` int(32) NOT NULL,
  `name` varchar(128) COLLATE utf8_bin NOT NULL DEFAULT 'unnamed',
  `size` int(32) NOT NULL DEFAULT '0' COMMENT 'KB',
  `used` int(32) NOT NULL DEFAULT '0' COMMENT 'KB',
  `fast` tinyint(1) NOT NULL DEFAULT '0',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '0:服务器,1:alyOSS',
  `lasttime` datetime NOT NULL,
  `config_message` json NOT NULL,
  `samearea` tinyint(1) NOT NULL DEFAULT '0',
  `use` tinyint(1) NOT NULL DEFAULT '1',
  `upload` tinyint(1) DEFAULT '1',
  `faster` int(11) DEFAULT NULL,
  PRIMARY KEY (`area_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `netdisk_file_list`;
CREATE TABLE IF NOT EXISTS `netdisk_file_list` (
  `file_id` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id` int(32) unsigned NOT NULL,
  `father` int(32) unsigned NOT NULL DEFAULT '0',
  `name` varchar(512) COLLATE utf8_bin NOT NULL,
  `type` varchar(128) COLLATE utf8_bin NOT NULL,
  `size` int(32) NOT NULL COMMENT 'KB',
  `lasttime` datetime NOT NULL,
  `uploading` tinyint(1) NOT NULL,
  `area` int(32) NOT NULL DEFAULT '1',
  `download_times` int(32) unsigned NOT NULL DEFAULT '0',
  `share` tinyint(1) NOT NULL DEFAULT '0',
  `self_share` tinyint(1) NOT NULL DEFAULT '0',
  `share_list` json DEFAULT NULL,
  `delete` tinyint(1) NOT NULL DEFAULT '0',
  `isdir` tinyint(1) NOT NULL DEFAULT '0',
  `trust` tinyint(1) NOT NULL DEFAULT '0',
  `extern` json DEFAULT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `netdisk_group`;
CREATE TABLE IF NOT EXISTS `netdisk_group` (
  `group_id` int(32) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(128) COLLATE utf8_bin NOT NULL,
  `allow_type` json NOT NULL,
  `lasttime` datetime NOT NULL,
  `sameareaonly` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
DELETE FROM `netdisk_group`;
INSERT INTO `netdisk_group` (`group_id`, `group_name`, `allow_type`, `lasttime`, `sameareaonly`) VALUES
	(1, '高级用户', '-1', '2019-01-17 12:30:52', 0),
	(2, '正常用户', '["jpg", "jpeg", "png", "bmp", "gif"]', '2019-01-17 12:30:54', 1);

DROP TABLE IF EXISTS `netdisk_share`;
CREATE TABLE IF NOT EXISTS `netdisk_share` (
  `share_id` int(32) NOT NULL AUTO_INCREMENT,
  `id` int(32) NOT NULL,
  `key` varchar(64) COLLATE utf8_bin NOT NULL,
  `file_id` int(32) NOT NULL,
  `lasttime` datetime NOT NULL,
  `fastdownload` tinyint(1) NOT NULL DEFAULT '0',
  `requesturl` varchar(256) COLLATE utf8_bin NOT NULL DEFAULT '*',
  PRIMARY KEY (`share_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `netdisk_size_package`;
CREATE TABLE IF NOT EXISTS `netdisk_size_package` (
  `size_package_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(32) NOT NULL,
  `size` int(32) NOT NULL,
  `endtime` datetime NOT NULL,
  PRIMARY KEY (`size_package_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `netdisk_users`;
CREATE TABLE IF NOT EXISTS `netdisk_users` (
  `id` int(32) NOT NULL,
  `size_total` int(32) NOT NULL DEFAULT '20480' COMMENT 'KB',
  `size_used` int(32) NOT NULL DEFAULT '0' COMMENT 'KB',
  `group_id` int(8) NOT NULL DEFAULT '2',
  `fast_size` int(32) NOT NULL DEFAULT '0' COMMENT 'KB',
  `lasttime` datetime NOT NULL,
  `size_uploading` int(32) NOT NULL DEFAULT '0' COMMENT 'KB'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `online_judge_classes`;
CREATE TABLE IF NOT EXISTS `online_judge_classes` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` char(128) DEFAULT NULL,
  `id` int(32) DEFAULT NULL,
  `lasttime` datetime NOT NULL DEFAULT '2018-04-10 19:39:29',
  `father` int(32) NOT NULL,
  `manager` json DEFAULT NULL,
  PRIMARY KEY (`class_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `online_judge_error`;
CREATE TABLE IF NOT EXISTS `online_judge_error` (
  `error_id` int(32) NOT NULL AUTO_INCREMENT,
  `question_id` int(32) NOT NULL,
  `id` int(32) NOT NULL,
  `lasttime` datetime NOT NULL,
  `times` int(32) NOT NULL DEFAULT '0',
  `maxtimes` int(32) NOT NULL DEFAULT '0',
  `extern` json NOT NULL,
  PRIMARY KEY (`error_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `online_judge_logs`;
CREATE TABLE IF NOT EXISTS `online_judge_logs` (
  `log_id` int(32) NOT NULL AUTO_INCREMENT,
  `id` int(32) NOT NULL,
  `question_id` int(32) NOT NULL,
  `time` datetime NOT NULL,
  `lasttime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ans` longtext NOT NULL,
  `result` json NOT NULL,
  `testconfig` json NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `online_judge_question_list`;
CREATE TABLE IF NOT EXISTS `online_judge_question_list` (
  `question_id` int(32) NOT NULL AUTO_INCREMENT,
  `id` int(32) NOT NULL,
  `question_type` int(8) NOT NULL DEFAULT '0',
  `submit` int(32) NOT NULL DEFAULT '0',
  `right` int(32) NOT NULL DEFAULT '0',
  `question` varchar(1024) DEFAULT NULL,
  `exdata` json DEFAULT NULL,
  `config` json DEFAULT NULL,
  `source` varchar(128) DEFAULT NULL,
  `lasttime` datetime NOT NULL,
  `class` json DEFAULT NULL,
  `use` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`question_id`)
) ENGINE=MyISAM AUTO_INCREMENT=646 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `smallapp_list`;
CREATE TABLE IF NOT EXISTS `smallapp_list` (
  `url` char(64) NOT NULL,
  `name` char(64) NOT NULL,
  `is_script` int(1) NOT NULL DEFAULT '0',
  `init_script` varchar(128) DEFAULT NULL,
  `run_script` varchar(128) DEFAULT NULL,
  `hash` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DELETE FROM `smallapp_list`;
INSERT INTO `smallapp_list` (`url`, `name`, `is_script`, `init_script`, `run_script`, `hash`) VALUES
	('color.js', '调色板', 1, 'color_init', 'color_run', 'color'),
	('message.php', '信息查看', 0, NULL, NULL, 'message');

DROP TABLE IF EXISTS `spider_163_music`;
CREATE TABLE IF NOT EXISTS `spider_163_music` (
  `mid` varchar(128) NOT NULL,
  `name` varchar(128) NOT NULL,
  `pic_url` varchar(512) NOT NULL,
  `album` varchar(128) NOT NULL,
  `music_url` varchar(512) NOT NULL,
  `singers` varchar(1024) NOT NULL,
  `lasttime` datetime NOT NULL DEFAULT '1926-08-17 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `spider_qq_music`;
CREATE TABLE IF NOT EXISTS `spider_qq_music` (
  `mid` varchar(128) NOT NULL,
  `name` varchar(128) NOT NULL,
  `pic_url` varchar(512) NOT NULL,
  `album` varchar(128) DEFAULT NULL,
  `music_url` varchar(512) DEFAULT NULL,
  `singers` varchar(1024) DEFAULT NULL,
  `lasttime` datetime NOT NULL DEFAULT '1926-08-17 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;