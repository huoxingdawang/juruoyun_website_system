function jry_wb_online_judge_manage_question_function(area,bufpro,data_source)
{
	this.arae=area;
	this.bufpro=bufpro;
	this.data_source=data_source;
}
jry_wb_online_judge_manage_question_function.prototype.jry_wb_cache.get=function()
{
	this.loadingcount+=5;
	jry_wb_sync_data_with_server(this.bufpro+'questionlist',this.data_source+'?action=questionlist&lasttime='+jry_wb_cache.get_last_time(this.bufpro+'questionlist')	,null,function(a,b){return a.ojquestionid	==b.ojquestionid}	,(data) => {this.questionlist=data;	this.loadingcount--;jry_wb_cache.set_last_time(this.bufpro+'questionlist'	,this.questionlist	[this.questionlist.length-1].lasttime);	if(this.loadingcount==0) this.aftersync();},function(a,b){return a.ojquestionaddid-b.ojquestionaddid})
	jry_wb_sync_data_with_server(this.bufpro+'link'		,this.data_source+'?action=link&lasttime='+jry_wb_cache.get_last_time(this.bufpro+'link')					,null,function(a,b){return a.ojquestionid	==b.ojquestionid}	,(data) => {this.link=data;			this.loadingcount--;jry_wb_cache.set_last_time(this.bufpro+'link'			,this.link			[this.link.length-1]		.lasttime);	if(this.loadingcount==0) this.aftersync();})
	jry_wb_sync_data_with_server(this.bufpro+'list'		,this.data_source+'?action=list&lasttime='+jry_wb_cache.get_last_time(this.bufpro+'list')					,null,function(a,b){return a.ojclassid		==b.ojclassid}		,(data) => {this.list=data;			this.loadingcount--;jry_wb_cache.set_last_time(this.bufpro+'list'			,this.list			[this.list.length-1]		.lasttime);	if(this.loadingcount==0) this.aftersync();})
	jry_wb_sync_data_with_server(this.bufpro+'manager'		,this.data_source+'?action=manager&lasttime='+jry_wb_cache.get_last_time(this.bufpro+'manager')			,null,function(a,b){return a.ojclassid		==b.ojclassid}		,(data) => {this.manager=data;		this.loadingcount--;jry_wb_cache.set_last_time(this.bufpro+'manager'		,this.manager		[this.manager.length-1]		.lasttime);	if(this.loadingcount==0) this.aftersync();},function(a,b){return b.ojlogid-a.ojlogid})
}
var myscript = document.createElement('script');document.body.appendChild(myscript);
myscript.src = '../jry_wb_online_judge/jry_wb_online_judge.js';
myscript.type = 'text/javascript';
myscript.defer = true;
myscript.onreadystatechange=myscript.onload = 
function() 
{
	jry_wb_online_judge_manage_question_function.prototype.get_type					=jry_wb_online_judge_function.prototype.get_type;
	jry_wb_online_judge_manage_question_function.prototype.get_jry_wb_online_judgeclass_by_classid	=jry_wb_online_judge_function.prototype.get_jry_wb_online_judgeclass_by_classid;
	jry_wb_online_judge_manage_question_function.prototype.get_class_by_questionid	=jry_wb_online_judge_function.prototype.get_class_by_questionid;
	
	
	
	jry_wb_online_judge_manage_question=new jry_wb_online_judge_manage_question_function(document.getElementById('show'),'jry_wb_online_judge_','../jry_wb_online_judge/jry_wb_online_judge_getinformation.php')
	jry_wb_online_judge_manage_question.jry_wb_cache.get();
}
function jry_wb_online_judge_manage_question_run()
{
	
}