function jry_wb_blog_draft_function(all_div)
{
	this.all_div=all_div;
}
jry_wb_blog_draft_function.prototype.data_get=function()
{
	jry_wb_sync_data_with_server("blog_draft_list",'jry_wb_blog_getinformation.php?action=get_draft_list',null,(data)=>
	{
		this.data=data;
		this.showall();
		return (this.data==null||this.data.length==0)?'1926-08-17 00:00:00':this.data[0].lasttime;
	},function(a,b){return jry_wb_compare_time(b.lasttime,a.lasttime)}); 
}
jry_wb_blog_draft_function.prototype.showall=function()
{
	this.all_div.innerHTML='';
	let onebody=document.createElement('div');	this.all_div.appendChild(onebody);	onebody.classList.add('jry_wb_blog_draft_one_body');
	let draft_id=document.createElement('a');	onebody.appendChild(draft_id);		draft_id.classList.add('id');					draft_id.innerHTML='草稿编号';
	let title=document.createElement('a');		onebody.appendChild(title);			title.classList.add('jry_wb_word_cut','title');	title.innerHTML='草稿题目';
	let time=document.createElement('div');		onebody.appendChild(time);			time.classList.add('time');						time.innerHTML='最后修改时间';
	let button=document.createElement("button");onebody.appendChild(button);		button.classList.add('button','jry_wb_button','jry_wb_button_size_small','jry_wb_color_ok');button.innerHTML='新建';
	button.onclick=()=>
	{
		jry_wb_ajax_load_data('jry_wb_blog_do.php?action=new',(data)=>
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
	if(this.data==null)
		return;
	var that=this;
	this.data=this.data.sort(function(a,b){return jry_wb_compare_time(b.last_modify_time,a.last_modify_time)});
	for(let i=0,n=this.data.length;i<n;i++)
		if(this.data[i].delete)
			jry_wb_add_on_indexeddb_open(()=>{jry_wb_indexeddb.transaction(['blog_draft_list'],'readwrite').objectStore('blog_draft_list').delete(this.data[i].blog_id)}),this.data.splice(i,1),i--;
		else
		{
			let onebody=document.createElement('div');	this.all_div.appendChild(onebody);	onebody.classList.add('jry_wb_blog_draft_one_body');
			let draft_id=document.createElement('a');	onebody.appendChild(draft_id);		draft_id.classList.add('id');					draft_id.innerHTML=this.data[i].blog_id;
			let title=document.createElement('a');		onebody.appendChild(title);			title.classList.add('jry_wb_word_cut','title');	title.innerHTML=this.data[i].title;
			let time=document.createElement('div');		onebody.appendChild(time);			time.classList.add('time');						time.innerHTML=this.data[i].last_modify_time;
			let button=document.createElement("button");onebody.appendChild(button);		button.classList.add('button','jry_wb_button','jry_wb_button_size_small','jry_wb_color_error');button.innerHTML='删除';
			button.onclick=()=>
			{
				jry_wb_beautiful_alert.check("确定删除？",()=>
				{
					jry_wb_ajax_load_data('jry_wb_blog_do.php?action=delete&blog_id='+this.data[i].blog_id,(data)=>
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
				},()=>{},"赶紧删！","我再想想")
			}
			if(this.data[i].show)
				onebody.classList.add('jry_wb_blog_draft_one_body_show');
			title.href=draft_id.href='jry_wb_blog_editor.php?reload=1&blog_id='+this.data[i].blog_id;
			jry_wb_add_on_indexeddb_open(function()
			{
				var re=jry_wb_indexeddb.transaction(['blog_draft_text'],'readwrite').objectStore('blog_draft_text').get(parseInt(that.data[i].blog_id));
				re.onsuccess=function()
				{
					if(this.result!=undefined&&jry_wb_compare_time(this.result.last_modify_time,that.data[i].last_modify_time)>=0)
						title.href=draft_id.href='jry_wb_blog_editor.php?reload=0&blog_id='+that.data[i].blog_id;
				};
			});					
		}
	window.onresize();
}