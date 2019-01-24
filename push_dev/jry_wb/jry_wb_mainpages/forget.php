<?php
	include_once('../tools/jry_wb_includes.php');
	jry_wb_print_head('忘记账号',false,false,true);
?>
<div class='jry_wb_top_toolbar'>
	<?php jry_wb_print_logo(false);?>	
	<?php jry_wb_print_href('login','');?>
	<?php jry_wb_print_href('add_user','');?>
	<?php jry_wb_print_href('forget','active');?>
</div>
<script language="javascript">
jry_wb_add_load(pro);
function pro()
{
	jry_wb_beautiful_alert.check("重置密码很复杂，是否查看提示",
	function()
	{
		jry_wb_beautiful_alert.alert('提示','根据实际情况乱点即可',function(){check(run);})
	},
	exit,'查看','不看');
}
function check(fun)
{
	jry_wb_beautiful_alert.check("你是不是觉得自己把密码忘了还有理了",
		exit,
		function()
		{
			jry_wb_beautiful_alert.check("你知道开发组开发这个网站多辛苦吗",
			fun,
			function()
			{
				jry_wb_beautiful_alert.alert("那我让你知道一下","蒟蒻云目前是ljy一个一个字母敲出来的",
				function()
				{
					jry_wb_beautiful_alert.check("现在你知道了吗",
					fun,
					exit,"知道了","不知道"	);
				});
			},'知道','不知道');
		}
		,'对，所以呢？','大大大大哥我错了'
	);
}
function exit()
{
	jry_wb_beautiful_alert.alert("再见","蒟蒻云不欢迎你",function(){window.open('/','_self');});	
}
function run()
{
	jry_wb_beautiful_alert.alert("行吧，那我们开始召唤账户或密码","",
	function()
	{
		jry_wb_beautiful_alert.check("您记得您的账号吗？",
		run2,
		function()
		{
			check(run3);
		},'朕记得','朕忘了'
		);
	});
}
function run2()
{
	jry_wb_beautiful_alert.prompt('请输入您的ID号',
	function(data_)
	{
		jry_wb_ajax_load_data('do_forget.php?action=serchid&id='+data_,
		function (data)
		{
			data=JSON.parse(data);
			jry_wb_loading_off();
			if(data=='')
				jry_wb_beautiful_alert.alert('您的邮箱为空','',run3);
			jry_wb_beautiful_alert.check('您的邮箱是<br>'+data+'<br>吗?<br>(依据您的隐私设置，可能被打码)',
			function()
			{
				jry_wb_ajax_load_data('do_forget.php?action=sendemail&id='+data_,
				function()
				{
					jry_wb_beautiful_alert.alert("成功","已向您的邮箱发送新的密码及账号，请及时处理<br>点击确定以退出",function (){window.open('/','_self');}
					);
				});
			},
			function()
			{
				jry_wb_beautiful_alert.check('您的ID是<br>'+data_+'<br>吗？',
				function()
				{
					jry_wb_beautiful_alert.alert("非常抱歉",'您的邮箱出现了未知错误<br>您可以尝试手机找回',run3);
				},
				function()
				{
					check(run2);
				},'是','不是'
				);
			},'是','不是');
		})
	});
}
function run3()
{
//	jry_wb_beautiful_alert.alert("抱歉",'依据手机号找回密码还没写好<br>请耐心等待',function (){window.open('/','_self');});
	jry_wb_beautiful_alert.prompt('请输入您的手机号',
		function(data_)
		{
			jry_wb_ajax_load_data('do_forget.php?action=checktel',
				function(data)
				{
					jry_wb_loading_off();
					data=JSON.parse(data);
					if(data==null)
						jry_wb_beautiful_alert.alert('您的手机没有绑定账号','建议您重新注册一个',function(){window.open('add.php','_self');});
					jry_wb_beautiful_alert.check('您的昵称是<br>'+data+'<br>吗？',
						function()
						{
							jry_wb_beautiful_alert.alert('我们即将验证您的手机','验证手机需要0.05元(不需要您支付)<br>但为了体谅开发组不宜，我们建议您到开发组简介页面进行打赏<br>点击以验证手机',
							function()
							{
								jry_wb_ajax_load_data('do_forget.php?action=sendtelcode',
								function(data)
								{
									jry_wb_loading_off();
									if(JSON.parse(data)=='OK')
										yanzhengma();
									else if(JSON.parse(data)=='toofast')
										jry_wb_beautiful_alert.alert("您提交过快",'请直接输入上一个',yanzhengma);									
									else
										jry_wb_beautiful_alert.alert("非常抱歉",'系统出现未知错误<br>您可以尝试联系管理员',function(){window.open('bugreport.php','_self');});
								});
							});
						},
						function()
						{
							jry_wb_beautiful_alert.alert("非常抱歉",'您的账号出现了未知错误<br>您可以尝试联系管理员',function(){window.open('bugreport.php','_self');});
						},'是','不是');				
				},[{'name':'tel','value':data_}]);
		});
}
function yanzhengma()
{
	jry_wb_beautiful_alert.prompt('请输入您的验证码',
	function (data)
	{
		jry_wb_ajax_load_data('do_forget.php?action=checkcode&code='+data,
		function(data)
		{
			jry_wb_loading_off();
			if(data==-1)
				jry_wb_beautiful_alert.alert('您输错了','',yanzhengma);
			else
				jry_wb_beautiful_alert.alert('成功','PASSWORD:'+data+'<br>您可以使用手机登录',function(){window.open('login.php','_self');});	
		});
	});
}
</script>
<?php jry_wb_print_tail();?>