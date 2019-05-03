<?php
	include_once('jry_nd_database_include.php');
	function jry_nd_database_operate_fast_save($mode,$time)
	{
		if(($file=fopen('jry_nd.fast_save_message','r'))==false)
		{
			$conn=jry_wb_connect_database();
			$data=array();
			if($mode=='group')
			{
				$st = $conn->prepare('SELECT lasttime FROM '.constant('jry_wb_database_netdisk').'area ORDER BY lasttime DESC LIMIT 1;');
				$st->execute();	
				$data['area']=$st->fetchAll()[0]['lasttime'];
				$data['group']=$time;
			}
			else if($mode=='area')
			{
				$st = $conn->prepare('SELECT lasttime FROM '.constant('jry_wb_database_netdisk').'group ORDER BY lasttime DESC LIMIT 1;');
				$st->execute();	
				$data['group']=$st->fetchAll()[0]['lasttime'];
				$data['area']=$time;
			}
			else
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>230000,'file'=>__FILE__,'line'=>__LINE__)));
			$file2=fopen('jry_nd.fast_save_message','w');
			fwrite($file2,json_encode($data));
			fclose($file2);
			fclose($file);
			return true;
		}
		else
		{
			$data=json_decode(fread($file,filesize('jry_nd.fast_save_message')));
			if($mode=='group')
				$data->group=$time;
			else if($mode=='area')
				$data->area=$time;
			else
				throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>230000,'file'=>__FILE__,'line'=>__LINE__)));
			$file2=fopen('jry_nd.fast_save_message','w');
			fwrite($file2,json_encode($data));
			fclose($file2);
			fclose($file);
			return false;
		}
		return false;
	}				
?>