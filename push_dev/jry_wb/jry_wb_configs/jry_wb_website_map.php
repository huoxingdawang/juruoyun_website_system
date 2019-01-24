<?php
	global $jry_wb_website_map;
	$jry_wb_website_map=array(
    array(
        "url"=> "#top",
        "name"=> "top",
        "type"=> 2,
        "show_name"=> "返回顶部",
        "show_at_mainpage"=> 0
    ),
    array(
        "url"=> "http://dev.juruoyun.top/jry_wb/jry_wb_dev/bugreport.php",
        "name"=> "bug",
        "type"=> 1,
        "show_name"=> "BUGreport",
        "show_at_mainpage"=> 0
    ),
    array(
        "url"=> "http://dev.juruoyun.top/jry_wb/aboutus/index.php",
        "name"=> "aboutus",
        "type"=> 1,
        "show_name"=> "关于我们",
        "show_at_mainpage"=> 0
    ),	
//mainpages
    array(
        "url"=> "jry_wb_mainpages/index.php",
        "name"=> "home",
        "type"=> 0,
        "show_name"=> "返回",
        "show_at_mainpage"=> 0
    ),
    array(
        "url"=> "jry_wb_mainpages/chenge.php",
        "name"=> "users",
        "type"=> 0,
        "show_name"=> "用户管理",
        "show_at_mainpage"=> 0
    ),
    array(
        "url"=> "jry_wb_mainpages/login.php",
        "name"=> "login",
        "type"=> 0,
        "show_name"=> "登录",
        "show_at_mainpage"=> 0
    ),
    array(
        "url"=> "jry_wb_mainpages/add.php",
        "name"=> "add_user",
        "type"=> 0,
        "show_name"=> "注册",
        "show_at_mainpage"=> 0
    ),
    array(
        "url"=> "jry_wb_mainpages/help.php",
        "name"=> "help",
        "type"=> 0,
        "show_name"=> "帮助中心",
        "show_at_mainpage"=> 0
    ),
    array(
        "url"=> "jry_wb_mainpages/forget.php",
        "name"=> "forget",
        "type"=> 0,
        "show_name"=> "老子把密码和账户忘了",
        "show_at_mainpage"=> 0
    ),
//tools
    array(
        "url"=> "tools/verificationcode.php",
        "name"=> "verificationcode",
        "type"=> 0,
        "show_name"=> "",
        "show_at_mainpage"=> 0
    ),
//manage
    array(
        "url"=> "jry_wb_manage_system/index.php",
        "name"=> "jry_wb_manage_system",
        "type"=> 0,
        "show_name"=> "管理员中心",
        "show_at_mainpage"=> 0
    ),
//smallapp
    array(
        "url"=> "smallapp/index.php",
        "name"=> "smallapp",
        "type"=> 0,
        "show_name"=> "小程序",
        "show_at_mainpage"=> 1
    ),
//oj
    array(
        "url"=> "oj/index.php",
        "name"=> "oj",
        "type"=> 0,
        "show_name"=> "在线测评",
        "show_at_mainpage"=> 1
    ),
    array(
        "url"=> "oj/oj_showquestion.php",
        "name"=> "showquestion",
        "type"=> 0,
        "show_name"=> "展示题目",
        "show_at_mainpage"=> 0
    ),
    array(
        "url"=> "oj/index.php?action=logs",
        "name"=> "ojlogs",
        "type"=> 0,
        "show_name"=> "提交记录",
        "show_at_mainpage"=> 0
    ),
    array(
        "url"=> "oj/index.php?action=all",
        "name"=> "ojall",
        "type"=> 0,
        "show_name"=> "题目总览",
        "show_at_mainpage"=> 0
    ),   
//blog
	array(
        "url"=> "blog/draft.php",
        "name"=> "blog_draft",
        "type"=> 0,
        "show_name"=> "博客草稿箱",
        "show_at_mainpage"=> 0
    ),
    array(
        "url"=> "blog/editor.php",
        "name"=> "blog_editor",
        "type"=> 0,
        "show_name"=> "博客编辑器",
        "show_at_mainpage"=> 0
    ),
    array(
        "url"=> "blog/index.php",
        "name"=> "blog",
        "type"=> 0,
        "show_name"=> "博客",
        "show_at_mainpage"=> 1
    ),
    array(
        "url"=> "blog/show.php",
        "name"=> "blog_show",
        "type"=> 0,
        "show_name"=> "博客展示",
        "show_at_mainpage"=> 0
    ),	
//user guide
    array(
        "url"=> "blog/show_chunjing.php?blog_id=3",
        "name"=> "introduction",
        "type"=> 0,
        "show_name"=> "开发组简介",
        "show_at_mainpage"=> 0
    ),
	array
	(
        "url"=> "blog/show_chunjing.php?blog_id=11",
        "name"=> "xieyi",
        "type"=> 0,
        "show_name"=> "蒟蒻云用户协议",
        "show_at_mainpage"=> 0
    ),
	array(
        "url"=> "blog/show_chunjing.php?blog_id=13",
        "name"=> "zhinan",
        "type"=> 0,
        "show_name"=> "用户指南",
        "show_at_mainpage"=> 0
    ),	
//picturebed
    array(
        "url"=> "picturebed/index.php",
        "name"=> "picturebed",
        "type"=> 0,
        "show_name"=> "图床",
        "show_at_mainpage"=> 1
    ),
    array(
        "url"=> "picturebed/mypicturebed.php",
        "name"=> "mypicturebed",
        "type"=> 0,
        "show_name"=> "我的图床",
        "show_at_mainpage"=> 0
    ),
//netdisk
    array(
        "url"=> "jry_wb_netdisk/index.php",
        "name"=> "jry_wb_netdisk",
        "type"=> 0,
        "show_name"=> "网盘",
        "show_at_mainpage"=> 1
    ),
//school
    array(
        "url"=> "school/index.php",
        "name"=> "school",
        "type"=> 0,
        "show_name"=> "校园",
        "show_at_mainpage"=> 1
    ),	
//主题管理器
    array(
        "url"=> "jry_wb_style_control/index.php",
        "name"=> "jry_wb_style_control",
        "type"=> 0,
        "show_name"=> "主题管理器",
        "show_at_mainpage"=> 0
    )	
);
?>