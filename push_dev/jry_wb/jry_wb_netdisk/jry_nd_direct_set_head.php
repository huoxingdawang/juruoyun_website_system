<?php
	include_once('jry_nd_direct_include.php');
	function jry_nd_direct_set_head($action,$file)
	{
		if($action!='open')
			header("Content-Disposition: attachment; filename=".$file['name'].'.'.$file['type']);
		else
		{
			if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
			{
				header('Last-Modified: '.$_SERVER['HTTP_IF_MODIFIED_SINCE'],true,304);
				exit();
			}
			header("Cache-Control: private, max-age=10800, pre-check=10800");
			header("Pragma: private");
			header("Expires: " . date(DATE_RFC822,strtotime(" 2 day")));
		}
		header("content-type: ".jry_nd_get_content_type($file['type']));
		header("Accept-Ranges: bytes");
	}
?>