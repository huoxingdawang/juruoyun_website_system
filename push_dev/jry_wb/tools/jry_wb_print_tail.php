<?php 
	include_once("jry_wb_includes.php");
	function jry_wb_print_tail()
	{
		global $jry_wb_login_user; ?>
		<a name="buttom"></a>
		<div id='__zhanwei'></div>
		<?php
			$conn=jry_wb_connect_database();
			$st=$conn->prepare('SELECT words FROM '.constant('jry_wb_database_mainpage').'tanmu  ORDER BY rand() LIMIT 20'); 
			$st->execute(); 
			$data=$st->fetchAll();
			$total=count($data);
			for($i=0;$i<$total;$i++)
				$json[$i]=	$data[$i]['words'];
		?>
		<div style="padding-bottom:10px;padding-left:10%;padding-right:10%;padding-top:10px;overflow:hidden;" id="buttom_message">
			<div style='text-align:left;float:left;overflow:hidden;' id='jry_wb_buttom_left_message'></div>
			<div style='text-align:right;float:right' id='jry_wb_buttom_right_message'>
				<?php include('../jry_wb_configs/jry_wb_config_tail.php');?>
				<a target="_blank" href="https://gitee.com/li_jun_yan"><b class="jry_wb_font_buttom" onmouseover='this.innerHTML="strong powered by jry web system(<?php echo constant('jry_wb_version')?>) which is developed by lijunyan"' onmouseout="this.innerHTML='由李俊彦开发的蒟蒻云网站系统(<?php echo constant('jry_wb_version')?>)强力驱动'">由李俊彦开发的蒟蒻云网站系统(<?php echo constant('jry_wb_version')?>)强力驱动</b></a><b class="jry_wb_font_buttom">&nbsp;|&nbsp;</b>
				<b class="jry_wb_font_buttom">主题来自<script>jry_wb_get_and_show_user_intext(jry_wb_login_user.style.id);</script>的</b><a target="_blank" href="<?php echo jry_wb_print_href('jry_wb_style_control','','',true);?>"><b class="jry_wb_font_buttom"><script>document.write(jry_wb_login_user.style.name)</script></b></a>
			</div>
		</div>
		<script language='javascript'>
			document.getElementById("buttom_message").style.height=document.getElementById("jry_wb_buttom_right_message").clientHeight;
			<?php if(constant('jry_wb_show_video_switch')){?>if(jry_wb_cache.get('showed')<2.0){jry_wb_beautiful_alert.check("有新版本的的宣传视频是否观看",function(){jry_wb_beautiful_alert.openvideo("宣传视频V2.0",(document.body.clientWidth)*0.75,(document.body.clientHeight)*0.75,"http://juruoyun.oss-cn-beijing.aliyuncs.com/video/1080P.mp4",function(){jry_wb_cache.set('showed',2.0);},function(video){return (video.duration-video.currentTime)<=60});jry_wb_beautiful_alert.alert("请观看宣传视频","由于服务器略菜,加载速度会比较慢,请耐心等待<br>在距离结束还有60秒时,关闭按钮会自己弹出");},function(){},"现在就看","等会再看")};<?php } ?>	
			<?php if(constant('jry_wb_word_special_fact_switch')){?>jry_wb_word_special_fact.word=JSON.parse('<?php echo json_encode($json);?>'.replace(/`/g,"'"));<?php } ?>
			follow_mouth=null;
			<?php if($jry_wb_login_user['jry_wb_test_is_mobile']=='disktop'&&constant('jry_wb_follow_mouth_special_fact_switch')){ ?>follow_mouth=new jry_wb_follow_mouth(document.body,{'size':4,'speed':10,'dou':1});<?php }?>
			jry_wb_add_load(function ()
			{
				jry_wb_set_shortcut([jry_wb_keycode_up],function(){window.scrollTo(window.scrollX,0);window.onmousewheel();});
				jry_wb_set_shortcut([jry_wb_keycode_down],function(){window.scrollTo(window.scrollX,document.body.scrollHeight-document.body.clientHeight);window.onmousewheel();});
				setTimeout(function(){window.onresize()},500);
				setInterval(jry_wb_add_onresize(function(){if(document.getElementById('__zhanwei')==null)return;document.getElementById('__zhanwei').style.height=Math.max(0,(Math.floor(window.innerHeight))-(document.getElementById('__zhanwei').getBoundingClientRect().top)-(document.body.scrollTop==0?document.documentElement.scrollTop:document.body.scrollTop)-(document.getElementById('buttom_message').getBoundingClientRect().height));}),2000);
				jry_wb_loading_off();
				<?php if($jry_wb_login_user['id']!=-1){if(!$jry_wb_login_user['word_special_fact']){ ?> jry_wb_word_special_fact.switch=false;<?php }?>
				<?php if(!$jry_wb_login_user['follow_mouth']||$jry_wb_login_user['device']!='pc'){ ?> if(follow_mouth!=null)follow_mouth.close();<?php }}?>
				if(typeof window.onresize =='function')
					window.onresize();
				jry_wb_set_delate_special();
				normal_title = document.title;
			<?php if(constant('jry_wb_chenge_title_switch')){?>
				document.addEventListener('visibilitychange', function () 
				{
					if (document.visibilityState == 'hidden') 
					{
						normal_title = document.title;
						var show=['WARNING :-(','大爷，快来玩啊！','操作异常'];
						document.title = show[parseInt(Math.random()*100%3)];
					} 
					else 
						document.title = normal_title;
				});
			<?php } ?>
				window.onmousewheel();
			});
		jry_wb_onload_function_data();
		</script>			
		</body>
		</html>
<?php
		ob_end_flush();
	}
?>