<?php 
	include_once("jry_wb_includes.php");
	function jry_wb_print_logo($active)
	{
		global $jry_wb_login_user;
		global $jry_wb_website_map;
		jry_wb_load_website_map();
		$width=22;
		$i=0;
		for(;$jry_wb_website_map[$i]['name']!='home';$i++);
		$website=$jry_wb_website_map[$i];
		echo '<a href="'.JRY_WB_HOST.$website['url'].'" target="_parent" class='.($active?'active':'').'>'.($active?'':'返回').$website['show_name'].JRY_WB_NAME.'<img width="'.$width.'px" src="'.JRY_WB_LOGO_PICTURE_ADDRESS.'"></img></a>';
	}
?>