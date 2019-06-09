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
	this.left.classList.add('jry_wb_online_judge_all_left_div');
	this.main_dom=document.createElement("div");this.left.appendChild(this.main_dom);
	this.main_dom.classList.add();
	this.tree_dom=document.createElement("div");this.left.appendChild(this.tree_dom);
	this.tree_dom.style.display='none';
	this.right=document.createElement("div");this.area.appendChild(this.right);
	this.right.classList.add('jry_wb_online_judge_all_right_div');
	jry_wb_add_onscroll(()=>
	{
		this.right.style.transform='translate(0px,'+window.scrollY+'px)';
	});
	let ul_type=document.createElement("ul");this.right.appendChild(ul_type);
	ul_type.classList.add('h56');
	ul_type.style.margin='0px';
	let li_type=document.createElement("li");ul_type.appendChild(li_type);
	li_type.innerHTML='类型';
	let ulc_type=document.createElement("ul");ul_type.appendChild(ulc_type);
	li_type.onclick=function(){ulc_type.style.display=ulc_type.style.display==''?'none':'',window.onresize();};
	var li=document.createElement("li");ulc_type.appendChild(li);
	var input=document.createElement("input");li.appendChild(input);input.type='radio';input.name='type';input.onclick=()=>{this.showwhat.question_type=0;};input.setAttribute('checked','checked');
	var h56=document.createElement("h56");li.appendChild(h56);h56.innerHTML='全部';	
	var li=document.createElement("li");ulc_type.appendChild(li);
	var input=document.createElement("input");li.appendChild(input);input.type='radio';input.name='type';input.onclick=()=>{this.showwhat.question_type=1;};
	var h56=document.createElement("h56");li.appendChild(h56);h56.innerHTML='单选';	
	var li=document.createElement("li");ulc_type.appendChild(li);
	var input=document.createElement("input");li.appendChild(input);input.type='radio';input.name='type';input.onclick=()=>{this.showwhat.question_type=2;};
	var h56=document.createElement("h56");li.appendChild(h56);h56.innerHTML='单词';	
	var li=document.createElement("li");ulc_type.appendChild(li);
	var input=document.createElement("input");li.appendChild(input);input.type='radio';input.name='type';input.onclick=()=>{this.showwhat.question_type=3;};
	var h56=document.createElement("h56");li.appendChild(h56);h56.innerHTML='填空';
	var li=document.createElement("li");ulc_type.appendChild(li);
	var input=document.createElement("input");li.appendChild(input);input.type='radio';input.name='type';input.onclick=()=>{this.showwhat.question_type=4;};
	var h56=document.createElement("h56");li.appendChild(h56);h56.innerHTML='C++编译题';	
	let ul_status=document.createElement("ul");this.right.appendChild(ul_status);
	ul_status.classList.add('h56');
	ul_status.style.margin='0px';
	let li_status=document.createElement("li");ul_status.appendChild(li_status);
	li_status.innerHTML='类型';
	let ulc_status=document.createElement("ul");ul_status.appendChild(ulc_status);
	li_status.onclick=function(){ulc_status.style.display=ulc_status.style.display==''?'none':'',window.onresize();};
	var li=document.createElement("li");ulc_status.appendChild(li);
	var input=document.createElement("input");li.appendChild(input);input.type='radio';input.name='status';input.onclick=()=>{this.showwhat.status=0;};input.setAttribute('checked','checked');
	var b=document.createElement("b");li.appendChild(b);b.classList.add('jry_wb_icon','jry_wb_font_normal_size','jry_wb_online_judge_normal','jry_wb_icon_quan'		);
	var li=document.createElement("li");ulc_status.appendChild(li);
	var input=document.createElement("input");li.appendChild(input);input.type='radio';input.name='status';input.onclick=()=>{this.showwhat.status=1;};
	var b=document.createElement("b");li.appendChild(b);b.classList.add('jry_wb_icon','jry_wb_font_normal_size','jry_wb_online_judge_right'	,'jry_wb_icon_duigoux'	);
	var li=document.createElement("li");ulc_status.appendChild(li);
	var input=document.createElement("input");li.appendChild(input);input.type='radio';input.name='status';input.onclick=()=>{this.showwhat.status=2;};
	var b=document.createElement("b");li.appendChild(b);b.classList.add('jry_wb_icon','jry_wb_font_normal_size','jry_wb_online_judge_error'	,'jry_wb_icon_cuowu'	);
	var li=document.createElement("li");ulc_status.appendChild(li);
	var input=document.createElement("input");li.appendChild(input);input.type='radio';input.name='status';input.onclick=()=>{this.showwhat.status=3;};
	var b=document.createElement("b");li.appendChild(b);b.classList.add('jry_wb_icon','jry_wb_font_normal_size','jry_wb_online_judge_nottry'	,'jry_wb_icon_hr'	);	
	let ul_question_id=document.createElement("ul");this.right.appendChild(ul_question_id);
	ul_question_id.style.margin='0px';
	let li_question_id=document.createElement("li");ul_question_id.appendChild(li_question_id);
	var span=document.createElement("span");li_question_id.appendChild(span);
	span.innerHTML='题号';span.classList.add('h56');
	let input_question_id=document.createElement("input");li_question_id.appendChild(input_question_id);
	input_question_id.classList.add('h56');
	input_question_id.style.width='200px';
	input_question_id.onkeyup=()=>
	{
		var buf=parseInt(input_question_id.value);
		if(isNaN(buf))
			this.showwhat.question_id=0;
		else 
			this.showwhat.question_id=parseInt(buf);
	};
	let ul_id=document.createElement("ul");this.right.appendChild(ul_id);
	ul_id.style.margin='0px';
	let li_id=document.createElement("li");ul_id.appendChild(li_id);
	var b=document.createElement("b");li_id.appendChild(b);
	b.classList.add('jry_wb_icon','jry_wb_icon_icon_zhanghao','h56');
	b.style.paddingRight=span.offsetWidth-b.offsetWidth;
	let input_id=document.createElement("input");li_id.appendChild(input_id);
	input_id.classList.add('h56');
	input_id.style.width='200px';
	input_id.onkeyup=()=>
	{
		var buf=parseInt(input_id.value);
		if(isNaN(buf))
			this.showwhat.id=0;
		else 
			this.showwhat.id=parseInt(buf);		
	};	
	let ul_class=document.createElement("ul");this.right.appendChild(ul_class);
	ul_class.style.margin='0px';
	var b=document.createElement("b");ul_class.appendChild(b);
	b.classList.add('jry_wb_icon','jry_wb_icon_biaoqian','h56','jry_wb_font_normal_size');
	this.classes_dom=document.createElement("span");ul_class.appendChild(this.classes_dom);
	this.classes_dom.classList.add('jry_wb_cut');
	b.onclick=()=>
	{
		if(this.main_dom.style.display=='')
			this.main_dom.style.display='none',this.tree_dom.style.display='';
		else
			this.main_dom.style.display='',this.tree_dom.style.display='none';
		this.showwhat.class=this.tree.get_checked();
		for(var i=0;i<this.showwhat.class.length;i++)
			this.showwhat.class[i]=parseInt(this.showwhat.class[i]);
		window.onresize();
	};
	let ul_buttom=document.createElement("ul");this.right.appendChild(ul_buttom);
	let serch=document.createElement('button');ul_buttom.appendChild(serch);
	serch.innerHTML="搜索";
	serch.classList.add('jry_wb_button','jry_wb_button_size_small','jry_wb_color_ok');
	serch.onclick=()=>
	{
		if(this.action=='logs')
			this.show_logs(1);
		else
			this.show_all(1);
	};
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
	this.sync();
}
jry_wb_online_judge_function.prototype.sync=function()
{
	if(this.fastsave.question_list	.to_time()-jry_wb_cache.get_last_time('oj_question_list')	.to_time()>0)	this.loadingcount++,jry_wb_sync_data_with_server('oj_question_list'	,jry_wb_message.jry_wb_host+'jry_wb_online_judge/jry_wb_online_judge_get_information.php?action=question_list&lasttime='+jry_wb_cache.get_last_time('oj_question_list')	,null,function(a){return a.question_id	==this.buf.question_id}	,(data)=>{this.question_list=data;jry_wb_cache.set_last_time('oj_question_list'	,new Date(data.max('lasttime','date')));this.loadingcount--;if(this.loadingcount==0)this.aftersync();},function(a,b){return a.question_id-b.question_id});	else this.question_list	=jry_wb_cache.get('oj_question_list');
	if(this.fastsave.logs			.to_time()-jry_wb_cache.get_last_time('oj_logs')			.to_time()>0)	this.loadingcount++,jry_wb_sync_data_with_server('oj_logs'			,jry_wb_message.jry_wb_host+'jry_wb_online_judge/jry_wb_online_judge_get_information.php?action=logs&lasttime='			+jry_wb_cache.get_last_time('oj_logs')			,null,function(a){return a.log_id		==this.buf.log_id}		,(data)=>{this.logs			=data;jry_wb_cache.set_last_time('oj_logs'			,new Date(data.max('lasttime','date')));this.loadingcount--;if(this.loadingcount==0)this.aftersync();},function(a,b){return b.log_id-a.log_id});			else this.logs			=jry_wb_cache.get('oj_logs');
	if(this.fastsave.classes		.to_time()-jry_wb_cache.get_last_time('oj_classes')			.to_time()>0)	this.loadingcount++,jry_wb_sync_data_with_server('oj_classes'		,jry_wb_message.jry_wb_host+'jry_wb_online_judge/jry_wb_online_judge_get_information.php?action=classes&lasttime='		+jry_wb_cache.get_last_time('oj_classes')		,null,function(a){return a.class_id		==this.buf.class_id}	,(data)=>{this.classes		=data;jry_wb_cache.set_last_time('oj_classes'		,new Date(data.max('lasttime','date')));this.loadingcount--;if(this.loadingcount==0)this.aftersync();});													else this.classes		=jry_wb_cache.get('oj_classes');
	if(jry_wb_login_user.id!=-1)																				this.loadingcount++,jry_wb_sync_data_with_server('oj_error'			,jry_wb_message.jry_wb_host+'jry_wb_online_judge/jry_wb_online_judge_get_information.php?action=error&lasttime='		+jry_wb_cache.get_last_time('oj_error')			,null,function(a){return a.error_id		==this.buf.error_id}	,(data)=>{this.error		=data;jry_wb_cache.set_last_time('oj_error'			,new Date(data.max('lasttime','date')));this.loadingcount--;if(this.loadingcount==0)this.aftersync();});
	if(this.loadingcount==0)
		this.aftersync();
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