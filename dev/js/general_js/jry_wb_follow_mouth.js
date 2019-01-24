function jry_wb_follow_mouth(div,config)
{
	var config = config;
	this.div = div;
	config.size = config.size*5+15;
	var objs = new Array(),N;
	var m={x:0,y:0};
	var center={x:100,y:100};
	var clientWidth = document.body.clientWidth-4;
	var clientHeight = document.body.clientHeight-4;
	N = 0;
	this.close = function()
	{
		this.switch = false;
		for( var i = 0;i<N;i++)
			if(objs[i]!=null&&objs[i].obj!=null&&objs[i].obj.parentNode!=null)
				objs[i].obj.parentNode.removeChild(objs[i].obj);
		N = 0;
	};
	this.reinit = function()
	{
		this.switch = true;
		setTimeout(()=>{this.mouth_move()},10);
		for( var i = 1;i<=360;i+=12)
			for( var j = 15;j<config.size;j+=5) 
			{
				objs[N]={'obj':document.createElement("span"),'j':j,'x_pian':Math.sin(i*Math.PI/180),'y_pian':Math.cos(i*Math.PI/180),'x':0,'y':0};
				this.div.appendChild(objs[N].obj);
				objs[N].obj.className='jry_wb_mouth_spacial_fact';
				objs[N].obj.style.left=(objs[N].x = center.x+objs[N].x_pian)+'px';
				objs[N].obj.style.top=(objs[N].y = center.y+objs[N].y_pian)+'px';
				objs[N].obj.style.zIndex = config.size+10-j+15000;
				objs[N].x_pian = objs[N].j*objs[N].x_pian;
				objs[N].y_pian = objs[N].j*objs[N].y_pian;
				objs[N].pian=(objs[N].j/config.size)*(config.dou);
				N++;
			}		
	};
	this.mouth_move = function()
	{
		var scrollTop = document.body.scrollTop==0?document.documentElement.scrollTop:document.body.scrollTop;
		var scrollLeft = document.body.scrollLeft==0?document.documentElement.scrollLeft:document.body.scrollLeft;
		var x = m.x+scrollLeft,y = m.y+scrollTop;
		center.x+=(x-center.x)/(config.speed);
		center.y+=(y-center.y)/(config.speed);
		var min_top = scrollTop,max_top = scrollTop+clientHeight;
		var min_left = scrollLeft,max_left = scrollLeft+clientWidth;
		for( var i = 0,n = objs.length;i<n;i++)
		{
			objs[i].obj.style.left = Math.max(min_left,Math.min(max_left,(objs[i].x = Math.round(center.x+objs[i].x_pian+objs[i].pian*(center.x-x)))))+'px';
			objs[i].obj.style.top = Math.max(min_top,Math.min(max_top,(objs[i].y = Math.round(center.y+objs[i].y_pian+objs[i].pian*(center.y-y)))))+'px';
		}
		if(this.switch)
			setTimeout(()=>{this.mouth_move()},10);
	};
	setTimeout(()=>{if(!this.switch)return;clientWidth = document.body.clientWidth-4;clientHeight = document.body.clientHeight-4;},1000);
	jry_wb_add_onresize(()=>{if(!this.switch)return;clientWidth = document.body.clientWidth-4;clientHeight = document.body.clientHeight-4;});
	jry_wb_add_onmousemove((event)=>{if(!this.switch)return;if(event.touches)event  =  event.touches[0];else event  =  event;m.x = event.clientX;m.y = event.clientY;});
	jry_wb_add_onclick(()=>{if(!this.switch)return;for( var i = 0;i<N;i++){objs[i].obj.className='jry_wb_mouth_spacial_fact_onclick'};setTimeout(function(){for( var i = 0;i<N;i++){objs[i].obj.className='jry_wb_mouth_spacial_fact';}},100);});
	this.reinit();
	this.switch = true;
}
