oj_function.prototype.showlist=function()
{
	if(this.tree_div==null)
		return;
	this.tree=new jry_wb_tree(this.tree_div,"右键展开所有题库标签");
	function __showlist (_this,father,data)
	{
		for(var i=0;i<data.length;i++)
		{
			var node=_this.tree.add(father,data[i].ojclassname,data[i].ojclassid);
			if(data[i].children!=null)
				__showlist(_this,node,data[i].children);
		}
	}
	__showlist(this,this.tree.root,this.list);
	var button=document.createElement('button');this.tree_div.appendChild(button);
	button.innerHTML="随机跳题";
	button.className="jry_wb_button jry_wb_button_size_small jry_wb_color_warn";
	button.onclick=(event)=>
	{
		var checked=this.tree.get_checked();
		for(var i=0;i<checked.length;i++)
			checked[i]=parseInt(checked[i]);
		if(checked.length==0)
			jry_wb_beautiful_alert.alert('请选中','');
		else
			window.location.herf=self.location="oj_showquestion.php?ojclassid="+JSON.stringify(checked);
	}
}