//此文档依赖缓存
jry_wb_online_judge_function.prototype.show_logs=function(page)
{
	this.main_dom.innerHTML='';
	if(this.main_dom.style.display=='none')
		this.main_dom.style.display='',this.tree_dom.style.display='none';	
	this.count=0;
	var check_status=(log)=>
	{
		if(this.showwhat.status==0)
			return true;
		else if(log.result!=undefined&&this.showwhat.status==3&&log.result.result!='right'&&log.result.result!='error')
			return true;
		else if(log.result!=undefined&&this.showwhat.status==1&&log.result.result=='right')
			return true;		
		else if(log.result!=undefined&&this.showwhat.status==1&&log.result.result=='error')
			return true;
		else return false;
	};
	var check_class=(log)=>
	{
		if(this.showwhat.class==null||this.showwhat.class.length==0)
			return true;
		for(var i=0,n=log.question.class.length;i<n;i++)
			if(this.showwhat.class.indexOf(log.question.class[i])!=-1)
				return true;
		return false;
	};
	var show=new Array();
	for(let i=0,n=this.logs.length;i<n;i++)
		if(	(this.showwhat.id==0||this.showwhat.id==this.logs[i].id)&&
			(this.showwhat.question_id==0||this.showwhat.question_id==this.logs[i].question_id)&&
			(this.showwhat.question_type==0||this.showwhat.question_type==this.logs[i].question_type)&&
			(check_status(this.logs[i]))&&
			(check_class(this.logs[i])))
				show.push(i),this.count++;
	var from=this.onepage*(page-1);
	var to=this.onepage*page;
	var pages=Math.ceil(show.length/this.onepage);
	page=Math.max(1,Math.min(page,pages));
	this.hash_prevent=true;
	window.location.hash='#'+JSON.stringify({'action':'logs','page':page,'showwhat':this.showwhat});
	var top_toolbar_all=document.getElementById('top_toolbar_all');
	if(top_toolbar_all!=undefined)
		top_toolbar_all.classList.remove('active');
	var top_toolbar_logs=document.getElementById('top_toolbar_logs');
	if(top_toolbar_logs!=undefined)
		top_toolbar_logs.classList.add('active');	
	for(var i=from,n=Math.min(show.length,to);i<n;i++)
	{
		let data=this.logs[show[i]];
		var onebody=document.createElement("div");this.main_dom.appendChild(onebody);
		onebody.className="jry_wb_online_judge_one_body";
		var id=document.createElement("a");onebody.appendChild(id);
		id.classList.add('jry_wb_online_judge_one_id','h56');
		id.innerHTML='#'+(Array(5).join('0')+parseInt(data.log_id)).slice(-5);
		jry_wb_get_and_show_user(onebody,data.id,null,'left');		
		var result=document.createElement("div");onebody.appendChild(result);
		result.classList.add('jry_wb_word_cut','h56','jry_wb_online_judge_one_ans')
		if(data.result.result=='error')
		{
			var icon=document.createElement("b");result.appendChild(icon);
			icon.classList.add('jry_wb_icon','jry_wb_icon_cuowu','jry_wb_online_judge_error');
		}
		else if(data.result.result=='right')
		{
			var icon=document.createElement("b");result.appendChild(icon);
			icon.classList.add('jry_wb_icon','jry_wb_icon_duigoux','jry_wb_online_judge_right');
		}
		else
		{
			var span=document.createElement("span");result.appendChild(span);
			span.innerHTML=data.result;
		}
		var span=document.createElement("span");result.appendChild(span);
		span.innerHTML=data.ans;
		var question=document.createElement("a");onebody.appendChild(question);
		question.classList.add('jry_wb_online_judge_one_question','h56','jry_wb_word_cut');
		if(data.question==undefined)
			question.innerHTML='已消失的题目';
		else
			question.innerHTML='#'+data.question.question_id+':'+data.question.question.slice(0,50),question.href=id.href='jry_wb_online_judge_show_question.php#{"class":'+JSON.stringify(data.question.class)+',"question_id":'+data.question.question_id+'}';		
		var time=document.createElement("div");onebody.appendChild(time);
		time.classList.add('jry_wb_online_judge_one_time');
		time.innerHTML='@'+data.time;	
	}
	jry_wb_beautiful_right_alert.alert('第'+page+'/'+pages+'页',3000,'auto','ok');	
	var div=document.createElement("div");this.main_dom.appendChild(div);
	div.style="width:100%;text-align:center;overflow: hidden;";
	var ul=document.createElement("ul");div.appendChild(ul);
	ul.classList.add('jry_wb_page_system');
	if(page!=1)
	{
		var li=document.createElement("li");ul.appendChild(li);
		var a=document.createElement("a");li.appendChild(a);
		a.classList.add('jry_wb_icon','jry_wb_icon_jiantou_xiangzuoliangci');
		a.onclick=()=>{this.show_logs(1);};		
		var li=document.createElement("li");ul.appendChild(li);
		var a=document.createElement("a");li.appendChild(a);
		a.classList.add('jry_wb_icon','jry_wb_icon_xiangzuo');
		a.onclick=()=>{this.show_logs(page-1);};
	}
	for(let i=Math.max(1,page-5);i<=Math.min(pages,page+5);i++)
	{
		var li=document.createElement("li");ul.appendChild(li);
		var a=document.createElement("a");li.appendChild(a);
		a.onclick=()=>{this.show_logs(i);};		
		a.innerHTML=i;
		if(i==page)
			a.classList.add("active");
	}
	if(page!=pages)
	{
		var li=document.createElement("li");ul.appendChild(li);
		var a=document.createElement("a");li.appendChild(a);
		a.classList.add('jry_wb_icon','jry_wb_icon_xuanzeqixiayige');
		a.onclick=()=>{this.show_logs(page+1);};
		var li=document.createElement("li");ul.appendChild(li);
		var a=document.createElement("a");li.appendChild(a);
		a.classList.add('jry_wb_icon','jry_wb_icon_jiantou_xiangyouliangci');
		a.onclick=()=>{this.show_logs(pages);};		
	}
	window.scrollTo(0,0);	
}