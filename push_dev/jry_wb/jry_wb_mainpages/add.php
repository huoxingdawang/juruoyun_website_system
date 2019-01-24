<?php
	include_once("../tools/jry_wb_includes.php");
	jry_wb_print_head("注册",false,false,true);
	if(!constant('jry_wb_host_switch'))
	{
		?><script>window.location="<?php echo constant('jry_wb_host_addr')?>mainpages/add.php"</script><?php
		exit();
	}
?>
<div class="jry_wb_top_toolbar">
	<?php jry_wb_print_logo(false);?>	
	<?php jry_wb_print_href("login","");?>
	<?php jry_wb_print_href('add_user',"active");?>
</div>
<body>
<div align="center" >
	<form id="form1" name="form1" method="post" action="do_add.php">
  		<table  border="1" cellspacing="0" cellpadding="0">
			<tr>
				<td width="200">
					<h55>昵称</h55>
				</td>
				<td width="300">
					<input name="name" type="text" id="name" class="h56"/>
				</td>
			</tr>
			<tr>
				<td>
					<h55>密码</h55>
				</td>
				<td>
					<input name="password1" type="password" class="h56" id="password1"/>
				</td>
			</tr>
			<tr>
				<td>
					<h55>再输密码</h55>
				</td>
				<td>
					<input name="password2" type="password" id="password2" class="h56"/>
				</td>
			</tr>
			<tr>
				<td>
					<h55>性别</h55>
				</td>
				<td>
					<input type="radio" name="sex" value=1 checked/>
					<h56>男&nbsp;&nbsp;</h56>
					<input type="radio" name="sex" value=0/>
					<h56>女&nbsp;&nbsp;</h56>
					<input type="radio" name="sex" value=2/>
					<h56>女装大佬</h56>		
				</td>
			</tr>
			
			<tr>
<?php if(constant('jry_wb_check_tel_switch')){ ?>
				<td width="200">
					<h55>电话</h55>
				</td>
				<td width="300">
					<input name="tel" type="text" id="tel" class="h56"/>
				</td>
			</tr>
<?php } ?>			
			<tr>
                <td>
                    <h55>验证码</h55>
                </td>
                <td>
				<input name="vcode" type="text" id="vcode" class="h56" size="4"/>
				<img id="vcodesrc" src="<?php echo jry_wb_print_href("verificationcode",0,"",1);?>" onload="window.onresize()" onclick="document.getElementById('vcodesrc').src='<?php echo jry_wb_print_href("verificationcode",0,"",1);?>?r='+Math.random()"/>
                </td>
			</tr>
<?php if(constant('jry_wb_check_tel_switch')){ ?>
			<tr>
				<td>
					<h55>电话验证码</h55>
                </td>
                <td>
				<input name="phonecode" type="text" id="phonecode" class="h56" size="4"
				onclick="";
				/>
				<button id="button" name="button" class="jry_wb_button jry_wb_button_size_middle jry_wb_color_ok" type="button" onclick="check_tel()">获取验证码</button>
			</tr>
<?php } ?>
			<tr>
				<td colspan="2">
                <div align="center">
						 <input type="submit" name="Submit" value="提交"  onclick="return check();" class="jry_wb_button jry_wb_button_size_big jry_wb_color_ok"/>
                </div>
				</td>
			</tr>
		</table>
	</form>
	<a target="_blank" href="<?php echo jry_wb_print_href("xieyi",'','',true);?>">注册即代表同意《蒟蒻云用户协议》</a><br>
	<a target="_blank" href="<?php echo jry_wb_print_href("zhinan",'','',true);?>">用户指南</a><br>
	<a href>验证码区分大小写</a><br>
	<a href>问题或建议点边上的小虫子</a>
</div>
<script language="javascript">
document.getElementById('name').focus();
function check()
{ 
	var name= document.getElementById("name").value;
	var password1= document.getElementById("password1").value;
	var password2= document.getElementById("password2").value;
	var vcode= document.getElementById("vcode").value;
	<?php if(constant('jry_wb_check_tel_switch')){ ?>
	var phonecode= document.getElementById("phonecode").value;
	var tel= document.getElementById("tel").value;
	<?php } ?>
	if(name=="")
    {
		jry_wb_beautiful_alert.alert("请填写完整信息","名字为空",function(){
        document.getElementById("password1").focus();});
        return false;
    }
	if(vcode=="")
    {
		jry_wb_beautiful_alert.alert("请填写完整信息","验证码为空",function(){
        document.getElementById("password1").focus();});
        return false;
    }
	if(password1=="")
    {
		jry_wb_beautiful_alert.alert("请填写完整信息","密码为空",function(){
        document.getElementById("password1").focus();});
        return false;
    }
	if(password2=="")
    {
		jry_wb_beautiful_alert.alert("请填写完整信息","密码为空",function(){
        document.getElementById("password2").focus();});
        return false;
    }
	if(password1.length<8)
    {
		jry_wb_beautiful_alert.alert("请填写正确信息","密码太短",function(){
        document.getElementById("password1").focus();});
        return false;
    }
    if(password1!=password2)
    {
		jry_wb_beautiful_alert.alert("请填写正确信息","密码不同",function(){
        document.getElementById("password2").focus();});
        return false;
    }
	<?php if(constant('jry_wb_check_tel_switch')){ ?>
	if(tel!=""&&jry_wb_test_phone_number(tel)==false)
	{
		jry_wb_beautiful_alert.alert("请填写正确信息","电话错误",function(){
        document.getElementById("tel").focus();});
        return false;
    }
	if(phonecode=='')
	{
		jry_wb_beautiful_alert.alert("请填写完整信息","电话验证码为空",function(){
        document.getElementById("password1").focus();});
        return false;
	}
	<?php } ?>
    return true;
}
<?php if(constant('jry_wb_check_tel_switch')){ ?>
function check_tel()
{ 
	var tel= document.getElementById("tel").value;
	var vcode= document.getElementById("vcode").value;
	if(vcode=='')
	{
		jry_wb_beautiful_alert.alert("请填写完整信息","验证码为空",'document.getElementById("vcode").focus()');
		return false;		
	}
	if(tel!=""&&jry_wb_test_phone_number(tel)==false)
	{
		jry_wb_beautiful_alert.alert("请填写正确信息","电话错误",'document.getElementById("tel").focus()');
        return false;
    }
	jry_wb_ajax_load_data('do_add.php?action=send_tel',function (data_){jry_wb_beautiful_alert.alert(data_,'',function(){jry_wb_loading_off();});},[{'name':'vcode','value':document.getElementById("vcode").value},{'name':'tel','value':document.getElementById("tel").value}],true);
    return true;
}
<?php } ?>
</script>
<?php jry_wb_print_tail()?>
