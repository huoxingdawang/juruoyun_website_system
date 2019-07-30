<?php 
	include_once("../jry_wb_tools/jry_wb_includes.php");
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
<div style="text-align: left" id="controler">
	<button class="jry_wb_button jry_wb_button_size_small jry_wb_color_normal" onclick="chenge_mood()">模式切换</button>
	<button class="jry_wb_button jry_wb_button_size_small jry_wb_color_normal" onclick="chenge_ziti()">编辑区字体切换</button>
	<button class="jry_wb_button jry_wb_button_size_small jry_wb_color_ok" onclick="save()">保存</button>
	<button class="jry_wb_button jry_wb_button_size_small jry_wb_color_warn" onclick="" id='push_pull'></button>
</div>
<table height="100%" width="100%" id='area'>
	<tr>
		<td width="50%" valign="top" id="bianji"><textarea id="oriContent" style="height:100%;width:100%;" onkeyup="if(timer!=null)clearTimeout(timer);timer=setTimeout(function(){convert();},Math.max(markdown.time,1000));" contenteditable="true"></textarea></td>
		<td width="50%" valign="top" id="show"><div id="result" style="overflow:scroll; height:100%;"></div></td>
	</tr>
</table>
<script type="text/javascript">
function do_text(text)
{
	if(text==null)
		return document.getElementById('oriContent').value;
	else
		return document.getElementById('oriContent').value=text;
}
push_pull=document.getElementById('push_pull');
jry_wb_add_onload(function ()
{
	if(!(!jry_wb_word_special_fact))
		jry_wb_word_special_fact.switch=false;
	if(!(!follow_mouth))
		follow_mouth.close();
	jry_wb_ajax_load_data("jry_wb_blog_getinformation.php?action=get_draft_one&blog_id=<?php echo $_GET['blog_id']?>",function(data){
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
		var d1 = data.last_modify_time;
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
		jry_wb_add_on_indexeddb_open(function()
		{
			var re=jry_wb_indexeddb.transaction(['blog_draft_text'],'readwrite').objectStore('blog_draft_text').get(blog_id);
			re.onsuccess=function()
			{
				if(this.result!=undefined&&jry_wb_compare_time(d1,this.result.last_modify_time)<0)
				{					
					do_text(jry_wb_ajax_get_text(this.result.text));
					jry_wb_beautiful_right_alert.alert('已成功从本地复原',4000,'auto','ok');
				}
				else
				{
					do_text(jry_wb_ajax_get_text(data.data));
					jry_wb_beautiful_right_alert.alert('已成功从服务器'+data.last_modify_time+'复原',4000,'auto','ok');
				}
				setTimeout(convert,100);
				jry_wb_beautiful_right_alert.alert("每隔5分钟自动保存",5000,'auto','warn');
				setInterval("autosave()",1000);
			};
		});		
	});
	document.getElementById('buttom_message').style.display='none';	
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
function convert()
{
	timer=null;
	var text=do_text();
	jry_wb_add_on_indexeddb_open(function(){jry_wb_indexeddb.transaction(['blog_draft_text'],'readwrite').objectStore('blog_draft_text').put({'blog_id':blog_id,'text':text,'last_modify_time':jry_wb_get_server_time()})});
	jry_wb_beautiful_right_alert.alert('保存到本地 at '+jry_wb_get_server_time(),500,'auto');
	if(typeof markdown=='undefined')
		markdown=new jry_wb_markdown(document.getElementById("result"),jry_wb_login_user.id,jry_wb_get_server_time().s(),text);
	else
		markdown.fresh(jry_wb_get_server_time().s(),text)
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
		document.getElementById("oriContent").style.fontSize='';
		jry_wb_beautiful_right_alert.alert('小字体模式',1000,'auto');
	}
	else
	{
		document.getElementById("oriContent").style.fontSize='30px';
		jry_wb_beautiful_right_alert.alert('大字体模式',1000,'auto'); 
	}
}
function save()
{
	jry_wb_ajax_load_data('jry_wb_blog_do.php?action=save_as_draft&blog_id='+blog_id+'&title='+markdown.title,function(data){jry_wb_loading_off();data=JSON.parse(data);if(data.code==false){if(data.reason==100000)jry_wb_beautiful_right_alert.alert('因为没有登录保存失败',10000,'auto','error');else if(data.reason==100001)jry_wb_beautiful_right_alert.alert("因为'"+data.extern+"'权限缺失保存失败",10000,'auto','error');return;}else jry_wb_beautiful_right_alert.alert('已保存为 '+data.message,1000,'auto','ok')},Array({'name':'data','value':JSON.stringify(do_text())}));
}
function push()
{
	jry_wb_ajax_load_data('jry_wb_blog_do.php?action=push&blog_id='+blog_id,function(data){jry_wb_loading_off();data=JSON.parse(data);if(data.code==false){if(data.reason==100000)jry_wb_beautiful_right_alert.alert('因为没有登录发布失败',10000,'auto','error');else if(data.reason==100001)jry_wb_beautiful_right_alert.alert("因为'"+data.extern+"'权限缺失发布失败",10000,'auto','error');return;}else{jry_wb_beautiful_right_alert.alert('已成功发布'+data.message,1000,'auto','ok');push_pull.innerHTML='收回';push_pull.setAttribute('onclick','pull()');}});
}
function pull()
{
	jry_wb_ajax_load_data('jry_wb_blog_do.php?action=pull&blog_id='+blog_id,function(data){jry_wb_loading_off();data=JSON.parse(data);if(data.code==false){if(data.reason==100000)jry_wb_beautiful_right_alert.alert('因为没有登录收回失败',10000,'auto','error');else if(data.reason==100001)jry_wb_beautiful_right_alert.alert("因为'"+data.extern+"'权限缺失收回失败",10000,'auto','error');return;}else{jry_wb_beautiful_right_alert.alert('已成功收回'+data.message,1000,'auto','ok');push_pull.innerHTML='发布';push_pull.setAttribute('onclick','push()');}});
}
var top_toolbar=document.getElementsByClassName('jry_wb_top_toolbar')[0];
if(top_toolbar==undefined)
	top_toolbar={'clientHeight':0};
var scroll=new jry_wb_beautiful_scroll(document.getElementById('result'),undefined,true);
jry_wb_add_onresize(function()
{
	if(mood==0)
		document.getElementById('result').style.width=document.body.clientWidth/2;
	else if(mood=1)
	{
		var all=document.getElementById("result");var width=document.documentElement.clientWidth;if(width>800){all_width=width-Math.min(width*0.3,width-800);all.style.width=all_width-10;all.style.margin="0px "+(width-all_width)/2+"px"}else{all.style.width=width-10,all.style.margin="0px 0px"}
	}
	document.getElementById('result').style.height=document.getElementById('area').style.height=document.body.clientHeight-top_toolbar.clientHeight-document.getElementById('controler').clientHeight-10;
});
timer=null;
</script>
<?php jry_wb_print_tail();?>