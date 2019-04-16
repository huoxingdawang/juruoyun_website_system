<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_get_father($conn,$user,$file,$judger=null,$extern=null)
	{
		if($judger==null)
			$judger=function()
			{
				return true;
			};
		$file=jry_nd_database_get_file($conn,$user,$file['father']);
		if($file===true)
			return true;
		if($file===null)
			return null;
		if($judger($file,$extern))
			return $file;
		else
			return jry_nd_database_get_father($conn,$user,$file,$judger);
	}				
?>