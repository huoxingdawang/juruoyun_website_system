<?php header('Access-Control-Allow-Origin:http://www.speedtest.cn');if(isset($_GET['file'])){if($_GET['file']=='1m'){$a=fread(fopen('1m.jpg','rw'),filesize('1m.jpg'));$b=getimagesize('1m.jpg');header('Content-Type:'.$b['mime']);echo $a;}else if($_GET['file']=='5m'){$a=fread(fopen('5m.jpg','rw'),filesize('5m.jpg'));$b=getimagesize('5m.jpg');header('Content-Type:'.$b['mime']);echo $a;}} ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="Description" content="测速网（SpeedTest.cn）为您提供在线免费网速测试,Ping测试，路由测试优质服务，拥有海内外，网通、联通、电信、移动、长城宽带等多个全面速度测试点，欢迎您的使用。">
<meta name="Keywords" content="测速网,测速,网速,在线测速,SpeedTest,网速测试,免费测速,网速测试,ping 测试, traceroute, 路由测试, bandwidth,bandwidth test,speedtest html5,上网速度测试,速度测试,提高网速,网络加速,速度测试,测试网速,带宽测试,宽带测试,上网速度">
<title>测速网 - 在线网速测试,网络测速 - SpeedTest.cn</title>
<link rel="shortcut icon" type="image/x-icon" href="http://www.speedtest.cn/assets/ico/favicon.ico">
<script>window.onload=bdtj;function bdtj(){var _hmt=_hmt||[];(function(){var hm=document.createElement("script");hm.src="//hm.baidu.com/hm.js?8decfd249e4c816635a72c825e27da1a";var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(hm,s)})()}</script>
</head>
<body style="color:#fff;text-align:center">
<p>bQ</p>
<h1 style="color:#000">speedtest测速点代码安装成功！</h1>
</body>
</html>