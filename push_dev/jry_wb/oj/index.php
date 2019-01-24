<?php 
	include_once("../tools/jry_wb_includes.php");
	$action=$_GET['action'];
	$jry_wb_keywords='在线测评';
	jry_wb_print_head("在线测评",true,true,true);
?>
<div class="jry_wb_top_toolbar" id='jry_wb_top_toolbar'>
	<?php jry_wb_print_logo(false);?>
	<?php jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_href('ojall',($_GET['action']!='logs')?'active':'');?>
	<?php jry_wb_print_href('ojlogs',($_GET['action']!='logs')?'':'active');?>
	<a style="background-color:#0066FF">
		<span id='ojclass'></span>
		<span id='people'></span>
		<span id='status'></span>
		<span id='type'></span>
		<span id='total'>一共</span>
	</a>		
</div>
<script language="javascript" src="oj.js"></script>
<script language="javascript" src="oj_showlist.js"></script>
<script language="javascript" src="oj_showlogs.js"></script>
<script language="javascript" src="oj_showall.js"></script>
<link rel="stylesheet" type="text/css" href="oj.css">
<script language="javascript">
var setted=false;
function reset()
{
	if(document.getElementById('type_all')!=null){document.getElementById('type_all').checked=true;document.getElementById('type_all').onclick();}
	if(document.getElementById('status_all')!=null){document.getElementById('status_all').checked=true;document.getElementById('status_all').onclick();}
	if(document.getElementById('__questionid')!=null){document.getElementById('__questionid').value='';}
	if(document.getElementById('__id')!=null){document.getElementById('__id').value='';}
	for(var all=document.getElementById('root').getElementsByTagName('input'),n=all.length,i=0;i<n;i++)all[i].checked=false;
	oj.showwhat={type:0,ojclass:0,status:0,id:0,questionid:0};
	serch();
}
</script>
<div class="oj_all" id="oj_all">
	<div class="oj_all_left_div"><div id="all"></div><ul id="root"  class="tree" style="display:none"></ul></div>
	<div class="oj_all_right_div" style="display:none" id='oj_all_right_div'>
		<div style="float:left;height:2000px;width:100%;"  id="zhanwei"></div>
		<div style="float:left;" id='right_main'>
			<?php if($_GET['action']!='logs'){?>
			<ul class="h56" style="margin:0px;">
				<li onclick="if(document.getElementById('type_ul').style.display=='none')document.getElementById('type_ul').style.display='';else document.getElementById('type_ul').style.display='none';window.onresize();">类型<li>
				<ul class="" style="width:100%;" id='type_ul'>
					<li><input name='type' 	type="radio"	onclick="oj.showwhat.type=0;chenge('type','');" id='type_all'/><h56>全部</h56></li>
					<li><input name='type' 	type="radio"	onclick="oj.showwhat.type=1;chenge('type','单选题');"/><h56>单选</h56></li>
					<li><input name='type' 	type="radio"	onclick="oj.showwhat.type=2;chenge('type','单词题');"/><h56>单词</h56></li>
					<li><input name='type' 	type="radio"	onclick="oj.showwhat.type=3;chenge('type','填空题');"/><h56>填空</h56></li>
					<li><input name='type' 	type="radio"	onclick="oj.showwhat.type=4;chenge('type','C++编译题');"/><h56>C++编译题</h56></li>
				</ul>
			</ul>
			<?php }?>
			<ul class="h56" style="margin:0px;">
				<li onclick="if(document.getElementById('status_ul').style.display=='none')document.getElementById('status_ul').style.display='';else document.getElementById('status_ul').style.display='none';window.onresize();">状态<li>
				<ul class="" style="width:100%;" id='status_ul'>
					<li><input name='status' 	type="radio"	onclick="oj.showwhat.status=0;chenge('status','');" checked id="status_all"/><b class="iconfont icon-quan oj_normal jry_wb_font_normal_size"></b><li>
					<li><input name='status' 	type="radio"	onclick="oj.showwhat.status=2;chenge('status','正确的');"/><b class="iconfont icon-duigoux oj_right jry_wb_font_normal_size"></b><li>
					<li><input name='status' 	type="radio"	onclick="oj.showwhat.status=1;chenge('status','错误的');"/><b class="iconfont icon-cuowu oj_error jry_wb_font_normal_size"></b><li>
			<?php if($_GET['action']!='logs'){?> <li><input name='status' 	type="radio"	onclick="oj.showwhat.status=3;chenge('status','尚未尝试的');"/><b class="iconfont icon-hr oj_nottry jry_wb_font_normal_size"></b><li><?php }?>
			<?php if($_GET['action']=='logs'){?> <li><input name='status' 	type="radio"	onclick="oj.showwhat.status=3;chenge('status','半对的');"/><b class="iconfont icon-hr oj_nottry jry_wb_font_normal_size"></b><li><?php }?>
				</ul>
			</ul>
			<ul class="h56" style="margin:0px;">
				<li>
					题号<input type='text' class="h56" id="__questionid" style="width:200px;" onkeyup="var buf=parseInt(document.getElementById('__questionid').value);if(isNaN(buf))oj.showwhat.questionid=0;else oj.showwhat.questionid=parseInt(buf);"/>
				</li>
			</ul>
			<ul class="" style="margin:0px;">
				<li class="icon-icon_zhanghao iconfont jry_wb_font_normal_size h56">
					&nbsp;&nbsp;<input type='text' class="h56" id="__id" style="width:200px;" onkeyup="var buf=parseInt(document.getElementById('__id').value);if(isNaN(buf))oj.showwhat.id=0;else oj.showwhat.id=parseInt(buf);"/>
				</li>
			</ul>			
			<ul style="margin:0px;" class="iconfont icon-biaoqian jry_wb_font_normal_size h56" onclick="click_class()"></ul>
			<ul>
				<button class="jry_wb_button jry_wb_button_size_small jry_wb_color_ok" onclick="serch()">搜索</button>
				<button class="jry_wb_button jry_wb_button_size_small jry_wb_color_error" onclick="reset();">全部重置</button>
			</ul>			
		</div>
	</div>	
</div>

<?php if($_GET['action']!='logs'){?>
<script language="javascript">	
jry_wb_add_load(function(){	oj=new oj_function('oj_','oj_getinformation.php',document.getElementById("all"),document.getElementById("root"));
					oj.aftersync=function(){oj.showlist();oj.showall(onepage,(JSON.parse(decodeURI(location.hash.slice(1))).page==null?1:parseInt(JSON.parse(decodeURI(location.hash.slice(1))).page)));document.getElementById('oj_all_right_div').style.display='';window.onscroll();window.onresize();};
					oj.getsync();
					onepage=75;
					reset();
					});
window.addEventListener('load',function(){oj.showall(onepage,(JSON.parse(decodeURI(location.hash.slice(1))).page==null?1:parseInt(JSON.parse(decodeURI(location.hash.slice(1))).page)));},false);
window.addEventListener('hashchange',function(){oj.showall(onepage,(JSON.parse(decodeURI(location.hash.slice(1))).page==null?1:parseInt(JSON.parse(decodeURI(location.hash.slice(1))).page)));},false);		
</script>
<?php }else{?>
<script language="javascript">	
jry_wb_add_load(function(){	oj=new oj_function('oj_','oj_getinformation.php',null,document.getElementById("root"),document.getElementById("all"));
					oj.aftersync=function(){oj.showlist();oj.showlogs(onepage,(JSON.parse(decodeURI(location.hash.slice(1))).page==null?1:parseInt(JSON.parse(decodeURI(location.hash.slice(1))).page)));oj.logs_div.style.display='';document.getElementById('oj_all_right_div').style.display='';window.onscroll();window.onresize();};
					oj.getsync();
					onepage=75; 
					reset();
					});
window.addEventListener('load',function(){oj.showlogs(onepage,(JSON.parse(decodeURI(location.hash.slice(1))).page==null?1:parseInt(JSON.parse(decodeURI(location.hash.slice(1))).page)));},false);
window.addEventListener('hashchange',function(){oj.showlogs(onepage,(JSON.parse(decodeURI(location.hash.slice(1))).page==null?1:parseInt(JSON.parse(decodeURI(location.hash.slice(1))).page)));},false);					
</script>
<?php }?>
<script language="javascript">
function serch()
{
//	click_class();
	if(oj.all_div!=null)oj.all_div.style.display='';
	if(oj.logs_div!=null)oj.logs_div.style.display='';
	oj.tree_div.style.display='none';
	
	oj.showall(onepage,1);
	oj.showlogs(onepage,1);
	document.getElementById('total').innerHTML='一共'+oj.count+"<?php echo $_GET['action']=='logs'?'次':'道'; ?>";
	window.onresize();
}
function chenge(name,value)
{
	document.getElementById(name).innerHTML=value;
}
function click_class()
{
	if(oj.tree_div.style.display=='')
	{
		if(oj.all_div!=null)oj.all_div.style.display='';
		if(oj.logs_div!=null)oj.logs_div.style.display='';
		oj.tree_div.style.display='none';
	}
	else
	{
		if(oj.all_div!=null)oj.all_div.style.display='none';
		if(oj.logs_div!=null)oj.logs_div.style.display='none';		
		oj.tree_div.style.display='';
	}
	var checked=oj.tree.get_checked();
	for(var i=0;i<checked.length;i++)
		checked[i]=parseInt(checked[i]);
	oj.showall(onepage,1);
	oj.showlogs(onepage,1);
	if(checked.length==0)
		chenge('ojclass','');
	else
		chenge('ojclass',JSON.stringify(checked));
	document.getElementById('zhanwei').style.height=0;
	window.onresize();
}
jry_wb_add_onresize(function(){var all=document.getElementById('oj_all');var width=document.documentElement.clientWidth;if(width>1000){all_width=width-Math.min(width*0.2,width-1000);all.style.width=all_width;all.style.margin="0px "+(width-all_width)/2+"px"}else{all.style.width="100%",all.style.margin="0px 0px"}});
jry_wb_add_onscroll(function()
{
	function chenge()
	{
		var hight=document.body.scrollTop==0?document.documentElement.scrollTop:document.body.scrollTop;
		hight=Math.min(hight,(document.documentElement.scrollHeight-document.getElementById('right_main').clientHeight-(document.getElementById('buttom_message')==null?0:document.getElementById('buttom_message').clientHeight)-(document.getElementById('jry_wb_top_toolbar')==null?0:document.getElementById('jry_wb_top_toolbar').clientHeight)));
		var all=document.getElementById('zhanwei');
		if(Math.abs(hight-parseInt(all.style.height))>=1)
			setTimeout(chenge,20);  
		else
			setted=false;
		all.style.height=parseInt(all.style.height)+(hight-parseInt(all.style.height))/10;
	}
	if(!setted)
	{
		setTimeout(chenge,20); 
		setted=true;
	}
});
</script> 
<?php jry_wb_print_tail();?>