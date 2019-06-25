/*
							"config"=>			($admin?json_decode($one['config']):NULL),
							"exquestion"=>			($admin?json_decode($one['exquestion']):NULL)
*/
jry_wb_online_judge_manage_function.prototype.manage_question=function()
{
	this.area.innerHTML='';
	var all = document.createElement('div');this.area.appendChild(all);
	all.style.width='100%';
	all.classList.add("jry_wb_left_toolbar");
	var list = document.createElement('div');all.appendChild(list);
	list.classList.add("jry_wb_left_toolbar_left");	
	list.style.width='150px';
	list.style.float='left';
	var show=document.createElement('div');all.appendChild(show);
	show.classList.add("jry_wb_left_toolbar_right");	
	show.style.float='left';
	all.style.height=list.style.height=show.style.height=document.body.clientHeight-((this.top_toolbar==null)?0:this.top_toolbar.clientHeight);
	show.style.position=list.style.position='relative';
	show.style.width=all.clientWidth-list.clientWidth;
	var buf=document.createElement('div');list.appendChild(buf);
	buf.classList.add('jry_wb_left_toolbar_left_list_default');
	buf.innerHTML='刷新';
	buf.onclick=(event)=>{this.sync();};
	var buf=document.createElement('div');list.appendChild(buf);
	buf.classList.add('jry_wb_left_toolbar_left_list_default');
	buf.innerHTML='清空缓存';
	buf.onclick=(event)=>{jry_wb_cache.delete_all();this.sync();};	
	if(jry_wb_login_user.compentence.manageonlinejudgeaddquestion)
	{
		var buf=document.createElement('div');list.appendChild(buf);
		buf.classList.add('jry_wb_left_toolbar_left_list_default');
		buf.innerHTML='新建';
		buf.onclick=(event)=>
		{
			jry_wb_beautiful_alert.check('确定新建？',()=>
			{
				jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_online_judge/jry_wb_online_judge_manage_do.php?action=new',(data)=>
				{
					data=JSON.parse(data);
					jry_wb_loading_off();
					if(data.code)
						this.show_question_id=data.question_id,this.sync(),jry_wb_beautiful_right_alert.alert('新建成功,题号:'+data.question_id,2000,'auto','ok');
					else if(data.reason==100000)
						jry_wb_beautiful_alert.alert("没有登录","","window.location.href=jry_wb_message.jry_wb_host+'jry_wb_mainpages/index.php'");
					else if(data.reason==100001)
						jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=jry_wb_message.jry_wb_host+'jry_wb_mainpages/index.php'");
					else
						jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
				});
			},function(){});
		};
	}
	for(let i=0,n=this.question_list.length;i<n;i++)
	{
		let manage_flag=true;
		for(var j=0,m=this.question_list[i].classes.length;j<m&&manage_flag;j++)
			if(!this.question_list[i].classes[j].manager.includes(jry_wb_login_user.id))
				manage_flag=false;
		if(!manage_flag&&!this.question_list[i].use)
			continue;
		let one=document.createElement('div');list.appendChild(one);
		one.style="text-overflow: ellipsis; overflow:hidden;";
		one.style.width='';
			one.classList.add(('jry_wb_left_toolbar_left_list_'+(i%2+1)));
		one.innerHTML=this.question_list[i].question_id+':'+this.question_list[i].question.slice(0,10);
		one.onclick=(event)=>
		{
			if(this.lasthighlight!=null)
				this.lasthighlight.classList.remove('jry_wb_left_toolbar_left_list_active');
			this.lasthighlight=one;	
			one.classList.add('jry_wb_left_toolbar_left_list_active');
			let question=this.question_list[i];
			this.show_question_id=question.question_id;
			console.log(question);
			show.innerHTML='';
			var table=document.createElement("table");show.appendChild(table);
			table.setAttribute('border',1);table.setAttribute('cellspacing',0);table.setAttribute('cellpadding',0);			
			table.style.width='100%';
			var tr=document.createElement("tr");table.appendChild(tr);
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');td.setAttribute('valign','top');td.width='20%';
			td.innerHTML='题目编号';
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');
			td.innerHTML=question.question_id;
			var tr=document.createElement("tr");table.appendChild(tr);
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');td.setAttribute('valign','top');td.width='20%';
			td.innerHTML='添加人';
			var td=document.createElement("td");tr.appendChild(td);
			jry_wb_get_and_show_user(td,question.id);
			var tr=document.createElement("tr");table.appendChild(tr);
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');td.setAttribute('valign','top');td.width='20%';
			td.innerHTML='使用情况';
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');			
			td.innerHTML=question.use?'使用中':'未使用';
			if(manage_flag)
			{
				var button=document.createElement("button");td.appendChild(button);
				button.classList.add('jry_wb_button','jry_wb_button_size_middle',!question.use?'jry_wb_color_ok':'jry_wb_color_warn');
				button.innerHTML=!question.use?'启用':'禁用';
				button.onclick=()=>
				{
					jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_online_judge/jry_wb_online_judge_manage_do.php?action=set_use',(data)=>
					{
						data=JSON.parse(data);
						jry_wb_loading_off();
						if(data.code)
							this.sync();
						else if(data.reason==100000)
							jry_wb_beautiful_alert.alert("没有登录","","window.location.href=jry_wb_message.jry_wb_host+'jry_wb_mainpages/index.php'");
						else if(data.reason==100001)
							jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=jry_wb_message.jry_wb_host+'jry_wb_mainpages/index.php'");
						else if(data.reason==700001)
							jry_wb_beautiful_alert.alert("不存在的题目",data.extern,function(){});
						else if(data.reason==700002)
							jry_wb_beautiful_alert.alert("您不是这个题的管理员",data.extern,function(){});
						else
							jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
					},[{'name':'question_id','value':question.question_id},{'name':'use','value':(question.use==true?0:1)}]);
				};
			}
			var tr=document.createElement("tr");table.appendChild(tr);
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');td.setAttribute('valign','top');td.width='20%';
			td.innerHTML='标签';
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');
			for(var j=0,m=question.classes.length,span;j<m;j++)
				td.appendChild(span=document.createElement("span")),span.innerHTML=question.classes[j].class_name+';';
			var tr=document.createElement("tr");table.appendChild(tr);
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');td.setAttribute('valign','top');td.width='20%';
			td.innerHTML='类型';
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');
			td.innerHTML=this.get_word_by_type(question.question_type);
			var tr=document.createElement("tr");table.appendChild(tr);
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');td.setAttribute('valign','top');td.width='20%';
			td.innerHTML='最后修改时间';
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');
			td.innerHTML=question.lasttime;
			var tr=document.createElement("tr");table.appendChild(tr);
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');td.setAttribute('valign','top');td.width='20%';
			td.innerHTML='通过/提交/ratio';
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');
			td.innerHTML=question.right+'/'+question.submit+'/'+parseInt(question.right/question.submit*100)+'%';				
			var tr=document.createElement("tr");table.appendChild(tr);
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');td.setAttribute('valign','top');td.width='20%';
			td.innerHTML='来源';
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');
			let source_dom=document.createElement("textarea");td.appendChild(source_dom);source_dom.classList.add('h56');
			source_dom.value=question.source;
			source_dom.style.width='90%';
			source_dom.style.height='100px';
			if(!manage_flag)
				source_dom.setAttribute('readonly','readonly');
			var tr=document.createElement("tr");table.appendChild(tr);
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');td.setAttribute('valign','top');td.width='20%';
			td.innerHTML='题干';
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');
			let question_dom=document.createElement("textarea");td.appendChild(question_dom);question_dom.classList.add('h56');
			question_dom.value=question.question;
			question_dom.style.width='90%';
			question_dom.style.height='200px';
			if(!manage_flag)
				question_dom.setAttribute('readonly','readonly');
			



			var tr=document.createElement("tr");table.appendChild(tr);
			var tdd=document.createElement("td");tr.appendChild(tdd);tdd.classList.add('h56');tdd.setAttribute('valign','top');tdd.width='20%';
			tdd.innerHTML='配置';
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');
			let config_dom=document.createElement("textarea");td.appendChild(config_dom);config_dom.classList.add('h56');
			config_dom.value=JSON.stringify(question.config);
			config_dom.style.width='90%';
			config_dom.style.height='200px';
			if(!manage_flag)
				config_dom.setAttribute('readonly','readonly');
			if(manage_flag)
			{
				tdd.appendChild(document.createElement("br"));
				var button=document.createElement("button");tdd.appendChild(button);
				button.classList.add('jry_wb_button','jry_wb_button_size_middle','jry_wb_color_normal');
				button.innerHTML='预检';
				button.onclick=function()
				{
					try{JSON.parse(config_dom.value);}catch(e){jry_wb_beautiful_alert.alert('配置有错误','','');return;};
					jry_wb_beautiful_right_alert.alert('正常',2000,'auto','ok');
				};
			}			
			
			
			
			var tr=document.createElement("tr");table.appendChild(tr);
			var tdd=document.createElement("td");tr.appendChild(tdd);tdd.classList.add('h56');tdd.setAttribute('valign','top');tdd.width='20%';
			tdd.innerHTML='扩展信息';
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');
			let exdata_dom=document.createElement("textarea");td.appendChild(exdata_dom);exdata_dom.classList.add('h56');
			exdata_dom.value=JSON.stringify(question.exdata);
			exdata_dom.style.width='90%';
			exdata_dom.style.height='200px';
			if(!manage_flag)
				exdata_dom.setAttribute('readonly','readonly');			
			if(manage_flag)
			{
				tdd.appendChild(document.createElement("br"));
				var button=document.createElement("button");tdd.appendChild(button);
				button.classList.add('jry_wb_button','jry_wb_button_size_middle','jry_wb_color_normal');
				button.innerHTML='预检';
				button.onclick=function()
				{
					try{JSON.parse(exdata_dom.value);}catch(e){jry_wb_beautiful_alert.alert('扩展信息有错误','','');return;};
					jry_wb_beautiful_right_alert.alert('正常',2000,'auto','ok');
				};
			}

			
			var tr=document.createElement("tr");table.appendChild(tr);
			var td=document.createElement("td");tr.appendChild(td);td.classList.add('h56');
			td.setAttribute('colspan',2);
			if(manage_flag)
			{
				var button=document.createElement("button");td.appendChild(button);
				button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_ok');
				button.innerHTML='保存';
				button.onclick=()=>
				{
					try{JSON.parse(config_dom.value);}catch(e){jry_wb_beautiful_alert.alert('配置有错误','','');return;};
					try{JSON.parse(exdata_dom.value);}catch(e){jry_wb_beautiful_alert.alert('扩展信息有错误','','');return;};
					jry_wb_ajax_load_data(jry_wb_message.jry_wb_host+'jry_wb_online_judge/jry_wb_online_judge_manage_do.php?action=save',(data)=>
					{
						data=JSON.parse(data);
						jry_wb_loading_off();
						if(data.code)
							this.sync();
						else if(data.reason==100000)
							jry_wb_beautiful_alert.alert("没有登录","","window.location.href=jry_wb_message.jry_wb_host+'jry_wb_mainpages/index.php'");
						else if(data.reason==100001)
							jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=jry_wb_message.jry_wb_host+'jry_wb_mainpages/index.php'");
						else if(data.reason==700001)
							jry_wb_beautiful_alert.alert("不存在的题目",data.extern,function(){});
						else if(data.reason==700002)
							jry_wb_beautiful_alert.alert("您不是这个题的管理员",data.extern,function(){});
						else if(data.reason==700003)
							jry_wb_beautiful_alert.alert("配置有错误",'',function(){});
						else if(data.reason==700004)
							jry_wb_beautiful_alert.alert("扩展信息有错误",data.extern,function(){});						
						else
							jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
					},[{'name':'question_id','value':question.question_id},{'name':'source','value':source_dom.value},{'name':'question','value':question_dom.value},{'name':'config','value':config_dom.value},{'name':'exdata','value':exdata_dom.value}]);
				};
			}			
			var show_scroll=new jry_wb_beautiful_scroll(show);
		};
		if(this.show_question_id==this.question_list[i].question_id)
			one.onclick();
	}
	var list_scroll=new jry_wb_beautiful_scroll(list);
};