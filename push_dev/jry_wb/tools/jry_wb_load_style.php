<?php
	include_once("jry_wb_includes.php");
	function jry_wb_load_style($style_id)
	{
		$st=jry_wb_connect_database()->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'style where style_id=?');
		$st->bindValue(1,$style_id);
		$st->execute();
		$buf=$st->fetchAll()[0];
		if($buf==null)
		{
			$st=jry_wb_connect_database()->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'style where style_id=?');
			$st->bindValue(1,1);
			$st->execute();
			$buf=$st->fetchAll()[0];			
		}
		$buf['update']			=json_decode($buf['update']);
		$buf['data']			=json_decode($buf['data']);
		$ans['style_id']		=$buf['style_id'];
		$ans['id']				=$buf['id'];
		$ans['name']			=$buf['name'];
		$ans['note']			=$buf['note'];
		$ans['data']['desktop_css_address']			=($ans['data']['desktop_css_type']=$buf['data']->desktop_css_type)?$buf['data']->desktop_css_address:JRY_WB_HOST.'jry_wb_css/'.$buf['data']->desktop_css_address;
		$ans['data']['general_css_address']			=($ans['data']['general_css_type']=$buf['data']->general_css_type)?$buf['data']->general_css_address:JRY_WB_HOST.'jry_wb_css/'.$buf['data']->general_css_address;
		$ans['data']['mobile_css_address']			=($ans['data']['mobile_css_type']=$buf['data']->mobile_css_type)?$buf['data']->mobile_css_address:JRY_WB_HOST.'jry_wb_css/'.$buf['data']->mobile_css_address;
		$ans['data']['mainpages_index_css_address']	=($ans['data']['mainpages_index_css_type']=$buf['data']->mainpages_index_css_type)?$buf['data']->mainpages_index_css_address:JRY_WB_HOST.'jry_wb_css/'.$buf['data']->mainpages_index_css_address;
		$i=0;
		foreach($buf['update'] as $one)
		{
			$ans['update'][$i]['time']=$one->time;
			$ans['update'][$i]['data']=$one->data;
			$i++;
		}
		return $ans;
	}
?>