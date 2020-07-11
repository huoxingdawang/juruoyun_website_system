<?php
	include_once("jry_wb_includes.php");
	function jry_wb_load_style($style_id)
	{
		$st=jry_wb_connect_database()->prepare("SELECT * FROM ".JRY_WB_DATABASE_GENERAL."style where style_id=?");
		$st->bindValue(1,$style_id);
		$st->execute();
		$buf=$st->fetchAll()[0];
		if($buf==null)
		{
			$st=jry_wb_connect_database()->prepare("SELECT * FROM ".JRY_WB_DATABASE_GENERAL."style where style_id=?");
			$st->bindValue(1,1);
			$st->execute();
			$buf=$st->fetchAll()[0];			
		}
		$ans["update"]			=json_decode($buf["update"],true);
		$ans["data"]			=json_decode($buf["data"],true);
		$ans["style_id"]		=$buf["style_id"];
		$ans["id"]				=$buf["id"];
		$ans["name"]			=$buf["name"];
		$ans["note"]			=$buf["note"];
		return $ans;
	}
	function jry_wb_include_css(&$style,$dir,$mobile=NULL)
	{
		$ans="";
		$buf=$style["data"];
		if($mobile==NULL)
			$mobile=jry_wb_include_judge_mobile();
		if($style["data"]["base_url"]=="")
			$style["data"]["base_url"]=JRY_WB_HOST."jry_wb_css/";
		foreach(explode("/",$dir) as $d)
			if(($buf=$buf[$d])==NULL)
				return '';
		if($buf['disable']==true||$buf[($mobile)?"mobile":"desktop"]=='')
			return '';
		return '<link rel="stylesheet" type="text/css" href="'.$style["data"]["base_url"].$buf[($mobile)?"mobile":"desktop"].'" id="'.$dir.'">';
	}
	function jry_wb_include_judge_mobile()
	{
		return (jry_wb_test_is_mobile()&&(jry_wb_get_device()!='ipad'));
	}
	
?>