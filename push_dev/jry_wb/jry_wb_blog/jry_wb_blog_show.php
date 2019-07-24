<?php 
	include_once("../jry_wb_tools/jry_wb_includes.php");
	$action=$_GET['action'];
	jry_wb_print_head("博客",false,true,true);
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>
	<?php if($jry_wb_login_user[id]!=-1)jry_wb_show_user($jry_wb_login_user);?>
	<?php jry_wb_print_href('blog');?>
	<?php if($jry_wb_login_user[id]!=-1)jry_wb_print_href('blog_draft');?>
	<?php jry_wb_print_href('blog_show','active');?>
</div>
<div id="result"></div>
<script type="text/javascript">
jry_wb_add_onresize(function()
{
	var all=document.getElementById("result");
	var width=document.documentElement.clientWidth;
	if(width>800)
	{
		all_width=width-Math.min(width*0.3,width-800);
		all.style.width=all_width;
		all.style.margin="0px "+(width-all_width)/2+"px";
	}
	else
	{
		all.style.width=width;
		all.style.margin="0px 0px";
	}
});
</script>
<script type="text/javascript">
jry_wb_add_on_indexeddb_open(function()
{
	var re=jry_wb_indexeddb.transaction(['blog_text'],'readwrite').objectStore('blog_text').get(parseInt(jry_wb_get_get().blog_id));
	re.onsuccess=function()
	{
		if(this.result!=undefined&&jry_wb_get_get().reload=='0')
			markdown=new jry_wb_markdown(document.getElementById("result"),this.result.id,this.result.lasttime,(this.result.data));
		else
			jry_wb_ajax_load_data("jry_wb_blog_getinformation.php?action=get_blog_one&blog_id="+jry_wb_get_get().blog_id,function(data)
			{
				jry_wb_loading_off();
				data=JSON.parse(data);
				markdown=new jry_wb_markdown(document.getElementById("result"),data.id,data.lasttime,(data.data));
				jry_wb_add_on_indexeddb_open(function(){jry_wb_indexeddb.transaction(['blog_text'],'readwrite').objectStore('blog_text').put(data);});
				document.title+='|'+markdown.title;
				window.onresize();
			});
	};
});
</script>
<?php jry_wb_print_tail();?>