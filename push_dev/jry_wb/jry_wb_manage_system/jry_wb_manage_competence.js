function jry_wb_manage_competence_function()
{
}
jry_wb_manage_competence_function.prototype.showall=function()
{
	this.area.innerHTML='';
	jry_wb_include_css('manage/competence');
	var all = document.createElement('table');this.area.appendChild(all);
	all.width='100%';all.height='100%';
	var tr=document.createElement('tr');all.appendChild(tr);
	tr.width='100%';tr.height='100%';
	tr.classList.add("jry_wb_left_toolbar_left");
	var list = document.createElement('td');tr.appendChild(list);
	list.width='100px';list.setAttribute('valign','top');
	
	var one=document.createElement('div');list.appendChild(one);
	one.style.width='100%';
	one.classList.add('jry_wb_left_toolbar_left_list_1');
	one.innerHTML='重载';
	one.onclick=function(event)
	{
		jry_wb_sync_data_with_server('manage_competence',"jry_wb_manage_competence_get_information.php",null,(data)=>
		{
			this.all=data;
			for(var i=0;i<this.all.length;i++)
				for(var k=0,nn=this.all[i].data.length;k<nn;k++)
					if(this.all[i].data[k].name=="competencename")
					{
						this.all[i].name=this.all[i].data[k].value;
						break;
					}
			this.showall();
		},function(a,b){return a.type-b.type});			
	};
	one=null;
	
	var one=document.createElement('div');list.appendChild(one);
	one.style.width='100%';
	one.innerHTML='newg';
	one.classList.add('jry_wb_left_toolbar_left_list_2');
	one.onclick=function()
	{
		jry_wb_beautiful_alert.check('确定？',function(event)
		{
			jry_wb_ajax_load_data('jry_wb_manage_competence_do.php?method=new',function (data)
			{
				jry_wb_loading_off();
				data=JSON.parse(data);
				if(data.code)
					jry_wb_beautiful_right_alert.alert(data.data,5000,'auto','ok');
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
			});
		},function(){});
	};
	one=null;	
	
	var one=document.createElement('div');list.appendChild(one);
	one.style.width='100%';
	one.innerHTML='addc';
	one.classList.add('jry_wb_left_toolbar_left_list_1');
	one.onclick=function()
	{
		jry_wb_beautiful_alert.prompt('请输入新权限名称',function(data)
		{
			jry_wb_ajax_load_data('jry_wb_manage_competence_do.php?method=add&name='+data,function (data)
			{
				jry_wb_loading_off();
				data=JSON.parse(data);
				if(data.code)
					jry_wb_beautiful_right_alert.alert(data.data,5000,'auto','ok');
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
			});
		});
	};
	one=null;
	
	var one=document.createElement('div');list.appendChild(one);
	one.style.width='100%';
	one.innerHTML='del';
	one.classList.add('jry_wb_left_toolbar_left_list_2');
	one.onclick=function()
	{
		jry_wb_beautiful_alert.prompt('请输入删除权限名称',function(data)
		{
			jry_wb_ajax_load_data('jry_wb_manage_competence_do.php?method=delete&name='+data,function (data)
			{
				jry_wb_loading_off();
				data=JSON.parse(data);
				if(data.code)
					jry_wb_beautiful_right_alert.alert(data.data,5000,'auto','ok');
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
			});
		});
	};
	var show = document.createElement('td');tr.appendChild(show);
	show.width='*';show.setAttribute('valign','top');	
	for(let i=0,n=this.all.length;i<n;i++)
	{
		let one=document.createElement('div');list.appendChild(one);
		one.style.width='100%';
		one.classList.add(('jry_wb_left_toolbar_left_list_'+(i%2+1)));
		one.innerHTML=this.all[i].type;
		one.onclick=(event)=>
		{
			if(this.lasthighlight!=null)
				this.lasthighlight.classList.remove('jry_wb_left_toolbar_left_list_active');
			this.lasthighlight=one;	
			one.classList.add('jry_wb_left_toolbar_left_list_active');
			var type=this.all[i].typeL;
			let data=this.all[i];
			if(data.data==null)
				return;
			show.innerHTML='';
			var body=document.createElement('table');show.appendChild(body)	;body.classList.add('jry_wb_manage_competence');
			var tr	=document.createElement('tr')	;body.appendChild(tr)	;
			var td	=document.createElement('td')	;tr.appendChild(td)		;td.classList.add('key')	;td.innerHTML='type';
			var td	=document.createElement('td')	;tr.appendChild(td)		;td.classList.add('value')	;td.innerHTML=data.type;
			for(var j=0,n=data.data.length;j<n;j++)
			{
				var tr	=document.createElement('tr')		;body.appendChild(tr)	;
				var td	=document.createElement('td')		;tr.appendChild(td)		;td.classList.add('key')	;td.innerHTML=data.data[j].name;
				var td	=document.createElement('td')		;tr.appendChild(td)		;td.classList.add('value')	;
				var input=document.createElement('input')	;td.appendChild(input)	;input.id=data.data[j].name	;input.value=data.data[j].value;
				if(data.data[j].name=='color')
				{
					jry_wb_manage_system_color_picker_area=input;
					tr.style.backgroundColor=data.data[j].value;
					color_picker_area.style.backgroundColor=color_picker_value.value=data.data[j].value;
					input.onkeyup=input.onchange=function()
					{
						color_picker_area.style.backgroundColor=color_picker_value.value=this.parentNode.parentNode.style.backgroundColor=this.value; 
					}
				}	
			}
			var tr		=document.createElement('tr')		;body.appendChild(tr)	;
			var td		=document.createElement('td')		;tr.appendChild(td)		;td.setAttribute('colspan','2');
			var button	=document.createElement('button')	;td.appendChild(button)	;button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');button.innerHTML='修改';
			button.onclick=(event)=>
			{
				var all=this.all[i];
				var inputs=document.getElementsByTagName('input');
				var flag=false;
				for(var k=0,n=inputs.length;k<n;k++)
				{	
					var j=all.data.find(function(a){return a.name==inputs[k].id});
					var jj=all.data.indexOf(j);
					if(j!=null)
					{
						if(j.value!=inputs[k].value)
						{	
							if(all.data[jj].name=='competencename'||all.data[jj].name=='color')
								all.data[jj].value=inputs[k].value;
							else
								all.data[jj].value=parseInt(inputs[k].value);
							jry_wb_ajax_load_data('jry_wb_manage_competence_do.php?method=chenge&name='+inputs[k].id+'&value='+inputs[k].value+'&type='+data.type,function(data)
							{
								jry_wb_loading_off();
								data=JSON.parse(data);
								if(data.code)
									jry_wb_beautiful_right_alert.alert(data.data,2000,'auto','ok');
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
							});	
							flag=true;
						}
					}
				}
				if(!flag)
					jry_wb_beautiful_right_alert.alert('没有改变',2000,'auto','warn');
				jry_wb_add_on_indexeddb_open(function(){jry_wb_indexeddb.transaction(['manage_competence'],'readwrite').objectStore('manage_competence').put(all)});
			}
			td=null;
			window.scrollTo(window.scrollX,0);
			window.onresize();
		}
		if(i==0)
			one.onclick();
	}
}
function jry_wb_manage_competence_init(area,mode)
{
	jry_wb_manage_competence=new jry_wb_manage_competence_function();
	jry_wb_manage_competence.area=area;
	jry_wb_sync_data_with_server('manage_competence',"jry_wb_manage_competence_get_information.php",null,(data)=>
	{
		jry_wb_manage_competence.all=data;
		for(var i=0;i<jry_wb_manage_competence.all.length;i++)
			for(var k=0,nn=jry_wb_manage_competence.all[i].data.length;k<nn;k++)
				if(jry_wb_manage_competence.all[i].data[k].name=="competencename")
				{
					jry_wb_manage_competence.all[i].name=jry_wb_manage_competence.all[i].data[k].value;
					break;
				}
		jry_wb_manage_competence.showall();
	},function(a,b){return a.type-b.type});
}
function jry_wb_manage_competence_run(area){jry_wb_manage_competence.area=area;jry_wb_manage_competence.showall();}