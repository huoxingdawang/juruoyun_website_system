<?php
	include_once("../tools/jry_wb_includes.php");
	function jry_wb_get_user($conn,$id)
	{
		$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_general').'users WHERE id=? LIMIT 1');
		$st->bindValue(1,$id);
		$st->execute();				
		$datas=$st->fetchAll();
		if(count($datas)==0)
			$user=NULL;				
		else
		{
			$user=$datas[0];
			$st = $conn->prepare('SELECT * FROM '.constant('jry_wb_database_manage_system').'competence WHERE type=? LIMIT 1');
			$st->bindValue(1,$user['type']);
			$st->execute();
			$datas=$st->fetchAll();
			if(count($datas)==0)
				$user=NULL;				
			else
				$user=array_merge($user,$datas[0]);
		}
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
	function jry_wb_show_user(&$row,$active=false)
	{
		if($row['name']=='')
		{
			$show="该用户消失了";
			$add=jry_wb_print_href('jry_wb_host','','',true);
			$color='33CCFF';
		}
		else if(!$row['use'])
		{
			$show="[禁止使用]";
			$add=jry_wb_print_href('jry_wb_host','','',true);
			$color='666';			
		}
		else
		{
			$show='<span name="jry_wb_user_name_'.$row['id'].'">'.$row['name'].'</span>';
			$width=22;
			$add=jry_wb_print_href('users','','',true);
			$on=	'animation:'		.$row['head_special']->mouse_on->result.
					'-moz-animation:'	.$row['head_special']->mouse_on->result.
					'-webkit-animation:'.$row['head_special']->mouse_on->result.
					'-o-animation:'		.$row['head_special']->mouse_on->result;
			$out=	'animation:'		.$row['head_special']->mouse_out->result.
					'-moz-animation:'	.$row['head_special']->mouse_out->result.
					'-webkit-animation:'.$row['head_special']->mouse_out->result.
					'-o-animation:'		.$row['head_special']->mouse_out->result;
			$show.=("<img name='jry_wb_user_head_".$row['id']."' style='".$out."' ".'onMouseOver="this.style=\''.$on.'\';" '.'onMouseOut="this.style=\''.$out.'\';" '."src= '".jry_wb_get_user_head($row)."' width='".$width."' height='".$width."'/>");
	
		}
		echo "<a href=".$add." target='_parent' class=".($active?'active':'')." jry_wb_top_toolbar_right>".$show."</a>";
	}
	function jry_wb_get_user_head(&$user)
	{
		if(is_string($user['head']))
			$user['head']=json_decode($user['head'],true);
		if($user['head']['type']=='default_head_man')
			return constant('jry_wb_defult_man_picture');
		else if($user['head']['type']=='default_head_woman')
			return constant('jry_wb_defult_woman_picture');
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
			return constant('jry_wb_host').'jry_wb_netdisk/jry_nd_do_file.php?action=open&share_id='.$user['head']['share_id'].'&file_id='.$user['head']['file_id'];
		else
			return '';
	}
?>