function jry_wb_manage_ping_init(area,mode)
{
	jry_wb_manage_ping_run(area);
}
function jry_wb_manage_ping_run(area)
{
	area.innerHTML='';
	var data_input = document.createElement('textarea');area.appendChild(data_input);
	data_input.style.height='500px';data_input.style.width='200px';
	jry_wb_ajax_load_data('jry_wb_manage_ping_get_information.php?action=get',function(data)
	{
		data=JSON.parse(data);
		data_input.value=data.data;
		jry_wb_loading_off();
	});
	area.appendChild(document.createElement('br'));
	var button = document.createElement('button');area.appendChild(button);
	button.innerHTML="保存";
	button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_warn');
	button.onclick=function()
	{
		jry_wb_ajax_load_data('jry_wb_manage_ping_do.php',function(data)
		{
			data=JSON.parse(data);
			if(data.code==false)
			{
				if(data.reason==100000)
					jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
				else if(data.reason==100001)
					jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
				else
					jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
				return ;
			}
			data_input.value=data;
			jry_wb_beautiful_right_alert.alert('修改成功',2000,'auto','ok');			
			jry_wb_loading_off();
		},[{'name':'data','value':btoa(data_input.value)}]);
	};
	var result = document.createElement('div');area.appendChild(result);
	jry_wb_ajax_load_data('jry_wb_manage_ping_get_information.php?action=result',function(data)
	{
		data=JSON.parse(data);
		result.innerHTML=data.data;
		jry_wb_loading_off();
	});	
}