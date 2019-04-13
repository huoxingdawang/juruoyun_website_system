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
		echo '<a href="'.constant("jry_wb_host").$website['url'].'" target="_parent" class='.($active?'active':'').'>'.($active?'':'返回').$website['show_name'].constant('jry_wb_name').'<img width="'.$width.'px" src="'.constant('jry_wb_logo_picture_address').'"></img></a>';
	}
?>