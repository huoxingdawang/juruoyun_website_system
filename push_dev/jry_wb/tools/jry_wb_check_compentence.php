<?php
	include_once("jry_wb_includes.php");
	function jry_wb_check_compentence($user=NULL,$compentence=NULL,$code='')
	{
		global $jry_wb_login_user;
		if($user==NULL)
			$user=$jry_wb_login_user;
		if($compentence==NULL||!is_array($compentence))
			$compentence=array('use');
		if($code=='')
			$code=$_COOKIE['code'];
		if(($code!=$jry_wb_login_user['code']||$jry_wb_login_user['code']==''||$code==''))
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100000,'file'=>__FILE__,'line'=>__LINE__)));		
		foreach($compentence as $c)
			if($jry_wb_login_user[$c]==0)
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100001,'extern'=>$c)));		
	}