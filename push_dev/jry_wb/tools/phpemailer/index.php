<?php include_once(dirname(dirname(dirname(__FILE__))).'/jry_wb/jry_wb_configs/jry_wb_config_includes.php');if($_SERVER['HTTP_HOST']!=constant('jry_wb_domin').(constant('jry_wb_port')==''?'':':').constant('jry_wb_port')){header("Location:".constant('jry_wb_host'));exit();}?><style type="text/css">.spinner{width:200px;height:100px;text-align:center;font-size:10px;}.spinner > div{margin:2px;background-color:#3498db;height:100%;width:15px;display:inline-block;-webkit-animation:stretchdelay 1.2s infinite ease-in-out;animation:stretchdelay 1.2s infinite ease-in-out;}.spinner .rect2{-webkit-animation-delay:-1.1s;animation-delay:-1.1s;}.spinner .rect3{-webkit-animation-delay:-1.0s;animation-delay:-1.0s;}.spinner .rect4{-webkit-animation-delay:-0.9s;animation-delay:-0.9s;}.spinner .rect5{-webkit-animation-delay:-0.8s;animation-delay:-0.8s;}@-webkit-keyframes stretchdelay{0%,40%,100%{-webkit-transform:scaleY(0.4)}20%{-webkit-transform:scaleY(1.0)}}@keyframes stretchdelay{0%,40%,100%{transform:scaleY(0.4);-webkit-transform:scaleY(0.4);}20%{transform:scaleY(1.0);-webkit-transform: scaleY(1.0);}}</style><script> var xmlhttp;if(window.XMLHttpRequest)xmlhttp=new XMLHttpRequest();else xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");xmlhttp.onreadystatechange=function(){if(xmlhttp.readyState==4&&xmlhttp.status==200)window.location.href='/jry_wb/jry_wb_mainpages/index.php';};xmlhttp.open("POST",'/jry_wb/tools/jry_wb_save_browsing_history.php',true);xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");xmlhttp.send('from='+document.referrer+'&now='+document.location.href);</script><body onLoad="" bgcolor="#999999"><div align="center"><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div></div></body>