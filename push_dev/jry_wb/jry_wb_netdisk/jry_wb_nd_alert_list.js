jry_wb_include_once_script('jry_wb_nd_fresh_file_list.js');
function jry_wb_nd_alert_list(callback,oneonly,dironly)
{
	if(oneonly==undefined)
		oneonly=true;
	if(dironly==undefined)
		dironly=true;	
	if(jry_wb_login_user.id=='-1'||jry_wb_login_user.id=='')
		return ;
	var file_list=jry_nd_file_list.slice();
	var alerter=new jry_wb_beautiful_alert_function();
	var title=alerter.frame(jry_wb_login_user.name+"的网盘",document.body.clientWidth*0.75,document.body.clientHeight*0.75,document.body.clientWidth*4/32,document.body.clientHeight*4/32);
	var confirm = document.createElement("button"); title.appendChild(confirm);
	confirm.type="button"; 
	confirm.innerHTML="取消"; 
	confirm.style='float:right;margin-right:20px;';
	confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_warn");
	confirm.onclick=function()
	{
		alerter.close();
	};
	var confirm = document.createElement("button"); title.appendChild(confirm);
	confirm.type="button"; 
	confirm.innerHTML="选择"; 
	confirm.style='float:right;margin-right:20px;';
	confirm.classList.add("jry_wb_button","jry_wb_button_size_small","jry_wb_color_ok");
	jry_wb_beautiful_scroll(alerter.msgObj);
	var div = document.createElement("div"); alerter.msgObj.appendChild(div);
	div.style.background="#999999";
	var tree=new jry_wb_tree(div,"请选中一个文件",true);
	confirm.onclick=function()
	{
		var checked=tree.get_checked(true);
		if(checked.length==0)
		{
			jry_wb_beautiful_right_alert.alert('请选中一个文件');
			return ;
		}
		if(oneonly&&checked.length!=1)
		{
			jry_wb_beautiful_right_alert.alert('请选中一个文件');
			return ;
		}
		for(var i=0;i<checked.length;i++)
			checked[i]=file_list.find(function(a){return a.file_id==checked[i]});
		if(dironly&&oneonly)
			if(!checked[0].isdir)
				jry_wb_beautiful_right_alert.alert('请选中一个文件夹');				
		if(typeof callback=='function')
			callback(checked);
		alerter.close();
	};
	function add_one(i)
	{
		if(file_list[i].added)
			return file_list[i].tree;
		var father=file_list.find(function(a){return a.file_id==file_list[i].father});
		if(father==null)
			tree_father=tree.root;
		else if(father.tree==null)
			tree_father=file_list[i].tree=add_one(file_list.indexOf(father));
		else
			tree_father=father.tree;
		file_list[i].tree=tree.add(tree_father,file_list[i].name+(file_list[i].isdir?'':('.'+file_list[i].type)),file_list[i].file_id);
		file_list[i].added=true;
		return file_list[i].tree;
	}
	for(let i=0,n=file_list.length;i<n;i++)
		if(!dironly||file_list[i].isdir)
			add_one(i);
}