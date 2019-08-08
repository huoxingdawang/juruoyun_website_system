function jry_wb_online_judge_function(area,onepage,fastsave)
{
	this.question_list=[];
	this.logs=[];
	this.classes=[];
	this.error=[];
	this.area=area;
	this.loadingcount=0;
	this.showwhat={'question_type':0,'class':[],'status':0,'id':0,'question_id':0};
	this.fastsave=fastsave==undefined?{}:fastsave;
	jry_wb_add_onresize(()=>
	{
		var width=document.documentElement.clientWidth;
		if(width>1000)
			all_width=width-Math.min(width*0.2,width-1000),this.area.style.width=all_width,this.area.style.margin="0px "+(width-all_width)/2+"px";
		else
			this.area.style.width="100%",this.area.style.margin="0px 0px"
	});
	this.aftersync=()=>
	{
		if(this.question_list==null)	this.question_list=[];
		if(this.logs==null)				this.logs=[];
		if(this.classes==null)			this.classes=[];
		if(this.error==null)			this.error=[];
		this.show_class();
		for(var i=0;i<this.question_list.length;i++)
			this.question_list[i].classes=this.get_classes_by_question(this.question_list[i]),this.question_list[i].error=this.get_error_by_question_id(this.question_list[i].question_id);
		for(var i=0;i<this.logs.length;i++)
			this.logs[i].question=this.get_question_by_question_id(this.logs[i].question_id);
		var hash=decodeURI(location.hash.slice(1));
		if(hash=='')
			hash='{}';
		hash=JSON.parse(hash);
		console.log(hash);
		var page=hash.page;
		if(hash.showwhat!=undefined)
			this.showwhat=hash.showwhat;
		if(hash.action!=undefined)
			if((this.action=hash.action)!='logs')
				this.action='ql';
		if(page!=null&&!isNaN(parseInt(page)))
			if(this.action=='logs')
				this.show_logs(parseInt(page));
			else
				this.show_all(parseInt(page));
		else
			if(this.action=='logs')
				this.show_logs(1);
			else
				this.show_all(1);
	};
	this.onepage=((isNaN(onepage))?75:onepage);
	this.area.classList.add('jry_wb_online_judge_all');
	this.left=document.createElement("div");this.area.appendChild(this.left);
	this.left.classList.add('all_left_div');
	this.main_dom=document.createElement("div");this.left.appendChild(this.main_dom);
	this.main_dom.classList.add();
	this.tree_dom=document.createElement("div");this.left.appendChild(this.tree_dom);
	this.tree_dom.style.display='none';
	this.right=document.createElement("div");this.area.appendChild(this.right);
	this.right.classList.add('all_right_div');
	jry_wb_add_onscroll(()=>
	{
		this.right.style.transform='translate(0px,'+window.scrollY+'px)';
	});
	
	var hash=decodeURI(location.hash.slice(1));
	if(hash=='')
		hash='{}';
	hash=JSON.parse(hash);
	console.log(hash);
	var page=hash.page;
	if(hash.showwhat!=undefined)
		this.showwhat=hash.showwhat;
	var show=()=>{if(this.action=='logs')this.show_logs(1);else this.show_all(1);};
	let type_div=document.createElement("div")	;this.right	.appendChild(type_div)	;type_div	.classList.add('type')		;
	let type_w	=document.createElement("div")	;type_div	.appendChild(type_w)	;type_w		.classList.add('w')			;type_w.innerHTML='类型';
	let type_c	=document.createElement("div")	;type_div	.appendChild(type_c)	;type_c		.classList.add('c')			;type_w.onclick=function(){type_c.style.display=type_c.style.display==''?'none':'',window.onresize();};
	var div		=document.createElement("div")	;type_c		.appendChild(div)		;div		.classList.add('all');
	var input	=document.createElement("input");div		.appendChild(input)		;										;input.type='radio';input.name='type';input.onclick=()=>{this.showwhat.question_type=0;show();};if(this.showwhat.question_type==0)input.setAttribute('checked','checked');
	var span	=document.createElement("span")	;div		.appendChild(span)		;										;span.innerHTML='全部';	
	var div		=document.createElement("div")	;type_c		.appendChild(div)		;div		.classList.add('check')		;
	var input	=document.createElement("input");div		.appendChild(input)		;										;input.type='radio';input.name='type';input.onclick=()=>{this.showwhat.question_type=1;show();};if(this.showwhat.question_type==1)input.setAttribute('checked','checked');
	var span	=document.createElement("span")	;div		.appendChild(span)		;										;span.innerHTML='单选';	
	var div		=document.createElement("div")	;type_c		.appendChild(div)		;div		.classList.add('word')		;
	var input	=document.createElement("input");div		.appendChild(input)		;										;input.type='radio';input.name='type';input.onclick=()=>{this.showwhat.question_type=2;show();};if(this.showwhat.question_type==2)input.setAttribute('checked','checked');
	var span	=document.createElement("span")	;div		.appendChild(span)		;										;span.innerHTML='单词';	
	var div		=document.createElement("div")	;type_c		.appendChild(div)		;div		.classList.add('blank')		;
	var input	=document.createElement("input");div		.appendChild(input)		;										;input.type='radio';input.name='type';input.onclick=()=>{this.showwhat.question_type=3;show();};if(this.showwhat.question_type==3)input.setAttribute('checked','checked');
	var span	=document.createElement("span")	;div		.appendChild(span)		;										;span.innerHTML='填空';
	var div		=document.createElement("div")	;type_c		.appendChild(div)		;div		.classList.add('compile')	;
	var input	=document.createElement("input");div		.appendChild(input)		;										;input.type='radio';input.name='type';input.onclick=()=>{this.showwhat.question_type=4;show();};if(this.showwhat.question_type==4)input.setAttribute('checked','checked');
	var span	=document.createElement("span")	;div		.appendChild(span)		;										;span.innerHTML='C++编译题';	

	let status_div	=document.createElement("div")	;this.right	.appendChild(status_div);status_div	.classList.add('status')	;
	let status_w	=document.createElement("div")	;status_div	.appendChild(status_w)	;status_w	.classList.add('w')			;status_w.innerHTML='状态';
	let status_c	=document.createElement("div")	;status_div	.appendChild(status_c)	;status_c	.classList.add('c')			;status_w.onclick=function(){status_c.style.display=status_c.style.display==''?'none':'',window.onresize();};
	var div			=document.createElement("div")	;status_c	.appendChild(div)		;div		.classList.add('normal')	;
	var input		=document.createElement("input");div		.appendChild(input)		;										;input.type='radio';input.name='status';input.onclick=()=>{this.showwhat.status=0;show();};if(this.showwhat.status==0)input.setAttribute('checked','checked');
	var span		=document.createElement("span")	;div		.appendChild(span)		;span		.classList.add('jry_wb_icon','jry_wb_icon_circle');
	var div			=document.createElement("div")	;status_c	.appendChild(div)		;div		.classList.add('right')		;
	var input		=document.createElement("input");div		.appendChild(input)		;										;input.type='radio';input.name='status';input.onclick=()=>{this.showwhat.status=1;show();};if(this.showwhat.status==1)input.setAttribute('checked','checked');
	var span		=document.createElement("span")	;div		.appendChild(span)		;span		.classList.add('jry_wb_icon','jry_wb_icon_ok');
	var div			=document.createElement("div")	;status_c	.appendChild(div)		;div		.classList.add('error')		;
	var input		=document.createElement("input");div		.appendChild(input)		;										;input.type='radio';input.name='status';input.onclick=()=>{this.showwhat.status=2;show();};if(this.showwhat.status==2)input.setAttribute('checked','checked');
	var span		=document.createElement("span")	;div		.appendChild(span)		;span		.classList.add('jry_wb_icon','jry_wb_icon_error');
	var div			=document.createElement("div")	;status_c	.appendChild(div)		;div		.classList.add('nottry')	;
	var input		=document.createElement("input");div		.appendChild(input)		;										;input.type='radio';input.name='status';input.onclick=()=>{this.showwhat.status=3;show();};if(this.showwhat.status==3)input.setAttribute('checked','checked');
	var span		=document.createElement("span")	;div		.appendChild(span)		;span		.classList.add('jry_wb_icon','jry_wb_icon_hr');

	let qiddiv	=document.createElement("div")	;this.right.appendChild(qiddiv)	;qiddiv.classList.add('question_id');
	var span	=document.createElement("span")	;qiddiv.appendChild(span)		;span.innerHTML='题号';
	var inputqid=document.createElement("input");qiddiv.appendChild(inputqid)	;inputqid.value=this.showwhat.question_id;inputqid.onkeyup=()=>{inputqid.value=this.showwhat.question_id=isNaN(parseInt(inputqid.value))?0:parseInt(inputqid.value);show();};
	let iddiv	=document.createElement("div")	;this.right.appendChild(iddiv)	;iddiv.classList.add('user');
	var span	=document.createElement("span")	;iddiv.appendChild(span)		;span.innerHTML='用户';
	var inputid	=document.createElement("input");iddiv.appendChild(inputid)		;inputid.value=this.showwhat.id;inputid.onkeyup=()=>{inputid.value=this.showwhat.id=isNaN(parseInt(inputid.value))?0:parseInt(inputid.value);show();};
		
	
	var div=document.createElement("div");this.right.appendChild(div);div.classList.add('class');
	var span=document.createElement("span");div.appendChild(span);
	span.classList.add('jry_wb_icon','jry_wb_icon_tag','w');
	this.classes_dom=document.createElement("span");div.appendChild(this.classes_dom);this.classes_dom.classList.add('v');
	this.classes_dom.classList.add('jry_wb_cut');
	span.onclick=()=>
	{
		if(this.main_dom.style.display=='')
			this.main_dom.style.display='none',this.tree_dom.style.display='';
		else
			this.main_dom.style.display='',this.tree_dom.style.display='none',show();
		this.showwhat.class=this.tree.get_checked();
		for(var i=0;i<this.showwhat.class.length;i++)
			this.showwhat.class[i]=parseInt(this.showwhat.class[i]);
		window.onresize();
	};
	let ul_buttom=document.createElement("ul");this.right.appendChild(ul_buttom);
	let serch=document.createElement('button');ul_buttom.appendChild(serch);
	serch.innerHTML="搜索";
	serch.classList.add('jry_wb_button','jry_wb_button_size_small','jry_wb_color_ok');
	serch.onclick=show;
	this.hash_prevent=false;
	var top_toolbar_all=document.getElementById('top_toolbar_all');
	if(top_toolbar_all!=undefined)
		top_toolbar_all.onclick=()=>{this.hash_prevent=false;};
	var top_toolbar_logs=document.getElementById('top_toolbar_logs');
	if(top_toolbar_logs!=undefined)
		top_toolbar_logs.onclick=()=>{this.hash_prevent=false;};
	window.onhashchange=(event)=>
	{
		if(this.hash_prevent)
			return this.hash_prevent=false;
		var hash=decodeURI(location.hash.slice(1));
		hash=JSON.parse(hash);
		if(hash.action!=undefined)
			if((this.action=hash.action)!='logs')
				this.action='ql';	
		if(hash.showwhat!=undefined)
			this.showwhat=hash.showwhat;
		if(hash.page!=null&&!isNaN(parseInt(hash.page)))
			if(this.action=='logs')
				this.show_logs(parseInt(hash.page));
			else
				this.show_all(parseInt(hash.page));
		else
			if(this.action=='logs')
				this.show_logs(1);		
			else
				this.show_all(1);
	};
	var top_toolbar_all=document.getElementById('top_toolbar_all');
	if(top_toolbar_all!=undefined)
		top_toolbar_all.onclick=()=>{this.hash_prevent=false;};
	var top_toolbar_logs=document.getElementById('top_toolbar_logs');
	if(top_toolbar_logs!=undefined)
		top_toolbar_logs.onclick=()=>{this.hash_prevent=false;};
	jry_wb_add_on_indexeddb_open(()=>{this.sync()});
}
jry_wb_online_judge_function.prototype.sync=function()
{
	this.loadingcount++;
	jry_wb_indexeddb_get_lasttime('oj_question_list',(time)=>
	{
		if(this.fastsave.question_list.to_time()-time>0)
		{
			jry_wb_sync_data_with_server('oj_question_list',jry_wb_message.jry_wb_host+'jry_wb_online_judge/jry_wb_online_judge_get_information.php?action=question_list',null,(data)=>
			{
				this.question_list=data;
				this.loadingcount--;
				if(this.loadingcount==0)
					this.aftersync();
				return data.max('lasttime','date');
			},function(a,b){return a.question_id-b.question_id});
		}
		else
			jry_wb_indexeddb_get_all('oj_question_list',(data)=>
			{
				this.question_list=data.sort(function(a,b){return a.question_id-b.question_id});
				this.loadingcount--;
				if(this.loadingcount==0)
					this.aftersync();				
			});
	});
	this.loadingcount++;
	jry_wb_indexeddb_get_lasttime('oj_logs',(time)=>
	{
		if(this.fastsave.logs.to_time()-time>0)
		{
			jry_wb_sync_data_with_server('oj_logs',jry_wb_message.jry_wb_host+'jry_wb_online_judge/jry_wb_online_judge_get_information.php?action=logs',null,(data)=>
			{
				this.logs=data;
				this.loadingcount--;
				if(this.loadingcount==0)
					this.aftersync();
				return data.max('lasttime','date');
			},function(a,b){return b.log_id-a.log_id});
		}
		else
			jry_wb_indexeddb_get_all('oj_logs',(data)=>
			{
				this.logs=data.sort(function(a,b){return b.log_id-a.log_id});
				this.loadingcount--;
				if(this.loadingcount==0)
					this.aftersync();				
			});
	});	
	this.loadingcount++;
	jry_wb_indexeddb_get_lasttime('oj_classes',(time)=>
	{
		if(this.fastsave.classes.to_time()-time>0)
		{
			jry_wb_sync_data_with_server('oj_classes',jry_wb_message.jry_wb_host+'jry_wb_online_judge/jry_wb_online_judge_get_information.php?action=classes',null,(data)=>
			{
				this.classes=data;
				this.loadingcount--;
				if(this.loadingcount==0)
					this.aftersync();
				return data.max('lasttime','date');
			},function(a,b){return a.class_id-b.class_id});
		}
		else
			jry_wb_indexeddb_get_all('oj_classes',(data)=>
			{
				this.classes=data;
				this.loadingcount--;
				if(this.loadingcount==0)
					this.aftersync();				
			});
	});	
	if(jry_wb_login_user.id!=-1)	
	{
		this.loadingcount++;
		jry_wb_indexeddb_get_lasttime('oj_error',(time)=>
		{
			if(true)
			{
				jry_wb_sync_data_with_server('oj_error',jry_wb_message.jry_wb_host+'jry_wb_online_judge/jry_wb_online_judge_get_information.php?action=error',null,(data)=>
				{
					this.error=data;
					this.loadingcount--;
					if(this.loadingcount==0)
						this.aftersync();
					return data.max('lasttime','date');
				},function(a,b){return a.error_id-b.error_id});
			}
			else
				jry_wb_indexeddb_get_all('oj_error',(data)=>
				{
					this.error=data;
					this.loadingcount--;
					if(this.loadingcount==0)
						this.aftersync();				
				});
		});
	}
};
jry_wb_online_judge_function.prototype.get_question_by_question_id=function(question_id)
{
	return this.question_list.find(function(a){return a.question_id==question_id});
};
jry_wb_online_judge_function.prototype.get_class_by_class_id=function(class_id)
{
	return this.classes.find(function(a){return a.class_id==class_id});
};
jry_wb_online_judge_function.prototype.get_logs_by_question_id=function(question_id)
{
	if(isNaN(parseInt(jry_wb_login_user.id))||parseInt(jry_wb_login_user.id)<=0)
		return [];
	var data=[];
	for(var i=0;i<this.logs.length;i++)
		if(this.logs[i].question_id==question_id&&this.logs[i].id==jry_wb_login_user.id)
			data.push(this.logs[i]);
	data.sort(function (a,b){return b.log_id-a.log_id});
	return data;
};
jry_wb_online_judge_function.prototype.get_classes_by_question=function(question)
{
	if(question==undefined||question.class==undefined)
		return [];
	if(typeof question.classes!='undefined')
		return question.classes;
	var data=[],buf;
	for(var i=0;i<question.class.length;i++)
		if((buf=this.get_class_by_class_id(question.class[i]))!=undefined)
			data.push(buf);
	return data;
};
jry_wb_online_judge_function.prototype.get_error_by_question_id=function(question_id)
{
	return this.error.find(function(a){return a.question_id==question_id});
};
jry_wb_online_judge_function.prototype.get_word_by_type=function(type)
{
	if(type==1)
		return '单选';
	else if(type==2)
		return '单词';	
	else if(type==3)
		return '填空';
	else if(type==4)
		return '编译题';		
};