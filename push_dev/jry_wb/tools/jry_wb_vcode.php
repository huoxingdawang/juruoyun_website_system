<?php
	include_once("../jry_wb_configs/jry_wb_config_default_user.php");
	include_once("jry_wb_get_device.php");
	include_once("jry_wb_test_device.php");
	if((!jry_wb_test_is_mobile())||jry_wb_get_device()=='ipad')
		$fontsize=30;
	else
		$fontsize=50;
	session_start();
	$fontcount=4; 
	$width=20+($fontcount+1)*$fontsize;
	$height=$fontsize*2;
	$im=imagecreate($width,$height);
	if(count(JRY_WB_VCODE_COLOR)==0)
	{
		$back=imagecolorallocate($im,0xFF,0xFF,0xFF);
		$pix=imagecolorallocate($im,187,230,247);
		$font=imagecolorallocate($im,41,163,238);
	}
	else
	{
		$buf=JRY_WB_VCODE_COLOR[mt_rand(0,count(JRY_WB_VCODE_COLOR)-1)];
		$back=imagecolorallocate($im,$buf['back']['r'],$buf['back']['g'],$buf['back']['b']);
		$pix=imagecolorallocate($im,$buf['pix']['r'],$buf['pix']['g'],$buf['pix']['b']);
		$font=imagecolorallocate($im,$buf['font']['r'],$buf['font']['g'],$buf['font']['b']);		
	}
	@header("Content-Type:image/png");
	mt_srand();
	for ($i = 0; $i <$width*$height*(3/4); $i++) 
		imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $pix);
	$srcstr = "123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	mt_srand();
	$_SESSION["vcode"]='';
	if(is_array(JRY_WB_VCODE_FONT_DIR))
		if(!is_nan((int)$_GET['use'])&&count(JRY_WB_VCODE_FONT_DIR)>(int)$_GET['use'])
			$dir=JRY_WB_VCODE_FONT_DIR[(int)$_GET['use']];
		else
			$dir=JRY_WB_VCODE_FONT_DIR[mt_rand(0,count(JRY_WB_VCODE_FONT_DIR)-1)];
	else
		$dir=JRY_WB_VCODE_FONT_DIR;
	for ($i = 0; $i < $fontcount; $i++) 
	{
		$c=$srcstr[mt_rand(0, 50)];
		$_SESSION["vcode"].=$c;
		if(!is_array(JRY_WB_VCODE_FONT_SLOPE)||count(JRY_WB_VCODE_FONT_SLOPE)!=2)
			$slope=0;
		else
			$slope=mt_rand(JRY_WB_VCODE_FONT_SLOPE[0],JRY_WB_VCODE_FONT_SLOPE[1]);
		imagettftext($im,$fontsize,$slope,$fontsize+$i*$fontsize,$fontsize*1.5,$font,$dir,$c);
	}
	imagerectangle($im, 0, 0, $width -1, $height -1, $font);
	imagepng($im);
	imagedestroy($im);
?>
