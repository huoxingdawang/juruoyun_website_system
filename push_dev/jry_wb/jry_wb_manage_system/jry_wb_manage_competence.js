function jry_wb_manage_competence_function()
{
}
jry_wb_manage_competence_function.prototype.doforsync=function(data)
{
	var buf=JSON.parse(data);
	if(buf!=null)
		if(buf.login==false)
		{
			jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
			return ;	
		}	
	data=buf;
	data.sort(function (a,b){return a.type-b.type;});
	jry_wb_cache.set('competence',data);
	this.all=data;
	for(var i=0;i<this.all.length;i++)
		for(var k=0,nn=this.all[i].data.length;k<nn;k++)
			if(this.all[i].data[k].name=="competencename")
			{
				this.all[i].name=this.all[i].data[k].value;
				break;
			}		
	jry_wb_loading_off();	
}
jry_wb_manage_competence_function.prototype.showall=function()
{
	this.area.innerHTML='';
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
	one.onclick=function(event){jry_wb_ajax_load_data('jry_wb_manage_competence_get_information.php',function (data)
	{
		jry_wb_manage_competence.doforsync(data);
		jry_wb_manage_competence.showall();
	});}
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
	one=null;	
	for(var i=0,n=this.all.length;i<n;i++)
	{
		var one=document.createElement('div');list.appendChild(one);
		one.style.width='100%';
		one.classList.add(('jry_wb_left_toolbar_left_list_'+(i%2+1)));
		one.innerHTML=this.all[i].type;
		one.onclick=(event)=>
		{
			if(this.lasthighlight!=null)
				this.lasthighlight.classList.remove('jry_wb_left_toolbar_left_list_active');
			this.lasthighlight=event.target;	
			event.target.classList.add('jry_wb_left_toolbar_left_list_active');
			var type=event.target.innerHTML;
			var all=jry_wb_cache.get('competence');	
			var data=null;
			var show=event.target.parentNode.nextElementSibling;
			for(var i=0,n=all.length;i<n;i++)
				if(all[i]!=null)
					if(all[i].type==type)
						data=all[i];
			if(data.data==null)
				return;
			all=null;type=null;
			show.innerHTML='';
			all = document.createElement('table');show.appendChild(all);
			all.setAttribute('border','1');
			var tr=document.createElement('tr');all.appendChild(tr);
			tr.width='100%';
			var td=document.createElement('td');tr.appendChild(td);
			td.width='30%';td.classList.add('h56');
			td.innerHTML='type';
			td=null;
			var td=document.createElement('td');tr.appendChild(td);
			td.width='30%';td.classList.add('h56');
			td.innerHTML=data.type;
			td=null;		
			for(var i=0,n=data.data.length;i<n;i++)
			{
				var tr=document.createElement('tr');all.appendChild(tr);
				tr.width='100%';
				var td1=document.createElement('td');tr.appendChild(td1);
				td1.width='30%';td1.classList.add('h56');
				td1.innerHTML=data.data[i].name;
				var td=document.createElement('td');tr.appendChild(td);
				td.width='*';
				var input=document.createElement('input');td.appendChild(input);
				input.id=data.data[i].name;
				input.classList.add('h56');
				input.value=data.data[i].value;
				if(data.data[i].name=='color')
				{
					jry_wb_manage_system_color_picker_area=input;
					tr.style.backgroundColor=data.data[i].value;
					color_picker_area.style.backgroundColor=color_picker_value.value=data.data[i].value;
					input.onkeyup=input.onchange=function()
					{
						color_picker_area.style.backgroundColor=color_picker_value.value=this.parentNode.parentNode.style.backgroundColor=this.value; 
					}
				}	
			}
			var tr=document.createElement('tr');all.appendChild(tr);
			tr.width='100%';
			var td=document.createElement('td');tr.appendChild(td);
			td.setAttribute('colspan','2');td.align='center';
			var button=document.createElement('button');td.appendChild(button);
			button.id=data.type;
			button.innerHTML='修改';
			button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');
			button.onclick=function (event)
			{
				var type=event.target.id;
				var all_=jry_wb_cache.get('competence');
				var all=all_.find(function(a){return a.type==type});
				var all_id=all_.indexOf(all);
				var inputs=document.getElementsByTagName('input');
				var flag=false;
				for(var i=0,n=inputs.length;i<n;i++)
				{	
					var j=all.data.find(function(a){return a.name==inputs[i].id});
					var jj=all.data.indexOf(j);
					if(j!=null)
					{
						if(j.value!=inputs[i].value)
						{	
							if(all.data[jj].name=='competencename'||all.data[jj].name=='color')
								all.data[jj].value=inputs[i].value;
							else
								all.data[jj].value=parseInt(inputs[i].value);
							jry_wb_ajax_load_data('jry_wb_manage_competence_do.php?method=chenge&name='+inputs[i].id+'&value='+inputs[i].value+'&type='+type,function(data)
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
				all_.splice(all_id,1,all)
				jry_wb_cache.set('competence',all_);			
			}
			td=null;
			window.scrollTo(window.scrollX,0);
			window.onresize();
		}
		window.onresize();
	}
	var one = document.createElement('td');tr.appendChild(one);
	one.width='*';one.setAttribute('valign','top');	
}
function jry_wb_manage_competence_init(area,mode)
{
	jry_wb_manage_competence=new jry_wb_manage_competence_function();
	jry_wb_manage_competence.area=area;
	jry_wb_ajax_load_data('jry_wb_manage_competence_get_information.php',function (data){jry_wb_manage_competence.doforsync(data);jry_wb_manage_competence.showall();});	
}
function jry_wb_manage_competence_run(area){jry_wb_manage_competence.area=area;jry_wb_manage_competence.showall();}