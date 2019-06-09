<?php
	include_once("../tools/jry_wb_includes.php");
	include_once("../jry_wb_configs/jry_wb_config_user_extern_message.php");
	jry_wb_print_head("注册",false,false,true);
	if(!JRY_WB_HOST_SWITCH&&$_GET['debug']!=1)
	{
		?><script>window.location="<?php echo JRY_WB_HOST_ADDRESS?>mainpages/add.php"</script><?php
		exit();
	}
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>	
	<?php jry_wb_print_href("login","");?>
	<?php jry_wb_print_href('add_user',"active");?>
</div>
<div align="center" >
	<table  border="1" cellspacing="0" cellpadding="0">
<?php foreach($jry_wb_config_user_extern_message as $one)if($one['type']=='cutter'&&$one['before']===true){?>
		<tr>
			<td colspan='2' style='text-align:center;'><h56><?php echo $one['name']; ?></h56></td>
		</tr>			
<?php }?>
		<tr>
			<td width="200" id="td1">
				<h55>昵称</h55>
			</td>
			<td width="300" id="td2">
				<input name="name" type="text" id="name" class="h56" style='width:99%;'/>
			</td>
		</tr>
		<tr>
			<td>
				<h55>密码</h55>
			</td>
			<td>
				<input name="password1" type="password" class="h56" id="password1" style='width:99%;'/>
			</td>
		</tr>
		<tr>
			<td>
				<h55>再输密码</h55>
			</td>
			<td>
				<input name="password2" type="password" id="password2" class="h56" style='width:99%;'/>
			</td>
		</tr>
		<tr>
			<td>
				<h55>性别</h55>
			</td>
			<td>
				<input type="radio" name="sex" value="1" checked/>
				<h56>男</h56>
				<input style="margin-left:20px;" type="radio" name="sex" value="0" />
				<h56>女</h56>
				<input style="margin-left:20px;" type="radio" name="sex" value="2" />
				<h56>女装大佬</h56>		
			</td>
		</tr>
		
<?php if(JRY_WB_CHECK_TEL_SWITCH){ ?>
		<tr>
			<td width="200">
				<h55>电话</h55>
			</td>
			<td width="300">
				<input name="tel" type="text" id="tel" class="h56" style='width:99%;'/>
			</td>
		</tr>
<?php } ?>
<?php if(JRY_WB_CHECK_MAIL_SWITCH){ ?>
		<tr>
			<td width="200">
				<h55>邮箱</h55>
			</td>
			<td width="300">
				<input name="mail" type="text" id="mail" class="h56" style='width:99%;'/>
			</td>
		</tr>
<?php } ?>			
<?php
	foreach($jry_wb_config_user_extern_message as $one)
	{
?>
		<?php if($one['type']=='cutter'){if($one['before']!==true){?>
		<tr>
			<td colspan='2' style='text-align:center;'><h56><?php echo $one['name']; ?></h56></td>
		</tr>			
		<?php }}else{ ?>
		<tr>
			<td>
				<h55><?php  echo $one['name']; ?></h55>
			</td>
			<td width="300">
				<?php
					if($one['type']=='word'||$one['type']=='tel'||$one['type']=='mail'||$one['type']=='china_id')
					{ ?>
						<input name="<?php  echo $one['key']; ?>" type="text" id="<?php  echo $one['key']; ?>" class="h56" style='width:99%;'/>
					<?php }else if($one['type']=='select')
					{ ?>
						<select id="<?php echo $one['key']; ?>" name="<?php echo $one['key']; ?>" class="h56" style='width:99%;'>
							<option style='display: none'></option>
							<?php foreach($one['select'] as $select)
							{ if(is_array($select)){?>
								<option value="<?php echo $select['value']; ?>"><?php echo $select['name']; ?></option>
							<?php }else{ ?>
								<option value="<?php echo $select; ?>"><?php echo $select; ?></option>
							<?php } 
							}?>
						</select>						
					<?php }else if($one['type']=='check')
					{ ?>
						<input type="radio" name="<?php echo $one['key']; ?>" value="1" checked/>
						<h56>是</h56>
						<input style="margin-left:20px;" type="radio" name="<?php echo $one['key']; ?>" value="0" />
						<h56>否</h56>					
					<?php } }?>
			</td>
		</tr>			
<?php }?>
		<tr>
			<td>
				<h55>验证码</h55>
			</td>
			<td>
				<input name="vcode" type="text" id="vcode" class="h56" style="width:200px"/>
				<img id="vcodesrc" src="" onload="window.onresize()"/>
			</td>
		</tr>
<?php if(JRY_WB_CHECK_TEL_SWITCH&&constant('jry_wb_short_message_switch')!=''){ ?>
		<tr id="tr_tel">
			<td>
				<h55>电话验证码</h55>
			</td>
			<td>
				<input name="phonecode" type="text" id="phonecode" class="h56" size="4" onclick="";/>
				<button id="phonecode_button" name="phonecode_button" class="jry_wb_button jry_wb_button_size_middle jry_wb_color_ok" type="button" onclick="check_tel()">获取验证码</button>
		</tr>
<?php } ?>
		<tr>
			<td colspan="2">
			<div align="center">
				<button onclick="return clear_all();" class="jry_wb_button jry_wb_button_size_big jry_wb_color_error"/>清空</button>
				<button id='tijiao_button' onclick="return check();" style="margin-left:100px;" class="jry_wb_button jry_wb_button_size_big jry_wb_color_ok"/>提交</button>
			</div>
			</td>
		</tr>
	</table>
	<a target="_blank" href="<?php echo jry_wb_print_href("xieyi",'','',true);?>">注册即代表同意《蒟蒻云用户协议》</a><br>
	<a target="_blank" href="<?php echo jry_wb_print_href("zhinan",'','',true);?>">用户指南</a><br>
	<a href>验证码区分大小写</a><br>
	<a href>问题或建议点边上的小虫子</a>
</div>
<script language="javascript" src="jry_wb_mainpages_add.js.php"></script>
<?php jry_wb_print_tail()?>
