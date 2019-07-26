<script language="javascript">
	var jry_wb_message={
		'jry_wb_name':'<?php echo JRY_WB_NAME?>',
		'jry_wb_title':title='<?php echo $title?>',
		'jry_wb_host':'<?php echo JRY_WB_HOST; ?>',
		'jry_wb_get_message':'<?php echo JRY_WB_HOST; ?>jry_wb_for_front_message/',
		'jry_wb_logo':'<?php echo JRY_WB_LOGO_PICTURE_ADDRESS; ?>',
		'jry_wb_data_host':'<?php echo JRY_WB_DATA_HOST; ?>',
		'jry_wb_index_page':'<?php echo jry_wb_print_href("home","","",true) ?>',
		'jry_wb_chenge_page':'<?php echo jry_wb_print_href("users","","",true) ?>'
	};
	var jry_wb_login_user=JSON.parse(decodeURI("<?php
		$data=array('id'=>$jry_wb_login_user['id'],
					'head_special'=>$jry_wb_login_user['head_special'],
					'color'=>$jry_wb_login_user['color'],		
					'use'=>$jry_wb_login_user['use'],
					'head'=>$jry_wb_login_user['head'],
					'green_money'=>$jry_wb_login_user['green_money'],
					'enroldate'=>$jry_wb_login_user['enroldate'],
					'logdate'=>$jry_wb_login_user['logdate'],
					'greendate'=>$jry_wb_login_user['greendate'],
					'competencename'=>$jry_wb_login_user['competencename'],
					'name'=>$jry_wb_login_user['name'],
					'sex'=>$jry_wb_login_user['sex'],
					'tel'=>$jry_wb_login_user['tel'],
					'type'=>$jry_wb_login_user['type'],
					'mail'=>$jry_wb_login_user['mail'],
					'language'=>$jry_wb_login_user['language'],
					'zhushi'=>str_replace(array("\r\n", "\r", "\n"),'<br>',$jry_wb_login_user['zhushi']),
					'style'=>($jry_wb_login_user['style']),
					'login_addr'=>$jry_wb_login_user['login_addr'],
					'tel_show'=>$jry_wb_login_user['tel_show'],
					'ip_show'=>$jry_wb_login_user['ip_show'],
					'oauth_show'=>$jry_wb_login_user['oauth_show'],
					'mail_show'=>$jry_wb_login_user['mail_show'],
					'word_special_fact'=>$jry_wb_login_user['word_special_fact'],
					'follow_mouth'=>$jry_wb_login_user['follow_mouth'],						
					'background_music_list'=>$jry_wb_login_user['background_music_list'],
					'oauth'=>array(	'qq'		=>array('message'=>$jry_wb_login_user['oauth']->qq		->message),
									'mi'		=>array('message'=>$jry_wb_login_user['oauth']->mi		->message),
									'github'	=>array('message'=>$jry_wb_login_user['oauth']->github	->message),
									'gitee'		=>array('message'=>$jry_wb_login_user['oauth']->gitee	->message)
									),
					'compentence'=>$jry_wb_login_user['compentence'],
					'extern'=>$jry_wb_login_user['extern']
					);
		echo str_replace('"','\\"',(json_encode($data)));?>"));
	jry_wb_login_user.zhushi=jry_wb_login_user.zhushi.replace(/<br>/g,'\n');
	jry_wb_login_user.id=parseInt(jry_wb_login_user.id);
	jry_wb_time_different=(new Date()-new Date('<?php  echo jry_wb_get_time();?>'.replace(/\-/g, "/")));
</script>