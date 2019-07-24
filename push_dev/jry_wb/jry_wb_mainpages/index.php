<?php
	include_once("../jry_wb_tools/jry_wb_includes.php");
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
			if($jry_wb_login_user['compentence']['manage']==1)
				jry_wb_print_href('jry_wb_manage_system');
		}
		jry_wb_load_website_map();
		foreach($jry_wb_website_map as $website)
			if($website['show_at_mainpage'])
				echo '<a href=\''.($website['type']==0?JRY_WB_HOST:'').$website[url].$zhuijia.'\' target="'.($website['type']==1?'_blank':'_parent').'">'.$website['show_name'].'</a>';
	?>	
</div>
<?php echo jry_wb_include_css($jry_wb_login_user['style'],'mainpages/index'); ?>
<table height="200px" width="100%" class="main_table">
	<tr height="*">
		<th class="table_left" id="table_left">
			<div id="date">
				<?php 
					$st=jry_wb_connect_database()->prepare('select * from '.JRY_WB_DATABASE_MAINPAGE.'bigdeal WHERE `enable`=1 and `delete`=0 order by rand() limit 1');
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
					$st=jry_wb_connect_database()->prepare('select * from  '.JRY_WB_DATABASE_MAINPAGE.'hengfu  WHERE `enable`=1 and `delete`=0 order by rand() limit 1');
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
	jry_wb_add_load(function()
	{
		jry_wb_ajax_load_data(jry_wb_message.jry_wb_get_message+'jry_wb_get_user.php?action=new',function (data)
		{
			var data=JSON.parse(data);
				jry_wb_add_on_indexeddb_open(function(){jry_wb_indexeddb.transaction(['user'],'readwrite').objectStore('user').add(data);});
			jry_wb_loading_off();
			jry_wb_show_user(document.getElementById('newuser'),data);	
		},null,false);
		
	});
</script>
<?php jry_wb_print_tail()?>