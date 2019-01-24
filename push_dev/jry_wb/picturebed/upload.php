<?php 
	include_once("../tools/jry_wb_includes.php");
	jry_wb_print_head("",true,true,true,array('use','usepicturebed'),false);
	$type=strtolower(trim(strrchr($_POST["name"], '.'),'.'));
	if($type=='jpeg'||$type=='jpg'||$type=='bmp'||$type=='gif'||$type=='png')
	{
		$target =constant('jry_wb_upload_file_address').iconv("utf-8","gbk",$_POST["name"]) . '-' . $_POST['index'];
		move_uploaded_file($_FILES['file']['tmp_name'], $target);
		// Might execute too quickly.
		sleep(1);
	}
	else
	{
		echo 'error';
	}