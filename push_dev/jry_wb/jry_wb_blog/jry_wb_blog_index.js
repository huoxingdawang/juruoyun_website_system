function jry_wb_blog_index_function(all_div)
{
	this.all_div=all_div;
}
jry_wb_blog_index_function.prototype.get_cache=function()
{
	jry_wb_sync_data_with_server("blog_list",'jry_wb_blog_getinformation.php?action=get_blog_list&lasttime='+jry_wb_cache.get_last_time("blog_list"),null,(data)=>
	{
		this.data=data;
		this.showall();
		return this.data[0].lasttime;
	},function(a,b){return jry_wb_compare_time(b.lasttime,a.lasttime)}); 
}
jry_wb_blog_index_function.prototype.showall=function()
{
	let onebody=document.createElement('div');	this.all_div.appendChild(onebody);	onebody.classList.add('jry_wb_blog_one_body');
	let draft_id=document.createElement('a');	onebody.appendChild(draft_id);		draft_id.classList.add('id');					draft_id.innerHTML='博客编号';
	let title=document.createElement('a');		onebody.appendChild(title);			title.classList.add('jry_wb_word_cut','title');	title.innerHTML='题目';
	let user=document.createElement('div');		onebody.appendChild(user);			user.classList.add('user');						user.innerHTML='发表者';
	let time=document.createElement('div');		onebody.appendChild(time);			time.classList.add('time');						time.innerHTML='最后修改时间';
	var that=this;
	this.data=this.data.sort(function(a,b){return jry_wb_compare_time(b.last_modify_time,a.last_modify_time)});	
	for(let i=0,n=this.data.length;i<n;i++) 
		if(this.data[i].delete)
			jry_wb_add_on_indexeddb_open(()=>{jry_wb_indexeddb.transaction(['blog_list'],'readwrite').objectStore('blog_list').delete(this.data[i].blog_id)}),this.data.splice(i,1),i--;
		else if(this.data[i].show)
		{
			let onebody=document.createElement('div');	this.all_div.appendChild(onebody);	onebody.classList.add('jry_wb_blog_one_body');
			let draft_id=document.createElement('a');	onebody.appendChild(draft_id);		draft_id.classList.add('id');					draft_id.innerHTML=(Array(5).join('0')+parseInt(this.data[i].blog_id)).slice(-5);
			let title=document.createElement('a');		onebody.appendChild(title);			title.classList.add('jry_wb_word_cut','title');	title.innerHTML=this.data[i].title;
			let user=document.createElement('div');		onebody.appendChild(user);			user.classList.add('user');						jry_wb_get_and_show_user(user,this.data[i].id,null,'left');
			let time=document.createElement('div');		onebody.appendChild(time);			time.classList.add('time');						time.innerHTML=this.data[i].last_modify_time;
			
			title.href=draft_id.href='jry_wb_blog_show.php?reload=1&blog_id='+this.data[i].blog_id;
			jry_wb_add_on_indexeddb_open(function()
			{
				var re=jry_wb_indexeddb.transaction(['blog_text'],'readwrite').objectStore('blog_text').get(parseInt(that.data[i].blog_id));
				re.onsuccess=function()
				{
					if(this.result!=undefined&&jry_wb_compare_time(this.result.last_modify_time,that.data[i].last_modify_time)==0)
						title.href=draft_id.href='jry_wb_blog_show.php?reload=0&blog_id='+that.data[i].blog_id;
				};
			});				
		}
	window.onresize();
}