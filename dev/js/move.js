var move=new function()
{
    this.flag = false;
	this.boxes=new Array();
    this.down=function(event)
	{
		if(this.flag==false)
		{
			this.flag = true;
			var touch ;
			if(event.touches)
				touch = event.touches[0];
			else 
				touch = event;
			//console.log(touch);
			this.from=event.target;
			this.movebuf=event.target.cloneNode();
			this.movebuf.id='movebuf';
			this.movebuf.style.position='absolute';
			this.movebuf.style.background='#66FFFF';
			this.movebuf.innerHTML='moving+'+this.from.innerHTML;
			
			var scrollTop=document.body.scrollTop==0?document.documentElement.scrollTop:document.body.scrollTop;
			var scrollLeft=document.body.scrollLeft==0?document.documentElement.scrollLeft:document.body.scrollLeft;
			this.x	= touch.clientX+(scrollLeft)-(this.movebuf.clientWidth)/2;
           	this.y 	= touch.clientY+(scrollTop)-(this.movebuf.clientHeight)/2;
			//console.log(this.x);
			//console.log(this.y);
           	this.movebuf.style.left	= this.x +"px";
           	this.movebuf.style.top 	= this.y +"px";
			this.addmove(this.movebuf);
			this.movebuf.style.zIndex=100000;
			document.body.appendChild(this.movebuf);
		}
    }
    this.move=function(event)
	{
        if(this.flag)
		{
			this.flag = true;
            var touch ;
            if(event.touches)
                touch = event.touches[0];
			else 
                touch = event;
			var scrollTop=document.body.scrollTop==0?document.documentElement.scrollTop:document.body.scrollTop;
			var scrollLeft=document.body.scrollLeft==0?document.documentElement.scrollLeft:document.body.scrollLeft;
			this.x	= touch.clientX+(scrollLeft)-(this.movebuf.clientWidth)/2;
           	this.y 	= touch.clientY+(scrollTop)-(this.movebuf.clientHeight)/2;
			this.xx=touch.clientX;
			this.yy=touch.clientY;
			//console.log(this.x);
			//console.log(this.y);
           	this.movebuf.style.left	= this.x +"px";
           	this.movebuf.style.top 	= this.y +"px";
            document.addEventListener("touchmove",function(){event.preventDefault();},false);
        }
    }
	this.drop=function(event)
	{
		//alert('drop'+this.xx+' '+this.yy);
		document.body.removeChild(this.movebuf);
		this.flag = false;
		//console.log(this.boxes);
		
		for(var i=0;i<this.boxes.length;i++)
		{
			if(	this.boxes[i].offsetLeft<this.xx&&
				this.boxes[i].offsetLeft+this.boxes[i].offsetWidth>this.xx&&
				this.boxes[i].offsetTop<this.yy&&
				this.boxes[i].offsetTop+this.boxes[i].offsetHeight>this.yy
			)
			{
				this.to=this.boxes[i];
				//console.log(this.to);
				this.to.endmove(this.from,this.to);
			}
		}
	}
	this.addmove=function(div2)
	{
		div2.addEventListener("mousedown"	,function(event){move.down(event);event.preventDefault();},false);
		div2.addEventListener("touchstart"	,function(event){move.down(event);event.preventDefault();},false);
		div2.addEventListener("mousemove"	,function(event){move.move(event);},false);
		div2.addEventListener("touchmove"	,function(event){move.move(event);},false);
		div2.addEventListener("mouseup"		,function(event){move.drop(event);},false);
		div2.addEventListener("touchend"	,function(event){move.drop(event);},false);
		div2.addEventListener("touchcancel"	,function(event){move.drop(event);},false);
	}
	this.setbox=function(div2,func)
	{
		div2.endmove=func;
		this.boxes.push(div2);
		
	}
	this.deletebox=function(div2)
	{
		var buf=this.boxes.find(function(a){return a.id==div2.id});
		this.boxes.splice(showbuf.indexOf(buf),1);
	}
}
