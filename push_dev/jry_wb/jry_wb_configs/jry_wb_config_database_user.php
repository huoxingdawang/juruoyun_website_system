<?php
	define('jry_wb_database_name'			,'');		//数据库名称
	define('jry_wb_database_addr'			,'');		//数据库位置
	define('jry_wb_database_user_name'		,'');		//用户名
	define('jry_wb_database_user_password'	,'');		//密码
	define('jry_wb_database_all_prefix'		,'');		//前缀
	
	if(!constant('jry_wb_host_switch'))					//分站控制
	{
		define('jry_wb_host_database_name'			,'');
		define('jry_wb_host_database_addr'			,'');
		define('jry_wb_host_database_user_name'		,'');
		define('jry_wb_host_database_user_password'	,'');
		define('jry_wb_host_database_all_prefix'	,'');	
	}
?>