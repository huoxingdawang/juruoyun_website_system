function jry_wb_manage_send_mail_init(area,mode)
{
	jry_wb_manage_send_mail_run(area);
}
function jry_wb_manage_send_mail_run(area)
{
	area.innerHTML='';
	var title_input = document.createElement('input');area.appendChild(title_input);
	title_input.classList.add('h56');
	
	var data_input = document.createElement('textarea');area.appendChild(data_input);
	data_input.classList.add('h56');
	var button = document.createElement('button');area.appendChild(button);
	button.innerHTML="发送";
	button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_warn');
	button.onclick=function()
	{
		jry_wb_ajax_load_data('jry_wb_manage_send_mail_do.php',function(data)
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
			for(var i=0,n=data.length;i<n;i++)
				if(data[i].data=='OK')
					jry_wb_beautiful_right_alert.alert('Send to '+data[i].id+' OK',Math.random()*5000,'auto','ok');
				else if(data[i].data=='notok')
					jry_wb_beautiful_right_alert.alert('Send to '+data[i].id+' not ok',Math.random()*5000,'auto','error');
				else if(data[i].data=='nomail')
					jry_wb_beautiful_right_alert.alert('Send to '+data[i].id+' no mail',Math.random()*5000,'auto','warn');
			jry_wb_loading_off();
		},[{'name':'title','value':title_input.value},{'name':'data','value':data_input.value}]);
	};
}