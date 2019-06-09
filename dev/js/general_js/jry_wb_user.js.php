<?php if(false){ ?><script><?php } ?>
function jry_wb_get_user_head_out(user)
{
	return 	'animation:'		+user.head_special.mouse_out.result+
			'-moz-animation:'	+user.head_special.mouse_out.result+
			'-webkit-animation:'+user.head_special.mouse_out.result+
			'-o-animation:'		+user.head_special.mouse_out.result;
}
function jry_wb_get_user_head_on(user)
{
	return 	'animation:'		+user.head_special.mouse_on.result+
			'-moz-animation:'	+user.head_special.mouse_on.result+
			'-webkit-animation:'+user.head_special.mouse_on.result+
			'-o-animation:'		+user.head_special.mouse_on.result;
}
function jry_wb_set_user_head_special(user,img)
{
	img.style.MozAnimationDuration			=img.style.animationDuration		=user.head_special.mouse_out.speed+'s';
	img.style.MozAnimationName				=img.style.animationName			=user.head_special.mouse_out.direction?'jry_wb_rotate_clockwise':'jry_wb_rotate_anticlockwise';
	img.style.MozAnimationIterationCount	=img.style.animationIterationCount	=user.head_special.mouse_out.times==-1?'infinite':user.head_special.mouse_out.times;
	img.style.MozAnimationDirection			=img.style.animationDirection		='initial';
	img.style.MozAnimationDelay				=img.style.animationDelay			="initial";
	img.style.MozAnimationTimingFunction	=img.style.animationTimingFunction	="linear";
	img.style.MozAnimationPlayState			=img.style.animationPlayState		="initial";
	if(img.tagName=='IMG')
		img.src=jry_wb_get_user_head(user);
	else
		img.style.backgroundImage='url('+jry_wb_get_user_head(user)+')',img.style.backgroundRepeat='no-repeat',img.style.backgroundSize="100% 100%";
	img.onmouseover=function()
	{
		img.style.MozAnimationDuration			=img.style.animationDuration		=user.head_special.mouse_on.speed+'s';
		img.style.MozAnimationName				=img.style.animationName			=user.head_special.mouse_on.direction?'jry_wb_rotate_clockwise':'jry_wb_rotate_anticlockwise';
		img.style.MozAnimationIterationCount	=img.style.animationIterationCount	=user.head_special.mouse_on.times==-1?'infinite':user.head_special.mouse_on.times;
	};
	img.onmouseout=function()
	{
		img.style.MozAnimationDuration			=img.style.animationDuration		=user.head_special.mouse_out.speed+'s';
		img.style.MozAnimationName				=img.style.animationName			=user.head_special.mouse_out.direction?'jry_wb_rotate_clockwise':'jry_wb_rotate_anticlockwise';
		img.style.MozAnimationIterationCount	=img.style.animationIterationCount	=user.head_special.mouse_out.times==-1?'infinite':user.head_special.mouse_out.times;
	};
	img.setAttribute('name','jry_wb_user_head_'+user.id);	
}
var jry_wb_getting_user=[];
var jry_wb_getting_user_call_back=[];
function jry_wb_get_user(id,reload,callback,yibu,admin_mode)
{
	if(reload==null)
		reload = false;
	if(admin_mode==null)
		admin_mode=false;
	var cache_name="users";
	if(admin_mode)
		cache_name="jry_wb_manage_user_cache";
	var data = jry_wb_cache.get(cache_name);
	if(data!=null)
		var user=data.find(function (a){ return a.id==id});
	else
		var user=null;
	if(user!=null&&(!reload)&&user.lasttime_sync!=''&&jry_wb_compare_time(new Date(),user.lasttime_sync)<1000*60*60*2)
	{
		if( typeof callback=='function')
			callback(user);		
		return;
	}
	if(user==null) 
		user={lasttime:"1926-08-17 00:00:00"};
	var aaa=jry_wb_getting_user.indexOf(id);
	if(aaa!=-1)
	{
		jry_wb_getting_user_call_back[aaa].push(callback);
		return;
	}
	var i=jry_wb_getting_user.length;
	jry_wb_getting_user[i]=id;
	jry_wb_getting_user_call_back[i]=[];
	jry_wb_ajax_load_data(jry_wb_message.jry_wb_get_message+'jry_wb_get_user.php?id='+id+'&lasttime='+user.lasttime+'&admin_mode='+admin_mode,function (data_)
	{
		var buf = JSON.parse(data_);
		var data = jry_wb_cache.get(cache_name); 
		if(buf.id==-1)
		{
			buf=data.find(function (a){return a.id==id});
			buf.lasttime_sync=new Date();
		}
		if(data==null)
		{
			data = new Array();
			data.push(buf);
		}
		else if(buf!=null)
		{
			var now = data.find(function(a){return a.id==buf.id});
			if(now==null)
				data.push(buf);
			else
				data.splice(now,1,buf);
		}
		jry_wb_cache.set(cache_name,data);
		if( typeof callback=='function')
			callback(buf);
		var aaa=jry_wb_getting_user.indexOf(id);
		if(aaa!=-1)
		{
			jry_wb_getting_user.splice(aaa,1);
			for(var i=0;i<jry_wb_getting_user_call_back[aaa].length;i++)
				jry_wb_getting_user_call_back[aaa][i](buf);
			jry_wb_getting_user_call_back.splice(aaa,1);
		}
		jry_wb_loading_off();		
	},null,yibu);
}
function jry_wb_show_user(addr,user,width,float,inline,after)
{
	if(width==null)
		width='200px';
	if(float==null)
		float='';
	else
		float='float:'+float+';';
	var flag = false;
	if((user==null)||(user.show==null&&user.name==null&&user.head==null))
		{user={color:666666,show:'用户已消失'};flag = true;}
	else if(user!=null&&!user.use)
		{user={color:666666,show:'[禁止使用]'};flag = true;}
	if(inline)
	{
		var adder = document.createElement("span");
		adder.classList.add("jry_wb_show_user_inline");
		adder.style='width:'+width;
	}
	else
	{
		var adder = document.createElement("div");
		adder.classList.add("jry_wb_show_user");
		adder.style = float+';width:'+width+';overflow:hidden;';	
	}
	adder.style.background="#"+user.color;
	if(user.show!=null)
		adder.innerHTML = user.show;
	else
	{
		var span = document.createElement("span");adder.appendChild(span);
		span.innerHTML = user.name;
		span.setAttribute('name','jry_wb_user_name_'+user.id);
		var img = document.createElement("img");
		jry_wb_set_user_head_special(user,img);
		adder.appendChild(img);
	}
	if(!flag)
		adder.setAttribute('onclick','jry_wb_get_and_show_user_full('+user.id+',document.body.clientWidth*0.75,document.body.clientHeight*0.75)');
	if(after==undefined||after.nextSibling==null)
		addr.appendChild(adder);
	else
		addr.insertBefore(adder,after.nextSibling);
	adder = null;		
}			
function jry_wb_show_user_full(user,width,height)
{
	if((user==null)||(user.show==null&&user.name==null&&user.head==null))
		return jry_wb_beautiful_alert.alert('这个用户不见了','可能去火星了');
	if(user!=null&&!user.use)
		return jry_wb_beautiful_alert.alert('这个用户被送到'+decodeURI('%E5%B9%BB%E6%83%B3%E4%B9%A1')+'了',atob('Qm95IG5leHQgY29tcHV0ZXIh'));
	var title = jry_wb_beautiful_alert.frame('用户查看',width,height,(document.body.clientWidth-width)/2,(document.body.clientHeight-height)/2);	
	var Confirm = document.createElement("button"); title.appendChild(Confirm);    
	Confirm.type="button"; 
	Confirm.innerHTML="关闭"; 
	Confirm.style='float:right;margin-right:20px;';
	Confirm.onclick=function(){jry_wb_beautiful_alert.close();};
	Confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_normal");
	Confirm = null;
	jry_wb_beautiful_alert.msgObj.align="center";
	var table = document.createElement("table");jry_wb_beautiful_alert.msgObj.appendChild(table);
	table.border = 1;table.width="75%";
	jry_wb_show_tr_no_input(table,'ID',user.id);	
	jry_wb_show_tr_no_input(table,'昵称',user.name).children[0].setAttribute('name','jry_wb_user_name_'+user.id);	
	var tr = document.createElement("tr");
	table.appendChild(tr);
	var td = document.createElement("td");
	td.width="400";
	var h55 = document.createElement("h56");
	td.appendChild(h55);	
	h55.innerHTML='头像';
	tr.appendChild(td);	
	td = null;
	var td = document.createElement("td");
	td.style="overflow: hidden;"; 
	var img = document.createElement("img");
	jry_wb_set_user_head_special(user,img);
	td.appendChild(img);	
	img.height = 80;
	img.width = 80;
	tr.appendChild(td);	
	td = null;
	tr = null;	
	jry_wb_show_tr_no_input(table,'绿币',user.green_money);	
	jry_wb_show_tr_no_input(table,'注册日期',user.enroldate);		
	jry_wb_show_tr_no_input(table,'权限组',user.competencename);
	if(user.sex==1)
		jry_wb_show_tr_no_input(table,'性别','男');
	else if(user.sex==0)
		jry_wb_show_tr_no_input(table,'性别','女');
	else if(user.sex==2)
		jry_wb_show_tr_no_input(table,'性别','女装大佬'); 
	else
		jry_wb_show_tr_no_input(table,'性别','???');
	jry_wb_show_tr_no_input(table,'电话',user.tel);
	jry_wb_show_tr_no_input(table,'邮箱',user.mail);
	jry_wb_show_tr_no_input(table,'使用情况',user.use==0?'禁止':'正常');
	if(user.zhushi!=''&&user.zhushi!=null)
	{
		var td = jry_wb_show_tr_no_input(table,'签名','');
		td.innerHTML='';
		new jry_wb_markdown(td,user.id,0,(user.zhushi),false);
	}
	if(user.login_addr==-1)
		jry_wb_show_tr_no_input(table,'登录信息','该用户的隐私策略不允许展示');
	else
	{
		if(user.login_addr.length==0)
			jry_wb_show_tr_no_input(table,'登录信息','该用户没有登录');			
		else
		{
			var td = jry_wb_show_tr_no_input(table,'登录信息','该用户的隐私策略不允许展示');
			td.innerHTML='';
			var h55 = document.createElement("h56");td.appendChild(h55); 
			for( var i = 0,n = user.login_addr.length;i<n;i++)
			{
				var li = document.createElement("li");h55.appendChild(li);
				if(typeof user.login_addr[i]=='object')
					li.innerHTML = user.login_addr[i].data;
				else
					li.innerHTML = user.login_addr[i];
			}
		}
	}
<?php if(JRY_WB_OAUTH_SWITCH){ ?>	
	var tr=document.createElement("tr");table.appendChild(tr);
	var td=document.createElement("td");tr.appendChild(td);	
	td.width="400";
	td.classList.add('h56');
	td.innerHTML='第三方接入';
	var td=document.createElement("td");tr.appendChild(td);	
	td.classList.add('h56');
<?php if($JRY_WB_TP_QQ_OAUTH_CONFIG!=NULL){ ?>
	td.innerHTML+='QQ:';
	if(user.oauth_qq==null)
		td.innerHTML+='无<br>';
	else
		td.innerHTML+=user.oauth_qq.nickname+'<img width="40px" src="'+user.oauth_qq.figureurl_qq_2+'"><br>';
<?php } ?>
<?php if(JRY_WB_TP_MI_OAUTH_CLIENT_ID!=''){ ?>
	td.innerHTML+='MI:';
	if(user.oauth_mi==null)
		td.innerHTML+='无<br>';
	else
		td.innerHTML+=user.oauth_mi.miliaoNick+'<img width="40px" src="'+user.oauth_mi.miliaoIcon_orig+'"><br>';
<?php } ?>
<?php if(JRY_WB_TP_GITHUB_OAUTH_CLIENT_ID!=''){ ?>
	td.innerHTML+='gayhub:';
	if(user.oauth_github==null)
		td.innerHTML+='无<br>';
	else
		td.innerHTML+=user.oauth_github.name+','+user.oauth_github.login+'<img width="40px" src="'+user.oauth_github.avatar_url+'"><br>';						
<?php } ?>
<?php if(JRY_WB_TP_GITEE_OAUTH_CLIENT_ID!=''){ ?>
	td.innerHTML+='码云:';
	if(user.oauth_gitee==null)
		td.innerHTML+='无<br>';
	else
		td.innerHTML+=user.oauth_gitee.name+','+user.oauth_gitee.login+'<img width="40px" src="'+user.oauth_gitee.avatar_url+'"><br>';	
<?php } ?>
<?php } ?>
	jry_wb_beautiful_scroll(jry_wb_beautiful_alert.msgObj);	
}
function jry_wb_show_user_intext(user)
{
	if(!user.use)
		{user={color:666666,show:'[禁止使用]'};flag = true;}
	else if((user==null)||(user.show==null&&user.name==null&&user.head==null))
		{user={color:666666,show:'用户已消失'};flag = true;}	
	document.write('<span onclick="jry_wb_get_and_show_user_full('+user.id+',document.body.clientWidth*0.75,document.body.clientHeight*0.75)" name="jry_wb_user_name_'+user.id+'" style="background:#'+user.color+';" class="jry_wb_show_user_intext">'+user.name+'</span>');
}
function jry_wb_get_and_show_user(addr,id,width,float,inline)
{
	let after=addr.children[addr.children.length-1];
	jry_wb_get_user(id,false,function()
	{
		var data = jry_wb_cache.get('users');
		var user = data.find(function (a){ return a.id==id});
		jry_wb_show_user(addr,user,width,float,inline,after);
	});
}
function jry_wb_get_and_show_user_intext(id)
{
	jry_wb_get_user(id,false,function(){},false);
	var data = jry_wb_cache.get('users');
	var user = data.find(function (a){ return a.id==id});
	jry_wb_show_user_intext(user);
}
function jry_wb_get_and_show_user_full(id,width,height)
{
	jry_wb_get_user(id,false,function()
	{
		var data = jry_wb_cache.get('users');
		var user = data.find(function (a){ return a.id==id});
		jry_wb_show_user_full(user,width,height);		
	});	
}
function jry_wb_update_user(user,mode)
{
	if(mode==undefined||mode=='head')
		for(var all=document.getElementsByName('jry_wb_user_head_'+user.id),i=0,n=all.length,head=jry_wb_get_user_head(user);i<n;i++)
			all[i].src=head;
}
function jry_wb_get_user_head(user)
{
	if(user.head.type=='default')
		if(user.sex==0)
			user.head.type='default_head_woman';
		else
			user.head.type='default_head_man';
	if(user.head.type=='default_head_man')
		return '<?php echo constant('jry_wb_defult_man_picture'); ?>';
	else if(user.head.type=='default_head_woman')
		return '<?php echo constant('jry_wb_defult_woman_picture'); ?>';
	else if(user.head.type=='gravatar')
		return "http://www.gravatar.com/avatar/"+hex_md5(user.mail)+"?size=80&d=404&r=g";
	else if(user.head.type=='qq'&&user.oauth_qq!=null)
		return user.oauth_qq.figureurl_qq_2;
	else if(user.head.type=='github'&&user.oauth_github!=null)
		return user.oauth_github.avatar_url;
	else if(user.head.type=='qq')
		return "https://q2.qlogo.cn/headimg_dl?dst_uin="+user.mail.split('@')[0]+"&spec=100";
	else if(user.head.type=='gitee')
		return user.oauth_gitee.avatar_url;	
	else if(user.head.type=='mi')
		return user.oauth_mi.miliaoIcon_orig;	
	else if(user.head.type=='url')
		return user.head.url;
	else if(user.head.type=='netdisk')
		return '<?php echo constant('jry_wb_host'); ?>jry_wb_netdisk/jry_nd_do_file.php?action=open&share_id='+user.head.share_id+'&file_id='+user.head.file_id;
}
<?php if(false){ ?></script><?php } ?>