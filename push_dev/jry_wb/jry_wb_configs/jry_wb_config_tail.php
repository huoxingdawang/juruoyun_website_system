	<a target="_blank" href="<?php echo jry_wb_print_href("aboutus",'','',true);?>"><b class="jry_wb_font_buttom">关于我们</b></a><b class="jry_wb_font_buttom">|</b>
	<a target="_blank" href="<?php echo jry_wb_print_href("zhinan",'','',true);?>"><b class="jry_wb_font_buttom">用户指南</b></a><b class="jry_wb_font_buttom">|</b>
	<a target="_blank" href="<?php echo jry_wb_print_href("introduction",'','',true);?>"><b class="jry_wb_font_buttom">开发组简介</b></a><b class="jry_wb_font_buttom">|</b>
	<a target="_blank" href="<?php echo jry_wb_print_href("help",'?question=all','',true);?>"><b class="jry_wb_font_buttom">帮助中心</b></a><b class="jry_wb_font_buttom">|</b>
	<a target="_blank" href="<?php echo jry_wb_print_href("xieyi",'','',true);?>"><b class="jry_wb_font_buttom">用户协议</b></a><b class="jry_wb_font_buttom">|</b>
	<a target="_blank" href="<?php echo jry_wb_print_href("bug",'','',true);?>"><b class="jry_wb_font_buttom">BUG反馈</a></b><br>
	<a target="_blank" href="http://www.miitbeian.gov.cn/"><b class="jry_wb_font_buttom">鲁ICP备18022035号</b></a><b class="jry_wb_font_buttom">|</b>
	<a target="_blank" target="_blank" href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=37020302371247" style="display:inline-block;text-decoration:none;height:20px;line-height:20px;"><b class="jry_wb_font_buttom"><img src="../../data/general/picture/beian1.png" style="">鲁公网安备 37020302371247号</b></a><br>
	<b class="jry_wb_font_buttom">巨佬博客:&nbsp;</b><a target="_blank" href="https://blog.hookan.top"><b class="jry_wb_font_buttom" onmouseover="this.innerHTML='这个人AK了IOI'" onmouseout="this.innerHTML='巨佬李明泽的博客'">巨佬李明泽的博客</b></a><b class="jry_wb_font_buttom">|</b>
	<a target="_blank" href="https://llh721113.github.io"><b class="jry_wb_font_buttom" onmouseover="this.innerHTML='这个人AK了IOI'" onmouseout="this.innerHTML='巨佬llh721113的个人网站'">巨佬llh721113的个人网站</b></a><b class="jry_wb_font_buttom">|</b>
	<a target="_blank" href="http://virtualman.fun/"><b class="jry_wb_font_buttom" onmouseover="this.innerHTML='这个人AK了IOI'" onmouseout="this.innerHTML='巨佬陈永贵的博客'">巨佬陈永贵的博客</b></a><br>
	<a target="_blank" href="http://juruoyun.top/mywork/blog/show.php?blog_id=00009"><b class="jry_wb_font_buttom">蒟蒻云美化计划</b></a><b class="jry_wb_font_buttom">|</b>
	<a target="_blank" href="https://promotion.aliyun.com/ntms/act/campus2018.html?spm=5176.8112568.520614.1.3f229ed57AzNMU&userCode=e05zvjms"><b class="jry_wb_font_buttom">阿里爸爸一折买服务姬</b></a><b class="jry_wb_font_buttom">|</b>
	<a target="_blank" href="https://lonelyi.top/auth/register?code=0xduamfduycHcAvgezk8VgbzgM3JWBlN"><b class="jry_wb_font_buttom">The road of fredom</b></a><b class="jry_wb_font_buttom">|</b>
	<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1176402460&site=qq&menu=yes"><b class="jry_wb_font_buttom">在QQ上嘲笑我的菜</b></a><br>
	<a target="_blank" href="http://www.juruoyun.top"><b class="jry_wb_font_buttom">您当前在开发版，点此返回稳定版本</b></a><br>
<script language="javascript">
	//重定向函数
	function jry_redirect(message, to_link) {
		jry_wb_beautiful_right_alert.alert(message, 3000, 'auto', 'ok');
		window.open(to_link);
	}
	//重定向列表，形式为"keycode"
	jry_redirect_list = [
		{"keycode": [jry_wb_keycode_f1], "message": "已打开新主页", "link": '<?php echo jry_wb_print_href('home','','',true);?>'},
		{"keycode": [jry_wb_keycode_f2], "message": "已打开个人中心", "link": '<?php echo jry_wb_print_href('users','','',true);?>'},
		{"keycode": [jry_wb_keycode_f3], "message": "已打开百度", "link": 'https://www.baidu.com'},
		{"keycode": [jry_wb_keycode_f4], "message": "已打开洛谷", "link": 'https://www.luogu.org/'},
		{"keycode": [jry_wb_keycode_a], "message": "已打开阿里云", "link": 'https://www.aliyun.com/?userCode=e05zvjms'},
		{"keycode": [jry_wb_keycode_b], "message": "已打开百度", "link": 'https://www.baidu.com'},
		{"keycode": [jry_wb_keycode_c,jry_wb_keycode_f], "message": "已打开CF", "link": 'http://codeforces.com/'},
		{"keycode": [jry_wb_keycode_b,jry_wb_keycode_i], "message": "已打开B站", "link": 'https://www.bilibili.com/'},
		{"keycode": [jry_wb_keycode_l], "message": "已打开洛谷", "link": 'https://www.luogu.org/'},
		{"keycode": [jry_wb_keycode_o], "message": "已打开开源电子网", "link": 'http://www.openedv.com/?fromuid=47947'},
		{"keycode": [jry_wb_keycode_p], "message": "已打开POJ", "link": 'http://poj.org/'},
		{"keycode": [jry_wb_keycode_q], "message": "已打开七牛云", "link": 'https://www.qiniu.com/'},
		{"keycode": [jry_wb_keycode_r], "message": "已打开RT-thread", "link": 'https://www.rt-thread.org/'},
		{"keycode": [jry_wb_keycode_t], "message": "已打开teencode", "link": 'http://106.12.116.155'}
	]
	jry_redirect_list.forEach(function(k) {
		jry_wb_set_shortcut(k.keycode, jry_redirect(k.message, k.link));
	});
</script>