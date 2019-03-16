// JavaScript Document
function school_showallschool_function(area)
{
	var data;
	this.body_struct=document.createElement('table');
	this.body_struct.border=2;
	this.body_struct.width='100%';
	area.appendChild(this.body_struct);
	this.dofordata=function(data)
	{
		document.getElementById('__LOAD').style.display='none';
		this.data=JSON.parse(data);	
	}
	this.showone=function(data,chenge)
	{
		var onebody=document.createElement('tr');
		onebody.style.width='100%';
		this.body_struct.appendChild(onebody);
		var td=document.createElement('td');
		td.width='200px';
		onebody.appendChild(td);
		var id=document.createElement('div');
		id.innerHTML=data.school_id;
		id.style.width='100%';
		id.className='h56';
		td.appendChild(id);
		td=null;
		id=null;
		if(chenge)
		{
			var td=document.createElement('td');
			td.width='*';
			onebody.appendChild(td);
			//创建form
			var form=document.createElement("form") ; 
			form.method='post';
			if(data.school_name=='')
				form.action='school_manage_do.php?action=addschool';
			else
				form.action='school_manage_do.php?action=chengeschool&id='+data.school_id;
			td.appendChild(form);
			var name=document.createElement('input');
			name.value=data.school_name;
			name.className='h56';
			name.id='name';
			name.name='name';
			form.appendChild(name);
			name=null;
			var button=document.createElement("input");
			button.type='submit';
			if(data.school_name=='')
				button.value='添加';
			else
				button.value='修改';
			button.className='button button1';
			form.appendChild(button);
			button=null;			
			form=null;
			td=null;
		}
		else
		{
			var td=document.createElement('td');
			td.width='*';
			onebody.appendChild(td);
			var name=document.createElement('div');
			name.innerHTML=data.school_name;
			name.className='h56';
			td.appendChild(name);
			td=null;
			name=null;			
			var td=document.createElement('td');
			td.width='100px';
			onebody.appendChild(td);
			if(typeof data.state=='undefined' ||data.state==0)
			{
				var button=document.createElement("button");
				button.type='button';
				button.innerHTML='申请加入';
				button.className='jry_wb_button jry_wb_button_size_small jry_wb_color_ok';
				button.onclick=function()
				{
					var alerter=new jry_wb_beautiful_alert_function();
					var title=alerter.frame("申请加入"+data.school_name,document.body.clientWidth*0.75,document.body.clientHeight*0.75,document.body.clientWidth*4/32,document.body.clientHeight*4/32);
					var confirm = document.createElement("button"); title.appendChild(confirm);
					confirm.type="button"; 
					confirm.innerHTML="取消"; 
					confirm.style='float:right;margin-right:20px;';
					confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_warn");
					confirm.onclick=function()
					{
						alerter.close();
					};
					var confirm = document.createElement("button"); title.appendChild(confirm);
					confirm.type="button"; 
					confirm.innerHTML="提交"; 
					confirm.style='float:right;margin-right:20px;';
					confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
					jry_wb_beautiful_scroll(alerter.msgObj);
					var table=document.createElement("div"); alerter.msgObj.appendChild(table);
					var tr=document.createElement("tr");table.appendChild(tr);
					var td=document.createElement("td");table.appendChild(td);td.classList.add('h56');
					td.innerHTML='姓名';
					var td=document.createElement("td");table.appendChild(td);td.classList.add('h56');
					var name_input=document.createElement("input");td.appendChild(name_input);name_input.classList.add('h56');
					name_input.value=jry_wb_login_user.name;
					var table=document.createElement("div"); alerter.msgObj.appendChild(table);
					var tr=document.createElement("tr");table.appendChild(tr);
					var td=document.createElement("td");table.appendChild(td);td.classList.add('h56');
					td.innerHTML='性别';
					var td=document.createElement("td");table.appendChild(td);td.classList.add('h56');
					var sex_input=[];
					sex_input[0]=document.createElement("input");td.appendChild(sex_input[0]);
					sex_input[0].name=sex_input[0].id='sex';sex_input[0].classList.add('h56');sex_input[0].type='radio';
					if(jry_wb_login_user.sex==(sex_input[0].value=1)||jry_wb_login_user.sex==2)
						sex_input[0].setAttribute('checked','');
					var h56=document.createElement("h56");td.appendChild(h56);
					h56.innerHTML="男"
					h56=null;
					sex_input[1]=document.createElement("input");td.appendChild(sex_input[1]);
					sex_input[1].name=sex_input[1].id='sex';sex_input[1].classList.add('h56');sex_input[1].type='radio';
					if(jry_wb_login_user.sex==(sex_input[1].value=0))
						sex_input[1].setAttribute('checked','');
					var h56=document.createElement("h56");td.appendChild(h56);
					h56.innerHTML="女"
					h56=null;
					var input_list=[]
					for(var i=0;i<data.enter_config.length;i++)
					{
						var tr=document.createElement("tr");table.appendChild(tr);
						var td=document.createElement("td");table.appendChild(td);td.classList.add('h56');
						td.innerHTML=data.enter_config[i].name;
						var td=document.createElement("td");table.appendChild(td);td.classList.add('h56');
						if(data.enter_config[i].type=='num')
						{
							input_list[input_list.length]=document.createElement("input");td.appendChild(input_list[input_list.length-1]);input_list[input_list.length-1].classList.add('h56');
							input_list[input_list.length-1].id=data.enter_config[i].id;
							if(i==0)
								input_list[input_list.length-1].focus();
							data.enter_config[i].num=input_list.length-1;
						}
					}
					var old_onkeydown=document.onkeydown;
					document.onkeydown=function(e)
					{
						if (!e) 
							e=window.event;
						var keycode=(e.keyCode||e.which);
						if(keycode==jry_wb_keycode_enter)
							confirm.onclick();
						return old_onkeydown();
					};
					confirm.onclick=function()
					{
						var sex=undefined;
						for(var i=0,n=sex_input.length;i<n;i++)
							if(sex_input[i].checked)
								sex=sex_input[i].value;
						var package=[];
						for(let i=0;i<data.enter_config.length;i++)
							if(data.enter_config[i].type=='num')
							{
								if(input_list[data.enter_config[i].num].value=='')
								{
									jry_wb_beautiful_alert.alert('请填写完整信息','缺少'+data.enter_config[i].name,function(){input_list[data.enter_config[i].num].focus();});
									return;
								}
								package[package.length]={'name':data.enter_config[i].id,'value':input_list[data.enter_config[i].num].value};
							}
						jry_wb_ajax_load_data('do_school.php?action=join',(data_)=>
						{
							data_=JSON.parse(data_);
							if(data_.result)
							{
								if(data_.reason==1)
									jry_wb_beautiful_alert.alert("提交成功，正在等待审核","");
								else if(data_.reason==2)
									jry_wb_beautiful_alert.alert("提交成功，已加入","");
								data.state=(data_.reason);
								this.show(false,false);
							}
							else
							{
								if(data_.reason==1)
									jry_wb_beautiful_alert.alert("提交失败，未知学校","");
								else if(data_.reason==2)
									jry_wb_beautiful_alert.alert("提交失败，学校不允许加入","");
								else if(data_.reason==3)
									jry_wb_beautiful_alert.alert("提交失败，已申请了","");
							}
							jry_wb_loading_off();
						},[{'name':'name','value':name_input.value},{'name':'sex','value':sex},{'name':'school_id','value':data.school_id},{'name':'extern','value':JSON.stringify(package)}])
						document.onkeydown=old_onkeydown;
						alerter.close();
					};
				};
			}
			else if(data.state==1)
			{
				
			}
			else if(data.state==2)
			{
				
			}
			td.appendChild(button);
			td=null;
		}
		onebody=null;
	}
	this.show=function(creat,chenge)
	{
		this.body_struct.innerHTML='';
		var onebody=document.createElement('tr');
		onebody.style.width='100%';
		this.body_struct.appendChild(onebody);
		var td=document.createElement('td');
		td.width='200px';
		onebody.appendChild(td);
		var id=document.createElement('div');
		id.innerHTML='ID';
		id.style.width='100%';
		id.className='h56';
		td.appendChild(id);
		id=null;
		td=null;
		var td=document.createElement('td');
		td.width='*';
		onebody.appendChild(td);
		var id=document.createElement('div');
		id.innerHTML='NAME';
		id.style.width='100%';
		id.className='h56';
		td.appendChild(id);
		id=null;
		td=null;
		if(!chenge)
		{
			var td=document.createElement('td');
			td.width='100px';
			onebody.appendChild(td);
			var id=document.createElement('div');
			id.innerHTML='操作';
			id.style.width='100%';
			id.className='h56';
			td.appendChild(id);
			id=null;
			td=null;			
		}
		onebody=null;
		for(var i=0;i<this.data.length;i++)
		{
			this.showone(this.data[i],chenge);
		}
		if(creat&&chenge)
		{
			if(this.data.length!=0)
				var school_id=this.data[this.data.length-1].school_id+1;
			else
				var school_id=1;
			this.showone({school_name:'',school_id:school_id},true);
		}
	}
	
}