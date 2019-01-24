function this_function () 
{
	var data; 
	var table;
	this.add=function(classid,type)
	{
		this.data={
			ojclassid:classid,
			questiontype:type,
			ojquestionid:'题目',
			user:{
				color:'66ccff',
				show:'you'
			},
			option:[]
		};
		this.creatbody();
		this.table.parentNode.action='oj_manage_do_add_question.php?action=add';
	}
	this.dofordata=function (data)
	{
		document.getElementById('__LOAD').style.display='none';
		this.data=JSON.parse(data);
		this.creatbody();
	}
	this.delate=function(event)
	{
		event.target.parentNode.parentNode.parentNode.removeChild(event.target.parentNode.parentNode);
	}
	this.addoption=function (i)
	{
		if(typeof i=='number')
			var table=this.table;
		else
			var table=i.target.parentNode.parentNode.parentNode;
		var tr=document.createElement("tr") ;
		tr.setAttribute('id','optiontr'+i);
		table.appendChild(tr);
		var td=document.createElement('td');
		td.setAttribute('class','h56');
		tr.appendChild(td);
		var option=document.createElement('input');
		option.setAttribute('type','text');
		option.setAttribute('id','option1'+i);
		option.setAttribute('name','option1');
		option.setAttribute('class','h56');
		option.setAttribute('size','5');
		if(typeof i=='number')
			if(this.data.option[i]!=null)option.setAttribute('value',this.data.option[i].option);
			else option.setAttribute('value','');
		else
			option.setAttribute('value','');
		td.appendChild(option);
		td=null;option=null;
		var td=document.createElement('td');
		td.setAttribute('class','h56');
		tr.appendChild(td);	
		var text=document.createElement('input');
		text.setAttribute('type','text');
		text.setAttribute('id','option2'+i);
		text.setAttribute('name','option2');
		text.setAttribute('class','h56');
		if(typeof i=='number')
			if(this.data.option[i]!=null)text.setAttribute('value',this.data.option[i].value);
			else text.setAttribute('value','');
		else
			text.setAttribute('value','');
		td.appendChild(text);
		td=null;text=null;
		var td=document.createElement('td');
		td.setAttribute('class','h56');
		tr.appendChild(td);	
		var button=document.createElement("button");
		button.innerHTML='删除';
		button.onclick=function(event){event.target.parentNode.parentNode.parentNode.removeChild(event.target.parentNode.parentNode);};
		button.setAttribute('class','button_small button1');
		button.setAttribute('type','button');
		td.appendChild(button);
		td=null;button=null;tr=null;
		connect_special_delate();
	}
	this.creatbody=function()
	{
		document.getElementById('question').innerHTML='';
		//创建form
		var form=document.createElement("form") ; 
		form.setAttribute('id','questionform');
		form.setAttribute('method','post');
		form.setAttribute('action','oj_manage_do_add_question.php?action=chenge');
		document.getElementById('question').appendChild(form)	
		//创建table
		this.table=document.createElement("table") ;
		form.appendChild(this.table);	
		var text=document.createElement('input');
		text.setAttribute('type','hidden');
		text.setAttribute('id','questiontype');
		text.setAttribute('name','questiontype');
		text.setAttribute('value',this.data.questiontype);
		this.table.parentNode.appendChild(text);
		text=null;
		var text=document.createElement('input');
		text.setAttribute('type','hidden');
		text.setAttribute('id','ojclassid');
		text.setAttribute('name','ojclassid');
		text.setAttribute('value',this.data.ojclassid);
		this.table.parentNode.appendChild(text);
		text=null;
		var text=document.createElement('input');
		text.setAttribute('type','hidden');
		text.setAttribute('id','ojquestionid');
		text.setAttribute('name','ojquestionid');
		text.setAttribute('value',this.data.ojquestionid);
		this.table.parentNode.appendChild(text);
		text=null;
		//题干部分
		var tr=document.createElement("tr") ;
		this.table.appendChild(tr);
		var div=document.createElement("div") ;
		div.setAttribute("class","h56");
		div.innerHTML="#"+this.data.ojquestionid;
		tr.appendChild(div);
		var td=document.createElement('td');
		td.setAttribute('colspan','2');
		td.setAttribute('class','h56');
		tr.appendChild(td);
		var qtitle=document.createElement('input');
		qtitle.setAttribute('id','question');
		qtitle.setAttribute('type','text');
		qtitle.setAttribute('name','question');
		qtitle.setAttribute('class','h56');
		qtitle.setAttribute('style','margin-left: 0px; margin-right: 0px; width: 470px;');
		qtitle.value=this.data.question;
		td.appendChild(qtitle);
		tr=null;div=null;td=null;qtitle=null;
		//来源部分
		var tr=document.createElement("tr") ;
		this.table.appendChild(tr);
		var div=document.createElement("div") ;
		div.setAttribute("class","h56");
		div.innerHTML="来源";
		tr.appendChild(div);
		var td=document.createElement('td');
		td.setAttribute('colspan','2');
		td.setAttribute('class','h56');
		tr.appendChild(td);
		var qsouce=document.createElement('input');
		qsouce.setAttribute('id','source');
		qsouce.setAttribute('type','text');
		qsouce.setAttribute('name','source');
		qsouce.setAttribute('class','h56');
		qsouce.setAttribute('style','margin-left: 0px; margin-right: 0px; width: 470px;');
		qsouce.value=this.data.source;
		td.appendChild(qsouce);
		tr=null;div=null;td=null;qsouce=null;
		//答案
		var tr=document.createElement("tr") ;
		this.table.appendChild(tr);
		var div=document.createElement("div") ;
		div.setAttribute("class","h56");
		div.innerHTML="答案";
		tr.appendChild(div);
		var td=document.createElement('td');
		td.setAttribute('colspan','2');
		td.setAttribute('class','h56');
		tr.appendChild(td);
		var qsouce=document.createElement('input');
		qsouce.setAttribute('id','ans');
		qsouce.setAttribute('name','ans');
		qsouce.setAttribute('class','h56');
		qsouce.setAttribute('type','text');
		qsouce.setAttribute('style','margin-left: 0px; margin-right: 0px; width: 470px;');
		qsouce.value=this.data.ans;
		td.appendChild(qsouce);
		tr=null;div=null;td=null;qsouce=null;
		//按钮
		var tr=document.createElement("tr") ;
		this.table.appendChild(tr);
		var button=document.createElement("input");
		button.setAttribute('type','submit');
		button.setAttribute('id','submit');
		button.setAttribute('value','修改');
		button.onclick=this.do_submit;																////////////////////////////////
		button.setAttribute('class','button button1');
		tr.appendChild(button);
		tr=null;button=null;		
		//单选题	
		if(this.data.questiontype==1)
		{
			var tr=document.createElement("tr") ;
			this.table.appendChild(tr);
			var td=document.createElement("td") ;
			td.setAttribute('colspan','2');
			td.setAttribute('class','h56');
			tr.appendChild(td);
			var button=document.createElement("button");
			button.innerHTML='添加';
			button.onclick=this.addoption;																////////////////////////////////
			button.setAttribute('class','button_small button1');
			button.setAttribute('type','button');
			td.appendChild(button);
			tr=null;td=null;button=null;
			for(var i=0;i<this.data.option.length;i++)
				this.addoption(i);
		}
		//填空题
		if(this.data.questiontype==2){}
		//添加着
		showuser(this.table,this.data.user);
		connect_special_delate();
	}
	this.do_submit=function (event)
	{
		var form=event.target.parentNode.parentNode.parentNode;
		var table=event.target.parentNode.parentNode;
		var inputs=form.getElementsByTagName('input');
		var type=0;
		for(var i=0;i<inputs.length;i++)
			if(inputs[i].id=='questiontype')
			{
				type=inputs[i].value;
				break;
			}
		type=parseInt(type);
		if(type==1)
		{
			var option=new Array();
			var buf=form.getElementsByTagName('input');
			for(var i=0,j=0;i<buf.length;i++)
			{
				if(buf[i].name=='option1')
				{
					option[j]={option:buf[i].value,
					value:buf[i+1].value};
					j++;
				}
			}
			var text=document.createElement('input');
			text.setAttribute('type','hidden');
			text.setAttribute('id','option');
			text.setAttribute('name','option');
			text.setAttribute('value',JSON.stringify(option));
			form.appendChild(text);
			text=null;
		}	
		if(type==2){}
		return true;
	}
}
var managequestion = new this_function;