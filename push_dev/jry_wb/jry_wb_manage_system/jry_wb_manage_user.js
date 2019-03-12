var jry_wb_manage_user=new function()
{
}
jry_wb_manage_user.sync=function()
{
	jry_wb_ajax_load_data('jry_wb_manage_competence_get_information.php',(data)=>{
		var buf=JSON.parse(data);
		if(buf!=null)
			if(buf.login==false)
			{
				jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
				return ;	
			}	
		data=buf;
		data.sort(function (a,b){return a.type-b.type;});
		jry_wb_cache.set('competence',data);
		this.competence=data;
		jry_wb_loading_off();	
	});	
	jry_wb_sync_data_with_server('user_list',"jry_wb_manage_user_get_information.php?action=list",null,function(a){return a.id==this.buf.id},function(data){jry_wb_manage_user.all=data;for(var i=0,n=jry_wb_manage_user.all.length;i<n;i++)jry_wb_manage_user.reload[jry_wb_manage_user.all[i]]=true;jry_wb_manage_user.showall();},function(a,b){return a.id-b.id});
}
jry_wb_manage_user.showall=function()
{
	this.area.innerHTML='';
	var all = document.createElement('table');this.area.appendChild(all);
	all.width='100%';all.height='100%';
	all.classList.add("jry_wb_left_toolbar");
	var tr=document.createElement('tr');all.appendChild(tr);
	tr.width='100%';tr.height='100%';
	tr.classList.add("jry_wb_left_toolbar_left");
	var td = document.createElement('td');tr.appendChild(td);
	td.setAttribute('valign','top');
	td.style.width='150px';
	var list = document.createElement('div');td.appendChild(list);
	list.style.width='150px';
	var one=document.createElement('div');list.appendChild(one);
	one.classList.add('jry_wb_left_toolbar_left_list_default');
	one.innerHTML='重载';
	one.onclick=function(event)
	{
		jry_wb_manage_user.sync();
		for(var i=0,n=jry_wb_manage_user.reload.length;i<n;i++)
			jry_wb_manage_user.reload[i]=true;
	};
	one=null;
	for(var i=0,n=this.all.length;i<n;i++)
	{
		var one=document.createElement('div');list.appendChild(one);
		one.style="text-overflow: ellipsis; overflow:hidden;";
		one.style.width='';
		if(!this.all[i].use)
			one.classList.add("jry_wb_left_toolbar_left_list_default","jry_wb_color_error");
		else
			one.classList.add(('jry_wb_left_toolbar_left_list_'+(i%2+1)));
		one.innerHTML=this.all[i].id+':'+this.all[i].name;
		jry_wb_get_user(this.all[i].id,jry_wb_manage_user.reload[this.all[i].id],()=>{jry_wb_loading_off();},undefined,true);
		jry_wb_manage_user.reload[this.all[i].id]=false;
		one.onclick=(event)=>
		{
			var id=parseInt(event.target.innerHTML);
			if(this.lasthighlight!=null&&this.lasthighlight.target!=null&&this.lasthighlight.classList!=null)
			{
				this.lasthighlight.target.classList.remove('jry_wb_left_toolbar_left_list_active');
				for(var i=0,n=this.lasthighlight.classList.length;i<n;i++)
					this.lasthighlight.target.classList.add(this.lasthighlight.classList[i]);
			}
			else
			{
				this.lasthighlight={};
			}
			this.lasthighlight.target=event.target;
			this.lasthighlight.classList=[];
			for(var i=0,n=event.target.classList.length;i<n;i++)
			{
				this.lasthighlight.classList.push(event.target.classList[i]);
				event.target.classList.remove(event.target.classList[i]);
			}				
			event.target.classList.add('jry_wb_left_toolbar_left_list_active');
			jry_wb_get_user(id,jry_wb_manage_user.reload[id],
				function()
				{
					jry_wb_manage_user.reload[id]=false;
					var data=jry_wb_cache.get('jry_wb_manage_user_cache');
					var user=data.find(function (a){return a.id==id});
					var show=event.target.parentNode.parentNode.nextElementSibling;
					jry_wb_loading_off();
					show.innerHTML='';
					var table=document.createElement("table");show.appendChild(table);
					table.border=1;
					jry_wb_show_tr_no_input(table,'ID',user.id);

					var button=document.createElement("button");jry_wb_show_tr_no_input(table,'昵称',user.name).appendChild(button);
					button.type="button";button.innerHTML="昵称不合法";button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_warn");button.name=user.id;
					button.onclick=function(event)
					{
						var id=parseInt(event.target.name); 
						jry_wb_manage_user.reload[id]=true;
						jry_wb_ajax_load_data('jry_wb_manage_user_do.php?action=name_not_ok&id='+id,function (data){jry_wb_loading_off();data=JSON.parse(data);if(data.login==false){jry_wb_beautiful_right_alert.alert('无法操作，因为'+data.reasion,2000,'auto','error');return;}if(data.data=='OK'){jry_wb_beautiful_right_alert.alert('OK',2000,'auto','ok');return;}if(data.data=='mail'){jry_wb_beautiful_right_alert.alert('Mail Error',2000,'auto','error');return;}jry_wb_beautiful_right_alert.alert('Unknow error',2000,'auto','alert');});
					}			
					var tr=document.createElement("tr");table.appendChild(tr);
					var td=document.createElement("td");tr.appendChild(td);	
					td.width="400";
					var h55=document.createElement("h56");td.appendChild(h55);	
					h55.innerHTML='头像';
					td=null;
					var td=document.createElement("td");tr.appendChild(td);	
					td.width="400";td.style="overflow: hidden;"; 
					var img=document.createElement("img");td.appendChild(img);
					jry_wb_set_user_head_special(user,img);
					img.height=80;
					img.width=80;
					td=null;
					tr=null;	
					jry_wb_show_tr_no_input(table,'绿币',user.green_money);	
					jry_wb_show_tr_no_input(table,'注册日期',user.enroldate);	
					//
					var tr=document.createElement("tr");table.appendChild(tr);
					var td=document.createElement("td");tr.appendChild(td);	
					td.width="400";
					var h55=document.createElement("h56");td.appendChild(h55);	
					h55.innerHTML='权限组';
					td=null;
					var td=document.createElement("td");tr.appendChild(td);	
					var select=document.createElement("select");td.appendChild(select);	
					select.name=select.id='type';select.classList.add('h56');
					for(var i=0,n=jry_wb_manage_user.competence.length;i<n;i++)
					{
						var option=document.createElement('option');select.appendChild(option);	
						option.value=jry_wb_manage_user.competence[i].type;
						if(jry_wb_manage_user.competence[i].type==user.type)
							option.setAttribute("selected","selected");
						for(var j=0,nn=jry_wb_manage_user.competence[i].data.length;j<nn;j++)
							if(jry_wb_manage_user.competence[i].data[j].name=="competencename")
							{
								option.innerHTML=jry_wb_manage_user.competence[i].data[j].value;
								break;
							}
					}
					td.innerHTML+='<h56>('+user.type+')</h56>'
					//
					var tr=document.createElement("tr");table.appendChild(tr);
					var td=document.createElement("td");tr.appendChild(td);	
					td.width="400";			
					var h55=document.createElement("h56");td.appendChild(h55);	
					h55.innerHTML='使用权';
					td=null;
					var td=document.createElement("td");tr.appendChild(td);	
					var select=document.createElement("select");td.appendChild(select);	
					select.name=select.id='use';select.classList.add('h56');
					var a=[{'value':0,'name':"禁止"},{'value':1,'name':"允许"}]
					for(var i=0,n=a.length;i<n;i++)
					{
						var option=document.createElement('option');select.appendChild(option);	
						option.value=a[i].value;
						if(a[i].value==user.use)
							option.setAttribute("selected","selected");
						option.innerHTML=a[i].name;
					}			
					if(user.sex==1)
						jry_wb_show_tr_no_input(table,'性别','男');
					else if(user.sex==0)
						jry_wb_show_tr_no_input(table,'性别','女');
					else if(user.sex==2)
						jry_wb_show_tr_no_input(table,'性别','女装大佬'); 
					else
						jry_wb_show_tr_no_input(table,'性别','???');
					jry_wb_show_tr_no_input(table,'电话',user.tel);
					if(user.mail=='') 
					{
						var button=document.createElement("button");jry_wb_show_tr_no_input(table,'邮箱',user.mail).appendChild(button);
						button.type="button";button.innerHTML="提醒绑邮箱";button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_warn");button.name=user.id;
						button.onclick=function(event)
						{
							var id=parseInt(event.target.name); 
							jry_wb_manage_user.reload[id]=true;
							jry_wb_ajax_load_data('jry_wb_manage_user_do.php?action=bangyouxiang&id='+id,function (data){jry_wb_loading_off();data=JSON.parse(data);if(data.login==false){jry_wb_beautiful_right_alert.alert('无法操作，因为'+data.reasion,2000,'auto','error');return;}if(data=='OK'){jry_wb_beautiful_right_alert.alert('OK',2000,'auto','ok');return;}jry_wb_beautiful_right_alert.alert('Unknow error',2000,'auto','alert');});
						}
					}
					else
						jry_wb_show_tr_no_input(table,'邮箱',user.mail);
					if(user.zhushi!=''&&user.zhushi!=null)
						markdown(jry_wb_show_tr_no_input(table,'签名',''),user.id,'',user.zhushi);
					
					var tr=document.createElement("tr");table.appendChild(tr);
					var td=document.createElement("td");tr.appendChild(td);	
					td.width="400";
					var h55=document.createElement("h56");td.appendChild(h55);	
					h55.innerHTML='登录IP';
					td=null;
					var td=document.createElement("td");tr.appendChild(td);	
					td.width="400";
					var h55=document.createElement("h55");td.appendChild(h55);	
					for(var i=0;i<user.login_addr.length;i++)
					{
						var li=document.createElement("li");h55.appendChild(li);	
						li.innerHTML=user.login_addr[i];
					}
					var tr=document.createElement("tr");table.appendChild(tr);
					var td=document.createElement("td");tr.appendChild(td);	
					td.width="400";
					td.classList.add('h56');
					td.innerHTML='第三方接入';
					var td=document.createElement("td");tr.appendChild(td);	
					td.width="400";
					td.classList.add('h56');
					td.innerHTML+='QQ:';
					if(user.oauth_qq==null)
						td.innerHTML+='无<br>';
					else
						td.innerHTML+=user.oauth_qq.nickname+'<img width="40px" src="'+user.oauth_qq.figureurl_qq_2+'"><br>';
					td.innerHTML+='MI:';
					if(user.oauth_mi==null)
						td.innerHTML+='无<br>';
					else
						td.innerHTML+=user.oauth_mi.miliaoNick+'<img width="40px" src="'+user.oauth_mi.miliaoIcon_orig+'"><br>';
					td.innerHTML+='gayhub:';
					if(user.oauth_github==null)
						td.innerHTML+='无<br>';
					else
						td.innerHTML+=user.oauth_github.name+','+user.oauth_github.login+'<img width="40px" src="'+user.oauth_github.avatar_url+'"><br>';						
					td.innerHTML+='码云:';
					if(user.oauth_gitee==null)
						td.innerHTML+='无<br>';
					else
						td.innerHTML+=user.oauth_gitee.name+','+user.oauth_gitee.login+'<img width="40px" src="'+user.oauth_gitee.avatar_url+'"><br>';						
					var tr=document.createElement("tr");table.appendChild(tr);
					var td=document.createElement("td");tr.appendChild(td);	
					td.setAttribute("colspan","2");td.setAttribute("align","center");
					var button=document.createElement("button");td.appendChild(button);
					button.type="button";button.innerHTML="修改";button.className="jry_wb_button jry_wb_button_size_big jry_wb_color_ok";button.name=user.id;
					button.onclick=function(event)
					{
						var id=parseInt(event.target.name); 
						jry_wb_manage_user.reload[id]=true;
						var inputs=event.target.parentNode.parentNode.parentNode.getElementsByTagName('input')
						var out=new Array();
						for(var i=0,n=inputs.length;i<n;i++)
							out.push({'name':inputs[i].name,'value':inputs[i].value});
						var inputs=event.target.parentNode.parentNode.parentNode.getElementsByTagName('select')
						for(var i=0,n=inputs.length;i<n;i++)
							out.push({'name':inputs[i].name,'value':inputs[i].value});
						jry_wb_ajax_load_data('jry_wb_manage_user_do.php?id='+id,function (data){jry_wb_loading_off();data=JSON.parse(data);if(data.login==false){jry_wb_beautiful_right_alert.alert('无法操作，因为'+data.reasion,2000,'auto','error');return;}if(data.data=='OK'){jry_wb_beautiful_right_alert.alert('OK',2000,'auto','ok');return;}jry_wb_beautiful_right_alert.alert('Unknow error',2000,'auto','alert');},out);
					}
					table=null;	
					window.onresize();
					window.scrollTo(window.scrollX,0);
				},undefined,true);
		}
		window.onresize();
	}
	var one = document.createElement('td');tr.appendChild(one);
	one.width='*';one.setAttribute('valign','top');	
}
function jry_wb_manage_user_init(area,mode)
{
	jry_wb_manage_user.area=area;
	jry_wb_manage_user.reload=new Array();
	jry_wb_manage_user.sync();
}
function jry_wb_manage_user_run(area)
{
	jry_wb_manage_user.area=area;
	jry_wb_manage_user.sync();
}