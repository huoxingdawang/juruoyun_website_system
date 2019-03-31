<?php
	include_once("jry_wb_includes.php");
	function jry_wb_print_head($title,$checklogin,$setweb,$addtool,$compentence=array('use'),$out=true,$mt=true)
	{
		ob_start();
		global $jry_wb_login_user; 
		global $jry_wb_keywords; 
		global $jry_wb_description; 
		if($setweb)
			$_SESSION['url']='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];	
		if($out==false)
		{
			if($checklogin)
			{
				if(((isset($_COOKIE['code']) ? $_COOKIE['code'] : '')!=$jry_wb_login_user['code']||$jry_wb_login_user['code']==''))
					throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100000,'file'=>__FILE__,'line'=>__LINE__)));
				foreach($compentence as $compentence_)
					if($jry_wb_login_user[$compentence_]==0)
						throw new jry_wb_exception(json_encode(array('code'=>false,'reason'=>100001,'file'=>__FILE__,'line'=>__LINE__,'extern'=>$compentence_)));
			}
			return true;
		}
	?>	
	<!DOCTYPE html PUBLIC >
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		<title id="title"><?php echo constant("jry_wb_name")."|".$title?></title>
		<link rel='icon' href='<?php echo constant('jry_wb_logo_ico_address');?>' type='image/x-icon'>
		<link rel='shortcut icon' href='<?php echo constant('jry_wb_logo_ico_address');?>' type='image/x-icon'>
		<link rel='stylesheet' type='text/css' href='<?php echo constant('jry_wb_host')?>jry_wb_css/iconfont.css'>
		<link rel='stylesheet' type='text/css' href='<?php echo constant('jry_wb_host')?>jry_wb_css/colorpicker.css'>
		<link rel='stylesheet' type='text/css' href='<?php echo $jry_wb_login_user['style']['data']['general_css_address'];?>'>
	<?php if($jry_wb_login_user['jry_wb_test_is_mobile']=='mobile'&&$jry_wb_login_user['device']!='ipad'){?>
		<link rel='stylesheet' type='text/css' href='<?php echo $jry_wb_login_user['style']['data']['mobile_css_address'];?>'>
	<?php }else if($jry_wb_login_user['jry_wb_test_is_mobile']=='weixin'){?>
		<link rel='stylesheet' type='text/css' href='<?php echo $jry_wb_login_user['style']['data']['mobile_css_address'];?>'>
	<?php }else{?>
		<link rel='stylesheet' type='text/css' href='<?php echo $jry_wb_login_user['style']['data']['desktop_css_address'];?>'>
	<?php } ?>
		<meta name="description" content="<?php echo constant('jry_wb_description');?>;由李俊彦开发的蒟蒻云网站系统(<?php echo constant('jry_wb_version')?>)强力驱动,strong powered by 'jry web system(<?php echo constant('jry_wb_version')?>)' which is developed by lijunyan;<?php echo $jry_wb_description;?>">
		<meta name="keywords" content="<?php echo constant('jry_wb_name')?>,蒟蒻云网站系统,juruoyun web system,<?php echo constant('jry_wb_keywords');?>,<?php echo $jry_wb_keywords;?>">
		<meta name="robots" content="noarchive">
	<?php include_once('jry_wb_connect_php_to_js.php');?>
		<script language="javascript" src="<?php echo constant('jry_wb_host')?>jry_wb_js/jry_wb_core_js.js"></script>
		<script language="javascript" src="<?php echo constant('jry_wb_host')?>jry_wb_js/md5.js"></script>
		<script language="javascript" src="<?php echo constant('jry_wb_host')?>jry_wb_js/colorpicker.js"></script>
		<script language="javascript" src="<?php echo constant('jry_wb_host')?>jry_wb_js/jry_wb_markdown.js"></script>
		<script language="javascript" src="<?php echo constant('jry_wb_character_drawing_logo_address')?>"></script>
	</head>
	<body ontouchstart="" onmouseover="">
	<!--[if IE]><div style="background-color:#000; font-size:50px; color:#FFFFFF; width:100%; height:100%;"><div style="font-size:200px; color:#F00;" align="center">WARNING!<br></div><div style="font-size:50px; color:#F00;" align="center">You are using browsers with too low versions.<br>Please stop now!<br>:-(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)-:</div><div style="font-size:25px; color:#F00;" align="center">本网站开发及测试工作均在QQ浏览器上完成，下载<a href="https://browser.qq.com/">QQ浏览器10</a>可以获得100%兼容的体验</div></div><![endif]-->
	<noscript><div style="background-color:#000; font-size:50px; color:#FFFFFF; width:100%; height:100%;"><div style="font-size:200px; color:#F00;" align="center">WARNING!<br></div><div style="font-size:50px; color:#F00;" align="center">You are useing browsers with out JS.<br>Please stop now!<br>:-(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)-:</div><div style="font-size:25px; color:#F00;" align="center"></div></div></noscript>
	<script type='text/javascript'>if (!navigator.cookieEnabled){document.body.innerHTML+='<div style="background-color:#000; font-size:50px; color:#FFFFFF; width:100%; height:100%;"><div style="font-size:200px; color:#F00;" align="center">WARNING!<br></div><div style="font-size:50px; color:#F00;" align="center">You are useing browsers with out Cookie.<br>Please stop now!<br>:-(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)-:</div><div style="font-size:25px; color:#F00;" align="center"></div></div>';}</script>
	<div align='center' id='__LOAD' >
		<div class="jry_wb_loading">
		  <div class="rect1"></div>
		  <div class="rect2" style="-webkit-animation-delay: -1.1s;animation-delay: -1.1s;"></div>
		  <div class="rect3" style="-webkit-animation-delay: -1.0s;animation-delay: -1.0s;"></div>
		  <div class="rect4" style="-webkit-animation-delay: -0.9s;animation-delay: -0.9s;"></div>
		  <div class="rect5" style="-webkit-animation-delay: -0.8s;animation-delay: -0.8s;"></div>
		</div>
	</div>
	<script  type='text/javascript'>/*jry_wb_ajax_load_data(jry_wb_save_browsing_history,function(data){data=JSON.parse(data);if(data==true)jry_wb_loading_off();else jry_wb_beautiful_alert.alert("网络错误","请刷新");},[{'name':'from','value':document.referrer},{'name':'now','value':document.location.href}]);*/</script>
	<?php ob_flush();
		if($checklogin)
		{
			if(((isset($_COOKIE['code']) ? $_COOKIE['code'] : '')!=$jry_wb_login_user['code']||$jry_wb_login_user['code']==''))
			{
	?><script language=javascript>jry_wb_beautiful_alert.alert("没有登录","","window.location.href='<?php echo jry_wb_print_href("login",0,"",1)?>'");</script> <?php			
				exit();
			}
			foreach($compentence as $compentence_)
			{
				if($jry_wb_login_user[$compentence_]==0)
				{
	?><script language=javascript>jry_wb_beautiful_alert.alert("权限不够","缺少<?php echo $compentence_?>","window.location.href='<?php echo jry_wb_print_href("home",0,"",1)?>'");</script> <?php			
						exit();
				}
			}
		}
		if($jry_wb_login_user['id']!=-1&&constant('jry_wb_check_tel_switch')&&$mt&&(($jry_wb_login_user['tel']==null||$jry_wb_login_user['tel']=='')))
		{
	?><script language=javascript>jry_wb_beautiful_alert.alert("请验证电话",'',"window.location.href='<?php echo jry_wb_print_href("users",0,"",1)?>'");</script> <?php			
				exit();
		}
		if($jry_wb_login_user['id']!=-1&&constant('jry_wb_check_mail_switch')&&$mt&&(($jry_wb_login_user['mail']==null||$jry_wb_login_user['mail']=='')))
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
	<?php if(constant('jry_wb_background_music_switch')){?>jry_wb_background_music.init();<?php } ?>
		<?php if($jry_wb_login_user['id']!=-1){?>
			document.addEventListener('visibilitychange',function()
			{
				if(jry_wb_cookie.get('id')==-1)
					history.go(0);
			});
		<?php }else{?>
			if(jry_wb_cache.get('jry_wb_login_user_id')!=undefined&&jry_wb_cache.get('jry_wb_login_user_id')!=-1)
			{
				jry_wb_cache.delete_all();
				jry_wb_cookie.set('id',-1);
				jry_wb_cookie.delete('password');
				jry_wb_cache.set('jry_wb_login_user_id',-1);
				history.go(0);
			}
			document.addEventListener('visibilitychange',function()
			{
				if(jry_wb_cookie.get('id')!=-1&&jry_wb_cookie.get('id')!=undefined)
					history.go(0);
			});
		<?php } ?>
	</script>
	<?php }
	}
?>