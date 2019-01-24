function blog_show_function(all_div)
{
	this.all_div=all_div;
}
blog_show_function.prototype.get_cache=function()
{
	jry_wb_sync_data_with_server("blog_show",'blog_getinformation.php?action=get_blog_list&lasttime='+jry_wb_cache.get_last_time("blog_show"),null,function(a){return a.blog_id==this.buf.blog_id},(data)=>{this.data=data;jry_wb_cache.set_last_time('blog_show',this.data[0].lasttime);this.showall();},function(a,b){return jry_wb_compare_time(b.lasttime,a.lasttime)}); 
}
blog_show_function.prototype.showall=function()
{
	function showone(div,button,draft_id_in,title_in,id,time_in)
	{
		var onebody=document.createElement("div");div.appendChild(onebody);
		onebody.className="blog_all_body";
		var draft_id=document.createElement("div");onebody.appendChild(draft_id);
		draft_id.className="h56 blog_all_id";
		draft_id.style.float="left";
		draft_id.innerHTML=draft_id_in;
		if(button)
			draft_id.setAttribute('onclick',"window.location.href='show.php?blog_id="+draft_id_in+"'");
		var title=document.createElement("div");onebody.appendChild(title);
		title.className="h56 blog_all_title jry_wb_word_cut";
		title.style.float="left";
		title.innerHTML=title_in;
		if(!isNaN(parseInt(id)))
			jry_wb_get_and_show_user(onebody,id,null,'left');
		else
		{
			var user=document.createElement("div");onebody.appendChild(user);
			user.className="h56";
			user.style.float="left";
			user.innerHTML=id;
		}
		if(button)
			title.setAttribute('onclick',"window.location.href='show.php?blog_id="+draft_id_in+"'");		
		var time=document.createElement("div");onebody.appendChild(time);
		time.className="h56 blog_all_time";
		time.style.float="right";
		time.innerHTML=time_in;	
		if(button)
			time.setAttribute('onclick',"window.location.href='show.php?blog_id="+draft_id_in+"'");
	}
	showone(this.all_div,false,'博客编号','博客题目','创作人','修改时间');
	var flag=false;
	for(var i=0,n=this.data.length;i<n;i++) 
		if(this.data[i].delete)
			this.data.splice(i,1),flag=true,i--;
		else if(this.data[i].show)
			showone(this.all_div,true,(Array(5).join('0') + parseInt(this.data[i].blog_id)).slice(-5),this.data[i].title,this.data[i].id,this.data[i].lasttime);
	if(flag)
		jry_wb_cache.set('blog_show',this.data),jry_wb_cache.set_last_time('blog_show',this.data[0].lasttime);
	window.onresize();
}