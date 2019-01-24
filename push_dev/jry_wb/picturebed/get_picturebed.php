<?php
	include_once("../tools/jry_wb_includes.php");
	$pictureid=$_GET['pictureid'];
	$size=$_GET['size'];	
	if($_GET['data']!='')
	{
		$data=json_decode(str_replace('`','"',urldecode($_GET['data'])));
		$pictureid=$data->pictureid;
		$size=$data->size;
	}
	$conn=jry_wb_connect_database();
	$st = $conn->prepare('SELECT * FROM '.constant('picturebeddb').'map where pictureid=? LIMIT 1');
	$st->bindParam(1,$pictureid);
	$st->execute();
	foreach($st->fetchAll()as $photo);
	$q='SELECT *,'.constant('jry_wb_database_general_prefix').'users.id AS id
		FROM '.constant('jry_wb_database_manage_system').'competence 
		INNER JOIN '.constant('jry_wb_database_general').'users  ON ('.constant('jry_wb_database_general_prefix').'users.type = '.constant('jry_wb_database_manage_system_prefix').'competence.type) 
		where '.constant('jry_wb_database_general_prefix')."users.id =? LIMIT 1";
	$st = $conn->prepare($q);
	$st->bindParam(1,$photo['id']);
	$st->execute();
	$errflag=false;
	foreach($st->fetchAll()as $user);
	if($user['use']&&$user['usepicturebed'])
		$filename = constant('jry_wb_upload_file_address').$photo['pictureid'].'.'.$photo['type']."_jryupload";
	else
		$errflag=true;
	if(!file_exists($filename))
		$errflag=true;
	$err_img=array('error1.jpg','error3.jpg');
	if(!$errflag)
	{
		$st = $conn->prepare("INSERT INTO ".constant('picturebeddb')."log (url,pictureid,time) VALUES (?,?,?)");
		if($_SERVER['HTTP_REFERER']==''&&$data->from!='')
			$st->bindParam(1,$data->from);
		else
			$st->bindParam(1,$_SERVER['HTTP_REFERER']);
		$st->bindParam(2,$pictureid);
		$st->bindParam(3,jry_wb_get_time());	
		$st->execute();
	}
	else
		$filename=$err_img[rand(0,count($err_img-1))];
	if ((!errflag)&&isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])&&(strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])==filemtime($filename)))
	{
	  header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($filename)).' GMT',true, 304);
	  exit;
	}
	$water_flag=true;
	if($_GET['action']!='download')
	{
		if($errflag)		
			header("Cache-Control: private, max-age=1, pre-check=1");
		else
			header("Cache-Control: private, max-age=10800, pre-check=10800");
		header("Pragma: private");
		header("Expires: " . date(DATE_RFC822,strtotime(" 2 day")));
		header("content-type: ".$src_img_information['mime']);
	}
	else
	{
		header("content-type:".getimagesize($filename)['mime']);  
		header("Accept-Ranges: bytes");
		header("Accept-Length:".filesize($filename));
		if(!$errflag)
			header("Content-Disposition: attachment; filename=juruoyun_".jry_wb_get_time().'_'.$photo['pictureid'].'.'.$photo['type']);
		else
			header("Content-Disposition: attachment; filename=".$filename);
		$water_flag=false;
	}	
	$size=$size==''?10000000000000:$size;
	$src_img_information = getimagesize($filename);
	$src_img_x=min(intval($size),$src_img_information[0]); 
	$src_img_y=($src_img_x/$src_img_information[0])*$src_img_information[1];
	$src_img_type=image_type_to_extension($src_img_information[2],false);
	$waterimg='logo_under_pic.png';
	$water_img_information =getimagesize($waterimg);
	$water_img_x=min($src_img_x/5,$water_img_information[0]*2); 
	$water_img_y=($water_img_x/$water_img_information[0])*$water_img_information[1];
	$water_img_type=image_type_to_extension($water_img_information[2],false);
	if($src_img_type=='gif'&&($_GET['small']==''||$_GET['small']==0))
	{
		function createWaterImagickDraw($waterimg,$x=10,$y=85,$width=16,$height=16)
		{
			$water = new Imagick($waterimg);
			$draw = new ImagickDraw();
			$draw->composite($water->getImageCompose(), $x, $y, $width, $height,$water);
			return $draw;
		}
		$image = new Imagick($filename);
		$animation=new Imagick();
		$animation->setFormat('gif');
		$image = $image->coalesceImages();
		$unitl = $image->getNumberImages();
		for ($i=0; $i<$unitl; $i++)
		{
			$image->setImageIndex($i);
			$thisimage = new Imagick();
			$thisimage->readImageBlob($image);
			$delay = $thisimage->getImageDelay();
			$thisimage->drawImage(createWaterImagickDraw($waterimg,$src_img_x-$water_img_x,$src_img_y-$water_img_y,$water_img_x,$water_img_y));
			$animation->addImage($thisimage);
			$animation->setImageDelay($delay);
		}
		echo $animation->getImagesBlob();
	}
	else
	{
		$result=imagecreatetruecolor($src_img_x,$src_img_y);
		$src_img_fun="imagecreatefrom".$src_img_type;
		$image=$src_img_fun($filename);
		imagecopyresampled($result,$image,0,0,0,0,$src_img_x,$src_img_y,$src_img_information[0],$src_img_information[1]);
		imagedestroy($image);
		if($water_flag)
		{
			$water_img_fun="imagecreatefrom".$water_img_type;
			$image=$water_img_fun($waterimg);
			imagecopyresampled($result,$image,$src_img_x-$water_img_x,$src_img_y-$water_img_y,0,0,$water_img_x,$water_img_y,$water_img_information[0],$water_img_information[1]);
			imagedestroy($image);
		}
		$result_fun = "image".$src_img_type;	
		$result_fun($result);
		imagedestroy($result);
	}
?>