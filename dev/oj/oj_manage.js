
/*function oj_manage_function(bufpro,data_source,all_div,tree_div)
{
	this.bufpro=bufpro;
	this.data_source=data_source;
	this.all_div=all_div;
	this.tree_div=tree_div;
	if(this.tree_div!=null)this.tree_div.style.display='none';
	this.loadingcount=0;
	this.showwhat={type:0,ojclass:0,status:0,id:0,questionid:0};
	this.count=0;
}
oj_manage_function.prototype.doforsync_manager=function(id)
{
	var buf=JSON.parse(data);
	var data=getsync(this.bufpro+'manager');
	if(buf!=null)
		if(buf.login==false)
		{
			win.alert("没有登录","","window.location.href=''");
			return ;	
		}	
	data=buf;
	setsync(this.bufpro+'manager',data);
	this.manager=data;
	this.loadingcount--;
	loading_off();
}*/
