function jry_wb_online_judge_manage_function(area)
{
	this.question_list=[];
	this.logs=[];
	this.classes=[];
	this.error=[];
	this.area=area;
	this.show_question_id=0;
	this.loadingcount=0;
	this.do=null;
	this.top_toolbar=document.getElementsByClassName('jry_wb_top_toolbar')[0];
	this.aftersync=function()
	{
		if(this.question_list==null)	this.question_list=[];
		if(this.classes==null)			this.classes=[];
		for(var i=0;i<this.question_list.length;i++)
			this.question_list[i].classes=this.get_classes_by_question(this.question_list[i]);	
		if(typeof this.do=='function')
			this.do();
	};
}
jry_wb_online_judge_manage_function.prototype.sync=function()
{
	this.loadingcount++;
	jry_wb_indexeddb_get_lasttime('oj_classes',(time)=>
	{
		jry_wb_sync_data_with_server('oj_classes',jry_wb_message.jry_wb_host+'jry_wb_online_judge/jry_wb_online_judge_get_information.php?action=classes',null,(data)=>
		{
			this.classes=data;
			this.loadingcount--;
			if(this.loadingcount==0)
				this.aftersync();
			return data.max('lasttime','date');
		},function(a,b){return a.class_id-b.class_id});
	});	
	this.loadingcount++;
	jry_wb_indexeddb_get_lasttime('oj_manage_question_list',(time)=>
	{
		jry_wb_sync_data_with_server('oj_manage_question_list',jry_wb_message.jry_wb_host+'jry_wb_online_judge/jry_wb_online_judge_get_information.php?action=question_list&admin_mode=1',null,(data)=>
		{
			this.question_list=data;
			this.loadingcount--;
			if(this.loadingcount==0)
				this.aftersync();
			return data.max('lasttime','date');
		},function(a,b){return a.class_id-b.class_id});
	});		
};
jry_wb_online_judge_manage_function.prototype.get_classes_by_question=function(question)
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
jry_wb_online_judge_manage_function.prototype.get_class_by_class_id=function(class_id)
{
	return this.classes.find(function(a){return a.class_id==class_id});
};
jry_wb_online_judge_manage_function.prototype.get_word_by_type=function(type)
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
var jry_wb_online_judge_manage=null;
function  jry_wb_online_judge_manage_init(area,callback)
{
	jry_wb_include_once_script(jry_wb_message.jry_wb_host+'jry_wb_online_judge/jry_wb_online_judge_manage_question.js',function()
	{
		if(jry_wb_online_judge_manage==null)
			jry_wb_online_judge_manage=new jry_wb_online_judge_manage_function(area);
		callback(area);
	});	
}
function jry_wb_online_judge_manage_question_init(area)
{
	jry_wb_online_judge_manage_init(area,jry_wb_online_judge_manage_question_run);
}
function jry_wb_online_judge_manage_question_run(area)
{
	jry_wb_online_judge_manage.area=area;
	jry_wb_online_judge_manage.do=jry_wb_online_judge_manage.manage_question;
	jry_wb_online_judge_manage.sync();
}
function jry_wb_online_judge_sync_init(area)
{
	jry_wb_online_judge_manage_init(area,jry_wb_online_judge_sync_run);
}
function jry_wb_online_judge_sync_run(area)
{
	jry_wb_online_judge_manage.sync();
	jry_wb_beautiful_right_alert.alert('已刷新',2000,'auto','ok');
}