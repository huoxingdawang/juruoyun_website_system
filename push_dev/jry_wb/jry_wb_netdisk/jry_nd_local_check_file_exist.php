<?php
	include_once("jry_wb_local_include.php");
	function jry_nd_local_check_file_exist($area,$file_name)
	{
		if($area['type']!=0)
			throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>200000,'file'=>__FILE__,'line'=>__LINE__)));			
		return file_exists($file_name);
	}
?>
