jry_wb_online_judge_function.prototype.show_class=function()
{
	if(this.tree_dom==null)
		return;
	this.tree=new jry_wb_tree(this.tree_dom,"右键展开所有题库标签",true,()=>
	{
		this.classes_dom.innerHTML='';
		var buf=this.tree.get_checked(true);
		for(var i=0;i<buf.length;i++)
			buf[i]=parseInt(buf[i]),this.classes_dom.innerHTML+=((i==0?'':',')+this.get_class_by_class_id(buf[i]).class_name);		
		this.showwhat.class=this.tree.get_checked();
		for(var i=0;i<this.showwhat.class.length;i++)
			this.showwhat.class[i]=parseInt(this.showwhat.class[i]);		
	});
	var showlist=(father,ftree)=>
	{
		for(var i=0;i<this.classes.length;i++)
			if(this.classes[i].father==father)
				showlist(this.classes[i].class_id,this.classes[i].tree=(this.tree.add(ftree,this.classes[i].class_name,this.classes[i].class_id)))
	};
	showlist(0,this.tree.root);
	var button=document.createElement('button');this.tree_dom.appendChild(button);
	button.innerHTML="随机跳题";
	button.classList.add('jry_wb_button','jry_wb_button_size_small','jry_wb_color_warn');
	button.onclick=(event)=>
	{
		var checked=this.tree.get_checked();
		for(var i=0;i<checked.length;i++)
			checked[i]=parseInt(checked[i]);
		if(checked.length==0)
			jry_wb_beautiful_alert.alert('请选中','');
		else
			window.location.herf=self.location='jry_wb_online_judge_show_question.php#{"class":'+JSON.stringify(checked)+'}';
	}
}