<?php
	include_once("jry_wb_get_device.php");
	include_once("jry_wb_test_device.php");
	if((!jry_wb_test_is_mobile())||jry_wb_get_device()=='ipad')
		$fontsize=30;
	else
		$fontsize=50;
	session_start();
	$fontcount=4; 
	$width=20+$fontcount*$fontsize;
	$height=$fontsize+6;
	@ header("Content-Type:image/png");
	$im = imagecreate($width,$height);
	$back = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
	$pix  = imagecolorallocate($im, 187, 230, 247);
	$font = imagecolorallocate($im, 41, 163, 238);
	 mt_srand();
	for ($i = 0; $i < 1000; $i++) 
		imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $pix);
	$srcstr = "23456789abcdefghijklmnpqrstwyzABCDEFGHJKLMNPQRSTWYZ";
	mt_srand();
	$_SESSION["vcode"]='';
	for ($i = 0; $i < $fontcount; $i++) 
	{
		$c=$srcstr[mt_rand(0, 50)];
		$_SESSION["vcode"].=$c;
		imagettftext($im,$fontsize,mt_rand(0,50),10+$i*$fontsize,$fontsize+4,$font,'../../data/font/simhei.ttf',$c);
	}
	imagerectangle($im, 0, 0, $width -1, $height -1, $font);
	imagepng($im);
	imagedestroy($im);
?>
