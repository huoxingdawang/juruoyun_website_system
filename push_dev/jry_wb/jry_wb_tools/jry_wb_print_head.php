<?php
	include_once("jry_wb_includes.php");
	function jry_wb_print_head($title,$checklogin,$setweb,$addtool,$compentence=array('use'),$out=true,$mt=true)
	{
		ob_start();
		global $jry_wb_login_user; 
		include("jry_wb_save_browsing_history.php");
		global $jry_wb_keywords; 
		global $jry_wb_description;
		session_start();
		if($setweb)
			$_SESSION['url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];	
		if($out==false)
		{
			if($checklogin)
				jry_wb_check_compentence(NULL,$compentence);
			return true;
		}
	?>	
	<!DOCTYPE html PUBLIC >
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		<title id="title"><?php echo JRY_WB_NAME."|".$title?></title>
		<link rel='icon' href='<?php echo JRY_WB_LOGO_ICO_ADDRESS;?>' type='image/x-icon'>
		<link rel='shortcut icon' href='<?php echo JRY_WB_LOGO_ICO_ADDRESS;?>' type='image/x-icon'>
		<link rel='stylesheet' type='text/css' href='<?php echo JRY_WB_HOST?>jry_wb_css/iconfont.css'>
		<link rel='stylesheet' type='text/css' href='<?php echo JRY_WB_HOST?>jry_wb_css/colorpicker.css'>
		<?php echo jry_wb_include_css($jry_wb_login_user['style'],'general'); ?>
		<meta name="description" content="<?php echo JRY_WB_DESCRIPTION;?>;由李俊彦开发的蒟蒻云网站系统(<?php echo JRY_WB_VERSION?>)强力驱动,strong powered by 'jry web system(<?php echo JRY_WB_VERSION?>)' which is developed by lijunyan;<?php echo $jry_wb_description;?>">
		<meta name="keywords" content="<?php echo JRY_WB_NAME?>,蒟蒻云网站系统,juruoyun web system,<?php echo JRY_WB_KEYWORDS;?>,<?php echo $jry_wb_keywords;?>">
		<meta name="robots" content="noarchive">
	<?php include_once('jry_wb_connect_php_to_js.php');?>
		<script language="javascript" src="<?php echo JRY_WB_HOST?>jry_wb_js/jry_wb_core_js.js.php"></script>
		<script language="javascript" src="<?php echo JRY_WB_HOST?>jry_wb_js/md5.js"></script>
		<script language="javascript" src="<?php echo JRY_WB_HOST?>jry_wb_js/colorpicker.js"></script>
		<script language="javascript" src="<?php echo JRY_WB_CHARACTER_DRAWING_LOGO_ADDRESS?>"></script>
	</head>
	<body ontouchstart="" onmouseover="">
	<?php if(strpos(jry_wb_get_browser(),'IE')!==false){ ?><div style="background-color:#000; font-size:50px; color:#FFFFFF; width:100%; height:100%;"><div style="font-size:200px; color:#F00;" align="center">WARNING!<br></div><div style="font-size:50px; color:#F00;" align="center">You are using browsers with too low versions.<br>Please stop now!<br>:-(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)-:</div><div style="font-size:25px; color:#F00;" align="center">本网站开发及测试工作均在<a href="https://www.google.cn/chrome/index.html">Chrome浏览器</a>上完成，下载<a href="https://www.google.cn/chrome/index.html">Chrome浏览器</a>可以获得100%兼容的体验</div></div></body><?php  exit();}?>
	<noscript><div style="background-color:#000; font-size:50px; color:#FFFFFF; width:100%; height:100%;"><div style="font-size:200px; color:#F00;" align="center">WARNING!<br></div><div style="font-size:50px; color:#F00;" align="center">You are useing browsers with out JS.<br>Please stop now!<br>:-(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)-:</div><div style="font-size:25px; color:#F00;" align="center"></div></div></noscript>
	<script type='text/javascript'>if (!navigator.cookieEnabled){document.body.innerHTML+='<div style="background-color:#000; font-size:50px; color:#FFFFFF; width:100%; height:100%;"><div style="font-size:200px; color:#F00;" align="center">WARNING!<br></div><div style="font-size:50px; color:#F00;" align="center">You are useing browsers with out Cookie.<br>Please stop now!<br>:-(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)-:</div><div style="font-size:25px; color:#F00;" align="center"></div></div>';}</script>
	<div align='center' id='__LOAD' style='position:fixed;top:0px;left:0px;width:100%;'>
		<div class="jry_wb_loading">
		  <div class="rect1"></div>
		  <div class="rect2" style="-webkit-animation-delay: -1.1s;animation-delay: -1.1s;"></div>
		  <div class="rect3" style="-webkit-animation-delay: -1.0s;animation-delay: -1.0s;"></div>
		  <div class="rect4" style="-webkit-animation-delay: -0.9s;animation-delay: -0.9s;"></div>
		  <div class="rect5" style="-webkit-animation-delay: -0.8s;animation-delay: -0.8s;"></div>
		</div>
	</div>
	<?php ob_flush();
		if($checklogin)
		{
			try{jry_wb_check_compentence(NULL,$compentence);}
			catch(jry_wb_exception $e)
			{
				$er=json_decode($e->getMessage());
				if($er->reason==100000)
				{?><script language=javascript>jry_wb_beautiful_alert.alert("没有登录","","window.location.href='<?php echo jry_wb_print_href("login",0,"",1)?>'");</script> <?php }
				else
				{?><script language=javascript>jry_wb_beautiful_alert.alert("权限不够","缺少<?php echo $er->extern?>","window.location.href='<?php echo jry_wb_print_href("home",0,"",1)?>'");</script> <?php }		
				exit();
			}			
		}
		if($jry_wb_login_user['id']!=-1&&JRY_WB_CHECK_TEL_SWITCH&&$mt&&(($jry_wb_login_user['tel']==null||$jry_wb_login_user['tel']=='')))
		{
	?><script language=javascript>jry_wb_beautiful_alert.alert("请验证电话",'',"window.location.href='<?php echo jry_wb_print_href("users",0,"",1)?>'");</script> <?php			
				exit();
		}
		if($jry_wb_login_user['id']!=-1&&JRY_WB_CHECK_MAIL_SWITCH&&JRY_WB_MAIL_SWITCH!=''&&$mt&&(($jry_wb_login_user['mail']==null||$jry_wb_login_user['mail']=='')))
		{
	?><script language=javascript>jry_wb_beautiful_alert.check("请在个人中心中验证邮箱",function(){window.location.href='<?php echo jry_wb_print_href("users",0,"",1)?>'},function(){},'现在就绑定','一会再绑定');</script> <?php		
		}
		if($addtool==true)
		{?>
	<p id="jry_wb_left_button_up" class="jry_wb_icon jry_wb_icon_jiantou_yemian_xiangshang" style="font-size:35px;z-index: 9999;right:0px;position:fixed;width:35px;" onClick="window.scrollTo(0,0)"></p>
	<p id="jry_wb_left_button_bug" class="jry_wb_icon jry_wb_icon_chongzi" style="font-size:35px;z-index: 9999;right:0px;position:fixed;width:35px;" onClick="window.open('<?php echo jry_wb_print_href('bug','','',1);?>');" ></p>
	<p id="jry_wb_left_button_down" class="jry_wb_icon jry_wb_icon_jiantou_yemian_xiangxia" style="font-size:35px;z-index: 9999;right:0px;position:fixed;width:35px;" onClick="window.scrollTo(0,document.body.scrollHeight)"></p>
	<script language="javascript">
			jry_wb_right_tools.add(document.getElementById('jry_wb_left_button_up'));
			jry_wb_right_tools.add(document.getElementById('jry_wb_left_button_bug'));
			jry_wb_right_tools.add(document.getElementById('jry_wb_left_button_down'));
	<?php if(JRY_WB_BACKGROUND_MUSIC_SWITCH){?>jry_wb_background_music.init();<?php } ?>
	</script>
	<?php }
	}
?>