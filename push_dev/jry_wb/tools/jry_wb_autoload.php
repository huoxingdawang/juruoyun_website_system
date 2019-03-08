<?php
	//阿里云loader
	spl_autoload_register(
		function ($class)
		{
			$file =dirname(__DIR__).'/jry_wb_tp_sdk/aly/'.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
			if (file_exists($file))
				include_once($file);
			$file =dirname(__DIR__).'/jry_wb_tp_sdk/aly/aliyun-php-sdk-core/'.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
			if (file_exists($file))
				include_once($file);
			$file =dirname(__DIR__).'/jry_wb_tp_sdk/aly/aliyun-php-sdk-core/Auth/'.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
			if (file_exists($file))
				include_once($file);
			$file =dirname(__DIR__).'/jry_wb_tp_sdk/aly/aliyun-php-sdk-core/Http/'.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
			if (file_exists($file))
				include_once($file);
			$file =dirname(__DIR__).'/jry_wb_tp_sdk/aly/aliyun-php-sdk-core/Profile/'.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
			if (file_exists($file))
				include_once($file);
			$file =dirname(__DIR__).'/jry_wb_tp_sdk/aly/aliyun-php-sdk-core/Regions/'.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
			if (file_exists($file))
				include_once($file);
			$file =dirname(__DIR__).'/jry_wb_tp_sdk/aly/aliyun-php-sdk-core/Exception/'.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
			if (file_exists($file))
				include_once($file);		
			$file =dirname(__DIR__).'/jry_wb_tp_sdk/aly/aliyun-php-sdk-sts/'.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
			if (file_exists($file))
				include_once($file);
		}
	);
?>