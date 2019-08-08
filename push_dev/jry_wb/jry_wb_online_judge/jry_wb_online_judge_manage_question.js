jry_wb_online_judge_manage_function.prototype.manage_question=function()
{
	this.area.innerHTML='';
	jry_wb_include_css('online_judge/manage_question');
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
	var addbutton=document.createElement('div');list.appendChild(addbutton);
	if(jry_wb_login_user.compentence.manageonlinejudgeaddquestion)
	{
		addbutton.classList.add('jry_wb_left_toolbar_left_list_default');
		addbutton.innerHTML='新建';
		addbutton.onclick=(event)=>
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
	jry_wb_set_shortcut(jry_wb_keycode_right,()=>{this.question_list[0].onclick();});
	this.show_question_id=1;
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
		this.question_list[i].onclick=one.onclick=(event)=>
		{
			if(this.list_scroll!=undefined)
				this.list_scroll.scrollto(0,one.offsetTop-addbutton.offsetTop-((document.body.clientHeight-((this.top_toolbar==null)?0:this.top_toolbar.clientHeight))/2));				
			if(i!=n-1)
				jry_wb_set_shortcut(jry_wb_keycode_right,()=>{this.question_list[i+1].onclick();});
			if(i!=0)
				jry_wb_set_shortcut(jry_wb_keycode_left,()=>{this.question_list[i-1].onclick();});
			if(this.lasthighlight!=null)
				this.lasthighlight.classList.remove('jry_wb_left_toolbar_left_list_active');
			this.lasthighlight=one;	
			one.classList.add('jry_wb_left_toolbar_left_list_active');
			let question=this.question_list[i];
			this.show_question_id=question.question_id;
			console.log(question);
			show.innerHTML='';
			var table	=document.createElement("table");show.appendChild(table);table.classList.add('jry_wb_online_judge_manage_question');
			var tr	=document.createElement("tr")	;table.appendChild(tr)	;
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('qid')	;td.innerHTML='题目编号';
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('qid_v')	;td.innerHTML=question.question_id;
			var tr	=document.createElement("tr")	;table.appendChild(tr)	;
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('id')		;td.innerHTML='添加人';
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('id_v')	;jry_wb_get_and_show_user(td,question.id);
			var tr	=document.createElement("tr")	;table.appendChild(tr)	;
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('use')	;td.innerHTML='使用情况';
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('use_v')	;td.innerHTML=question.use?'使用中':'未使用';
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
			var tr	=document.createElement("tr")	;table.appendChild(tr)	;
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('tag')	;td.innerHTML='标签<span>右键删除</span>';td.setAttribute('valign','top');
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('tag_v')	;
			let class_doms=undefined;
			if(manage_flag)
			{
				class_doms=td;
				for(let j=0,m=question.classes.length,span;j<m;j++)
				{
					let class_dom	=document.createElement("select");td		.appendChild(class_dom)	;
					var option		=document.createElement("option");class_dom	.appendChild(option)	;option.innerHTML=question.classes[j].class_name;option.value=question.classes[j].class_id;
					if(!question.classes[j].manager.includes(jry_wb_login_user.id))
						option.setAttribute('disabled','disabled');
					else
					{
						class_dom.oncontextmenu=function()
						{
							jry_wb_beautiful_alert.check('确定删除标签"'+class_dom.value+'"吗?',()=>
							{
								class_dom.parentNode.removeChild(class_dom);
							},function(){});
							return false;
						}
					}
				}
				let add_class_button=document.createElement("button");td.appendChild(add_class_button);
				add_class_button.classList.add('jry_wb_button','jry_wb_button_size_small','jry_wb_color_normal','jry_wb_icon','jry_wb_icon_new');
				add_class_button.onclick=()=>
				{
					let class_dom=document.createElement("select");add_class_button.parentNode.insertBefore(class_dom,add_class_button);class_dom.classList.add('class');
					for(let j=0;j<this.classes.length;j++)
						if(this.classes[j].manager.includes(jry_wb_login_user.id))
						{
							var option=document.createElement("option");class_dom.appendChild(option);option.innerHTML=this.classes[j].class_name;option.value=this.classes[j].class_id;
							class_dom.oncontextmenu=()=>
							{
								jry_wb_beautiful_alert.check('确定删除标签"'+this.classes.find(function(a){return a.class_id==class_dom.value}).class_name+'"吗?',()=>
								{
									class_dom.parentNode.removeChild(class_dom);
								},function(){});
								return false;
							}
						}
					
				};				
			}
			else
				for(var j=0,m=question.classes.length,span;j<m;j++)
					td.appendChild(span=document.createElement("span")),span.innerHTML=question.classes[j].class_name+';';
			var tr	=document.createElement("tr")	;table.appendChild(tr)	;
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('type')	;td.innerHTML='类型';
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('type_v')	;
			let type_dom=undefined;
			if(manage_flag)
			{
				type_dom=document.createElement("select");td.appendChild(type_dom);
				for(var j=1;this.get_word_by_type(j)!=undefined;j++)
				{
					var option=document.createElement("option");type_dom.appendChild(option);
					option.innerHTML=this.get_word_by_type(j);
					option.value=j;
					if(j==question.question_type)
						option.setAttribute('selected','selected');
				}
			}
			else
				td.innerHTML=this.get_word_by_type(question.question_type);
			var tr	=document.createElement("tr")	;table.appendChild(tr)	;
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('lasttime')	;td.innerHTML='最后修改时间';
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('lasttime_v')	;td.innerHTML=question.lasttime;
			var tr	=document.createElement("tr")	;table.appendChild(tr)	;
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('data')		;
			var span=document.createElement("span")	;td.appendChild(span)	;span.classList.add('pass')		;span.innerHTML='通过';
			var span=document.createElement("span")	;td.appendChild(span)	;span.classList.add('submit')	;span.innerHTML='提交';
			var span=document.createElement("span")	;td.appendChild(span)	;span.classList.add('ratio')	;span.innerHTML='比例';
			var td	=document.createElement("td");tr.appendChild(td)			;td.classList.add('data_v');
			var span=document.createElement("span")	;td.appendChild(span)	;span.classList.add('pass')		;span.innerHTML=question.right;
			var span=document.createElement("span")	;td.appendChild(span)	;span.classList.add('submit')	;span.innerHTML=question.submit;
			var span=document.createElement("span")	;td.appendChild(span)	;span.classList.add('ratio')	;span.innerHTML=(isNaN(question.right/question.submit)?'100':parseInt(question.right/question.submit*100))+'%';				
			var tr	=document.createElement("tr")	;table.appendChild(tr)	;
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('sorce')		;td.innerHTML='来源';
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('sorce_v')	;
			let source_dom=document.createElement("textarea");td.appendChild(source_dom);source_dom.value=question.source;
			if(!manage_flag)source_dom.setAttribute('readonly','readonly');
			var tr	=document.createElement("tr")	;table.appendChild(tr)	;
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('question')	;td.innerHTML='题干';
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('question_v')	;
			let question_dom=document.createElement("textarea");td.appendChild(question_dom);question_dom.value=question.question;
			if(!manage_flag)question_dom.setAttribute('readonly','readonly');
			var tr	=document.createElement("tr")	;table.appendChild(tr)	;
			var tdd	=document.createElement("td")	;tr.appendChild(tdd)	;tdd.classList.add('config')	;tdd.innerHTML='配置';
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('config_v')	;
			let config_dom=document.createElement("textarea");td.appendChild(config_dom);config_dom.value=JSON.stringify(question.config);
			if(!manage_flag)config_dom.setAttribute('readonly','readonly');
			if(manage_flag)
			{
				tdd.appendChild(document.createElement("br"));
				var button=document.createElement("button");tdd.appendChild(button);
				button.classList.add('jry_wb_button','jry_wb_button_size_middle','jry_wb_color_normal');
				button.innerHTML='预检';
				button.onclick=function()
				{
					try{JSON.parse(config_dom.value);}catch(e){jry_wb_beautiful_alert.alert('配置有错误','','');return;};
					jry_wb_beautiful_right_alert.alert('配置正常',2000,'auto','ok');
				};
			}
			var tr	=document.createElement("tr")	;table.appendChild(tr)	;
			var tdd	=document.createElement("td")	;tr.appendChild(tdd)	;tdd.classList.add('extern')	;tdd.innerHTML='扩展信息';
			var td	=document.createElement("td")	;tr.appendChild(td)		;td.classList.add('extern_v')	;
			let exdata_dom=document.createElement("textarea");td.appendChild(exdata_dom);exdata_dom.value=JSON.stringify(question.exdata);
			if(!manage_flag)exdata_dom.setAttribute('readonly','readonly');			
			if(manage_flag)
			{
				tdd.appendChild(document.createElement("br"));
				var button=document.createElement("button");tdd.appendChild(button);
				button.classList.add('jry_wb_button','jry_wb_button_size_middle','jry_wb_color_normal');
				button.innerHTML='预检';
				button.onclick=function()
				{
					try{JSON.parse(exdata_dom.value);}catch(e){jry_wb_beautiful_alert.alert('扩展信息有错误','','');return;};
					jry_wb_beautiful_right_alert.alert('扩展信息正常',2000,'auto','ok');
				};
			}
			var tr=document.createElement("tr");table.appendChild(tr);
			var td=document.createElement("td");tr.appendChild(td);
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
					if(class_doms!=undefined)
					{
						var classes=[];
						for(var j=0;j<class_doms.children.length;j++)
							if(class_doms.children[j].tagName=='SELECT')
								classes.push(parseInt(class_doms.children[j].value));
					}
					else
						classes=question.class;
					classes=classes.unique();
					console.log(classes);
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
							jry_wb_beautiful_alert.alert("您不是这个题的管理员",'题号:'+data.extern+'<br>或您添加或删除不属于您管理的标签',function(){});
						else if(data.reason==700003)
							jry_wb_beautiful_alert.alert("配置有错误",'',function(){});
						else if(data.reason==700004)
							jry_wb_beautiful_alert.alert("扩展信息有错误",data.extern,function(){});	
						else if(data.reason==700005)
							jry_wb_beautiful_alert.alert("类型异常",data.extern,function(){});	
						else if(data.reason==700006)
							jry_wb_beautiful_alert.alert("标签异常",data.extern,function(){});						
						else
							jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
					},[{'name':'question_id','value':question.question_id},{'name':'question_type','value':((typeof type_dom=='undefined')?question.question_type:type_dom.value)},{'name':'source','value':source_dom.value},{'name':'class','value':JSON.stringify(classes)},{'name':'question','value':question_dom.value},{'name':'config','value':config_dom.value},{'name':'exdata','value':exdata_dom.value}]);
				};
				var button=document.createElement("button");td.appendChild(button);
				button.classList.add('jry_wb_button','jry_wb_button_size_big','jry_wb_color_warn');
				button.innerHTML='放弃';
				button.onclick=()=>
				{
					one.onclick();
				};
			}			
			var show_scroll=new jry_wb_beautiful_scroll(show);
		};
		if(this.show_question_id==this.question_list[i].question_id)
			one.onclick();
	}
	this.list_scroll=new jry_wb_beautiful_scroll(list);
};