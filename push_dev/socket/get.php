<?php
//	$code="#include <iostream>\n#include <cstdio>\nusing namespace std;\nint main()\n{\n\nint a,b,c[10000000],d[10000000],e[10000000],f[10000000],g[10000000];\ncin>>a>>b;\nfor(int i=0;i<10000000;i++)c[i]=d[i]=g[i]=e[i]=f[i]=g[i]=19260817;cout<<a+b;\nreturn 0;\n}\n";
	$code="#include <iostream>\n#include <cstdio>\nusing namespace std;\nint main()\n{\n\nint a,b;\ncin>>a>>b;\ncout<<a+b;\nreturn 0;\n}\n";
	$lan="";
	$n=5;
	$problemid=645;
	$time=1000;
	$memory=1024;
	$lan=2;
	$count=0;
	echo "\n\n\n";
	include_once("config.php");
	$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
	echo "IP:".constant("test_machine_jry_wb_host_ip")."\n Port:".constant("test_machine_port")."\n";
	if(socket_bind($socket,constant("test_machine_jry_wb_host_ip"),constant("test_machine_port")) == false)
	{
		echo "server bind fail:".socket_strerror(socket_last_error())."\n";
		socket_close($socket);
		exit();
	}
	echo "Bind OK\r\n";
	if(socket_listen($socket,4)==false)
	{
        echo "server listen fail:".socket_strerror(socket_last_error())."\n";
		socket_close($socket);
		exit();
	}
	echo "Listened OK\n\n";
//	$test_machine_queue=new array();
	do
	{
		$accept_resource = socket_accept($socket);
		if($accept_resource !== false)
		{
			$count++;
			socket_getpeername($accept_resource, $remoteIP, $remotePort);
			echo "Oh new connection!\nnumber:".$count."\nIP:".$remoteIP." , Port:".$remotePort."\n";
			$message=json_encode(array("code"=>$code,"lan"=>(string)$lan,"n"=>(string)$n,'problemid'=>(string)$problemid,'time'=>(string)$time,'memory'=>(string)$memory,"logid"=>$count,
			'in'=>array("1 2\n","1 100000","0 0","19260817 0","1,100000000000000000"),
			'out'=>array("3\n","100001","0","19260817","100000000000000001")
			));
			echo "Writing data "/*.$message*/."\n";
			socket_write($accept_resource,$message,strlen($message));
			if(socket_write($accept_resource,$message,strlen($message)) == false)
			{
				echo "fail to write".socket_strerror(socket_last_error())."\n";
				break;
			}
			else
			{
				echo "Code write OK\n";
				$time1=date("Y-m-d H:i:s");
				echo "Waiting result start at".$time1."\n";
				$time1=strtotime($time1);
				$flag=false;
				$callback=socket_read($accept_resource,4096);
				/*if(((strtotime(date("Y-m-d H:i:s"))-$time1)%60)>5)
				{
					$flag=false;
				};*/
				$callback=(array)json_decode($callback);
				if($callback!=null&&$callback['logid']!='')//loaddata
				{
					echo "loaded data\n";
					print_r($callback);
					echo "\n";
				}				
				echo "Result got OK\n";
			}
			socket_close($accept_resource);
			echo "waiting for next connection\n\n\n";
		}
	}while(true);
	echo "exit\n";
	socket_close($socket);
?>