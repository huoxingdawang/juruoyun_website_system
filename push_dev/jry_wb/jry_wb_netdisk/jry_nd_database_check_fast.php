<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_check_fast($conn,&$area,$share_mode,$user,$file,&$by,$share_user=null)
	{
		if($_GET['fast']!='1')
			return false;
		if($user['nd_ei']==NULL)
			$user['nd_ei']=jry_wb_get_netdisk_information_by_id($user['id']);		
		if(	($share_mode&&$user['id']!=-1&&$user['nd_ei']['fast_size']>$file['size'])||
			(!$share_mode&&$user['nd_ei']['fast_size']>$file['size']))
			$by='user';
		else if($share_mode&&$share_user['nd_ei']['fast_size']>$file['size'])
			$by='share';
		
		if(	($share_mode&&$by=='share'&&$share_user['nd_ei']['fast_size']>$file['size'])||
			($share_mode&&$by=='user'&&$user['nd_ei']['fast_size']>$file['size'])||
			(!$share_mode&&$user['nd_ei']['fast_size']>$file['size']))
			if($area['fast'])
				return true;
			else
				return ($area['faster']!=''&&(($area['faster_area']=jry_nd_database_get_area($conn,$area['faster']))!=null));
		else
			return false;	
	}
?>