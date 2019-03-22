<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_check_size($user,$size)
	{
		return (($user['nd_ei']['size_total']-$user['nd_ei']['size_uploading']-$size-$user['nd_ei']['size_used'])>=0);
	}		
?>