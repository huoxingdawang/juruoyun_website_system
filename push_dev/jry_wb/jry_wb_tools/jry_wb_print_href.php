<?php
	include_once("jry_wb_includes.php");
	function jry_wb_print_href($name,$css="",$zhuijia="",$return=false,$id='')
	{
		global $jry_wb_website_map;
		jry_wb_load_website_map();
		$i=0;$n=count($jry_wb_website_map);
		for(;$jry_wb_website_map[$i]['name']!=$name&&$i<=$n;$i++);
		if($i==$n)
			return '';
		$website=$jry_wb_website_map[$i];
		if(!$return)
		{
			if($website['type']==1)
			{
				$add=$website[url].$zhuijia;
				echo '<a id="'.$id.'" href='.$add.' target="_blank" class='.$css.'>'.$website['show_name'].'</a>';
			}
			if($website['type']==2)
			{
				$add=$website[url].$zhuijia;
				echo '<a id="'.$id.'" href='.$add.' target="_parent" class='.$css.'>'.$website['show_name'].'</a>';
			}
			if($website['type']==0)
			{
				$add=JRY_WB_HOST.$website[url].$zhuijia;
				echo '<a id="'.$id.'" href='.$add.' target="_parent" class='.$css.'>'.$website['show_name'].'</a>';
			}
		}
		else
		{
			if($website['type']==1)
			{
				$add=$website[url].$zhuijia;
				return $add; 
			}
			if($website['type']==2)
			{
				$add=$website[url].$zhuijia;
				return $add; 			
			}
			if($website['type']==0)
			{
				$add=JRY_WB_HOST.$website[url].$zhuijia;
				return $add; 			
			}
		}
	}
?>