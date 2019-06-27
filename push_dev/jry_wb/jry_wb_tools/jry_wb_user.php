<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
	function jry_wb_get_user($conn,$id,$host_mode)
	{
		if($id=='')
			$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'users ORDER BY id DESC LIMIT 1');
		else
		{
			$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'users WHERE id=? LIMIT 1');
			$st->bindValue(1,$id);
		}
		$st->execute();				
		$datas=$st->fetchAll();
		if(count($datas)==0)
			return NULL;				
		else
		{
			$user=$datas[0];
			$user['type']=json_decode($user['type']);
			if(is_int($user['type']))
				$user['type']=[$user['type']];
			$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_MANAGE_SYSTEM.'competence WHERE type IN ('.implode(',',$user['type']).')  ORDER BY `order` ASC');
			$st->execute();
			$datas=$st->fetchAll();
			if(count($datas)==0)
				return NULL;				
		}
		$_SESSION['language']=$user['language'];
		$user['compentence']=$datas[0];
		$user['color']=$user['compentence']['color'];
		$user['competencename']=[$user['compentence']['competencename']];
		unset($datas[0]);
		foreach($datas as $data)
			foreach($data as $key=>$value)
				if($key=='order')
					$user['order']=min($user['order'],$data['order']);
				else if($key=='competencename')
					$user['competencename'][]=$data['competencename'];
				else if($key!='type'&&$key!='or'&&$key!='color')
					if($data['or'])
						$user['compentence'][$key]|=$data[$key];
					else 
						$user['compentence'][$key]&=$data[$key];
		for($i=0,$n=count($user['compentence']);$i<$n;$i++)
			unset($user['compentence'][$i]);
		unset($user['compentence']['competencename']);
		unset($user['compentence']['type']);
		unset($user['compentence']['order']);
		unset($user['compentence']['or']);
		unset($user['compentence']['color']);
		$user['login_addr']=[];
		if($user['ip_show']||($host_mode))
		{
			$st = $conn->prepare('SELECT * FROM '.JRY_WB_DATABASE_GENERAL.'login where id=? ORDER BY `device`,`time`,`browser`,`ip`');
			$st->bindValue(1,$user['id']);
			$st->execute();
			foreach($st->fetchAll() as $ips)
			{
				if($isthis=($_COOKIE['code']==$ips['code']))	
					$user['logdate']=$ips['time'];
				$user['login_addr'][]=array('isthis'=>$isthis,'ip'=>$ips['ip'],'time'=>$ips['time'],'device'=>$ips['device'],'browser'=>$ips['browser'],'trust'=>$ips['trust'],'login_id'=>$ips['login_id']);
			}			
		}
		if($user['mail']!=''&&(!$host_mode))
		{
			if($user['mail_show']==0)
			{
				$buf=explode('@',$user['mail']);
				$user['mail']=substr_replace($buf[0],'****',3,count($buf[0])-3).'@'.$buf[1];
			}
			else if($user['mail_show']==1)
			{
				$buf=explode('@',$user['mail']);
				$count=count($buf[0]);
				$user['mail']='';
				for($i=0;$i<$count;$i++)
					$user['mail'].='*';
				$user['mail'].='@'.$buf[1];
			}
		}
		if($user['tel']!=''&&(!$host_mode))
		{
			if($user['tel_show']==0)
				$user['tel']=substr_replace($user['tel'],'****',3,4);
			else if($user['tel_show']==1)
				$user['tel']=substr_replace($user['tel'],'***********',0,11);
		}
		if($user['head_special']!='')
			$user['head_special']=json_decode($user['head_special']);
		else
			$user['head_special']=json_decode('{"mouse_out":{"speed":2,"direction":0,"times":-1},"mouse_on":{"speed":2,"direction":0,"times":-1}}');
		if($user['head_special']->mouse_on->times!=-1&&($user['head_special']->mouse_out->times==0||$user['head_special']->mouse_out->speed==0))
		{
			$user['head_special']->mouse_out->speed=$user['head_special']->mouse_on->speed;
			$user['head_special']->mouse_out->direction=(($user['head_special']->mouse_on->direction)?0:1);
			$user['head_special']->mouse_out->times=1;
		}
		if($user['head']==''||$user['head']==NULL||$user['head']=='NULL')
			if($user['sex']==0)
				$user['head']=array('type'=>'default_head_woman');
			else
				$user['head']=array('type'=>'default_head_man');
		else
			$user['head']=json_decode($user['head'],true);
		$user['style']=jry_wb_load_style($user['style_id']);
		if($user['oauth']!='')
			$user['oauth']=json_decode(preg_replace('/\\\n/i','<br>',$user['oauth']));
		if($user['extern']!='')
			$user['extern']=json_decode($user['extern']);
		$user['background_music_list']=json_decode($user['background_music_list']==''||$user['id']==-1?'[{"slid": "0", "type": "songlist"}]':$user['background_music_list']);		
		$n=count($user);
		for($i=0;$i<$n;$i++)
			unset($user[$i]);			
		return $user;
	}
	function jry_wb_get_user_head_style_out($user)
	{
		if($user['head_special']->mouse_out->direction)
			$ans='jry_wb_rotate_clockwise';
		else
			$ans='jry_wb_rotate_anticlockwise';
		$ans.=(' '.$user['head_special']->mouse_out->speed.'s');
		$ans.=' linear ';
		$ans.=($user['head_special']->mouse_out->times==-1?'infinite':$user['head_special']->mouse_out->times);
		$ans.=';';
		return $ans;
	}
	function jry_wb_get_user_head_style_on($user)
	{
		if($user['head_special']->mouse_on->direction)
			$ans='jry_wb_rotate_clockwise';
		else
			$ans='jry_wb_rotate_anticlockwise';
		$ans.=(' '.$user['head_special']->mouse_on->speed.'s');
		$ans.=' linear ';
		$ans.=($user['head_special']->mouse_on->times==-1?'infinite':$user['head_special']->mouse_on->times);
		$ans.=';';
		return $ans;
	}
	function jry_wb_show_user(&$user,$active=false)
	{
		if($user['name']=='')
		{
			$show="该用户消失了";
			$add=jry_wb_print_href('jry_wb_host','','',true);
			$color='33CCFF';
		}
		else if(!$user['use'])
		{
			$show="[禁止使用]";
			$add=jry_wb_print_href('jry_wb_host','','',true);
			$color='666';			
		}
		else
		{
			$show='<span name="jry_wb_user_name_'.$user['id'].'">'.$user['name'].'</span>';
			$width=22;
			$add=jry_wb_print_href('users','','',true);
			$on=	'animation:'		.$buf=jry_wb_get_user_head_style_on($user).
					'-moz-animation:'	.$buf.
					'-webkit-animation:'.$buf.
					'-o-animation:'		.$buf;
			$out=	'animation:'		.$buf=jry_wb_get_user_head_style_out($user).
					'-moz-animation:'	.$buf.
					'-webkit-animation:'.$buf.
					'-o-animation:'		.$buf;
			$show.=("<img name='jry_wb_user_head_".$user['id']."' style='".$out."' ".'onMouseOver="this.style=\''.$on.'\';" '.'onMouseOut="this.style=\''.$out.'\';" '."src= '".jry_wb_get_user_head($user)."' width='".$width."' height='".$width."'/>");
	
		}
		echo "<a href=".$add." target='_parent' class=".($active?'active':'')." jry_wb_top_toolbar_right>".$show."</a>";
	}
	function jry_wb_get_user_head(&$user)
	{
		if(is_string($user['head']))
			$user['head']=json_decode($user['head'],true);
		if($user['head']['type']=='default_head_man')
			return JRY_WB_DEFULT_MAN_PICTURE;
		else if($user['head']['type']=='default_head_woman')
			return JRY_WB_DEFULT_WOMAN_PICTURE;
		else if($user['head']['type']=='gravatar')
			return "http://www.gravatar.com/avatar/".md5($user['mail'])."?size=80&d=404&r=g";
		else if($user['head']['type']=='qq'&&$user['oauth_qq']!='')
			return $user['oauth_qq']->message->figureurl_qq_2;
		else if($user['head']['type']=='github'&&$user['oauth_github']!=null)
			return $user['oauth_github']->message->avatar_url;
		else if($user['head']['type']=='qq')
			return "https://q2.qlogo.cn/headimg_dl?dst_uin=".(explode("@",$user['mail'])[0])."&spec=100";
		else if($user['head']['type']=='gitee')
			return $user['oauth_gitee']->message->avatar_url;	
		else if($user['head']['type']=='mi')
			return $user['oauth_mi']->message->miliaoIcon_orig;
		else if($user['head']['type']=='url')
			return $user['head']['url'];
		else if($user['head']['type']=='netdisk')
			return JRY_WB_HOST.'jry_wb_netdisk/jry_nd_do_file.php?action=open&share_id='.$user['head']['share_id'].'&file_id='.$user['head']['file_id'];
		else
			return '';
	}
?>