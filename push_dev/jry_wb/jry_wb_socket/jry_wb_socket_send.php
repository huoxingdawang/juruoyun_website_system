<?php
	include_once("jry_wb_cli_includes.php");
	function jry_wb_socket_send($client,$message)
	{
		$message=json_encode($message);
		$b1=0x80|(0x1&0x0f);
		$length=strlen($message);
		if($length<=125)
		{
			$header=pack('CC',$b1,$length);
		}
		elseif($length>125&&$length<65536)
		{
			$header=pack('CCn',$b1,126,$length);
		}
		elseif($length>=65536)
		{
			$header=pack('CCNN',$b1,127,$length);
		}
		$message=$header.$message;
		socket_write($client,$message,strlen($message));
		return $length;
	}