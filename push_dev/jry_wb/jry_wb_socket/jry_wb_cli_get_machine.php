<?php
	include_once("jry_wb_cli_includes.php");
	if((!jry_wb_test_is_cli_mode())){header('HTTP/1.1 404 Not Found');header("status: 404 Not Found");include('../../404.php');exit();}	
	function jry_wb_cli_get_machine()
	{
		$information=array();
		$fp=popen('top -b -n 2 -i1 | grep -E "^(%Cpu|Tasks|KiB Mem|KiB Swap)"',"r");
		$rs=fread($fp,4096*4);
		pclose($fp);
		$data=explode("\n",$rs);
		
		$sys_info=explode(":",$data[4])[1];
		$information['tast_info']				=array();
		$information['tast_info']['total']		=(int)explode(",",$sys_info)[0];
		$information['tast_info']['running']	=(int)explode(",",$sys_info)[1];
		$information['tast_info']['sleeping']	=(int)explode(",",$sys_info)[2];
		$information['tast_info']['stopped']	=(int)explode(",",$sys_info)[3];
		$information['tast_info']['zombie']		=(int)explode(",",$sys_info)[4];
		
		$cpu_info=explode(":",$data[5])[1];
		$information['cpu_info']		=array();
		$information['cpu_info']['us']	=(float)explode(",",$cpu_info)[0];//us 用户空间占用CPU百分比
		$information['cpu_info']['sy']	=(float)explode(",",$cpu_info)[1];//sy 内核空间占用CPU百分比
		$information['cpu_info']['ni']	=(float)explode(",",$cpu_info)[2];//ni 用户进程空间内改变过优先级的进程占用CPU百分比
		$information['cpu_info']['id']	=(float)explode(",",$cpu_info)[3];//id 空闲CPU百分比
		$information['cpu_info']['wa']	=(float)explode(",",$cpu_info)[4];//wa 等待输入输出的CPU时间百分比
		$information['cpu_info']['hi']	=(float)explode(",",$cpu_info)[5];//hi 硬件中断
		$information['cpu_info']['si']	=(float)explode(",",$cpu_info)[6];//si 软件中断
		$information['cpu_info']['st']	=(float)explode(",",$cpu_info)[7];//st 实时
		
		$mem_info=explode(":",$data[6])[1];
		$mem_info=preg_replace("/\s{2,}/",' ',$mem_info);		
		$information['mem_info']				=array();
		$information['mem_info']['total']		=(int)explode(",",$mem_info)[0]/1024;
		$information['mem_info']['free']		=(int)explode(",",$mem_info)[1]/1024;
		$information['mem_info']['used']		=(int)explode(",",$mem_info)[2]/1024;
		$information['mem_info']['buff_cache']	=(int)explode(",",$mem_info)[3]/1024;
		
		$swap_info=explode(":",$data[7])[1];
		$swap_info=preg_replace("/\s{2,}/",' ',$swap_info);		
		$information['swap_info']				=array();
		$information['swap_info']['total']		=(int)explode(",",$swap_info)[0]/1024;
		$information['swap_info']['free']		=(int)explode(",",$swap_info)[1]/1024;
		$information['swap_info']['used']		=(int)explode(",",$swap_info)[2]/1024;
		$information['swap_info']['avail_mem']	=(int)explode(".",$swap_info)[1]/1024;

		$fp=popen('df -lh | grep -E "^(/)"',"r");
		$rs=fread($fp,4096);
		pclose($fp);
		$hd_info=explode("\n",$rs);
		$information['hd_info']=array();
		foreach($hd_info as $info)
		{
			$info=preg_replace("/\s{2,}/",' ',$info);
			if($info!='')
			{
				$buf=array();
				$buf['filesystem']	=(string)	explode(" ",$info)[0];
				$buf['size']		=(float)	explode(" ",$info)[1];
				$buf['used']		=(float)	explode(" ",$info)[2];
				$buf['avail']		=(float)	explode(" ",$info)[3];
				$buf['use']			=(float)	explode(" ",$info)[4];
				$buf['mounted']		=(string)	explode(" ",$info)[5];
				$information['hd_info'][]=$buf;
			}
		}
		$information['time']=jry_wb_get_time();
		return $information;
	}