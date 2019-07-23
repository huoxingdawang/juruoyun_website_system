function jry_wb_manage_tanmu_load_data(area)
{
	jry_wb_sync_data_with_server('manage_tanmu',"jry_wb_manage_tanmu_get_information.php?action=list",null,function(data)
	{
		jry_wb_add_on_indexeddb_open(function()
		{
			jry_wb_manage_tanmu_data=data;
			var re=jry_wb_indexeddb.transaction(['manage_tanmu'],'readwrite').objectStore('manage_tanmu');
			for(var i=0;i<jry_wb_manage_tanmu_data.length;i++)
				if(jry_wb_manage_tanmu_data[i].delete)
					re.delete(jry_wb_manage_tanmu_data[i].tanmu_id),jry_wb_manage_tanmu_data.splice(i,1),i--;
			jry_wb_manage_tanmu_run(area);
		});
	},function(a,b){return a.tanmu_id-b.tanmu_id});
}
function jry_wb_manage_tanmu_init(area,mode)
{
	jry_wb_manage_tanmu_load_data(area);
}
function jry_wb_manage_tanmu_run(area)
{
	area.innerHTML='';
	var all = document.createElement('table');area.appendChild(all);
	var width=area.clientWidth-30;
	all.style.width=width;
	all.border=2;
	var tr = document.createElement('tr');all.appendChild(tr);
	var td = document.createElement('td');tr.appendChild(td);td.classList.add('h55');td.innerHTML='弹幕';td.align='center';
	var td = document.createElement('td');tr.appendChild(td);td.classList.add('h55');td.innerHTML='操作';td.align='center';td.setAttribute('colspan','2');
	for(var i=0,n=jry_wb_manage_tanmu_data.length;i<n;i++)
	{
		var tr = document.createElement('tr');all.appendChild(tr);
		var td = document.createElement('td');tr.appendChild(td);
		var input= document.createElement('input');td.appendChild(input);
		input.value=jry_wb_manage_tanmu_data[i].words;
		input.classList.add('h56');
		input.name=jry_wb_manage_tanmu_data[i].tanmu_id
		var td = document.createElement('td');tr.appendChild(td);
		var chenge= document.createElement('button');td.appendChild(chenge);
		chenge.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_warn');
		chenge.innerHTML='修改';
		chenge.onclick=function(event)
		{
			var input=event.target.parentNode.parentNode.getElementsByTagName('input')[0];
			jry_wb_ajax_load_data('jry_wb_manage_tanmu_do.php?action=chenge',function(data)
			{
				data=JSON.parse(data);
				if(data.code==1)
				{
					jry_wb_beautiful_right_alert.alert('修改成功',2000,'auto','ok');
					jry_wb_manage_tanmu_load_data(area);
				}
				else
				{
					jry_wb_beautiful_right_alert.alert('修改失败,因为'+data.reasion,2000,'auto','ok');	
				}
				jry_wb_loading_off();
			},[{'name':'words','value':input.value},{'name':'tanmu_id','value':input.name}]);
		};
		var td = document.createElement('td');tr.appendChild(td);
		var del= document.createElement('button');td.appendChild(del);
		del.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_error');
		del.innerHTML='删除'
		del.onclick=function(event)
		{
			var input=event.target.parentNode.parentNode.getElementsByTagName('input')[0];
			jry_wb_ajax_load_data('jry_wb_manage_tanmu_do.php?action=delete',function(data)
			{
				data=JSON.parse(data);
				if(data.code==1)
				{
					jry_wb_beautiful_right_alert.alert('删除成功',2000,'auto','ok');
					jry_wb_manage_tanmu_load_data(area);
				}
				else
				{
					jry_wb_beautiful_right_alert.alert('删除失败,因为'+data.reasion,2000,'auto','ok');	
				}
				jry_wb_loading_off();
			},[{'name':'tanmu_id','value':input.name}]);			
		};
		input.style.width=(width-chenge.clientWidth-del.clientWidth)*0.9;
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
		jry_wb_ajax_load_data('jry_wb_manage_tanmu_do.php?action=add',function(data)
		{
			data=JSON.parse(data);
			if(data.code==1)
			{
				jry_wb_beautiful_right_alert.alert('添加成功',2000,'auto','ok');
				jry_wb_manage_tanmu_load_data(area);
			}
			else
			{
				jry_wb_beautiful_right_alert.alert('添加失败,因为'+data.reasion,2000,'auto','ok');	
			}
			jry_wb_loading_off();
		},[{'name':'words','value':input.value}]);
	};
	var td = document.createElement('td');tr.appendChild(td);
	var del= document.createElement('button');td.appendChild(del);
	del.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_error');
	del.innerHTML='清空'
	del.onclick=function(event)
	{
		var input=event.target.parentNode.parentNode.getElementsByTagName('input')[0];		
		input.value='';
	}	
	input.style.width=(width-chenge.clientWidth-del.clientWidth)*0.9;
	window.onresize();
}
