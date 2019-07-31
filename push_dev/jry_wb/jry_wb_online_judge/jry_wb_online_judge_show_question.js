function  jry_wb_online_judge_show_question_function(area) 
{
	this.area=area;
	this.area.classList.add('jry_wb_online_judge_show_question');
	this.hash_prevent=false;
	this.class=[];
	this.question={};
	window.onhashchange=(event)=>
	{
		if(this.hash_prevent)
			return this.hash_prevent=false;
		var hash=decodeURI(location.hash.slice(1));
		if(hash=='')
			hash='{}';
		hash=JSON.parse(hash);
		if(hash.class!=undefined)
			this.class=hash.class;
		var question_id=hash.question_id==undefined?0:hash.question_id;
		jry_wb_ajax_load_data("jry_wb_online_judge_check_question.php",(data)=>
		{
			jry_wb_loading_off();
			this.do(JSON.parse(data));
		},[{'name':'question_id','value':question_id},{'name':'class','value':JSON.stringify(this.class)}]);		
	};
	window.onhashchange();
	jry_wb_add_onresize(()=>
	{
		var width=document.documentElement.clientWidth;
		if(width>800)
			all_width=width-Math.min(width*0.2,width-800),this.area.style.width=all_width,this.area.style.margin="0px "+(width-all_width)/2+"px";
		else
			this.area.style.width="100%",this.area.style.margin="0px 0px"
	});
	this.key=[];
	var old_onkeyup=window.onkeyup;
	window.onkeyup=(e)=>
	{
		if (!e) 
			e=window.event;
		var keycode=(e.keyCode||e.which);
		if(typeof this.key[keycode]=='function')
			return this.key[keycode](),false;
		else if(typeof old_onkeyup=='function')
			return old_onkeyup(e);
	};
	this.do=(data)=>
	{
		console.log(data);
		if(data.code)
		{
			var fun=function(dom)
			{
				for(;dom.children.length>0;)
					fun(dom.children[0]),dom.removeChild(dom.children[0]);
			};
			fun(this.area);
			if(data.result===false)
			{
				jry_wb_beautiful_alert.alert("您做错了","","");
				setTimeout(function(){jry_wb_beautiful_alert.close()},1000);
				if(this.question.error!=null)
					this.question.error.times-=1,this.question.submit++;
				else
					this.question.error={'times':-1};
				data.reason='lasterror'
			}
			else if(data.result===true)
			{
				jry_wb_beautiful_alert.alert("您做对了","","");
				setTimeout(function(){jry_wb_beautiful_alert.close()},800);
			}
			if(data.question!=undefined)
				this.question=data.question,this.hash_prevent=true,window.location.hash=JSON.stringify({'question_id':data.question.question_id,'class':this.class.length==0?data.question.class:this.class});
			else
				data.question=this.question;
			var left_div	=document.createElement("div");this.area.appendChild(left_div)	;left_div.classList	.add('left_div');
			var qtitle		=document.createElement("div");left_div	.appendChild(qtitle)	;qtitle.classList	.add('qtitle');
			new jry_wb_markdown(qtitle,data.question.id,data.question.lasttime,data.question.question,true);
			if(data.question.question_type==1)
			{
				var option=document.createElement("div");left_div.appendChild(option);option.classList.add('option');
				data.question.exdata.option.sort(function(){return Math.random()-0.5});
				let cnt=-1;
				for(let i=0;i<data.question.exdata.option.length;i++)
				{
					let input=document.createElement("input");option.appendChild(input);
					input.type='radio';
					input.name='option';
					input.value=data.question.exdata.option[i].option;
					if(i==0)
						input.focus();
					data.question.exdata.option[i].dom=input;
					let op=document.createElement("span") ;option.appendChild(op);
					op.innerHTML=(i+1)+':'+data.question.exdata.option[i]['value'];
					op.onclick=()=>
					{
						input.click();
					};
					input.onclick=()=>
					{
						cnt=i;
						this.ans=data.question.exdata.option[i].option;
					}
					this.key[eval('jry_wb_keycode_'+(i+1))]=this.key[eval('jry_wb_keycode_'+(i+1)+'_')]=()=>
					{
						input.click();
						input.focus();
					};
					option.appendChild(document.createElement("br"));
				}
				this.key[jry_wb_keycode_tab]=()=>
				{
					cnt++;
					if(cnt>=data.question.exdata.option.length)
						cnt=0;
					data.question.exdata.option[cnt].dom.click();
					data.question.exdata.option[cnt].dom.focus();
				};
				()=>
				{
					cnt--;
					if(cnt<0)
						cnt=data.question.exdata.option.length-1;
					data.question.exdata.option[cnt].dom.click();
					data.question.exdata.option[cnt].dom.focus();
				}
			}
			else if(data.question.question_type==2)
			{
				var input=document.createElement("input");left_div.appendChild(input);
				input.type='text';
				input.classList.add('input');
				input.focus();
				input.onkeyup=(e)=>
				{
					console.log(e);
					if (!e)e=window.event;var keycode=(e.keyCode||e.which);if(keycode!=jry_wb_keycode_enter)e.stopPropagation();
					this.ans=input.value;
				};
			}
			else if(data.question.question_type==3)
			{
				var input=document.createElement("input");left_div.appendChild(input);
				input.type='text';
				input.classList.add('input');
				input.focus();
				input.onkeyup=()=>
				{
					if (!e)e=window.event;var keycode=(e.keyCode||e.which);if(keycode!=jry_wb_keycode_enter)e.stopPropagation();
					this.ans=input.value;
				};
			}
			else if(data.question.question_type==4)
			{	
				var input=document.createElement("textarea");left_div.appendChild(input);
				input.classList.add('textarea');
				input.focus();
				input.onkeyup=()=>
				{
					this.ans=input.value;
				};
			}		
			var button=document.createElement("button");left_div.appendChild(button);
			button.classList.add('jry_wb_button','jry_wb_button_size_middle','jry_wb_color_ok');
			button.innerHTML='下一题';
			button.onclick=(event)=>
			{
				jry_wb_ajax_load_data("jry_wb_online_judge_check_question.php",(data)=>
				{
					jry_wb_loading_off();
					this.do(JSON.parse(data));
				},[{'name':'ans_question_id','value':data.question.question_id},{'name':'class','value':JSON.stringify(this.class)},{'name':'ans','value':this.ans}]);
			};
			this.key[jry_wb_keycode_enter]=()=>
			{
				button.click();
			};
			var right_div	=document.createElement("div");this.area.appendChild(right_div)	;right_div	.classList.add('right_div')	;
			var submit		=document.createElement("div");right_div.appendChild(submit)	;submit		.classList.add('submit')	;submit.innerHTML='提交/通过:'+data.question.right+'/'+data.question.submit;
			var reast		=document.createElement("div");right_div.appendChild(reast)		;reast		.classList.add('reast')		;reast.innerHTML='同原因剩余:'+data.count;
			jry_wb_get_and_show_user(right_div,data.question.id,Math.min(250,right_div.clientWidth),null);
			var source		=document.createElement("div");right_div.appendChild(source)	;source		.classList.add('source','jry_wb_word_cut');source.innerHTML="来源:"+data.question.source;
			var reason		=document.createElement("div");right_div.appendChild(reason)	;reason.classList.add('reason')			;reason.innerHTML='原因:';
			if(data.reason=='lasterror')reason.innerHTML+='上一次错了';else if(data.reason=='point')reason.innerHTML+='您指定的';else if(data.reason=='error')reason.innerHTML+='复习一下';else if(data.reason=='notdo')reason.innerHTML+='您尚未尝试';
			if(data.question.error!=null)
			{
				if(data.question.error.maxtimes!=0)
				{
					var maxtimes=document.createElement("div");right_div.appendChild(maxtimes);maxtimes.classList.add('maxtimes');maxtimes.innerHTML="最差记录:"+(data.question.error.maxtimes<0?('错'+(-data.question.error.maxtimes)):('对'+(data.question.error.maxtimes)))+'次';	
				}
				var times=document.createElement("div");right_div.appendChild(times);times.classList.add('times');times.innerHTML="当前记录:"+(data.question.error.times<0?('错'+(-data.question.error.times)):('对'+(data.question.error.times)))+'次';				
			}
			
			window.onresize();
		}
		else
		{
			if(data.reason==100000)
				jry_wb_beautiful_alert.alert("没有登录","","window.location.href='"+jry_wb_message.jry_wb_host+'jry_wb_mainpages/login.php'+"'");
			else if(data.reason==100001)
				jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
			else if(data.reason==700001)
				jry_wb_beautiful_alert.alert("不存在的题目",'题目编号:'+data.extern,"window.location.href='index.php'");
			else
				jry_wb_beautiful_alert.alert("错误"+data.reason,"请联系开发组");
		}		
	};
}