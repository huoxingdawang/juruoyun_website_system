<?php
	include_once("../tools/jry_wb_includes.php");
	jry_wb_print_head("",true,true,true,array('use','usepicturebed'),false);
	$type=strtolower(trim(strrchr($_POST["name"], '.'),'.'));
	if($type=='jpeg'||$type=='jpg'||$type=='bmp'||$type=='gif'||$type=='png')
	{
		$conn=jry_wb_connect_database();
		$st = $conn->prepare("INSERT INTO ".constant('picturebeddb')."map (type,id,time) VALUES (?,?,?)");
		$st->bindParam(1,$type);
		$st->bindParam(2,$jry_wb_login_user['id']);
		$st->bindParam(3,jry_wb_get_time());
		$st->execute();
		$name=$conn->lastInsertId();
		$target = constant('jry_wb_upload_file_address').iconv("utf-8","gbk",$_POST["name"]);
		$dst = fopen(constant('jry_wb_upload_file_address').iconv("utf-8","gbk",$name.'.'.$type).'_jryupload', 'wb');
		
		for($i = 0; $i < $_POST['index']; $i++) 
		{
			$slice = $target . '-' . $i;
			$src = fopen($slice, 'rb');
			stream_copy_to_stream($src, $dst);
			fclose($src);
			unlink($slice);
		}
		fclose($dst);
		echo json_encode(array('src'=>constant('jry_wb_host')."picturebed/get_picturebed.php?pictureid=".$name,'id'=>$name));	
	}