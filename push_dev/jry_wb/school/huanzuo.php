<?php
	include_once("../tools/jry_wb_includes.php");
	jry_wb_print_head("换座位内测在线版",false,false,true);
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>	
	<a class='active' >换座位系统内测版</a>	
</div>
<script language="javascript" src="seat.js"></script>
<div>
<table width="100%" height="*" border="1" id="zuowei"></table>
<input type="button" class="jry_wb_color_ok jry_wb_button jry_wb_button_size_big" value="随机生成" onclick="seat.promanage();if(seat.rand())seat.show();">
<input type="button" class="jry_wb_color_ok jry_wb_button jry_wb_button_size_big" value="生成" onclick="seat.getdata(JSON.parse(document.getElementById(&#39;test&#39;).value));seat.promanage();seat.show();">
<input type="button" class="jry_wb_color_ok jry_wb_button jry_wb_button_size_big" value="多次生成" onclick="var cnt=0;function a(){seat.promanage();if(seat.rand()){seat.show();jry_wb_beautiful_alert.close();cnt++;if(cnt&lt;100)setTimeout(a,100);}}a();"><br>
<textarea style="width:90%;height: 172px;" id="test">
{"num":40,"seat":{"lie":8,"mubiao_hang":6,"break":[2,6],"one_seat_from_hang":5},"stu":[{"id":1,"name":"毕*明 ","position_hang":0,"position_lie":1,"connect":true},{"id":2,"name":"陈*阳 ","position_hang":1,"position_lie":5},{"id":3,"name":"单*豪 ","position_hang":4,"position_lie":3},{"id":4,"name":"刁*昊 ","position_hang":1,"position_lie":1,"connect":true},{"id":5,"name":"范*简 ","position_hang":1,"position_lie":4},{"id":6,"name":"侯*毅 ","position_hang":3,"position_lie":7},{"id":7,"name":"李*本 ","position_hang":2,"position_lie":4},{"id":8,"name":"李*彦 ","position_hang":3,"position_lie":4},{"id":9,"name":"李*轩","position_hang":3,"position_lie":0},{"id":10,"name":"李*源","position_hang":3,"position_lie":2},{"id":11,"name":"李*彬 ","position_hang":4,"position_lie":7},{"id":12,"name":"李*然 ","position_hang":2,"position_lie":7},{"id":13,"name":"刘*骏 ","position_hang":3,"position_lie":3},{"id":14,"name":"刘*豪 ","position_hang":0,"position_lie":0,"connect":true},{"id":15,"name":"刘*瑜 ","position_hang":2,"position_lie":0},{"id":17,"name":"逯*恒 ","position_hang":2,"position_lie":6},{"id":18,"name":"吕*轩 ","position_hang":0,"position_lie":5},{"id":19,"name":"孟*宇","position_hang":3,"position_lie":1},{"id":20,"name":"史*涛 ","position_hang":2,"position_lie":3},{"id":21,"name":"孙*豪 ","position_hang":4,"position_lie":1},{"id":22,"name":"王*岳 ","position_hang":4,"position_lie":0},{"id":23,"name":"王*丞 ","position_hang":0,"position_lie":4},{"id":25,"name":"王*兴 ","position_hang":0,"position_lie":3},{"id":28,"name":"于*","position_hang":4,"position_lie":5,"connect":true},{"id":29,"name":"于*","position_hang":1,"position_lie":7},{"id":30,"name":"张*维 ","position_hang":1,"position_lie":3},{"id":31,"name":"张*泽 ","position_hang":2,"position_lie":5},{"id":32,"name":"张*天 ","position_hang":2,"position_lie":1},{"id":33,"name":"张*予 ","position_hang":4,"position_lie":4},{"id":34,"name":"张*宇 ","position_hang":4,"position_lie":2},{"id":35,"name":"赵*宇 ","position_hang":0,"position_lie":7},{"id":36,"name":"郑*海 ","position_hang":1,"position_lie":6},{"id":37,"name":"郑*豪 ","position_hang":0,"position_lie":6},{"id":38,"name":"孔*琳 ","position_hang":2,"position_lie":2},{"id":39,"name":"李*琪 ","position_hang":1,"position_lie":0,"connect":true},{"id":40,"name":"李*珺 ","position_hang":4,"position_lie":6,"connect":true},{"id":41,"name":"李*飞 ","position_hang":0,"position_lie":2,"connect":true},{"id":43,"name":"温*玮 ","position_hang":1,"position_lie":2,"connect":true},{"id":45,"name":"张*","position_hang":3,"position_lie":5}],"connect":[[1,41],[4,43],[14,39],[28,40]]}
</textarea>
</div>
<script language="javascript">
jry_wb_add_load(function(){
	seat = new seat_function(JSON.parse(document.getElementById('test').value),document.getElementById('zuowei'));
	seat.show();
	jry_wb_set_shortcut([jry_wb_keycode_enter],function()
	{
		seat.promanage();
		if(seat.rand())
			seat.show();
	});
});
</script>
<div class="" style="color:#ff0000;font-size:50px;text-align:center;">
开发组严正声明<br>
以上数据都是开发组通过"脸压键盘法"随机生成的<br>
仅供测试用,没有实际意义<br>
开发组严厉谴责造谣生事者<br>
<a href="http://www.juruoyun.top/mywork/blog/show.php?blog_id=00021">技术实现</a>
</div>
<?php jry_wb_print_tail();?>
