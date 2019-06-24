function jry_wb_blog_draft_function(all_div)
{
	this.all_div=all_div;
}
jry_wb_blog_draft_function.prototype.data_get=function()
{
	jry_wb_sync_data_with_server("blog_draft",'jry_wb_blog_getinformation.php?action=get_draft_list&lasttime='+jry_wb_cache.get_last_time("blog_draft"),null,function(a){return a.blog_id==this.buf.blog_id},(data)=>
	{
		this.data=data;
		if(this.data==null||this.data.length==0)
			jry_wb_cache.set_last_time('blog_draft','1926-08-17 00:00:00');
		else
			jry_wb_cache.set_last_time('blog_draft',this.data[0].lasttime);
		this.showall();
	},function(a,b){return jry_wb_compare_time(b.lasttime,a.lasttime)}); 
}
jry_wb_blog_draft_function.prototype.showall=function()
{
	function showone(this_,button,draft_id_in,title_in,time_in,show)
	{
		var onebody=document.createElement("div");this_.all_div.appendChild(onebody);
		onebody.classList.add('jry_wb_blog_draft_one_body');
		if(show)
			onebody.classList.add('jry_wb_blog_draft_one_body_show');
		var draft_id=document.createElement("div");onebody.appendChild(draft_id);
		draft_id.classList.add("h56","jry_wb_blog_draft_one_id");
		draft_id.style.float="left";
		draft_id.innerHTML=draft_id_in;
		if(button)
			draft_id.setAttribute('onclick',"window.location.href='jry_wb_blog_editor.php?blog_id="+draft_id_in+"'");
		var title=document.createElement("div");onebody.appendChild(title);
		title.classList.add("h56","jry_wb_blog_draft_one_title");
		title.style.float="left";
		title.innerHTML=title_in;
		if(button)
			title.setAttribute('onclick',"window.location.href='jry_wb_blog_editor.php?blog_id="+draft_id_in+"'");		
		var time=document.createElement("div");onebody.appendChild(time);
		time.classList.add("h56","jry_wb_blog_draft_one_time");
		time.style.float="right";
		time.innerHTML=time_in;	
		if(button)
			time.setAttribute('onclick',"window.location.href='jry_wb_blog_editor.php?blog_id="+draft_id_in+"'");
		if(show)
		{
			var ojtype=document.createElement("div");onebody.appendChild(ojtype);
			ojtype.classList.add('jry_wb_word_cut','jry_wb_rotate_45_deg');
			ojtype.innerHTML='已发布';			
		}
		var button_=document.createElement("button");onebody.appendChild(button_);
		button_.style.float="right";
		if(button)
		{
			button_.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_error");
			button_.innerHTML='删除';
			button_.onclick=function()
			{
				jry_wb_ajax_load_data('jry_wb_blog_save.php?action=delete&blog_id='+draft_id_in,(data)=>
				{
					jry_wb_loading_off();
					data=JSON.parse(data);
					if(data.login==false)
						jry_wb_beautiful_right_alert.alert('因为'+data.reasion+'删除失败',1000,'auto','error');
					else
					{						
						jry_wb_beautiful_right_alert.alert('已删除',1000,'auto','ok');
						this_.data_get();
						jry_wb_beautiful_right_alert.alert('正在重载',1000,'auto');
					}
				});			
			}
		}
		else
		{
			button_.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
			button_.innerHTML='新建';
			button_.onclick=()=>
			{
				jry_wb_ajax_load_data('jry_wb_blog_save.php?action=new',(data)=>
				{
					jry_wb_loading_off();
					data=JSON.parse(data);
					if(data.login==false)
						jry_wb_beautiful_right_alert.alert('因为'+data.reasion+'保存失败',1000,'auto','error');
					else
					{						
						jry_wb_beautiful_right_alert.alert('已新建',1000,'auto','ok');
						this_.data_get();
						jry_wb_beautiful_right_alert.alert('正在重载',1000,'auto');
					}
				});				
			}
		}
	}
	this.all_div.innerHTML='';
	showone(this,false,'草稿编号','草稿题目','修改时间');
	if(this.data==null)
		return;
	var flag=false;
	for(var i=0,n=this.data.length;i<n;i++)
		if(this.data[i].delete)
			this.data.splice(i,1),flag=true,i--;
		else
			showone(this,true,(Array(5).join('0') + parseInt(this.data[i].blog_id)).slice(-5),this.data[i].title,this.data[i].lasttime,this.data[i].show);
	if(flag)
		jry_wb_cache.set('blog_draft',this.data),jry_wb_cache.set_last_time('blog_show',this.data[0].lasttime);
	window.onresize();
}