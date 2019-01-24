<?php
	define('jry_wb_database_name'			,'juruoyun_dev');
	define('jry_wb_database_addr'			,'localhost');
	define('jry_wb_database_user_name'		,'webserve');
	define('jry_wb_database_user_password'	,'webserve');
	define('jry_wb_database_all_prefix'		,'');
	
	if(!constant('jry_wb_host_switch'))
	{
		define('jry_wb_host_database_name'			,'juruoyun');
		define('jry_wb_host_database_addr'			,'localhost');
		define('jry_wb_host_database_user_name'		,'webserve');
		define('jry_wb_host_database_user_password'	,'webserve');
		define('jry_wb_host_database_all_prefix'	,'');		
	}
?>