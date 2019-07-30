jry_wb_online_judge_function.prototype.show_all=function(page)
{
	this.main_dom.innerHTML='';
	if(this.main_dom.style.display=='none')
		this.main_dom.style.display='',this.tree_dom.style.display='none';	
	this.count=0;
	var check_status=(question)=>
	{
		if(this.showwhat.status==0)
			return true;
		else if(question.error==undefined&&this.showwhat.status==3)
			return true;
		else if(question.error!=undefined&&this.showwhat.status==1&&question.error.times>=0)
			return true;		
		else if(question.error!=undefined&&this.showwhat.status==2&&question.error.times<0)
			return true;
		else return false;
	};
	var check_class=(question)=>
	{
		if(this.showwhat.class==null||this.showwhat.class.length==0)
			return true;
		for(var i=0,n=question.class.length;i<n;i++)
			if(this.showwhat.class.indexOf(question.class[i])!=-1)
				return true;
		return false;
	};
	var show=new Array();
	for(let i=0,n=this.question_list.length;i<n;i++)
		if(	this.question_list[i].use&&
			(this.showwhat.id==0||this.showwhat.id==this.question_list[i].id)&&
			(this.showwhat.question_id==0||this.showwhat.question_id==this.question_list[i].question_id)&&
			(this.showwhat.question_type==0||this.showwhat.question_type==this.question_list[i].question_type)&&
			(check_status(this.question_list[i]))&&
			(check_class(this.question_list[i])))
				show.push(i),this.count++;
	var from=this.onepage*(page-1);
	var to=this.onepage*page;
	var pages=Math.ceil(show.length/this.onepage);
	page=Math.max(1,Math.min(page,pages));
	this.hash_prevent=true;
	window.location.hash='#'+JSON.stringify({'action':'ql','page':page,'showwhat':this.showwhat});
	var top_toolbar_all=document.getElementById('top_toolbar_all');
	if(top_toolbar_all!=undefined)
		top_toolbar_all.classList.add('active');
	var top_toolbar_logs=document.getElementById('top_toolbar_logs');
	if(top_toolbar_logs!=undefined)
		top_toolbar_logs.classList.remove('active');
	for(let i=from,n=Math.min(show.length,to);i<n;i++)
	{
		let data=this.question_list[show[i]];
		var onebody=document.createElement("div");this.main_dom.appendChild(onebody);
		onebody.className="jry_wb_online_judge_one_body";
		var id=document.createElement("a");onebody.appendChild(id);
		id.classList.add('jry_wb_online_judge_one_id');
		id.innerHTML='#'+(Array(5).join('0')+parseInt(data.question_id)).slice(-5);
		jry_wb_get_and_show_user(onebody,data.id,null,'left');
		var question=document.createElement("a");onebody.appendChild(question);
		question.classList.add('jry_wb_online_judge_one_question','jry_wb_word_cut');
		question.innerHTML=data.question.slice(0,50);
		var ans=document.createElement("div");onebody.appendChild(ans);
		ans.classList.add('jry_wb_online_judge_one_ans','jry_wb_word_cut','h11');
		if(data.error==undefined)
		{
			var icon2=document.createElement("b");ans.appendChild(icon2);
			icon2.classList.add('jry_wb_icon','jry_wb_icon_hr','jry_wb_online_judge_nottry');
			var span=document.createElement("span");ans.appendChild(span);span.innerHTML='您尚未尝试此题';
		}
		else if(data.error.times<0)
		{
			var icon2=document.createElement("b");ans.appendChild(icon2);
			icon2.classList.add('jry_wb_icon','jry_wb_icon_cuowu','jry_wb_online_judge_error');
			var span=document.createElement("span");ans.appendChild(span);span.innerHTML='错过'+(data.error.times)+'遍了';
		}
		else if(data.error.times>=0)
		{
			var icon1=document.createElement("b");ans.appendChild(icon1);
			icon1.classList.add('jry_wb_icon','jry_wb_icon_duigoux','jry_wb_online_judge_right');
			var icon2=document.createElement("b");ans.appendChild(icon2);
			icon2.classList.add('jry_wb_icon','jry_wb_icon_rili','jry_wb_online_judge_right_calendar');
			var span=document.createElement("span");ans.appendChild(span);span.innerHTML=data.error.lasttime;
		}
		question.href=id.href='jry_wb_online_judge_show_question.php#{"class":'+JSON.stringify(data.class)+',"question_id":'+data.question_id+'}';
		var class_=document.createElement("div");onebody.appendChild(class_);
		class_.classList.add('jry_wb_online_judge_one_class','jry_wb_icon','jry_wb_icon_biaoqian','jry_wb_tool_tip');
		var class__=document.createElement("div");class_.appendChild(class__);
		class__.classList.add('jry_wb_tool_tip_text');
		for(var j=0;j<data.classes.length;j++)
			class__.innerHTML+=(j==0?'':',')+data.classes[j].class_name;
		var type=document.createElement("div");onebody.appendChild(type);
		type.classList.add('jry_wb_online_judge_one_type');
		type.innerHTML=this.get_word_by_type(data.question_type);
		
	}
	var div=document.createElement("div");this.main_dom.appendChild(div);
	div.style="width:100%;text-align:center;overflow: hidden;";
	var ul=document.createElement("ul");div.appendChild(ul);
	ul.classList.add('jry_wb_page_system');
	jry_wb_beautiful_right_alert.alert('第'+page+'/'+pages+'页',3000,'auto','ok');	
	if(page!=1)
	{
		var li=document.createElement("li");ul.appendChild(li);
		var a=document.createElement("a");li.appendChild(a);
		a.classList.add('jry_wb_icon','jry_wb_icon_jiantou_xiangzuoliangci');
		a.onclick=()=>{this.show_all(1);};		
		var li=document.createElement("li");ul.appendChild(li);
		var a=document.createElement("a");li.appendChild(a);
		a.classList.add('jry_wb_icon','jry_wb_icon_xiangzuo');
		a.onclick=()=>{this.show_all(page-1);};
	}
	for(let i=Math.max(1,page-5);i<=Math.min(pages,page+5);i++)
	{
		var li=document.createElement("li");ul.appendChild(li);
		var a=document.createElement("a");li.appendChild(a);
		a.onclick=()=>{this.show_all(i);};		
		a.innerHTML=i;
		if(i==page)
			a.classList.add("active");
	}
	if(page!=pages)
	{
		var li=document.createElement("li");ul.appendChild(li);
		var a=document.createElement("a");li.appendChild(a);
		a.classList.add('jry_wb_icon','jry_wb_icon_xuanzeqixiayige');
		a.onclick=()=>{this.show_all(page+1);};
		var li=document.createElement("li");ul.appendChild(li);
		var a=document.createElement("a");li.appendChild(a);
		a.classList.add('jry_wb_icon','jry_wb_icon_jiantou_xiangyouliangci');
		a.onclick=()=>{this.show_all(pages);};		
	}
	window.scrollTo(0,0);
}