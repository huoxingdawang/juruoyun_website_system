function jry_wb_tree(area,text,check,callback)
{
	this.check=check==undefined?true:check;
	this.root = document.createElement('div');area.appendChild(this.root);
	this.root.classList.add('jry_wb_tree_root');
	this.root.oncontextmenu=(event)=>{
		if(this.openedall)
			this.closeall();
		else
			this.openall();
		return false;
	};
	this.root.check=this.check;
	this.openedall=false;
	this.update=function(node,name,value)
	{
		node.children[0].children[2].innerHTML=name;
		node.children[0].children[0].value=node.children[0].children[1].value=value;
	};
	this.add = function(father,name,value,check,do_update)
	{
		check=check==undefined?true:check;
		do_update=do_update==undefined?true:do_update;
		if(father==this.root)
		{
			var one = document.createElement('ul');father.appendChild(one);
		}
		else
		{
			var one = document.createElement('ul');father.children[1].appendChild(one);
			one.style='padding-left:25px;';
			father.style='';
			father.children[0].children[0].classList.add('jry_wb_tree_one_open','jry_wb_icon','jry_wb_icon_arrow_down');
		}
		one.classList.add('jry_wb_tree_one_body');
		var self = document.createElement('li');one.appendChild(self);
		self.classList.add('jry_wb_tree_one_body_self');
		var button = document.createElement('span');self.appendChild(button);
		button.id = value;
		var input = document.createElement('input');self.appendChild(input);
		input.value = value;
		input.type='checkbox';
		input.id='i'+value;
		if(!(check&&this.check&&father.check))
			input.style.display='none',one.check=false;
		else
			one.check=true;
		var name_dom=document.createElement('span');self.appendChild(name_dom);
		name_dom.innerHTML=name;
		var child = document.createElement('li');one.appendChild(child);
		child.classList.add('jry_wb_tree_one_body_children');
		child.style.display='none';
		var update=(input,from_child)=>
		{
			if(!from_child)
			{
				var all = input.parentNode.parentNode.children[1].getElementsByTagName('input');
				for( var i = 0,n = all.length;i<n;i++)
					all[i].checked = input.checked;
			}
			var father = input.parentNode.parentNode;
			if(father==null||father.className=='jry_wb_tree_root')
				return;
			if(input.checked)
			{
				var flag = true;
				for( var i = 0,n = father.parentNode.children.length;i<n;i++)
					if(!(flag&=father.parentNode.children[i].children[0].children[1].checked))
						break;
				if(flag)
				{
					if(father.parentNode.parentNode.children[0]==null||father.parentNode.parentNode.children[0].children[1]==null)
						return;
					father.parentNode.parentNode.children[0].children[1].checked = true;
					update(father.parentNode.parentNode.children[0].children[1],true);
				}
			}
			else
			{
				if(father==this.root||father.parentNode==this.root||father.parentNode.parentNode==this.root||father.parentNode.parentNode.children[0]==null||father.parentNode.parentNode.children[0].children[1]==null)
					return;	
				father.parentNode.parentNode.children[0].children[1].checked = false;
				update(father.parentNode.parentNode.children[0].children[1],true);
			}
		};
		one.children[0].children[0].onclick = (e)=>
		{
			if(!e)
				e = window.event;
			child.style.display = child.style.display=='none'?'':'none';
			if(child.style.display=='none')
				e.target.classList.remove('jry_wb_icon_arrow_up'),e.target.classList.add('jry_wb_icon_arrow_down');
			else
				e.target.classList.remove('jry_wb_icon_arrow_down'),e.target.classList.add('jry_wb_icon_arrow_up');
			this.openedall=false;
			window.onresize();
		};	
		one.children[0].children[1].onclick = function()
		{
			if(do_update)
				update(this,false);	
			if(typeof callback=='function')
				callback();
		};
		return one;
	};
	this.get_checked=(yasuo)=>
	{
		yasuo = yasuo==null?false:yasuo;
		var ans=[];
		if(!yasuo)
		{
			var all = this.root.getElementsByTagName('input');
			for( var i = 0,n = all.length;i<n;i++)
				if(all[i].checked)
					ans.push(all[i].value);
		}
		else
		{
			function get_one(onebody)
			{
				if(onebody.children[0].children[1].checked)
					ans.push(onebody.children[0].children[1].value);
				else
					for( var all = onebody.children[1].children,n = all.length,i = 0;i<n;i++)
						get_one(all[i]);	
			}
			for( var all = this.root.children,n = all.length,i = 0;i<n;i++)
				get_one(all[i]);
		}
		return ans;
	};
	this.openall=()=>
	{
		this.openedall=true;
		for( var all = this.root.getElementsByClassName("jry_wb_tree_one_body_children"),i = 0,n = all.length;i<n;i++)
		{
			if(all[i].innerHTML!='')
			{	
				all[i].style.display='';
				all[i].parentNode.children[0].children[0].classList.remove('jry_wb_icon_arrow_down'),all[i].parentNode.children[0].children[0].classList.add('jry_wb_icon_arrow_up');
			}
		}
		window.onresize();
	};
	this.closeall=()=>
	{
		this.openedall=false;
		for( var all = this.root.getElementsByClassName("jry_wb_tree_one_body_children"),i = 0,n = all.length;i<n;i++)
		{
			if(all[i].innerHTML!='')
			{
				all[i].style.display='none';
				all[i].parentNode.children[0].children[0].classList.remove('jry_wb_icon_arrow_up'),all[i].parentNode.children[0].children[0].classList.add('jry_wb_icon_arrow_down');
			}
		}		
		window.onresize();
	};
}
