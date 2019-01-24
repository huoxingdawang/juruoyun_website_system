// JavaScript Document
function school_showallschool_function(area)
{
	var data;
	this.body_struct=document.createElement('table');
	this.body_struct.border=2;
	this.body_struct.width='100%';
	area.appendChild(this.body_struct);
	this.dofordata=function(data)
	{
		document.getElementById('__LOAD').style.display='none';
		this.data=JSON.parse(data);	
	}
	this.showone=function(data,chenge)
	{
		var onebody=document.createElement('tr');
		onebody.style.width='100%';
		this.body_struct.appendChild(onebody);
		var td=document.createElement('td');
		td.width='200px';
		onebody.appendChild(td);
		var id=document.createElement('div');
		id.innerHTML=data.schoolid;
		id.style.width='100%';
		id.className='h56';
		td.appendChild(id);
		td=null;
		id=null;
		if(chenge)
		{
			var td=document.createElement('td');
			td.width='*';
			onebody.appendChild(td);
			//创建form
			var form=document.createElement("form") ; 
			form.method='post';
			if(data.schoolname=='')
				form.action='school_manage_do.php?action=addschool';
			else
				form.action='school_manage_do.php?action=chengeschool&id='+data.schoolid;
			td.appendChild(form);
			var name=document.createElement('input');
			name.value=data.schoolname;
			name.className='h56';
			name.id='name';
			name.name='name';
			form.appendChild(name);
			name=null;
			var button=document.createElement("input");
			button.type='submit';
			if(data.schoolname=='')
				button.value='添加';
			else
				button.value='修改';
			button.className='button button1';
			form.appendChild(button);
			button=null;			
			form=null;
			td=null;
		}
		else
		{
			var td=document.createElement('td');
			td.width='*';
			onebody.appendChild(td);
			var name=document.createElement('div');
			name.innerHTML=data.schoolname;
			name.className='h56';
			td.appendChild(name);
			td=null;
			name=null;			
			var td=document.createElement('td');
			td.width='100px';
			onebody.appendChild(td);
			var button=document.createElement("button");
			button.type='button';
			button.innerHTML='申请加入';
			button.className='button_small button1';
			button.setAttribute('onClick',"jry_wb_beautiful_alert.open('申请加入',1000,500,'index.php?action=add&schoolid="+data.schoolid+"')");
			td.appendChild(button);
			td=null;
		}
		onebody=null;
	}
	this.show=function(creat,chenge)
	{
		this.body_struct.innerHTML='';
		var onebody=document.createElement('tr');
		onebody.style.width='100%';
		this.body_struct.appendChild(onebody);
		var td=document.createElement('td');
		td.width='200px';
		onebody.appendChild(td);
		var id=document.createElement('div');
		id.innerHTML='ID';
		id.style.width='100%';
		id.className='h56';
		td.appendChild(id);
		id=null;
		td=null;
		var td=document.createElement('td');
		td.width='*';
		onebody.appendChild(td);
		var id=document.createElement('div');
		id.innerHTML='NAME';
		id.style.width='100%';
		id.className='h56';
		td.appendChild(id);
		id=null;
		td=null;
		if(!chenge)
		{
			var td=document.createElement('td');
			td.width='100px';
			onebody.appendChild(td);
			var id=document.createElement('div');
			id.innerHTML='操作';
			id.style.width='100%';
			id.className='h56';
			td.appendChild(id);
			id=null;
			td=null;			
		}
		onebody=null;
		for(var i=0;i<this.data.length;i++)
		{
			this.showone(this.data[i],chenge);
		}
		if(creat&&chenge)
		{
			if(this.data.length!=0)
				var schoolid=this.data[this.data.length-1].schoolid+1;
			else
				var schoolid=1;
			this.showone({schoolname:'',schoolid:schoolid},true);
		}
	}
	
}