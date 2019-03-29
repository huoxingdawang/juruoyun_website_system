<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_direct_check_new_fast($area,$file,$action)
	{
		if($file['extern']==NULL)
			return true;
		else
			if($file['extern']->$action!=''&&($area['fast']?jry_nd_direct_check_file_exist($area,$file,$action):jry_nd_direct_check_file_exist($area['faster_area'],$file,$action))==true)
				return $file['extern']->$action;
			else
				return true;
	}
?>