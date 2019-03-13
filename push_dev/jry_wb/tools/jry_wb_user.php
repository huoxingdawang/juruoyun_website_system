<?php
	include_once("../tools/jry_wb_includes.php");
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
	function jry_wb_show_user($row,$active=false)
	{
		global $jry_wb_login_user;
		if($row[name]=='')
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
			$show=$row[name];
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
			$show.=("<img style='".$out."' ".'onMouseOver="this.style=\''.$on.'\';" '.'onMouseOut="this.style=\''.$out.'\';" '."src= '".jry_wb_get_user_head($row)."' width='".$width."' height='".$width."'/>");
	
		}
		echo "<a href=".$add." target='_parent' class=".($active?'active':'')." jry_wb_top_toolbar_right>".$show."</a>";
	}
	function jry_wb_get_user_head($user)
	{
		if($user['head']=='default_head_man')
			return constant('jry_wb_defult_man_picture');
		else if($user['head']=='default_head_woman')
			return constant('jry_wb_defult_woman_picture');
		else if($user['head']=='gravatar')
			return "http://www.gravatar.com/avatar/".md5($user['mail'])."?size=80&d=404&r=g";
		else if($user['head']=='qq'&&$user['oauth_qq']!='')
			return $user['oauth_qq']->message->figureurl_qq_2;
		else if($user['head']=='github'&&$user['oauth_github']!=null)
			return $user['oauth_github']->message->avatar_url;
		else if($user['head']=='qq')
			return "https://q2.qlogo.cn/headimg_dl?dst_uin=".(explode("@",$user['mail'])[0])."&spec=100";
		else if($user['head']=='gitee')
			return $user['oauth_github']->message->avatar_url;	
		else if($user['head']=='mi')
			return $user['oauth_mi']->message->miliaoIcon_orig;	
		else if($user['head']!='')
			return "http://juruoyun.top/mywork/picturebed/get_picturebed.php?size=80&pictureid=".$user[head];						
	}
	function jry_wb_update_user_head($user)
	{
		$conn=jry_wb_connect_database();
		if(strtolower(array_pop(explode("@",$user['mail'])))=='qq.com')
		{
			$q ="update ".constant('jry_wb_database_general')."users set head='qq' where id=?";
			$st = $conn->prepare($q);
			$st->bindParam(1,$user['id']);
			$st->execute();
			return;
		}
		$headers = @get_headers('http://www.gravatar.com/avatar/' .md5($user['mail']). '?d=404');
		if (preg_match("|200|", $headers[0])) 
		{
			$q ="update ".constant('jry_wb_database_general')."users set head='gravatar' where id=?";
			$st = $conn->prepare($q);
			$st->bindParam(1,$user['id']);
			$st->execute();	
		}
		else if($user['sex']==0&&$user['head']=='default_head_man')
		{
			$q ="update ".constant('jry_wb_database_general')."users set head='default_head_woman' where id=?";
			$st = $conn->prepare($q);
			$st->bindParam(1,$user['id']);
			$st->execute();
		}
		else if(($user['sex']==1||$user['sex']==2)&&$user['head']=='default_head_woman')
		{
			$q ="update ".constant('jry_wb_database_general')."users set head='default_head_man' where id=?";
			$st = $conn->prepare($q);
			$st->bindParam(1,$user['id']);
			$st->execute();
		}
	}
?>