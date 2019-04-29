function jry_wb_nd_show_files(checker)
{
	var flag=false;
	document_list.innerHTML='';
	if(typeof jry_nd_file_list!='object'||jry_nd_file_list==null)
		return ;
	var sorttype=jry_wb_cache.get('jry_nd_sort');
	if(sorttype==null||sorttype==0)
		jry_nd_file_list.sort(function(a,b){return a.file_id-b.file_id});
	else if(sorttype==1)
		jry_nd_file_list.sort(function(a,b){return jry_wb_compare_time(a.lasttime,b.lasttime)});
	else if(sorttype==2)
		jry_nd_file_list.sort(function(a,b){return a.name>b.name});
	else if(sorttype==3)
		jry_nd_file_list.sort(function(a,b){if(a.isdir&&b.isdir)return a.name>b.name;else if((a.isdir&&!b.isdir||!a.isdir&&b.isdir))return b.isdir-a.isdir;else return a.type>b.type});	
	for(let i=0,n=jry_nd_file_list.length;i<n;i++)
	{
		if(typeof jry_nd_file_list[i].dir=='undefined'||jry_nd_file_list[i].dir=='')
			jry_nd_file_list[i].dir=get_dir(i);
		if(checker(jry_nd_file_list[i]))
		{
			flag=true;
			let one=document.createElement('div');document_list.appendChild(one);
			one.classList.add('jry_wb_netdisk_file');
			one.name='jry_nd_file_memeber';
			jry_nd_file_list[i].body=one;
			let button=document.createElement('div');one.appendChild(button);
			button.classList.add('jry_wb_netdisk_file_type','jry_wb_icon',jry_wb_nd_get_class(jry_nd_file_list[i])[0],jry_wb_nd_get_class(jry_nd_file_list[i])[1]);
			button.name='jry_nd_file_memeber';
			button.onclick=function(event)
			{
				if(event.ctrlKey&&!jry_nd_share_mode_flag||(!jry_wb_test_is_pc()&&jry_nd_selected_list.length!=0))
				{
					select_call_back(one,jry_nd_file_list[i]);
				}
				else
				{
					if(jry_nd_file_list[i].isdir)
						jry_wb_nd_show_files_by_dir(jry_nd_file_list[i].dir+jry_nd_file_list[i].name+'/');
					else if(jry_nd_file_list[i].type=='jpg'||jry_nd_file_list[i].type=='jpeg'||jry_nd_file_list[i].type=='png'||jry_nd_file_list[i].type=='bmp')
						jry_wb_beautiful_alert.openpicture(jry_nd_file_list[i].name,document.body.clientWidth*0.95,document.body.clientHeight*0.95,'http://dev.juruoyun.top/jry_wb/jry_wb_netdisk/jry_nd_do_file.php?action=open&fast=1&file_id='+jry_nd_file_list[i].file_id+(jry_nd_share_mode_flag?('&share_id='+share_id+'&key='+key):''));
					else if(jry_nd_file_list[i].type=='mp4'||jry_nd_file_list[i].type=='flv')	
						jry_wb_beautiful_alert.openvideo(jry_nd_file_list[i].name,document.body.clientWidth*0.95,document.body.clientHeight*0.95,'http://dev.juruoyun.top/jry_wb/jry_wb_netdisk/jry_nd_do_file.php?action=open&fast=1&file_id='+jry_nd_file_list[i].file_id+(jry_nd_share_mode_flag?('&share_id='+share_id+'&key='+key):''),'');
					else
						one.oncontextmenu(event);
				}
			};
			if(jry_nd_file_list[i].uploading)
			{
				let uploading=document.createElement('span');one.appendChild(uploading);
				uploading.classList.add('uploading_and_trust','jry_wb_icon','jry_wb_icon_shangchuan','jry_wb_color_warn_font');
				uploading.name='jry_nd_file_memeber';
			}
			if(jry_nd_file_list[i].trust)
			{
				let trust=document.createElement('span');one.appendChild(trust);
				trust.classList.add('uploading_and_trust','jry_wb_icon','jry_wb_icon_shenhetongguo','jry_wb_color_ok_font');
				trust.name='jry_nd_file_memeber';			
			}
			if(jry_nd_file_list[i].share)
			{
				let share=document.createElement('span');one.appendChild(share);
				share.classList.add('share','jry_wb_icon','jry_wb_icon_fenxiang','jry_wb_color_ok_font');
				share.name='jry_nd_file_memeber';			
			}
			let name=document.createElement("div");one.appendChild(name);
			name.name='jry_nd_file_memeber';
			name.innerHTML=(jry_nd_file_list[i].name+(jry_nd_file_list[i].isdir?'':('.'+jry_nd_file_list[i].type)));
			name.classList.add('jry_wb_netdisk_file_name','jry_wb_word_cut');
			if(!jry_nd_share_mode_flag)/*非分享模式*/
			{
				let input=document.createElement("input");
				input.name='jry_nd_file_memeber';
				input.classList.add('jry_wb_netdisk_file_name');
				input.value=(jry_nd_file_list[i].name+(jry_nd_file_list[i].isdir?'':('.'+jry_nd_file_list[i].type)));
				input.onblur=function(event)
				{
					one.removeChild(input);
					one.appendChild(name);
				};
				input.onkeyup=function(e)
				{
					var keycode=(e.keyCode||e.which);
					if(keycode==jry_wb_keycode_enter)
					{
						var text=input.value,name,type,arr;
						input.blur();
						if(jry_nd_file_list[i].isdir)
							type='',name=text;
						else
							arr=text.split('.'),type=arr[arr.length-1],arr.pop(),name=arr.join('.'),delete arr;
						jry_wb_ajax_load_data(jry_wb_netdisk_do_file+'?action=rename',(data)=>
						{
							jry_wb_loading_off();
							data=JSON.parse(data);
							if(!data.code)
							{
								if(data.reason==100000)			jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
								else if(data.reason==100001)	jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
								else if(data.reason==200006)	jry_wb_beautiful_right_alert.alert("文件不存在或已删除",3000,"auto","error");
								return;
							}
							jry_wb_login_user.nd_ei.lasttime=data.lasttime;
							jry_wb_nd_fresh_file_list();
						},[{'name':'file_id','value':jry_nd_file_list[i].file_id},{'name':'dir','value':jry_nd_file_list[i].dir},{'name':'name','value':name},{'name':'type','value':type}]);
						
					}
				}
				name.oncontextmenu=function()
				{
					one.appendChild(input);
					one.removeChild(name);
					input.focus();
					input.value=(jry_nd_file_list[i].name+(jry_nd_file_list[i].isdir?'':('.'+jry_nd_file_list[i].type)));
					return false;
				};
				jry_wb_add_oncontextmenu(name);		
			}
			one.oncontextmenu=function(event)
			{
				if(typeof event=='undefined'||event==undefined)
					event=window.event; 					
				if(event.target==name)
					return false;
				var event_src=event.srcElement?event.srcElement:event.target;
				var file_id=jry_nd_file_list[i].file_id;
				if(event.touches!=null&&event.touches.length==1)
					event = event.touches[0];
				else if(event.changedTouches!=null&&event.changedTouches.length==1)
					event=event.changedTouches[0];
				else
					event = event;
				var scrollTop=document.body.scrollTop==0?document.documentElement.scrollTop:document.body.scrollTop;
				var scrollLeft=document.body.scrollLeft==0?document.documentElement.scrollLeft:document.body.scrollLeft;
				if(jry_wb_right_meau!=null)
				{
					document.body.removeChild(jry_wb_right_meau);
					jry_wb_right_meau=null;
				}
				jry_wb_right_meau=document.createElement("div");document.body.appendChild(jry_wb_right_meau);
				jry_wb_right_meau.className='jry_wb_netdisk_right_menu';
				jry_wb_right_meau.lasttime=new Date();
				if(!jry_nd_share_mode_flag)/*非分享模式*/
				{
					var delate=document.createElement("div");jry_wb_right_meau.appendChild(delate);
					delate.innerHTML='删除';
					delate.classList.add('jry_wb_netdisk_right_menu_text'); 
					delate.onclick=()=>
					{
						jry_wb_beautiful_alert.check("确定将文件《"+jry_nd_file_list[i].name+"》删除",()=>
						{
							jry_wb_ajax_load_data(jry_wb_netdisk_do_file+'?action=delete',(data)=>
							{
								jry_wb_loading_off();
								data=JSON.parse(data);
								if(!data.code)
								{
									if(data.reason==100000)			jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
									else if(data.reason==100001)	jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
									return;
								}
								jry_wb_login_user.nd_ei.lasttime=data.lasttime;
								jry_wb_nd_fresh_file_list();							
								jry_wb_login_user.nd_ei.size_total=data.size_total;
								jry_wb_login_user.nd_ei.size_used=data.size_used;
								progress.update(data.size_used/data.size_total,jry_wb_nd_get_size(data.size_used)+'/'+jry_wb_nd_get_size(data.size_total));
							},[{'name':'file_id','value':JSON.stringify([file_id])}]);
						},function()
						{
							jry_wb_beautiful_alert.alert("未执行操作","");
							setTimeout(function()
							{
								jry_wb_beautiful_alert.close();
							},800);
						});
					}
				}
				if(!jry_nd_file_list[i].isdir)
				{
					var open=document.createElement("div");jry_wb_right_meau.appendChild(open);
					open.innerHTML='打开';
					open.classList.add('jry_wb_netdisk_right_menu_text'); 
					open.onclick=function()
					{
						window.open(jry_wb_netdisk_do_file+'?action=open&file_id='+file_id+(jry_nd_share_mode_flag?('&share_id='+share_id+'&key='+key):''));
					};
					var download=document.createElement("div");jry_wb_right_meau.appendChild(download);
					download.innerHTML='下载';
					download.classList.add('jry_wb_netdisk_right_menu_text');
					download.onclick=function()
					{
						window.open(jry_wb_netdisk_do_file+'?action=download&file_id='+file_id+(jry_nd_share_mode_flag?('&share_id='+share_id+'&key='+key):''));
					};
					if((jry_nd_share_mode_flag&&jry_nd_share_mode_allow_fast)||((!jry_nd_share_mode_flag)&&jry_wb_login_user.nd_ei.fast_size>=jry_nd_file_list[i].size))
					{
						var open=document.createElement("div");jry_wb_right_meau.appendChild(open);
						open.innerHTML='高速打开';
						open.classList.add('jry_wb_netdisk_right_menu_text'); 
						open.onclick=function()
						{
							window.open(jry_wb_netdisk_do_file+'?action=open&fast=1&file_id='+file_id+(jry_nd_share_mode_flag?('&share_id='+share_id+'&key='+key):''));
						};			
						var download=document.createElement("div");jry_wb_right_meau.appendChild(download);
						download.innerHTML='高速下载';
						download.classList.add('jry_wb_netdisk_right_menu_text');
						download.onclick=function()
						{
							window.open(jry_wb_netdisk_do_file+'?action=download&fast=1&file_id='+file_id+(jry_nd_share_mode_flag?('&share_id='+share_id+'&key='+key):''));
						};		
					}
					var link=document.createElement("div");jry_wb_right_meau.appendChild(link);
					link.innerHTML='打开连接';
					link.classList.add('jry_wb_netdisk_right_menu_text'); 
					link.onclick=function()
					{
						jry_wb_copy_to_clipboard(jry_wb_netdisk_do_file+'?action=open&file_id='+file_id+(jry_nd_share_mode_flag?('&share_id='+share_id+'&key='+key):''));
					};				
					var link=document.createElement("div");jry_wb_right_meau.appendChild(link);
					link.innerHTML='下载连接';
					link.classList.add('jry_wb_netdisk_right_menu_text');
					link.onclick=function()
					{
						jry_wb_copy_to_clipboard(jry_wb_netdisk_do_file+'?action=download&file_id='+file_id+(jry_nd_share_mode_flag?('&share_id='+share_id+'&key='+key):''));
					};					
					if((jry_nd_share_mode_flag&&jry_nd_share_mode_allow_fast)||((!jry_nd_share_mode_flag)&&jry_wb_login_user.nd_ei.fast_size>=jry_nd_file_list[i].size))
					{
						var open=document.createElement("div");jry_wb_right_meau.appendChild(open);
						open.innerHTML='高速打开连接';
						open.classList.add('jry_wb_netdisk_right_menu_text'); 
						open.onclick=function()
						{
							jry_wb_copy_to_clipboard(jry_wb_netdisk_do_file+'?action=open&fast=1&file_id='+file_id+(jry_nd_share_mode_flag?('&share_id='+share_id+'&key='+key):''));
						};						
						var download=document.createElement("div");jry_wb_right_meau.appendChild(download);
						download.innerHTML='高速下载连接';
						download.classList.add('jry_wb_netdisk_right_menu_text');
						download.onclick=function()
						{
							jry_wb_copy_to_clipboard(jry_wb_netdisk_do_file+'?action=download&fast=1&file_id='+file_id+(jry_nd_share_mode_flag?('&share_id='+share_id+'&key='+key):''));
						};					
					}
				}
				var attribute=document.createElement("div");jry_wb_right_meau.appendChild(attribute);
				attribute.innerHTML='属性';
				attribute.classList.add('jry_wb_netdisk_right_menu_text'); 
				attribute.onclick=function()
				{
					var attribute_alert=new jry_wb_beautiful_alert_function;
					var title=attribute_alert.frame("属性",document.body.clientWidth*0.50,document.body.clientHeight*0.75,document.body.clientWidth*1/4,document.body.clientHeight*3/32);
					var Confirm = document.createElement("button"); title.appendChild(Confirm);
					Confirm.type="button"; 
					Confirm.innerHTML="关闭"; 
					Confirm.style='float:right;margin-right:20px;';
					Confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_warn");
					Confirm.onclick=function()
					{
						attribute_alert.close();
					};
					jry_wb_beautiful_scroll(attribute_alert.msgObj);
					var table = document.createElement("table"); attribute_alert.msgObj.appendChild(table);
					table.classList.add('h56');
					var tr = document.createElement("tr"); table.appendChild(tr);
					var td = document.createElement("td"); tr.appendChild(td);td.innerHTML='文件ID';
					var td = document.createElement("td"); tr.appendChild(td);td.innerHTML=jry_nd_file_list[i].file_id;					
					var tr = document.createElement("tr"); table.appendChild(tr);
					var td = document.createElement("td"); tr.appendChild(td);td.innerHTML='名称';
					var td = document.createElement("td"); tr.appendChild(td);td.innerHTML=jry_nd_file_list[i].name;
					var tr = document.createElement("tr"); table.appendChild(tr);
					var td = document.createElement("td"); tr.appendChild(td);td.innerHTML='类型';
					var td = document.createElement("td"); tr.appendChild(td);td.innerHTML=jry_nd_file_list[i].isdir?'文件夹':jry_nd_file_list[i].type;					
					var tr = document.createElement("tr"); table.appendChild(tr);
					var td = document.createElement("td"); tr.appendChild(td);td.innerHTML='最后操作时间';
					var td = document.createElement("td"); tr.appendChild(td);td.innerHTML=jry_nd_file_list[i].lasttime;
					var tr = document.createElement("tr"); table.appendChild(tr);
					var td = document.createElement("td"); tr.appendChild(td);td.innerHTML='所有者';
					var td = document.createElement("td"); tr.appendChild(td);jry_wb_get_and_show_user(td,jry_nd_file_list[i].id)
					var tr = document.createElement("tr"); table.appendChild(tr);
					var td = document.createElement("td"); tr.appendChild(td);td.innerHTML='位置';
					var td = document.createElement("td"); tr.appendChild(td);td.innerHTML=(jry_nd_share_mode_flag?'分享的目录:':'')+jry_nd_file_list[i].dir;
					var tr = document.createElement("tr"); table.appendChild(tr);
					var td = document.createElement("td"); tr.appendChild(td);td.innerHTML='大小';
					var td = document.createElement("td"); tr.appendChild(td);td.innerHTML=jry_wb_nd_get_size(jry_nd_file_list[i].size);
					if(!jry_nd_file_list[i].isdir)
					{
						var tr = document.createElement("tr"); table.appendChild(tr);
						var td = document.createElement("td"); tr.appendChild(td);td.innerHTML='区域';
						var td = document.createElement("td"); tr.appendChild(td);td.innerHTML=jry_nd_get_area_by_area_id(jry_nd_file_list[i].area).name;					
					}
				};
				if(!jry_nd_share_mode_flag&&jry_nd_file_list[i].trust)
				{
					var share=document.createElement("div");jry_wb_right_meau.appendChild(share);
					share.classList.add('jry_wb_netdisk_right_menu_text');
					if(jry_nd_file_list[i].self_share)
						share.innerHTML='取消分享';
					else
						share.innerHTML='分享';
					share.onclick=function()
					{
						jry_wb_ajax_load_data(jry_wb_netdisk_do_file+'?action='+(jry_nd_file_list[i].self_share?'unshare':'share'),function(data)
						{
							jry_wb_loading_off();
							data=JSON.parse(data);
							if(!data.code)
							{
								if(data.reason==100000)			jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
								else if(data.reason==100001)	jry_wb_beautiful_alert.alert("权限缺失","缺少"+data.extern,"window.location.href=''");
								return;
							}
							jry_wb_login_user.nd_ei.lasttime=data.lasttime;
							jry_wb_nd_fresh_file_list();
							jry_wb_nd_fresh_share_list();
						},[{'name':'file_id','value':file_id}]);
					};
					if(jry_nd_file_list[i].share)
					{
						var share_attribute=document.createElement("div");jry_wb_right_meau.appendChild(share_attribute);
						share_attribute.classList.add('jry_wb_netdisk_right_menu_text');
						share_attribute.innerHTML='分享属性';
						jry_nd_file_list[i].share_attribute=share_attribute;
						share_attribute.onclick=function(open_share,file_id)
						{
							var data=[];
							for(var j=0;j<jry_nd_file_list[i].share_list.length;j++)
								data[data.length]=jry_nd_share_list.find(function(a){return a.share_id==jry_nd_file_list[i].share_list[j]});
							if(typeof open_share=='number')
								if(data.find(function(a){return a.share_id==open_share&&a.file_id==jry_nd_file_list[i].file_id})!=undefined)
									open_share=open_share;
								else
									open_share=0;
							else
								open_share=0;
							if(file_id==undefined)
								file_id=0;
							var attribute_alert=new jry_wb_beautiful_alert_function;
							var title=attribute_alert.frame("分享属性",document.body.clientWidth*0.50,document.body.clientHeight*0.75,document.body.clientWidth*1/4,document.body.clientHeight*3/32);
							var Confirm = document.createElement("button"); title.appendChild(Confirm);
							Confirm.type="button"; 
							if(file_id!=0)
								Confirm.innerHTML="返回"; 
							else
								Confirm.innerHTML="关闭"; 
							Confirm.style='float:right;margin-right:20px;';
							Confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_warn");
							Confirm.onclick=function()
							{
								if(file_id!=0)
								{
									var child=jry_nd_file_list.find(function(a){return a.file_id==file_id});
									jry_wb_nd_show_files_by_dir(child.dir);
									child.body.oncontextmenu();
									child.share_attribute.onclick();
									document.body.removeChild(jry_wb_right_meau);
									jry_wb_right_meau=null;
								}
								attribute_alert.close();
							};
							jry_wb_beautiful_scroll(attribute_alert.msgObj);
							var table1 = document.createElement("table"); attribute_alert.msgObj.appendChild(table1);
							for(let j=0,n=data.length;j<n;j++)
							{
								if(data[j]==undefined)
									continue;
								var tr=document.createElement("tr"); table1.appendChild(tr);
								var td=document.createElement("td"); tr.appendChild(td);
								let span=document.createElement("span"); td.appendChild(span);
								span.classList.add('jry_wb_icon_xiajiantou','jry_wb_icon');								
								let table = document.createElement("table"); td.appendChild(table);
								if((open_share==0&&j==0)||(open_share==data[j].share_id))
									span.classList.add('jry_wb_icon_shangjiantou'),span.classList.remove('jry_wb_icon_xiajiantou');									
								else
									table.style.display='none';
								table.border=1;
								table.cellspacing=0;
								var tr=document.createElement("tr"); table.appendChild(tr);
								var td=document.createElement("td"); tr.appendChild(td);td.innerHTML='文件ID';			td.width='150px';
								var td=document.createElement("td"); tr.appendChild(td);td.innerHTML=data[j].file_id;	td.width='*';
								if(data[j].file_id!=jry_nd_file_list[i].file_id)td.innerHTML+='(继承自父目录)';
								var tr=document.createElement("tr"); table.appendChild(tr);
								var td=document.createElement("td"); tr.appendChild(td);td.innerHTML='分享ID';
								var td=document.createElement("td"); tr.appendChild(td);td.innerHTML=data[j].share_id;
								var tr=document.createElement("tr"); table.appendChild(tr);
								var td=document.createElement("td"); tr.appendChild(td);td.innerHTML='分享秘钥';
								var td=document.createElement("td"); tr.appendChild(td);td.innerHTML=data[j].key;
								var tr=document.createElement("tr"); table.appendChild(tr);
								var td=document.createElement("td"); tr.appendChild(td);td.innerHTML='防盗链请求URL';
								var td=document.createElement("td"); tr.appendChild(td);td.innerHTML=data[j].requesturl;
								var tr=document.createElement("tr"); table.appendChild(tr);
								var td=document.createElement("td"); tr.appendChild(td);td.innerHTML='高速下载';
								var td=document.createElement("td"); tr.appendChild(td);
								var spann=document.createElement("span"); td.appendChild(spann);spann.innerHTML=(data[j].fastdownload==1)?'允许':'不允许';
								spann.classList.add((data[j].fastdownload==1)?'jry_wb_color_ok_font':'jry_wb_color_warn_font');
								if(data[j].file_id!=jry_nd_file_list[i].file_id)
								{
									var father=jry_nd_file_list.find(function(a){return a.file_id==data[j].file_id});
									var spann=document.createElement("span"); td.appendChild(spann);spann.innerHTML='请返回主目录"'+father.dir+father.name+'"以修改';
									spann.classList.add('jry_wb_color_error_font');
									spann.onclick=function()
									{
										jry_wb_nd_show_files_by_dir('/');
										father.body.oncontextmenu();
										father.share_attribute.onclick(data[j].share_id,jry_nd_file_list[i].file_id);
										attribute_alert.close();
										document.body.removeChild(jry_wb_right_meau);
										jry_wb_right_meau=null;
									}
								}
								else
								{
									var button=document.createElement("button"); td.appendChild(button);button.innerHTML=(data[j].fastdownload==1)?'禁用高速下载':'启用高速下载';
									button.classList.add('jry_wb_button','jry_wb_button_size_small','jry_wb_color_warn');
									button.onclick=function()
									{
										jry_wb_ajax_load_data(jry_wb_netdisk_do_file+'?action='+(data[j].fastdownload?'disallow_share_fast':'allow_share_fast'),function(dataa)
										{
											jry_wb_loading_off();
											dataa=JSON.parse(dataa);
											if(!dataa.code)
											{
												if(dataa.reason==100000)			jry_wb_beautiful_alert.alert("没有登录","","window.location.href=''");
												else if(dataa.reason==100001)	jry_wb_beautiful_alert.alert("权限缺失","缺少"+dataa.extern,"window.location.href=''");
												else if(dataa.reason==230000)	jry_wb_beautiful_alert.alert("操作失败","不存在的分享");
												return;
											}
											jry_wb_login_user.nd_ei.lasttime=dataa.lasttime;
											jry_wb_nd_fresh_share_list(undefined,function()
											{
												attribute_alert.close();
												jry_nd_file_list[i].body.oncontextmenu();
												jry_nd_file_list[i].share_attribute.onclick(data[j].share_id);
												document.body.removeChild(jry_wb_right_meau);
												jry_wb_right_meau=null;					
											});
										},[{'name':'share_id','value':data[j].share_id}]);
									}
								}
								var tr=document.createElement("tr"); table.appendChild(tr);
								var td=document.createElement("td"); tr.appendChild(td);td.innerHTML='最后修改时间';
								var td=document.createElement("td"); tr.appendChild(td);td.innerHTML=data[j].lasttime;
								let down=data[j].shareurl=jry_wb_message.jry_wb_host+'jry_wb_netdisk/index_share.php?action=download&share_id='+data[j].share_id+(data[j].key!=''?'&key='+data[j].key:'');
								let open=data[j].shareurl=jry_wb_message.jry_wb_host+'jry_wb_netdisk/index_share.php?action=open&share_id='+data[j].share_id+(data[j].key!=''?'&key='+data[j].key:'');
								var tr=document.createElement("tr"); table.appendChild(tr);
								var td=document.createElement("td"); tr.appendChild(td);td.innerHTML='分享链接(蒟蒻云打开)(点击复制)';
								var td=document.createElement("td"); tr.appendChild(td);td.innerHTML=down;				td.style="word-break:break-all;";
								tr.onclick=function()
								{
									jry_wb_copy_to_clipboard(down);
									jry_wb_beautiful_right_alert.alert('已复制');
								};									
								if(!jry_nd_file_list[i].isdir)
								{
									var tr=document.createElement("tr"); table.appendChild(tr);
									var td=document.createElement("td"); tr.appendChild(td);td.innerHTML='分享链接(直接输出)(点击复制)';
									var td=document.createElement("td"); tr.appendChild(td);td.innerHTML=open;			td.style="word-break:break-all;";
									tr.onclick=function()
									{
										jry_wb_copy_to_clipboard(open);
										jry_wb_beautiful_right_alert.alert('已复制');
									};
								}
								
								span.onclick=function()
								{
									if(table.style.display=='')
									{
										table.style.display='none';
										span.classList.add('jry_wb_icon_xiajiantou'),span.classList.remove('jry_wb_icon_shangjiantou');
									}
									else
									{
										table.style.display='';
										span.classList.add('jry_wb_icon_shangjiantou'),span.classList.remove('jry_wb_icon_xiajiantou');
									}
								};
							}
						};
					}
					if(!jry_wb_test_is_pc())
					{
						var select=document.createElement("div");jry_wb_right_meau.appendChild(select);
						select.classList.add('jry_wb_netdisk_right_menu_text');	
						select.innerHTML='选中';
						select.name='select_mesage_button_member';
						select.onclick=function()
						{
							button.onclick({'ctrlKey':true});
						};
					}
				}
				if(jry_wb_right_meau.clientHeight>document.body.clientHeight)
				{
					jry_wb_right_meau.style.height=document.body.clientHeight
					new jry_wb_beautiful_scroll(jry_wb_right_meau);
				}
				var y=Math.min(event.clientY,document.body.clientHeight+scrollTop-jry_wb_right_meau.clientHeight);
				var x=Math.min(event.clientX,document.body.clientWidth+scrollLeft-jry_wb_right_meau.clientWidth);
				jry_wb_right_meau.style.left=x;jry_wb_right_meau.style.top=y;				
				return false;		
			};
			jry_wb_add_oncontextmenu(one);
		}
		else
		{
			jry_nd_file_list[i].body=null;
		}
	}
	if(!flag)
	{
		jry_wb_beautiful_alert.alert("没有文件或不存在的目录","");
	}
	delete right_body_scroll;
	right_body_scroll=new jry_wb_beautiful_scroll(document_list,true);
	return flag;
}