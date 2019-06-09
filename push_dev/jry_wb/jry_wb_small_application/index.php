<?php
	include_once("../tools/jry_wb_includes.php");
	jry_wb_print_head("小程序中心",false,true,true);
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>
	<?php if($jry_wb_login_user['id']!=-1)jry_wb_show_user($jry_wb_login_user);?>		
	<?php jry_wb_print_href('smallapp','active');?>
</div>
<div id="_top"></div>
<div  style="width:100%;" class="jry_wb_left_toolbar" id="body"></div>
<script language="javascript">
jry_wb_add_load(function()
{
	var area=document.getElementById('body');
	var left_body=document.createElement('div');area.appendChild(left_body);
	left_body.classList.add('jry_wb_left_toolbar_left');
	left_body.style.float='left';
	var right_body=document.createElement('div');area.appendChild(right_body);
	right_body.style.float='left';
	var one_function=document.createElement('div');right_body.appendChild(one_function);
	one_function.id='one_function';
	jry_wb_add_onresize(function()
	{
		document.getElementById('one_function').style.width=document.body.clientWidth-left_body.clientWidth;
		right_body.style.width=document.body.clientWidth-left_body.clientWidth;
		document.getElementById('one_function').style.height=Math.max(0,window.innerHeight-document.getElementById('_top').getBoundingClientRect().top-document.getElementById('buttom_message').clientHeight);
		area.style.height=Math.max(left_body.clientHeight,right_body.clientHeight);
	});
	var data=JSON.parse('<?php
	
		$st = jry_wb_connect_database()->prepare("SELECT * FROM ".JRY_WB_DATABASE_SMALL_APPLICATION."list ORDER BY name ASC");
		$st->execute();
		echo json_encode($st->fetchAll());
	?>');
	for(var i=0,n=data.length;i<n;i++)
	{
		var one=document.createElement('div');left_body.appendChild(one);
		one.classList.add("jry_wb_left_toolbar_left_list_"+((i%2)+1));
		one.innerHTML=data[i].name;
		one.name=i;
		if(data[i].is_script)
		{
			one.onclick=function()
			{
				var i=parseInt(this.name);
				window.location.hash=data[i].hash
				if(one_function.tagName=="IFRAME")
				{
					right_body.removeChild(one_function);
					one_function=document.createElement('div');right_body.appendChild(one_function);
					one_function.id='one_function';
				}
				one_function.innerHTML='';
				if(data[i].inited==undefined||data[i].inited==false)
				{
					data[i].inited=true;
					one_function.innerHTML='';
					jry_wb_include_once_script(jry_wb_message.jry_wb_host+'jry_wb_small_application/'+data[i].url,function(){eval(data[i].init_script+'(one_function)');window.onresize();})
				}
				else
					eval(data[i].run_script+'(one_function)');
				window.onresize();
			}
		}
		else
		{
			one.onclick=function()
			{
				var i=parseInt(this.name);
				window.location.hash=data[i].hash
				if(one_function.tagName!="IFRAME")
				{
					right_body.removeChild(one_function);
					one_function=document.createElement('iframe');right_body.appendChild(one_function);
					one_function.id='one_function';					
				}
				if(data[i].url.includes('http'))
					one_function.src=data[i].url;
				else
					one_function.src=jry_wb_message.jry_wb_host+'jry_wb_small_application/'+data[i].url;
				one_function.style.border=0;
				one_function.style.height=Math.max(0,window.innerHeight-document.getElementById('_top').getBoundingClientRect().top-document.getElementById('buttom_message').clientHeight);
				jry_wb_beautiful_right_alert.alert('加载内联页面中，请稍等',1000,'auto','warn');jry_wb_loading_on();
				one_function.onload=function()
				{
					jry_wb_beautiful_right_alert.alert('加载内联页面完毕',500,'auto','ok');	jry_wb_loading_off();
					window.onresize();					
				}
			}
		}
		if(window.location.hash=='#'+data[i].hash)
			one.onclick();
	}
});
</script>
<?php jry_wb_print_tail();?>