<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	header("Content-Type:text/xml");
	$dom=new DOMDocument("1.0");
	$root=$dom->createElement("urlset");												$dom->appendChild($root);
	$xmlns=$dom->createAttribute("xmlns");												$root->appendChild($xmlns);
	$xmlnsvalue=$dom->createTextNode("http://www.google.com/schemas/sitemap/0.84");		$xmlns->appendChild($xmlnsvalue);
	function add_one($urll,$priority,$date)
	{
		global $root;
		global $dom;
		$url=$dom->createElement("url");												$root->appendChild($url);
		$loc=$dom->createElement("loc");												$url->appendChild($loc);
		$text=$dom->createTextNode($urll);												$loc->appendChild($text);
		$loc=$dom->createElement("lastmod");											$url->appendChild($loc);
		$text=$dom->createTextNode($date);												$loc->appendChild($text);
		$loc=$dom->createElement("changefreq");											$url->appendChild($loc);
		$text=$dom->createTextNode("weekly");											$loc->appendChild($text);
		$loc=$dom->createElement("priority");											$url->appendChild($loc);
		$text=$dom->createTextNode("1");												$loc->appendChild($text);		
	}
	add_one("http://dev.juruoyun.top/",1,date("Y-m-d H:i:s",time()));
	add_one("http://dev.juruoyun.top/index.php",1,date("Y-m-d H:i:s",time()));
	add_one(JRY_WB_HOST.'',1,date("Y-m-d H:i:s",time()));
	add_one(JRY_WB_HOST.'index.php',1,date("Y-m-d H:i:s",time()));
	jry_wb_load_website_map();
	foreach($jry_wb_website_map as $one)
		if($one['type']==1)
			add_one($one['url'],0.9,date("Y-m-d H:i:s",time()));
		else if($one['type']==0)
			add_one(JRY_WB_HOST.$one['url'],0.9,date("Y-m-d H:i:s",time()));
	//BOLG
	$conn=jry_wb_connect_database();
	$st = $conn->prepare("SELECT `blog_id`,`lasttime` FROM ".constant('blogdb')."text WHERE `delete`=0 AND `ifshow`=1");
	$st->execute();				
	$data=$st->fetchAll();
	$urlll=jry_wb_print_href("blog","","",true);
	foreach($data as $one)
		add_one($urlll.'?blog_id='.$one['blog_id'],0.6,$one['lasttime']);
	//STYLE	
	$st = $conn->prepare("SELECT `style_id`,`lasttime` FROM ".JRY_WB_DATABASE_GENERAL."style");
	$st->execute();				
	$data=$st->fetchAll();
	$urlll=jry_wb_print_href("jry_wb_style_control","","",true);
	foreach($data as $one)
		add_one($urlll.'?try='.$one['style_id'],0.6,$one['lasttime']);
	//OJ
	$st = $conn->prepare("SELECT `ojquestionid`,`lasttime` FROM ".constant('ojdb')."questionlist");
	$st->execute();				
	$data=$st->fetchAll();
	$urlll=jry_wb_print_href("showquestion","","",true);
	foreach($data as $one)
		add_one($urlll.'#{"ojquestionid":'.$one['ojquestionid']."}",0.6,$one['lasttime']);
	$dom->save(dirname(dirname(__DIR__))."/sitemap.xml");		
	echo $dom->saveXML();	
	
?>