<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_check_fast($conn,&$area,$share_mode,$user,$file,&$by,$share_user=null)
	{
		if($_GET['fast']!='1')
			return false;
		if(	($share_mode&&$user['id']!=-1&&$user['nd_ei']['fast_size']>$file['size'])||
			(!$share_mode&&$user['nd_ei']['fast_size']>$file['size']))
			$by='user';
		else if($share_mode&&$share_user['nd_ei']['fast_size']>$file['size'])
			$by='share';
		if($area['fast'])									//高速区域文件
		{
			if(	($share_mode&&$share_user['nd_ei']['fast_size']>$file['size'])||
				($share_mode&&$user['id']!=-1&&$user['nd_ei']['fast_size']>$file['size'])||
				(!$share_mode&&$user['nd_ei']['fast_size']>$file['size']))
				return true;								//可以高速下载
			else
				return false;								//不可以高速下载
		}
		else												//低速区
		{
			if($area['faster']!='')							//有加速器
			{
				if(($area['faster_area']=jry_nd_database_get_area($conn,$area['faster']))!=null)
				{
					if(	($share_mode&&$share_user['nd_ei']['fast_size']>$file['size'])||
						($share_mode&&$user['id']!=-1&&$user['nd_ei']['fast_size']>$file['size'])||
						(!$share_mode&&$user['nd_ei']['fast_size']>$file['size']))
						if($area['faster_area']['fast'])
							return true;							//可以高速下载
						else
							return false;							//可以高速下载
					else
						return false;								//不可以高速下载					
				}
				else
					return false;
			}
			else
				return false;								//不行
		}		
	}
?>