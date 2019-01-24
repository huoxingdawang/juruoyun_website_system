<?php
	include_once("../tools/jry_wb_includes.php");
	jry_wb_print_head('首页',false,true,true);
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(true);?>
	<?php 
		if($jry_wb_login_user[id]==-1)
		{
			jry_wb_print_href('login');
			jry_wb_print_href('add_user');
		}
		else
		{
			jry_wb_show_user($jry_wb_login_user);
			if($jry_wb_login_user['manage']==1)
				jry_wb_print_href('jry_wb_manage_system');
		}
		foreach($jry_wb_website_map as $website)
			if($website['show_at_mainpage'])
				echo '<a href="'.($website['type']==0?constant("jry_wb_host"):'').$website[url].$zhuijia.'" target="'.($website['type']==1?'_blank':'_parent').'">'.$website['show_name'].'</a>';
	?>	
</div>
<link rel='stylesheet' type='text/css' href='<?php echo $jry_wb_login_user['style']['data']['mainpages_index_css_address'];?>'>
<table height="200px" width="100%" class="main_table">
	<tr height="*">
		<th class="table_left" id="table_left">
			<div id="date">
				<?php 
					$st=jry_wb_connect_database()->prepare('select * from '.constant('jry_wb_database_mainpage').'big_deal order by rand() limit 1');
					$st->execute();
					foreach($st->fetchAll()as $big_deal);
				?>
				<span class="event_normal">距</span><span class="event_event"><?php echo $big_deal['name']?></span><span class="event_normal">还有</span>
				<br>
				<span class="event_time" id="bigdeal"></span>
				<script language="javascript">jry_wb_add_load(function(){jry_wb_show_time("<?php echo $big_deal['time']?>","bigdeal")});</script>
			</div>
			<div id="ssr" style='display:none;'>
				<?php ?>
			</div>
			<!---<script language='javascript'>document.getElementById('table_left').onmouseover=function(){document.getElementById('date').style.display='none';document.getElementById('ssr').style.display='';}document.getElementById('table_left').onmouseout=function(){document.getElementById('date').style.display='';document.getElementById('ssr').style.display='none';}</script>--->
		</th>
		<th width="*" class="table_mid">
			<marquee scrollAmount=20 >
				<?php
					$st=jry_wb_connect_database()->prepare('select * from  '.constant('jry_wb_database_mainpage').'hengfu order by rand() limit 1');
					$st->execute();
					echo $st->fetchAll()[0]['words'];
				?>
  			</marquee>
		</th>
		<th class="table_right">		
			<span class="welcome_new">欢迎新用户</span>
			<div id='newuser'></div>
			<span class="welcome_new"><?php jry_wb_print_href("introduction");?></span>
		</th>
	</tr>
</table>
<script language="javascript">
	var last;
	jry_wb_add_load(function(){
		jry_wb_beautiful_right_alert.alert("开发组不想接到关于本站太丑的投诉<br>开发组已经知道此问题但由于非技术原因无法解决<br>如果您愿意使她变得更美观一些，或您知道有人愿意让她美观一点<br>请联系开发组<br>感谢",10000,'auto','');
		<?php if($jry_wb_login_user['jry_wb_test_is_mobile']=='disktop'){?>
		if(!jry_wb_cache.get('index_note')){jry_wb_beautiful_right_alert.alert('F1新建首页<br>F2打开个人中心<br>F3或B打开百度<br>F4或L打开洛咕???<br>P打开POJ<br>A打开阿里云<br>BI打开B站<br>更多参见用户指南',30000,'auto','warn');jry_wb_cache.set('index_note',true);}
		<?php }?>jry_wb_ajax_load_data(jry_wb_message.jry_wb_get_message+'jry_wb_get_user.php?action=new',function (data_){
		var buf=JSON.parse(data_);
		var data=jry_wb_cache.get('users');
		if(data==null)
		{
			data=new Array();
			data.push(buf);
		}
		else
			if(buf!=null)
			{
				var now=data.find(function(a){return a.id==buf.id});
				if(now==null)
					data.push(buf);
				else
					data.splice(now,1,buf);
			}
		jry_wb_cache.set('users',data);
		jry_wb_loading_off();
		jry_wb_show_user(document.getElementById('newuser') ,buf);	
		},null,false);
		
	});
</script>
<?php jry_wb_print_tail()?>