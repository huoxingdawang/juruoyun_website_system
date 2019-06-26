(function()
{
	jry_wb_ajax_load_data('jry_wb_manage_system_get_information.php',function(data)
	{
		document.getElementById('buttom_message').style.display='none';
		var hash=location.hash.slice(1).split('/');
		location.hash='';
		jry_wb_manage_system_information=JSON.parse(data);
		jry_wb_loading_off();
		color_picker_area=document.createElement('div');document.body.appendChild(color_picker_area);
		color_picker_area.style.top="100px";color_picker_area.style.right="50px";color_picker_area.style.position="fixed";
		color_picker_area.style.display="none";color_picker_area.style.width="270px";
		var color_picker_main=document.createElement('div');color_picker_area.appendChild(color_picker_main);
		color_picker_main.classList.add("cp-default");
		color_picker_value=document.createElement('input');color_picker_area.appendChild(color_picker_value);
		color_picker_value.classList.add("h56");
		color_picker_value.style.width="200px";
		var color_picker_button=document.createElement('button');color_picker_area.appendChild(color_picker_button);
		color_picker_button.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
		color_picker_button.onclick=function()
		{
			jry_wb_manage_system_color_picker_area.value=jry_wb_manage_system_color_picker_area.innerHTML=jry_wb_color_to_string(color_picker_area.style.backgroundColor);
			jry_wb_manage_system_color_picker_area.onchange();
		};
		color_picker_value.onkeyup=function()
		{
			color_picker_area.style.backgroundColor="#"+color_picker_value.value;
		};
		color_picker_button.innerHTML='应用';
		var color_picker_close=document.createElement('div');color_picker_area.appendChild(color_picker_close);
		color_picker_close.style.position="fixed";color_picker_close.style.top="100px";color_picker_close.style.right="50px";
		color_picker_close.style.fontSize="20px";color_picker_close.style.color="#ff0000";
		color_picker_close.classList.add("jry_wb_icon","jry_wb_icon_guanbi");
		color_picker_close.onclick=function()
		{
			color_picker_area.style.display='none';			
		}
		ColorPicker(
			color_picker_main,
			function(hex, hsv, rgb) 
			{
				color_picker_area.style.backgroundColor = hex;
				color_picker_value.value = hex;
			}
		);
		var area=document.getElementById('body');
		var left_body=document.createElement('div');area.appendChild(left_body);
		left_body.classList.add('jry_wb_left_toolbar_left');
		left_body.style.overflow='hidden';
		left_body.style.float='left';
		var right_body=document.createElement('div');area.appendChild(right_body);
		right_body.style.float='left';
		jry_wb_add_onresize(function()
		{
			right_body.style.width=document.body.clientWidth-left_body.clientWidth-3;
			area.style.height=Math.max(left_body.clientHeight,right_body.clientHeight)
		});
		var one_function=document.createElement('div');right_body.appendChild(one_function);
		function show_head()
		{
			var one=document.createElement('div');left_body.appendChild(one);
			one.classList.add('jry_wb_left_toolbar_left_list_default');
			one.onclick=function()
			{
				color_picker_area.style.display=color_picker_area.style.display==''?'none':'';
			}
			one.innerHTML="颜色小工具";	
		}
		var first=true;
		function do_one(data,back,tree)
		{
			left_body.innerHTML='';
			show_head();
			if(back)
			{
				var one=document.createElement('div');left_body.appendChild(one);
				one.classList.add('jry_wb_left_toolbar_left_list_default');
				one.onclick=function()
				{
					var nnn=2;
					if((one_function.tagName!="IFRAME"&&one_function.innerHTML!='')||(one_function.tagName=="IFRAME"&&one_function.src!=''))
						nnn++;
					if(one_function.tagName!="IFRAME")
						one_function.innerHTML='';
					else
						one_function.src='';
					tree.pop();
					var data_=jry_wb_manage_system_information;
					for(var i=0,n=tree.length;i<n;data_=data_[tree[i]].children,i++);
					var a=location.hash.slice(1).split('/');location.hash='';for(var i=0,n=a.length;i<n-nnn;i++)location.hash+=a[i]+'/';
					document.getElementById('hash').innerHTML='/'+location.hash.slice(1);
					do_one(data_,tree.length!=0,tree);
					window.onresize();
				}
				one.innerHTML="上一页";
			}
			for(let i=0,n=data.length;i<n;i++)
			{
				if((data[i].children==null||data[i].children.length==0)&&data[i].url=='')
					continue;
				var one=document.createElement('div');left_body.appendChild(one);
				one.classList.add("jry_wb_left_toolbar_left_list_"+((i%2)+1));
				one.innerHTML=data[i].name;
				one.name=i;
				if(data[i].children!=null&&data[i].children.length!=0)
				{
					one.onclick=function()
					{
						document.getElementById('hash').innerHTML='/'+(location.hash+=data[i].hash+'/').slice(0);
						tree.push(i);
						do_one(data[i].children,true,tree);
					}
				}
				else if(data[i].hash=='')
					one.onclick=function()
					{
						if(data[i].inited==undefined||data[i].inited==false)
							data[i].inited=true,jry_wb_include_once_script(jry_wb_message.jry_wb_host+data[i].url,function(){eval(data[i].init_script+'(one_function)');});
						else
							eval(data[i].run_script+'(one_function)');
						window.onresize();						
					};
				else if(data[i].is_script)
					one.onclick=function()
					{
						if(one_function.innerHTML!='')
						{
							var a=location.hash.slice(1).split('/');
							location.hash='';
							for(var j=0,n=a.length;j<n-2;j++)
								location.hash+=a[j]+'/';
						}					
						document.getElementById('hash').innerHTML='/'+(location.hash+=data[i].hash+'/').slice(1);
						if(one_function.tagName=="IFRAME")
						{
							right_body.removeChild(one_function);
							one_function=document.createElement('div');right_body.appendChild(one_function);
						}
						if(data[i].inited==undefined||data[i].inited==false)
						{
							data[i].inited=true;
							one_function.innerHTML='';
							jry_wb_include_once_script(jry_wb_message.jry_wb_host+data[i].url,function(){eval(data[i].init_script+'(one_function)');});
						}
						else
							eval(data[i].run_script+'(one_function)');
						window.onresize();
					}
				else if(data[i].url=='')
					one.onclick=function()
					{
						if(one_function.innerHTML!='')
						{
							var a=location.hash.slice(1).split('/');
							location.hash='';
							for(var i=0,n=a.length;i<n-2;i++)
								location.hash+=a[i]+'/';
						}					
						document.getElementById('hash').innerHTML='/'+(location.hash+=data[parseInt(this.name)].hash+'/').slice(1);
						var i=parseInt(this.name);
						if(one_function.tagName!="IFRAME")
						{
							right_body.removeChild(one_function);
							one_function=document.createElement('iframe');right_body.appendChild(one_function);			
						}
						if(data[i].url.includes('http'))
							one_function.src=data[i].url;
						else
							one_function.src=jry_wb_message.jry_wb_host+data[i].url;
						one_function.style.border=0;
						one_function.style.width="100%";
						one_function.style.height=Math.max(0,window.innerHeight-document.getElementById('_top').getBoundingClientRect().top-document.getElementById('buttom_message').clientHeight);
						jry_wb_beautiful_right_alert.alert('加载内联页面中，请稍等',1000,'auto','warn');jry_wb_loading_on();
						one_function.onload=function()
						{
							jry_wb_beautiful_right_alert.alert('加载内联页面完毕',500,'auto','ok');	jry_wb_loading_off();
							window.onresize();					
						}
						window.onresize();
					}
				else
					one.onclick=function(){jry_wb_beautiful_right_alert.alert('并没有项目',5000,'auto','error');};
				if(hash[tree.length]==data[i].hash&&(first)&&hash[tree.length]!='')
				{
					one.onclick(false);
					if(data[i].children!=null&&data[i].children.length!=0)
						return;
				}
			}
			window.onresize();
		}
		document.getElementById('hash').innerHTML='/'+location.hash.slice(1);
		do_one(jry_wb_manage_system_information,false,[]);
		first=false;
	});
}()
);
mode='admin';
jry_wb_manage_system_color_picker_area=null;
//window.addEventListener('load',function(){oj.showlogs(onepage,(JSON.parse(decodeURI(location.hash.slice(1))).page==null?1:parseInt(JSON.parse(decodeURI(location.hash.slice(1))).page)));},false);
//window.addEventListener('hashchange',function(){oj.showlogs(onepage,(JSON.parse(decodeURI(location.hash.slice(1))).page==null?1:parseInt(JSON.parse(decodeURI(location.hash.slice(1))).page)));},false);					
