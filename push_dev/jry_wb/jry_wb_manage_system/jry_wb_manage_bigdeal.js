function jry_wb_manage_bigdeal_load_data(area)
{
	jry_wb_sync_data_with_server('manage_bigdeal',"jry_wb_manage_bigdeal_get_information.php?action=list",null,function(data)
	{
		jry_wb_add_on_indexeddb_open(function()
		{
			jry_wb_manage_bigdeal_data=data;
			var re=jry_wb_indexeddb.transaction(['manage_bigdeal'],'readwrite').objectStore('manage_bigdeal');
			for(var i=0;i<jry_wb_manage_bigdeal_data.length;i++)
				if(jry_wb_manage_bigdeal_data[i].delete)
					re.delete(jry_wb_manage_bigdeal_data[i].bigdeal_id),jry_wb_manage_bigdeal_data.splice(i,1),i--;			
			jry_wb_manage_bigdeal_run(area);
		});
	},function(a,b){return a.bigdeal_id-b.bigdeal_id});
}
function jry_wb_manage_bigdeal_init(area,mode)
{
	jry_wb_manage_bigdeal_load_data(area);
}
function jry_wb_manage_bigdeal_run(area)
{
	area.innerHTML='';
	jry_wb_include_css('manage/bigdeal');		
	var all	=document.createElement('table');area.appendChild(all)	;all.classList.add('jry_wb_manage_bigdeal');
	var tr	=document.createElement('tr')	;all.appendChild(tr)	;
	var td	=document.createElement('td')	;tr.appendChild(td)		;td.classList.add('name')	;td.innerHTML='名称';
	var td	=document.createElement('td')	;tr.appendChild(td)		;td.classList.add('time')	;td.innerHTML='时间';
	var td	=document.createElement('td')	;tr.appendChild(td)		;td.classList.add('option')	;td.innerHTML='操作';td.setAttribute('colspan','3');
	for(let i=0,n=jry_wb_manage_bigdeal_data.length;i<n;i++)
	{
		var tr		=document.createElement('tr')		;all.appendChild(tr)	;
		var td		=document.createElement('td')		;tr.appendChild(td)		;
		let input1	=document.createElement('input')	;td.appendChild(input1)	;input1.classList.add('name_v')	;input1.value=jry_wb_manage_bigdeal_data[i].name;
		var td		=document.createElement('td')		;tr.appendChild(td)		;
		let input2	=document.createElement('input')	;td.appendChild(input2)	;input2.classList.add('name_v')	;input2.value=jry_wb_manage_bigdeal_data[i].time;
		var td		=document.createElement('td')		;tr.appendChild(td)		;
		var chenge	=document.createElement('button')	;td.appendChild(chenge)	;chenge.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_warn');chenge.innerHTML='修改';
		chenge.onclick=function(event)
		{
			jry_wb_ajax_load_data('jry_wb_manage_bigdeal_do.php?action=chenge',function(data)
			{
				data=JSON.parse(data);
				if(data.code==true)
				{
					jry_wb_beautiful_right_alert.alert('修改成功',2000,'auto','ok');
					jry_wb_manage_bigdeal_load_data(area);
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
				jry_wb_loading_off();
			},[{'name':'name','value':input1.value},{'name':'time','value':input2.value},{'name':'bigdeal_id','value':jry_wb_manage_bigdeal_data[i].bigdeal_id}]);
		};
		var td		=document.createElement('td')		;tr.appendChild(td)		;
		var del		=document.createElement('button')	;td.appendChild(del)	;del.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_error');del.innerHTML='删除'
		del.onclick=function(event)
		{
			jry_wb_ajax_load_data('jry_wb_manage_bigdeal_do.php?action=delete',function(data)
			{
				data=JSON.parse(data);
				if(data.code==true)
				{
					jry_wb_beautiful_right_alert.alert('删除成功',2000,'auto','ok');
					jry_wb_manage_bigdeal_load_data(area);
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
				jry_wb_loading_off();
			},[{'name':'bigdeal_id','value':jry_wb_manage_bigdeal_data[i].bigdeal_id}]);			
		};
		var td		=document.createElement('td')		;tr.appendChild(td)		;
		var enable	=document.createElement('button')	;td.appendChild(enable)	;enable.classList.add('jry_wb_button','jry_wb_button_size_big',(jry_wb_manage_bigdeal_data[i].enable?'jry_wb_color_error':'jry_wb_color_ok'));enable.innerHTML=(jry_wb_manage_bigdeal_data[i].enable?'停用':'启用');
		enable.onclick=function(event)
		{
			jry_wb_ajax_load_data('jry_wb_manage_bigdeal_do.php?action='+(jry_wb_manage_bigdeal_data[i].enable?'disable':'enable'),function(data)
			{
				data=JSON.parse(data);
				if(data.code==true)
				{
					jry_wb_beautiful_right_alert.alert('操作成功',2000,'auto','ok');
					jry_wb_manage_bigdeal_load_data(area);
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
				jry_wb_loading_off();
			},[{'name':'bigdeal_id','value':jry_wb_manage_bigdeal_data[i].bigdeal_id}]);			
		};		
	}
	var tr		=document.createElement('tr')		;all.appendChild(tr)	;
	var td		=document.createElement('td')		;tr.appendChild(td)		;
	let input1	=document.createElement('input')	;td.appendChild(input1)	;input1.classList.add('name_v')	;
	var td		=document.createElement('td')		;tr.appendChild(td)		;
	let input2	=document.createElement('input')	;td.appendChild(input2)	;input2.classList.add('name_v')	;
	var td		=document.createElement('td')		;tr.appendChild(td)		;
	var add		=document.createElement('button')	;td.appendChild(add)	;add.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');add.innerHTML='添加';
	add.onclick=function(event)
	{
		jry_wb_ajax_load_data('jry_wb_manage_bigdeal_do.php?action=add',function(data)
		{
			data=JSON.parse(data);
			if(data.code==true)
			{
				jry_wb_beautiful_right_alert.alert('添加成功',2000,'auto','ok');
				jry_wb_manage_bigdeal_load_data(area);
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
			jry_wb_loading_off();
		},[{'name':'name','value':input1.value},{'name':'time','value':input2.value}]);
	};
	var td		=document.createElement('td')		;tr.appendChild(td)		;td.setAttribute('colspan','2');
	var del		=document.createElement('button')	;td.appendChild(del)	;del.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_error');del.innerHTML='清空';
	del.onclick	=function(event){input1.value=input2.value='';}	
	window.onresize();
}
