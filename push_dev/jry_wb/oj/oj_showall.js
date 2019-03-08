// JavaScript Document
oj_function.prototype.showall=function(onepage,page)
{
	if(this.all_div==null)
		return;
	this.all_div.innerHTML='';
	this.count=0;
	function __forstatus(showwhat,data)
	{
		if(showwhat.status==0)
			return true;
		else if(showwhat.status==3&&data.times==null)
			return true;
		else if(showwhat.status==2&&data.times>=0&&data.times!=null)
			return true;		
		else if(showwhat.status==1&&data.times<0)
			return true;
		else return false;
	}
	function __forclass(checked,data)
	{
		if(checked.length==0)
			return true;
		for(var i=0,n=data.class.length;i<n;i++)
			if(checked.indexOf(data.class[i].ojclassid)!=-1)
				return true;
		return false;
	}
	if(this.tree==null)
		var checked=[];
	else
		var checked=this.tree.get_checked();
	for(var i=0;i<checked.length;i++)
		checked[i]=parseInt(checked[i]);
	if(this.questionlist!=null)
		this.questionlist.sort(function(a,b){return a.ojquestionid-b.ojquestionid});
	else
		return;
	var show=new Array();
	for(var i=0,n=this.questionlist.length;i<n;i++)
	{
		var data=this.get_by_questionid(this.questionlist[i].ojquestionid);
		if((data.questiontype==this.showwhat.type||this.showwhat.type==0)&&__forstatus(this.showwhat,data)&&__forclass(checked,data)&&(data.ojquestionaddid==this.showwhat.id||this.showwhat.id==0)&&(data.ojquestionid==this.showwhat.questionid||this.showwhat.questionid==0))
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
		var data=this.get_by_questionid(this.questionlist[show[i]].ojquestionid);
		var onebody=document.createElement("div") ;
		onebody.className="oj_one_body";
		this.all_div.appendChild(onebody);
		var classbuf=this.push_class(data.class);
		//id
		var id=document.createElement("div");
		id.setAttribute('onClick',"window.location.href='oj_showquestion.php?ojclassid="+classbuf+'#{"ojquestionid":"'+data.ojquestionid+'"}'+"'");
		id.className='oj_one_id h56';
		id.innerHTML='#'+(Array(5).join('0') + parseInt(data.ojquestionid)).slice(-5);
		onebody.appendChild(id);
		id=null;
		//做题人
		jry_wb_get_and_show_user(onebody,data.ojquestionaddid,null,'left');
		//体干
		var question=document.createElement("div");
		question.className='oj_one_question h56 jry_wb_word_cut';
		question.setAttribute('onClick',"window.location.href='oj_showquestion.php?ojclassid="+classbuf+'#{"ojquestionid":"'+data.ojquestionid+'"}'+"'");
		question.innerHTML=data.question;
		onebody.appendChild(question);
		question=null;
		//尝试情况
		var ans=document.createElement("div");
		ans.className='oj_one_ans jry_wb_word_cut h11';
		onebody.appendChild(ans);
		if(data.times==null)
		{
			var icon2=document.createElement("b");ans.appendChild(icon2);
			icon2.className="jry_wb_icon jry_wb_icon_hr oj_nottry";
			ans.innerHTML+='您尚未尝试此题';
		}else if(data.times<0)
		{
			var icon2=document.createElement("b");ans.appendChild(icon2);
			icon2.className="jry_wb_icon jry_wb_icon_cuowu oj_error";
			ans.innerHTML+='错过'+(-data.times)+'遍了';
		}else if(data.times>=0)
		{
			var icon1=document.createElement("b");ans.appendChild(icon1);
			icon1.className="jry_wb_icon jry_wb_icon_duigoux oj_right";
			var icon2=document.createElement("b");ans.appendChild(icon2);
			icon2.className="jry_wb_icon jry_wb_icon_rili oj_right_calendar";
			ans.innerHTML+=data.lasttime;
		}ans=null;				
		var ojclass=document.createElement("div");
		ojclass.className='oj_one_class jry_wb_rotate_45_deg';
		ojclass.innerHTML='';
		for(var j=0;j<data.class.length;j++)
			ojclass.innerHTML+=data.class[j].ojclassname+';';
		onebody.appendChild(ojclass);
		ojclass=null;
		var ojtype=document.createElement("div");
		ojtype.className='oj_one_type jry_wb_rotate_45_deg';
		ojtype.innerHTML=this.get_type(data.questiontype);
		onebody.appendChild(ojtype);
		ojtype=null;		
		onebody=null;
	}
	//输出分页系统
	var div=document.createElement("div");this.all_div.appendChild(div);
	div.style="width:100%;text-align:center;overflow: hidden;";
	var ul=document.createElement("ul");div.appendChild(ul);
	ul.className="jry_wb_page_system";
	if(page!=1)
	{
		var li=document.createElement("li");ul.appendChild(li);
		var a=document.createElement("a");li.appendChild(a);
		window.scrollTo(0,0) 
		a.className="jry_wb_icon jry_wb_icon_jiantou_xiangzuoliangci h11";
		a.setAttribute("onclick","jry_wb_beautiful_right_alert.alert('第"+(page-1)+"/"+pages+"页',3000,'auto','ok');"+this.name+".showall("+onepage+","+(page-1)+");");
	}
	for(var i=Math.max(1,page-5);i<=Math.min(pages,page+5);i++)
	{
		var li=document.createElement("li");ul.appendChild(li);
		var a=document.createElement("a");li.appendChild(a);
		window.scrollTo(0,0)
		a.setAttribute("onclick","jry_wb_beautiful_right_alert.alert('第"+i+"/"+pages+"页',3000,'auto','ok');"+this.name+".showall("+onepage+","+(i)+");");
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
		a.className="jry_wb_icon jry_wb_icon_jiantou_xiangyouliangci h11";	
		a.setAttribute("onclick","jry_wb_beautiful_right_alert.alert('第"+(page+1)+"/"+pages+"页',3000,'auto','ok');"+this.name+".showall("+onepage+","+(page+1)+");");
	}	
}