<?php
	include_once("../tools/jry_wb_includes.php");
	include_once('163_music_spider.php');
	include_once('qq_music_spider.php');
	$slid=(int)$_GET['slid'];
	$ans=array();
	if($slid==0)
	{
		foreach(constant('jry_wb_background_music_default_list') as $one)
		{
			if($one['type']=='jry')
			{
				
			}
			else if($one['type']=='qq')
				array_push($ans,jry_wb_qq_music_spider($one['mid']));
			else if($one['type']=='163')
				array_push($ans,jry_wb_163_music_spider($one['mid']));
			else
			{
				
			}
		}
	}
	echo json_encode(array('slid'=>$slid,'makerid'=>1,'data'=>$ans));
?>