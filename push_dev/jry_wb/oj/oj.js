//定义结构体
function oj_function(bufpro,data_source,all_div,tree_div,logs_div,name)
{
	this.logs=new Array();this.questionlist=new Array();this.error=new Array();this.link=new Array();this.list=new Array();
	this.bufpro=bufpro;
	this.data_source=data_source;
	this.all_div=all_div;
	this.tree_div=tree_div;
	this.logs_div=logs_div;
	if(name==null)name='oj';
	this.name=name;
	if(this.tree_div!=null)this.tree_div.style.display='none';
	if(this.logs_div!=null)this.logs_div.style.display='none';
	this.loadingcount=0;
	this.showwhat={type:0,ojclass:0,status:0,id:0,questionid:0};
	this.count=0;
}
//依据题库ID获取题库
oj_function.prototype.get_ojclass_by_classid=function(id)
{
	function get_name(id,data)
	{
		if(data==null)
			return null;
		for(var i=0;i<data.length;i++)
		{
			if(data[i].ojclassid==id)
				return data[i];
			var buf=get_name(id,data[i].children);
			if(buf!=null)
				return buf;
		}
		return null;
	}
	return get_name(id,this.list);
}
//依据题目ID获取提交信息
oj_function.prototype.get_logs_by_questionid=function(questionid)
{
	var data=new Array();
	for(var i=0;i<this.logs.length;i++)
		if(this.logs[i].ojquestionid==questionid&&this.logs[i].id==jry_wb_login_user.id)
			data.push(this.logs[i]);
	data.sort(function (a,b){return b.ojlogid-a.ojlogid});
	return data;
}
//依据题目ID获取全部题库
oj_function.prototype.get_class_by_questionid = function(questionid)
{
	//连接
	var links=new Array();
	for(var i=0;i<this.link.length;i++)
		if(questionid==this.link[i].ojquestionid)
			links.push(this.link[i]);
	//类
	var classs=new Array();
	for(var i=0;i<links.length;i++)
	{
		buf=this.get_ojclass_by_classid(links[i].ojclassid);
		if(buf==null)return null;
		classs.push(buf);
	}	
	return classs;
}
//把JSON数据打包成只有ID的JSON数组
oj_function.prototype.push_class = function(data)
{
	var classbuf=new Array();
	for(var j=0;j<data.length;j++)
		classbuf.push(data[j].ojclassid);		
	return JSON.stringify(classbuf);	
}
//依据题目ID获取题目全部信息
oj_function.prototype.get_by_questionid = function(questionid)
{
	//题目
	var data=this.questionlist.find(function (a){return a.ojquestionid==questionid});				//ERROR
	if(data==null)return null;
	
	data=Object.assign(data,{class:this.get_class_by_questionid(questionid)});
	//记录
	var buf=this.get_logs_by_questionid(questionid);
	if(buf==null)data=Object.assign(data,{logs:null});
	else data=Object.assign(data,{logs:buf});	
	//错题
	if(this.error!=null)
		var buf=this.error.find(function (a){return (a.ojquestionid==questionid)&&(a.id==jry_wb_login_user.id)});
	else
		var buf=null;
	if(buf==null)data=Object.assign(data,{ojerrorid:null});
	else data=Object.assign(data,buf);	
	delete data.children;delete data.father;
	return data;
}
//获取题目类型的中文
oj_function.prototype.get_type=function(type)
{
	if(type==1)
		return '单选';
	else if(type==2)
		return '单词';	
	else if(type==3)
		return '填空';
	else if(type==4)
		return '编译题';		
}
//与后台同步数据
oj_function.prototype.getsync=function()
{
	this.loadingcount+=5;
	jry_wb_sync_data_with_server(this.bufpro+'questionlist',this.data_source+'?action=questionlist&lasttime='+jry_wb_cache.get_last_time(this.bufpro+'questionlist'),null,function(a){return a.ojquestionid		==this.buf.ojquestionid}	,(data) => {this.questionlist=data;	this.loadingcount--;if(this.loadingcount==0) this.aftersync();},function(a,b){return a.ojquestionaddid-b.ojquestionaddid})
	jry_wb_sync_data_with_server(this.bufpro+'link'		,this.data_source+'?action=link&lasttime='+jry_wb_cache.get_last_time(this.bufpro+'link')					,null,function(a){return a.ojquestionid		==this.buf.ojquestionid}	,(data) => {this.link=data;			this.loadingcount--;if(this.loadingcount==0) this.aftersync();})
	jry_wb_sync_data_with_server(this.bufpro+'list'		,this.data_source+'?action=list&lasttime='+jry_wb_cache.get_last_time(this.bufpro+'list')					,null,function(a){return a.ojclassid		==this.buf.ojclassid}		,(data) => {this.list=data;			this.loadingcount--;if(this.loadingcount==0) this.aftersync();})
	jry_wb_sync_data_with_server(this.bufpro+'error'		,this.data_source+'?action=error&lasttime='+jry_wb_cache.get_last_time(this.bufpro+'error')				,null,function(a){return a.ojerrorid		==this.buf.ojerrorid}		,(data) => {this.error=data;		this.loadingcount--;if(this.loadingcount==0) this.aftersync();})
	jry_wb_sync_data_with_server(this.bufpro+'logs'		,this.data_source+'?action=logs&lasttime='+jry_wb_cache.get_last_time(this.bufpro+'logs')					,null,function(a){return a.ojlogid			==this.buf.ojlogid}		,(data) => {this.logs=data;			this.loadingcount--;jry_wb_cache.set_last_time(this.bufpro+'logs',this.logs[0].time);if(this.loadingcount==0)this.aftersync();},function(a,b){return jry_wb_compare_time(b.time,a.time)})
}