// JavaScript Document
function seat_function (data,area) 
{
	this.data=data;
	this.area=area;
	this.debug=true;
	var fang=[[0,1],[0,-1],[1,0],[-1,0],[1,1],[1,-1],[-1,1],[-1,-1]];
	this.promanage=function()
	{
		this.data.seat.break.sort(function(a,b){return a-b});
		this.data.seat.break.unique()
		this.data.num=this.data.stu.length; 
		this.data.seat.hang=Math.ceil(this.data.num/this.data.seat.lie);
		this.data.seat.mubiao_hang=Math.max(this.data.seat.mubiao_hang,this.data.seat.hang);
		this.data.seat.one_seat_from_hang=this.data.seat.one_seat_from_hang==undefined?this.data.seat.mubiao_hang:this.data.seat.one_seat_from_hang;
		for(var i=0,n=this.data.connect.length;i<n;i++)
			if(this.data.connect[i][0]>this.data.connect[i][1])
			{
				var a=this.data.connect[i][0];
				this.data.connect[i][0]=this.data.connect[i][1];
				this.data.connect[i][1]=a;
			}
		this.data.connect.sort(function(a,b){return a[0]-b[0]});
		for(var i=8,n=Math.max(this.data.seat.hang,this.data.seat.lie)*Math.max(this.data.seat.hang,this.data.seat.lie),hang_count=5;i<n;i++,hang_count+=2)
		{
			fang[i]=[0,parseInt(hang_count/2)];i++;
			fang[i]=[0,-parseInt(hang_count/2)];i++;
			for(var j=1;j<=parseInt(hang_count/2);j++)
			{
				fang[i]=[j,parseInt(hang_count/2)];i++;
				fang[i]=[j,-parseInt(hang_count/2)];i++;
				fang[i]=[-j,parseInt(hang_count/2)];i++;
				fang[i]=[-j,-parseInt(hang_count/2)];i++;			
			}
			for(var j=0;j<hang_count;j++)
			{
				fang[i]=[parseInt(hang_count/2),j];i++;
				fang[i]=[-parseInt(hang_count/2),j];i++;			
			}
		}
	}
	this.rand=function()
	{
		for (var i=0;i<this.data.num;i++)
			delete this.data.stu[i].connect;		
		this.data.stu.sort(function(a,b){return a.id-b.id;});
		for (var i=0;i<this.data.num;i++)
			this.data.stu[i].set=false;
		var used=new Array(new Array());
		for (var i=0;i<this.data.seat.mubiao_hang;used[++i]=new Array())
			for(var j=0;j<this.data.seat.lie;j++)
				used[i][j]=false;
		var connect_length=this.data.connect.length;
//		var positions=new Array();
//		for (var i=0,hang=0;i<this.data.num;i++,hang++)
//			for(var lie=0;i<this.data.num&&lie<this.data.seat.lie;i++,lie++)
//				positions[i]={"lie":lie,"hang":hang};
		for (var i=0;i<this.data.num;i++)
		{
			if(this.data.stu[i].set==true)
				continue;
			if((i==this.data.num-1)&&(!used[this.data.stu[i].position_hang][this.data.stu[i].position_lie]))
				break;
			var position_lie=parseInt(Math.random()*(this.data.seat.lie));
			var position_hang=parseInt(Math.random()*(this.data.seat.hang));
			if(((this.data.stu[i].position_hang==position_hang)&&(this.data.stu[i].position_lie==position_lie)))
			{
				var position_lie_=position_lie;
				var position_hang_=position_hang;
				var continue_flag=true;
				for(var cnt=0;cnt<=this.data.num&&continue_flag;cnt++,position_hang++,position_hang%=this.data.seat.hang)
					for(var cnt=0;cnt<=this.data.num&&continue_flag;cnt++,position_lie++,position_lie%=this.data.seat.lie)
						if(this.data.stu[i].position_hang!=position_hang&&this.data.stu[i].position_lie!=position_lie&&used[position_hang][position_lie]==false)
							continue_flag=false;
				if(continue_flag)
					position_lie=position_lie_,position_hang=position_hang_;
			}
			if(used[position_hang][position_lie]==false)
			{
				this.data.stu[i].position_hang=position_hang;
				this.data.stu[i].position_lie=position_lie;
				used[position_hang][position_lie]=true;
				this.data.stu[i].set=true;
				for(var connect_count=0;connect_count<connect_length&&this.data.connect[connect_count][0]!=this.data.stu[i].id;connect_count++);
				for(var fang_count=0,quan_count=1;connect_count<connect_length&&this.data.connect[connect_count][0]==this.data.stu[i].id;connect_count++)
				{
					this.data.stu[i].connect=true;
					var connect_position_hang=0,connect_position_lie=0;
					while(true)
					{
						connect_position_hang	=position_hang	+fang[fang_count][0];
						connect_position_lie	=position_lie	+fang[fang_count][1];
						if((connect_position_hang>=0&&connect_position_hang<this.data.seat.hang&&connect_position_lie>=0&&connect_position_lie<this.data.seat.lie))
							if((!used[connect_position_hang][connect_position_lie]))
							{
								used[connect_position_hang][connect_position_lie]=true;
								var id=this.data.connect[connect_count][1];
								var stu_now=this.data.stu.find(function(a){return a.id==id});
								if(stu_now==null)
									continue;
								stu_now.position_hang	=	connect_position_hang;
								stu_now.position_lie	=	connect_position_lie;
								stu_now.set=true;
								stu_now.connect=true;
//								if(this.debug)jry_wb_beautiful_right_alert.alert(id,Math.random()*3000,'auto','ok');
								break;
							}
						fang_count++;
						if(fang_count>fang.length)
							break;
					}
				}
			}
			else
			{
				i--;
			}
		}
		var flag=false;
		for (var hang=this.data.seat.one_seat_from_hang-1;hang<this.data.seat.hang;hang++,this.area.appendChild(nowhang=document.createElement("tr")))
			for(var lie=0;lie<this.data.seat.lie;lie++)
			{
				flag=!flag;
				if(flag)
					continue;
				var now_stu=this.data.stu.find(function(a){return a.position_hang==hang&&a.position_lie==lie});
				if(now_stu==null||now_stu.connect)
					continue;
				var position_lie=parseInt(Math.random()*(this.data.seat.lie));
				var position_hang=this.data.seat.hang+parseInt(Math.random()*(this.data.seat.mubiao_hang-this.data.seat.hang));
				if(position_hang>this.data.seat.hang-1)
				{
					if(used[position_hang][position_lie]==false)
					{
						now_stu.position_hang=position_hang;
						now_stu.position_lie=position_lie;
						used[position_hang][position_lie]=true;						
					}
				}
			}		
		this.save();
	}
	this.save=function()
	{
		for (var i=0;i<this.data.num;i++)
			delete this.data.stu[i].set;
		document.getElementById('test').innerHTML=JSON.stringify(this.data);
		jry_wb_beautiful_alert.alert("随机座位已生成","请保存数据",function(){setTimeout(window.onresize,4000)});
	}
	this.chenge=function(from,to)
	{
		var buf=seat.data.stu[from.id].position;
		seat.data.stu[from.id].position=seat.data.stu[to.id].position;
		seat.data.stu[to.id].position=buf;
		seat.save();
		seat.show();
	}
	this.show=function ()
	{
		this.data.stu.sort(function(a,b){return a.position-b.position;});
		this.area.innerHTML="";
		var tr=document.createElement('tr');this.area.appendChild(tr);	
		tr.style.height="50px";
		var td=document.createElement('td');tr.appendChild(td);
		td.colSpan=this.data.seat.lie+this.data.seat.break.length;
		td.style.background="#FFFF00";
		td.align="center";
		td.className='h56';
		td.innerHTML='讲台';
		var break_count=0;
		var nowhang=document.createElement("tr")
		this.area.appendChild(nowhang);
		for (var hang=0;hang<this.data.seat.mubiao_hang;hang++,this.area.appendChild(nowhang=document.createElement("tr")))
			for(var lie=0;lie<this.data.seat.lie;lie++)
			{
				if(hang==0&&lie==this.data.seat.break[break_count])
				{
					break_count++;
					lie--;
					var now=document.createElement("td");nowhang.appendChild(now);
					now.rowSpan=this.data.seat.mubiao_hang;
					now.style.background="#66FFFF";
					now.className="h55";
					now.innerHTML="走<br>廊";
				}
				else
				{
					var now=document.createElement("td");nowhang.appendChild(now);
					now.style.height="50px";
					var now_stu=this.data.stu.find(function(a){return a.position_hang==hang&&a.position_lie==lie});
					if(now_stu==null)
						continue;
					now.innerHTML=now_stu.name;
					now.className="move h55";
					if(now_stu.connect&&this.debug)
						now.style.background="blueviolet";
						
				}
				
			}
		window.onresize();
	}
	this.promanage();
}