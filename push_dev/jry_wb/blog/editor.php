<?php 
	include_once("../tools/jry_wb_includes.php");
	$action=$_GET['action'];
	jry_wb_print_head("博客",true,true,true,array('use','editorblog'));
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>
	<?php jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_href('blog');?>	
	<?php jry_wb_print_href('blog_draft');?>
	<?php jry_wb_print_href('blog_editor','active');?> 
</div>
<script type="text/javascript">
function do_text(text)
{
	if(text==null)
		return document.getElementById('oriContent').value;
	else
		return document.getElementById('oriContent').value=text;
}
jry_wb_set_shortcut([jry_wb_keycode_control,jry_wb_keycode_p],function(){jry_wb_beautiful_right_alert.alert("已打开图床",3000,'auto','ok');	window.open('http://juruoyun.top/mywork/picturebed/index.php');});
jry_wb_set_shortcut([jry_wb_keycode_control,jry_wb_keycode_l],function(){jry_wb_beautiful_right_alert.alert("已打开草稿箱列表",3000,'auto','ok');window.open('<?php echo jry_wb_print_href('blog_draft','','',true);?>');});
jry_wb_set_shortcut([jry_wb_keycode_control,jry_wb_keycode_b],function(){jry_wb_beautiful_right_alert.alert("已打开博客列表",3000,'auto','ok');window.open('<?php echo jry_wb_print_href('blog','','',true);?>');});
jry_wb_set_shortcut([jry_wb_keycode_control,jry_wb_keycode_s],save);
jry_wb_add_load(function ()
{
	if(!(!jry_wb_word_special_fact))
		jry_wb_word_special_fact.switch=false;
	if(!(!follow_mouth))
		follow_mouth.close();
	push_pull=document.getElementById('push_pull');
	jry_wb_ajax_load_data("blog_getinformation.php?action=get_draft_one&blog_id=<?php echo $_GET['blog_id']?>",function(data){
		jry_wb_loading_off();
		data=JSON.parse(data);
		if(data.code==false)
		{
			if(data.reason==100000)
				jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
			else if(data.reason==100001)
				jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
			return ;
		}
		if(data.delete||data.blog_id==null)
		{
			jry_wb_ajax_load_data("http://<?php echo $_SERVER['HTTP_HOST'];?>/404.php?url=http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>",function(data){document.write(data);});
			return;
		}	
		var d1 = data.lasttime;
		blog_id=data.blog_id;
		show=data.show;
		if(show)
		{
			push_pull.setAttribute('onclick','pull()');
			push_pull.innerHTML='收回';
		}
		else
		{
			push_pull.setAttribute('onclick','push()');
			push_pull.innerHTML='发布';
		}
		var d2 = jry_wb_cache.get_last_time("blog_"+blog_id);
		if(jry_wb_compare_time(d1,d2)<0) 
		{
			do_text(jry_wb_ajax_get_text(jry_wb_cache.get("blog_"+blog_id)));
			jry_wb_beautiful_right_alert.alert('已成功从本地复原',4000,'auto','ok');
		}
		else
		{
			do_text(jry_wb_ajax_get_text(data.data));
			jry_wb_beautiful_right_alert.alert('已成功从服务器'+data.lasttime+'复原',4000,'auto','ok');
		}
		convert();
		jry_wb_beautiful_right_alert.alert("每隔5分钟自动保存",5000,'auto','warn');
		setInterval("autosave()",1000);
	});
});
time=0;
function autosave()
{
	time++;
	if(time>=60*5)
	{
		jry_wb_beautiful_right_alert.alert("正在保存",2000,'auto','warn');
		time=0;
		save();
	}
}
var refresh=true;
var timeout=false;
var ctrlkey;
var refresh_time=1000;
function convert()
{
	if(refresh&&((!ctrlkey)))
	{
		setTimeout(convert,refresh_time);
		refresh=false;timeout=true;timeout=true;
		jry_wb_cache.set('blog_'+blog_id,do_text());
		jry_wb_beautiful_right_alert.alert('保存到本地 at '+jry_wb_get_server_time(),500,'auto');
		if(xuanran)
		{
			var date=jry_wb_get_server_time(),month=date.getMonth()+1,strDate=date.getDate();
			return_data=markdown(document.getElementById("result"),jry_wb_login_user.id,date.getFullYear()+"-"+((month>=1&&month<=9)?"0":"")+month+"-"+((strDate>=0&&strDate<=9)?"0":"")+strDate+" "+date.getHours()+":"+date.getMinutes()+":"+date.getSeconds(),do_text());
		}
	}
	else
		timeout=false;
}
var mood=0;
var ziti=0;
var xuanran=true; 
function chenge_mood()
{
	mood++;
	mood%=3;
	if(mood==0)
	{
		document.getElementById("bianji").style.display='';
		document.getElementById("show").style.display='';
		document.getElementById("result").style='overflow:scroll; height:100%;';		
		jry_wb_beautiful_right_alert.alert('双窗口模式',1000,'auto');
		document.getElementById('result').style.width=document.body.clientWidth/2;
		document.getElementById('controler').style="text-align: left";
		convert();
		xuanran=true;
	}
	else if(mood==1)
	{
		document.getElementById("bianji").style.display='none';
		document.getElementById("show").style.display='';
		document.getElementById("result").style='';
		jry_wb_beautiful_right_alert.alert('演示模式',1000,'auto');
		document.getElementById('controler').style="text-align: center";
		var all=document.getElementById("result");var width=document.documentElement.clientWidth;if(width>800){all_width=width-Math.min(width*0.3,width-800);all.style.width=all_width-10;all.style.margin="0px "+(width-all_width)/2+"px"}else{all.style.width=width-10,all.style.margin="0px 0px"}
		convert();
	}
	else if(mood==2)
	{
		document.getElementById('controler').style="text-align: center";
		document.getElementById("bianji").style.display='';
		document.getElementById("show").style.display='none';
		jry_wb_beautiful_right_alert.alert('编辑模式',1000,'auto');
		xuanran=false;
	}
}
function chenge_ziti()
{
	ziti++;
	ziti%=2;
	if(ziti==0)
	{
		document.getElementById("oriContent").className='';
		jry_wb_beautiful_right_alert.alert('小字体模式',1000,'auto');
	}
	else
	{
		document.getElementById("oriContent").className='h56';
		jry_wb_beautiful_right_alert.alert('大字体模式',1000,'auto'); 
	}
}
function save()
{
	jry_wb_ajax_load_data('save.php?action=save_as_draft&blog_id='+blog_id+'&title='+return_data.title,function(data){jry_wb_loading_off();data=JSON.parse(data);if(data.code==false){if(data.reason==100000)jry_wb_beautiful_right_alert.alert('因为没有登录保存失败',10000,'auto','error');else if(data.reason==100001)jry_wb_beautiful_right_alert.alert("因为'"+data.extern+"'权限缺失保存失败",10000,'auto','error');return;}else jry_wb_beautiful_right_alert.alert('已保存为 '+data.message,1000,'auto','ok')},Array({'name':'data','value':JSON.stringify(do_text())}));
}
function push()
{
	jry_wb_ajax_load_data('save.php?action=push&blog_id='+blog_id,function(data){jry_wb_loading_off();data=JSON.parse(data);if(data.code==false){if(data.reason==100000)jry_wb_beautiful_right_alert.alert('因为没有登录发布失败',10000,'auto','error');else if(data.reason==100001)jry_wb_beautiful_right_alert.alert("因为'"+data.extern+"'权限缺失发布失败",10000,'auto','error');return;}else{jry_wb_beautiful_right_alert.alert('已成功发布'+data.message,1000,'auto','ok');push_pull.innerHTML='收回';push_pull.setAttribute('onclick','pull()');}});
}
function pull()
{
	jry_wb_ajax_load_data('save.php?action=pull&blog_id='+blog_id,function(data){jry_wb_loading_off();data=JSON.parse(data);if(data.code==false){if(data.reason==100000)jry_wb_beautiful_right_alert.alert('因为没有登录收回失败',10000,'auto','error');else if(data.reason==100001)jry_wb_beautiful_right_alert.alert("因为'"+data.extern+"'权限缺失收回失败",10000,'auto','error');return;}else{jry_wb_beautiful_right_alert.alert('已成功收回'+data.message,1000,'auto','ok');push_pull.innerHTML='发布';push_pull.setAttribute('onclick','push()');}});
}
jry_wb_add_onresize(function()
	{
		if(mood==0)
			document.getElementById('result').style.width=document.body.clientWidth/2;
		else if(mood=1)
		{
			var all=document.getElementById("result");var width=document.documentElement.clientWidth;if(width>800){all_width=width-Math.min(width*0.3,width-800);all.style.width=all_width-10;all.style.margin="0px "+(width-all_width)/2+"px"}else{all.style.width=width-10,all.style.margin="0px 0px"}
		}
	});
</script>
<div style="text-align: left" id="controler">
<button class="jry_wb_button jry_wb_button_size_small jry_wb_color_normal" onclick="chenge_mood()">模式切换</button>
<button class="jry_wb_button jry_wb_button_size_small jry_wb_color_normal" onclick="chenge_ziti()">编辑区字体切换</button>
<button class="jry_wb_button jry_wb_button_size_small jry_wb_color_ok" onclick="save()">保存</button>
<button class="jry_wb_button jry_wb_button_size_small jry_wb_color_warn" onclick="" id='push_pull'></button>
</div>
<table height="100%" width="100%">
	<tr>
		<td width="50%" valign="top" id="bianji"><textarea id="oriContent" style="height:100%;width:100%;" onkeyup="ctrlkey=window.event.ctrlKey;if(!timeout){setTimeout(convert,100);timeout=true;/*console.log('setTimeout');*/}refresh=true;" contenteditable="true"></textarea></td>
		<td width="50%" valign="top" id="show"><div id="result" style="overflow:scroll; height:100%;"></div></td>
	</tr>
</table>
<?php jry_wb_print_tail();?>