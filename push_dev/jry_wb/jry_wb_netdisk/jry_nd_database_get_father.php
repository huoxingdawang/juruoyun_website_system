<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_get_father($conn,$user,$file_id,$judger=null)
	{
		if($judger==null)
			$judger=function()
			{
				return true;
			};
		$file=jry_nd_database_get_file($conn,$user,$file_id);
		if($file===true)
			return true;
		if($file===null)
			return null;	
		if($judger($file))
			return $file;
		else
			return jry_nd_database_get_father($judger,$user,$file['file_id'],$judger);
	}				
?>