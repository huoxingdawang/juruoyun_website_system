//此文档依赖缓存
oj_function.prototype.showlogs=function(onepage,page)
{
	if(this.logs_div==null)
		return;
	this.logs_div.innerHTML='';
	this.count=0;	
	function __forstatus(showwhat,data)
	{
		if(showwhat.status==0)
			return true;
		else if(showwhat.status==2&&data.result=='right')
			return true;		
		else if(showwhat.status==1&&data.result=='error')
			return true;
		else if(showwhat.status==3&&data.result!='error'&&data.result!='right')
			return true;
		else return false;
	}
	function __forclass(checked,data)
	{
		if(checked.length==0)
			return true;
		for(var i=0,n=data.length;i<n;i++)
			if(checked.indexOf(data[i].ojclassid)!=-1)
				return true;
		return false;
	}
	if(this.tree==null)
		var checked=[];
	else
		var checked=this.tree.get_checked();
	for(var i=0;i<checked.length;i++)
		checked[i]=parseInt(checked[i]);
	var show=new Array();
	//筛出来
	for(var i=0,n=this.logs.length;i<n;i++)
	{
		var data=this.logs[i];
		var __class=this.get_class_by_questionid(data.ojquestionid); 	
		if(	__forstatus(this.showwhat,data)&&__forclass(checked,__class)
			&&(data.ojquestionid==this.showwhat.questionid||this.showwhat.questionid==0)
			&&(data.id==this.showwhat.id||this.showwhat.id==0))
		{
			show.push(i);
			this.count++;
		}
	}
	
	var from=onepage*(page-1);
	var to=onepage*page;
	var pages=Math.ceil(show.length/onepage);
	page=Math.max(1,Math.min(page,pages));
	location.hash='#'+JSON.stringify({'page':page});	
	for(var i=from,n=Math.min(show.length,to);i<n;i++)
	{
		var data=this.logs[show[i]];
		var __class=this.get_class_by_questionid(data.ojquestionid); 	
		var classbuf=this.push_class(__class);
		var onebody=document.createElement("div") ;this.logs_div.appendChild(onebody);	
		onebody.className='oj_one_body';
		//id
		var id=document.createElement("div");
		id.className='oj_one_id h56';
		id.innerHTML='#'+(Array(5).join('0') + parseInt(data.ojlogid)).slice(-5);
		onebody.appendChild(id);
		id=null;
		//做题人
		jry_wb_get_and_show_user(onebody,data.id,null,'left');
		var result=document.createElement("div");onebody.appendChild(result);
		result.className='jry_wb_word_cut h56 oj_one_ans';
		if(data.result=='error')
		{
			var icon2=document.createElement("b");result.appendChild(icon2);
			icon2.className="iconfont icon-cuowu oj_error";
		}else if(data.result=='right')
		{
			var icon1=document.createElement("b");result.appendChild(icon1);
			icon1.className="iconfont icon-duigoux oj_right";
		}else
			result.innerHTML+=data.result;
		result.innerHTML+=data.logans;
		//体干
		var question=document.createElement("div");
		question.className='h56 jry_wb_word_cut oj_one_question'
		question.setAttribute('onClick',"window.location.href='oj_showquestion.php?ojclassid="+classbuf+'#{"ojquestionid":"'+data.ojquestionid+'"}'+"'");
		question.innerHTML='#'+data.ojquestionid+':'+this.questionlist.find(function (a){return a.ojquestionid==data.ojquestionid}).question;
		onebody.appendChild(question);
		question=null;				
		//时间
		var time=document.createElement("div");
		time.className='oj_one_time h4'
		time.innerHTML='@'+data.time;
		onebody.appendChild(time);
		time=null;				
		onebody=null;				
	}
	//输出分页系统
	var div=document.createElement("div");this.logs_div.appendChild(div);
	div.style="width:100%;text-align:center;overflow: hidden;";
	var ul=document.createElement("ul");div.appendChild(ul);
	ul.className="jry_wb_page_system";
	if(page!=1)
	{
		var li=document.createElement("li");ul.appendChild(li);
		var a=document.createElement("a");li.appendChild(a);
		window.scrollTo(0,0)
		a.className="iconfont icon-jiantou_xiangzuoliangci h11";
		a.setAttribute("onclick","jry_wb_beautiful_right_alert.alert('第"+(page-1)+"/"+pages+"页',3000,'auto','ok');"+this.name+".showlogs("+onepage+","+(page-1)+");");
	}
	for(var i=Math.max(1,page-5);i<=Math.min(pages,page+5);i++) 
	{
		var li=document.createElement("li");ul.appendChild(li); 
		var a=document.createElement("a");li.appendChild(a);
		window.scrollTo(0,0)
		a.setAttribute("onclick","jry_wb_beautiful_right_alert.alert('第"+i+"/"+pages+"页',3000,'auto','ok');"+this.name+".showlogs("+onepage+","+(i)+");");
		a.innerHTML=i;
		a.className="h11";
		if(i==page)
			a.className="active";
	}
	if(page!=pages)
	{
		var li=document.createElement("li");ul.appendChild(li);
		var a=document.createElement("a");li.appendChild(a);
		window.scrollTo(0,0)
		a.className="iconfont icon-jiantou_xiangyouliangci h11";	
		a.setAttribute("onclick","jry_wb_beautiful_right_alert.alert('第"+(page+1)+"/"+pages+"页',3000,'auto','ok');"+this.name+".showlogs("+onepage+","+(page+1)+");");
	}
}