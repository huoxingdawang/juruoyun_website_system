<?php
	include_once("../tools/jry_wb_includes.php");
	jry_wb_print_head("聊天室",true,true,true,array('use','usechat'));
?>
<div class='jry_wb_top_toolbar'></div>
<script language="javascript" src="jry_wb_chat.js.php"></script>
<script>
	jry_wb_add_load(function()
	{
		document.getElementsByClassName('jry_wb_top_toolbar')[0].style.display='none';
		document.getElementById('buttom_message').style.display='none';
		jry_wb_chat_room.init(document.getElementById('chat_body'));
		jry_wb_chat_room.show_main_button.onclick();
		jry_wb_chat_room.show_main_button.onclick=function(){};
	});
</script>
<link href="jry_wb_chat.css" type="text/css" rel="stylesheet">
<div id='chat_body' style="width:100%;height:100%"></div>
<?php jry_wb_print_tail();?>