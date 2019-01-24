//此文档相对独立不依赖缓存数据全部由后台直接提供
function showquestion_function (area,returnaddr,clean) 
{
	var t;
	var question_struct;
	var returnaddr;
	this.question_struct=area;
	if(clean==true)
		this.question_struct.innerHTML='';
	this.returnaddr=returnaddr;	
	this.dofordata=function(data)
	{
		document.getElementById('__LOAD').style.display='none';
		this.t = JSON.parse(data); 
		location.hash='#'+JSON.stringify({'ojquesionid':this.t.ojquestionid});
		var notdoflag;
		if(this.t.reason=='notdo')
			notdoflag=true;
		if(this.t.reason=='lasterror')
			this.t.reason='上一次错了';
		else if(this.t.reason=='point')
			this.t.reason='您指定的';
		else if(this.t.reason=='error')
			this.t.reason='错了但还没对';
		else if(this.t.reason=='notdo')
			this.t.reason='尚未尝试';
		else if(this.t.reason=='max')
			this.t.reason='历史最错记录';
		else if(this.t.reason=='rand')
			this.t.reason='系统xjb搞了一个';
		if(this.t.login==false)
		{
			jry_wb_beautiful_alert.alert("没有登录","","window.location.href='"+returnaddr+"'");
			return ;	
		}
		if(this.t.check==false)
		{
			jry_wb_beautiful_alert.alert("您做错了","","");
			setTimeout(function(){jry_wb_beautiful_alert.close()},1000);
			this.t.error=this.t.error-1;
		}
		if(this.t.check==true)
		{
			jry_wb_beautiful_alert.alert("您做对了","","");
			setTimeout(function(){jry_wb_beautiful_alert.close()},800);
		}
		this.question_struct.innerHTML='';
		this.question_struct.className='oj_showquestion';
		var message=document.createElement("input");this.question_struct.appendChild(message);
		message.type='hidden';message.id='hidden'; 
		message.value=JSON.stringify({ansid:this.t.ojquestionid,ojclassid:this.t.ojclassid,id:this.question_struct.id,returnaddr:this.returnaddr,questiontype:this.t.questiontype,isoption:(this.t.option!=null)});
		message=null;
		
		var left_div=document.createElement("div");this.question_struct.appendChild(left_div);
		left_div.className='oj_showquestion_left_div';
		
		
		if(this.t.error<0)
		{
			var div=document.createElement("div") ;
			div.innerHTML='错过'+(-this.t.error)+'遍了';
			div.setAttribute('id','error');
			left_div.appendChild(div);
			div=null;
		}
		var qtitle=document.createElement("div");
		qtitle.setAttribute('id','qtitle');
		qtitle.setAttribute('class','h56');
		qtitle.innerHTML="#"+this.t.ojquestionid+":"+this.t.question;
		left_div.appendChild(qtitle);
		qtitle=null;
		if(this.t.questiontype==1)
		{
			var option=document.createElement("div");
			option.setAttribute('id','option');
			option.setAttribute('class','h56');
			left_div.appendChild(option);
			for(var i=0;i<this.t.option.length;i++)
			{
				var op=document.createElement("input") ;
				op.setAttribute("type","radio"); 	
				op.setAttribute("name","option");
				op.setAttribute("class","question");
				op.setAttribute("value",this.t.option[i]['option']);
				option.appendChild(op);
				op=null;
				op=document.createElement("b") ;
				op.setAttribute("class","h56"); 	
				op.innerHTML=this.t.option[i]['option']+':'+this.t.option[i]['value'];
				option.appendChild(op);
				op=null;
				option.innerHTML+="<br>";
			}	 
		}
		if(this.t.questiontype==2)
		{
			if(this.t.option!=null)
			{
				var option=document.createElement("div");
				option.setAttribute('id','option');
				option.setAttribute('class','h56');
				left_div.appendChild(option);
				for(var i=0;i<this.t.option.length;i++)
				{
					var op=document.createElement("input");option.appendChild(op);
					op.type="radio"; 	
					op.name="option";
					op.className="question";
					op.value=this.t.option[i]['ans'];
					op=null;
					op=document.createElement("b");option.appendChild(op);
					op.setAttribute("class","h56"); 	
					op.innerHTML=this.t.option[i]['ans'];
					op=null;
					option.innerHTML+="<br>";
				}					
			}
			else
			{
				var op=document.createElement("input") ;
				op.setAttribute("type","text"); 	
				op.setAttribute("id","ans"); 	
				op.setAttribute("class","h56 ans");
				left_div.appendChild(op);
				op.focus();
				op=null;
			}
		}
		if(this.t.questiontype==3)
		{
			var op=document.createElement("input") ;
			op.setAttribute("type","text"); 	
			op.setAttribute("id","ans"); 	
			op.setAttribute("class","h56 ans");
			left_div.appendChild(op);
			op.focus();
			op=null;			
		}
		if(this.t.questiontype==4)
		{
			var op=document.createElement("textarea") ;
			op.setAttribute("type","text"); 	
			op.setAttribute("id","ans"); 	
			op.setAttribute("class","h56 ans");
			op.innerHTML='测试专用'
			left_div.appendChild(op);
			op.focus();
			op=null;			
		}		
		option=null;
		var button=document.createElement("input");left_div.appendChild(button);
		button.className='jry_wb_button jry_wb_button_size_middle jry_wb_color_ok';
		button.value='下一题';
		button.type='button';
		button.onclick=function(event)
		{
			var parent=event.target.parentNode;
			var buf=parent.parentNode.getElementsByTagName('input');
			var message;
			for(var i=0;i<buf.length;i++)
				if(buf[i].id=='hidden')
					message=JSON.parse(buf[i].value);
			var ans;
			if(message.questiontype==1)
			{
				var x = parent.getElementsByClassName("question");
				var i;
				for (i = 0; i < x.length; i++) 
					if(x[i].checked==true)
						ans=x[i].value;
			}	
			if(message.questiontype==2)
			{
				if(message.isoption)
				{
					var x = parent.getElementsByClassName("question");
					var i;
					for (i = 0; i < x.length; i++) 
						if(x[i].checked==true)
							ans=x[i].value;					
				}
				else
				{
					var x = parent.getElementsByClassName("ans");
					ans= x[0].value;
				}
			}
			if(message.questiontype==3)
			{
				var x = parent.getElementsByClassName("ans");
				ans= x[0].value;				
			}
			if(message.questiontype==4)
			{
				var x = parent.getElementsByClassName("ans");
				ans= x[0].value;				
			}			
			/*console.log(ans);*/
			jry_wb_ajax_load_data("oj_checkquestion.php",
			function (data)
			{
				if(showquestion==null)
					var showquestion = new showquestion_function(document.getElementById(message.id),message.returnaddr);
				showquestion.dofordata(data);
			},[{'name':'ansid','value':message.ansid},{'name':'ojquestionid','value':'rand'},{'name':'ojclassid','value':message.ojclassid},{'name':'ans','value':ans},{'name':'isoption','value':message.isoption}]);
		}
		if(this.t.ans!=null)
		{
			var ans=document.createElement("div");
			ans.setAttribute('id','ans');
			ans.setAttribute('class','h56');
			ans.id=this.t.ans;
			ans.innerHTML='释放答案';
			ans.onclick=function(event){event.target.innerHTML=event.target.id}
			left_div.appendChild(ans);
			ans=null;	
		}
		button=null;


		var right_div=document.createElement("div");this.question_struct.appendChild(right_div);
		right_div.className='oj_showquestion_right_div';
		var submit=document.createElement("div");right_div.appendChild(submit);
		submit.className='h56 oj_showquestion_submit';submit.innerHTML=''+this.t.right+'/'+this.t.submit;
		var reast=document.createElement("div");right_div.appendChild(reast);
		reast.className='h56 oj_showquestion_reast';reast.innerHTML='同原因剩余:'+this.t.count;
		jry_wb_get_and_show_user(right_div,this.t.ojquestionaddid,'90%',null);
		var source=document.createElement("div");right_div.appendChild(source);
		source.className='h56 oj_showquestion_source jry_wb_word_cut';source.innerHTML="来源:"+this.t.source;
		var reason=document.createElement("div");right_div.appendChild(reason);
		reason.className='h56 oj_showquestion_reason';reason.innerHTML="原因:"+this.t.reason;
		if(!notdoflag)
		{
			var maxtimes=document.createElement("div");right_div.appendChild(maxtimes);
			maxtimes.className='h56 oj_showquestion_maxtimes';maxtimes.innerHTML="最差记录:"+(this.t.maxtimes<0?('错'+(-this.t.maxtimes)):('对'+(this.t.maxtimes)))+'次';	
			var times=document.createElement("div");right_div.appendChild(times);
			times.className='h56 oj_showquestion_times';times.innerHTML="当前记录:"+(this.t.times<0?('错'+(-this.t.times)):('对'+(this.t.times)))+'次';				
		}
		
	}
	this.getans=function()
	{
		if(this.t.questiontype==1)
		{
			var ans;
			var x = this.question_struct.getElementsByClassName("question");
			var i;
			for (i = 0; i < x.length; i++) 
				if(x[i].checked==true)
					return x[i].value;
		}	
		if(this.t.questiontype==2)
		{
			if(this.t.option!=null)
			{
				var ans;
				var x = this.question_struct.getElementsByClassName("question");
				var i;
				for (i = 0; i < x.length; i++) 
					if(x[i].checked==true)
						return x[i].value;					
			}
			else
			{
				var ans;
				var x = this.question_struct.getElementsByClassName("ans");
				return x[0].value;
			}
		}
		if(this.t.questiontype==3)
		{
			var ans;
			var x = this.question_struct.getElementsByClassName("ans");
			return x[0].value;			
		}
		if(this.t.questiontype==4)
		{
			var ans;
			var x = this.question_struct.getElementsByClassName("ans");
			return x[0].value;			
		}		
	}
}