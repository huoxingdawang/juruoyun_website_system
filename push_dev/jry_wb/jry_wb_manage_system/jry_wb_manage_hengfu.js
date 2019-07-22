function jry_wb_manage_hengfu_load_data(area)
{
	jry_wb_sync_data_with_server('manage_hengfu',"jry_wb_manage_hengfu_get_information.php?action=list",null,function(data)
	{
		jry_wb_add_on_indexeddb_open(function()
		{
			jry_wb_manage_hengfu_data=data;
			var re=jry_wb_indexeddb.transaction(['manage_hengfu'],'readwrite').objectStore('manage_hengfu');
			for(var i=0;i<jry_wb_manage_hengfu_data.length;i++)
				if(jry_wb_manage_hengfu_data[i].delete)
					re.delete(jry_wb_manage_hengfu_data[i].hengfu_id),jry_wb_manage_hengfu_data.splice(i,1),i--;		
			jry_wb_manage_hengfu_run(area);
		});
	},function(a,b){return a.hengfu_id-b.hengfu_id});
}
function jry_wb_manage_hengfu_init(area,mode)
{
	jry_wb_manage_hengfu_load_data(area);
}
function jry_wb_manage_hengfu_run(area)
{
	area.innerHTML='';
	var all = document.createElement('table');area.appendChild(all);
	var width=area.clientWidth-30;
	all.style.width=width;
	all.border=2;
	var tr = document.createElement('tr');all.appendChild(tr);
	var td = document.createElement('td');tr.appendChild(td);td.classList.add('h55');td.innerHTML='横幅';td.align='center';
	var td = document.createElement('td');tr.appendChild(td);td.classList.add('h55');td.innerHTML='操作';td.align='center';td.setAttribute('colspan','3');
	for(let i=0,n=jry_wb_manage_hengfu_data.length;i<n;i++)
	{
		var tr = document.createElement('tr');all.appendChild(tr);
		var td = document.createElement('td');tr.appendChild(td);
		var input= document.createElement('input');td.appendChild(input);
		input.value=jry_wb_manage_hengfu_data[i].words;
		input.classList.add('h56');
		input.name=jry_wb_manage_hengfu_data[i].hengfu_id
		var td = document.createElement('td');tr.appendChild(td);
		var chenge= document.createElement('button');td.appendChild(chenge);
		chenge.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_warn');
		chenge.innerHTML='修改';
		chenge.onclick=function(event)
		{
			var input=event.target.parentNode.parentNode.getElementsByTagName('input')[0];
			jry_wb_ajax_load_data('jry_wb_manage_hengfu_do.php?action=chenge',function(data)
			{
				data=JSON.parse(data);
				jry_wb_loading_off();
				if(data.code==true)
				{
					jry_wb_beautiful_right_alert.alert('修改成功',2000,'auto','ok');
					jry_wb_manage_hengfu_load_data(area);
				}
				else
				{
					if(data.reason==100000)
						jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
					else if(data.reason==100001)
						jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
					else
						jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
					return ;
				}
			},[{'name':'words','value':input.value},{'name':'hengfu_id','value':input.name}]);
		};
		var td = document.createElement('td');tr.appendChild(td);
		var del= document.createElement('button');td.appendChild(del);
		del.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_error');
		del.innerHTML='删除'
		del.onclick=function(event)
		{
			var input=event.target.parentNode.parentNode.getElementsByTagName('input')[0];
			jry_wb_ajax_load_data('jry_wb_manage_hengfu_do.php?action=delete',function(data)
			{
				data=JSON.parse(data);
				jry_wb_loading_off();
				if(data.code==true)
				{
					jry_wb_beautiful_right_alert.alert('删除成功',2000,'auto','ok');
					jry_wb_manage_hengfu_load_data(area);
				}
				else
				{
					if(data.reason==100000)
						jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
					else if(data.reason==100001)
						jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
					else
						jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
					return ;
				}
			},[{'name':'hengfu_id','value':input.name}]);			
		};
		var td = document.createElement('td');tr.appendChild(td);
		var enable= document.createElement('button');td.appendChild(enable);
		enable.classList.add('jry_wb_button','jry_wb_button_size_big',(jry_wb_manage_hengfu_data[i].enable?'jry_wb_color_error':'jry_wb_color_ok'));
		enable.innerHTML=(jry_wb_manage_hengfu_data[i].enable?'停用':'启用');
		enable.onclick=function(event)
		{
			var input=event.target.parentNode.parentNode.getElementsByTagName('input')[0];
			jry_wb_ajax_load_data('jry_wb_manage_hengfu_do.php?action='+(jry_wb_manage_hengfu_data[i].enable?'disable':'enable'),function(data)
			{
				data=JSON.parse(data);
				jry_wb_loading_off();
				if(data.code==true)
				{
					jry_wb_beautiful_right_alert.alert('操作成功',2000,'auto','ok');
					jry_wb_manage_hengfu_load_data(area);
				}
				else
				{
					if(data.reason==100000)
						jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
					else if(data.reason==100001)
						jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
					else
						jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
					return ;
				}
			},[{'name':'hengfu_id','value':input.name}]);			
		};				
		input.style.width=(width-chenge.clientWidth-del.clientWidth-enable.clientWidth)*0.9;
	}
	var tr = document.createElement('tr');all.appendChild(tr);
	var td = document.createElement('td');tr.appendChild(td);
	var input= document.createElement('input');td.appendChild(input);
	input.classList.add('h56');
	var td = document.createElement('td');tr.appendChild(td);
	var add= document.createElement('button');td.appendChild(add);
	add.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');
	add.innerHTML='添加';
	add.onclick=function(event)
	{
		var input=event.target.parentNode.parentNode.getElementsByTagName('input')[0];
		jry_wb_ajax_load_data('jry_wb_manage_hengfu_do.php?action=add',function(data)
		{
			data=JSON.parse(data);
			jry_wb_loading_off();
			if(data.code==true)
			{
				jry_wb_beautiful_right_alert.alert('添加成功',2000,'auto','ok');
				jry_wb_manage_hengfu_load_data(area);
			}
			else
			{
				if(data.reason==100000)
					jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
				else if(data.reason==100001)
					jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
				else
					jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
				return ;
			}
		},[{'name':'words','value':input.value}]);
	};
	var td = document.createElement('td');tr.appendChild(td);
	td.setAttribute('colspan','2');
	var del= document.createElement('button');td.appendChild(del);
	del.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_error');
	del.innerHTML='清空';
	del.onclick=function(event)
	{
		var input=event.target.parentNode.parentNode.getElementsByTagName('input')[0];		
		input.value='';
	}	
	input.style.width='90%';
	window.onresize();
}
