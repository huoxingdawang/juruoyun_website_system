function jry_wb_manage_bigdeal_load_data(area)
{
	jry_wb_sync_data_with_server('',"jry_wb_manage_bigdeal_get_information.php?action=list",null,function(a){return a.id==this.buf.id},function(data){jry_wb_manage_bigdeal_data=data;jry_wb_manage_bigdeal_run(area);},function(a,b){return a.id-b.id});
}
function jry_wb_manage_bigdeal_init(area,mode)
{
	jry_wb_manage_bigdeal_load_data(area);
}
function jry_wb_manage_bigdeal_run(area)
{
	area.innerHTML='';
	var all = document.createElement('table');area.appendChild(all);
	var width=area.clientWidth-30;
	all.style.width=width;
	all.border=2;
	var tr = document.createElement('tr');all.appendChild(tr);
	var td = document.createElement('td');tr.appendChild(td);td.classList.add('h55');td.innerHTML='名称';td.align='center';
	var td = document.createElement('td');tr.appendChild(td);td.classList.add('h55');td.innerHTML='时间';td.align='center';
	var td = document.createElement('td');tr.appendChild(td);td.classList.add('h55');td.innerHTML='操作';td.align='center';td.setAttribute('colspan','2');
	for(var i=0,n=jry_wb_manage_bigdeal_data.length;i<n;i++)
	{
		var tr = document.createElement('tr');all.appendChild(tr);
		var td = document.createElement('td');tr.appendChild(td);
		var input1= document.createElement('input');td.appendChild(input1);
		input1.value=jry_wb_manage_bigdeal_data[i].name;
		input1.classList.add('h56');
		input1.name=jry_wb_manage_bigdeal_data[i].id;
		var td = document.createElement('td');tr.appendChild(td);
		var input2= document.createElement('input');td.appendChild(input2);
		input2.value=jry_wb_manage_bigdeal_data[i].time;
		input2.classList.add('h56');
		input2.name=jry_wb_manage_bigdeal_data[i].id;
		var td = document.createElement('td');tr.appendChild(td);
		var chenge= document.createElement('button');td.appendChild(chenge);
		chenge.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_warn');
		chenge.innerHTML='修改';
		chenge.onclick=function(event)
		{
			var input1=event.target.parentNode.parentNode.getElementsByTagName('input')[0];
			var input2=event.target.parentNode.parentNode.getElementsByTagName('input')[1];
			jry_wb_ajax_load_data('jry_wb_manage_bigdeal_do.php?action=chenge',function(data)
			{
				data=JSON.parse(data);
				if(data.code==1)
				{
					jry_wb_beautiful_right_alert.alert('修改成功',2000,'auto','ok');
					jry_wb_manage_bigdeal_load_data(area);
				}
				else
				{
					jry_wb_beautiful_right_alert.alert('修改失败,因为'+data.reasion,2000,'auto','ok');	
				}
				jry_wb_loading_off();
			},[{'name':'name','value':input1.value},{'name':'time','value':input2.value},{'name':'id','value':input1.name}]);
		};
		var td = document.createElement('td');tr.appendChild(td);
		var del= document.createElement('button');td.appendChild(del);
		del.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_error');
		del.innerHTML='删除'
		del.onclick=function(event)
		{
			var input1=event.target.parentNode.parentNode.getElementsByTagName('input')[0];
			var input2=event.target.parentNode.parentNode.getElementsByTagName('input')[1];	
			jry_wb_ajax_load_data('jry_wb_manage_bigdeal_do.php?action=delete',function(data)
			{
				data=JSON.parse(data);
				if(data.code==1)
				{
					jry_wb_beautiful_right_alert.alert('删除成功',2000,'auto','ok');
					jry_wb_manage_bigdeal_load_data(area);
				}
				else
				{
					jry_wb_beautiful_right_alert.alert('删除失败,因为'+data.reasion,2000,'auto','ok');	
				}
				jry_wb_loading_off();
			},[{'name':'id','value':input1.name}]);			
		};
		input1.style.width=(width-chenge.clientWidth-del.clientWidth)*0.9*0.5;
		input2.style.width=(width-chenge.clientWidth-del.clientWidth)*0.9*0.5;
	}
	var tr = document.createElement('tr');all.appendChild(tr);
	var td = document.createElement('td');tr.appendChild(td);
	var input1= document.createElement('input');td.appendChild(input1);
	input1.classList.add('h56');
	var td = document.createElement('td');tr.appendChild(td);
	var input2= document.createElement('input');td.appendChild(input2);
	input2.classList.add('h56');
	var td = document.createElement('td');tr.appendChild(td);
	var add= document.createElement('button');td.appendChild(add);
	add.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');
	add.innerHTML='添加';
	add.onclick=function(event)
	{
		var input1=event.target.parentNode.parentNode.getElementsByTagName('input')[0];
		var input2=event.target.parentNode.parentNode.getElementsByTagName('input')[1];
		jry_wb_ajax_load_data('jry_wb_manage_bigdeal_do.php?action=add',function(data)
		{
			data=JSON.parse(data);
			if(data.code==1)
			{
				jry_wb_beautiful_right_alert.alert('添加成功',2000,'auto','ok');
				jry_wb_manage_bigdeal_load_data(area);
			}
			else
			{
				jry_wb_beautiful_right_alert.alert('添加失败,因为'+data.reasion,2000,'auto','ok');	
			}
			jry_wb_loading_off();
		},[{'name':'name','value':input1.value},{'name':'time','value':input2.value}]);
	};
	var td = document.createElement('td');tr.appendChild(td);
	var del= document.createElement('button');td.appendChild(del);
	del.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_error');
	del.innerHTML='清空'
	del.onclick=function(event)
	{
		var input1=event.target.parentNode.parentNode.getElementsByTagName('input')[0];
		var input2=event.target.parentNode.parentNode.getElementsByTagName('input')[1];
		input1.value=input2.value='';
	}	
	input1.style.width=(width-chenge.clientWidth-del.clientWidth)*0.9*0.5;
	input2.style.width=(width-chenge.clientWidth-del.clientWidth)*0.9*0.5;
	window.onresize();
}
