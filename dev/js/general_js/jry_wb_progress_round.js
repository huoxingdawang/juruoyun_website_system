function jry_wb_progress_bar_round(area,width,r,background_color,front_color,font_size,font_color,round)
{
	this.width = width;
	this.r = r;
	this.background_color = background_color;
	this.front_color = front_color;
	this.font_size = font_size;
	this.font_color = font_color;
	this.round = round==null?true:false;
	this.body = document.createElement('div');area.appendChild(this.body);
	this.body.style.height = this.body.style.width=(this.width+this.r)*2;
	this.body.style.position="relative";
	this.word = document.createElement('div');this.body.appendChild(this.word);
	this.word.innerHTML="0%";
	this.word.style="position:absolute;font-size:"+this.font_size+'px;top:'+((this.width+this.r)-this.font_size/2)+'px;left:'+((this.width+this.r)-this.font_size/4*(this.word.innerHTML.length))+'px;color:'+this.font_color+';';

	this.canvas = document.createElement('canvas');this.body.appendChild(this.canvas);
	this.canvas.style="position:absolute;top:0px;left:0px;";
	this.canvas.width = this.canvas.height=(this.width+this.r)*2;
	this.background = this.canvas.getContext("2d");
	this.background.beginPath();
	this.background.lineWidth  = this.width;	
	this.background.arc(this.width+this.r,this.width+this.r,this.r,0,Math.PI*2);
	this.background.strokeStyle = this.background_color;
	this.background.lineCap='round';
	this.background.stroke();
	this.background.save();
	this.front = this.canvas.getContext("2d");
	this.front.beginPath();
	this.front.lineWidth  = this.width;	
	this.front.strokeStyle = this.front_color;
	this.front.lineCap = this.round?'round':'butt';
	this.front.arc(this.width+this.r,this.width+this.r,this.r,Math.PI*1.5,Math.PI*(1.5+0));
	this.front.stroke();
}
jry_wb_progress_bar_round.prototype.update = function(progress)
{
	this.front.beginPath();
	this.front.lineWidth  = this.width;	
	this.front.strokeStyle = this.background_color;
	this.front.lineCap='round';
	this.front.arc(this.width+this.r,this.width+this.r,this.r,Math.PI*0,Math.PI*2);
	this.front.stroke();
	this.front.beginPath();
	this.front.lineWidth  = this.width;	
	this.front.strokeStyle = this.front_color;
	this.front.lineCap = this.round?'round':'butt';
	this.front.arc(this.width+this.r,this.width+this.r,this.r,Math.PI*1.5,Math.PI*(1.5+progress*2));
	this.front.stroke();
	this.word.innerHTML = parseInt(progress*100)+"%";
	this.word.style="position:absolute;font-size:"+this.font_size+'px;top:'+((this.width+this.r)-this.font_size/2)+'px;left:'+((this.width+this.r)-this.font_size/4*(this.word.innerHTML.length))+'px;color:'+this.font_color+';';
};
jry_wb_progress_bar_round.prototype.add = function(from,to,time,callback)
{
	if(from<to)
	{
		this.update(Math.max(Math.min(1,from+0.01),0));
		setTimeout(()=>{this.add(from+0.01,to,time,callback);},time);
	}
	else
		callback();
};
jry_wb_progress_bar_round.prototype.minus = function(from,to,time,callback)
{	
	if(from>to)
	{
		this.update(Math.max(Math.min(1,from-0.01),0));
		setTimeout(()=>{this.minus(from-0.01,to,time,callback);},time);
	}
	else
		callback();		
};
jry_wb_progress_bar_round.prototype.set = function(from,to,time,callback)
{
	callback = typeof callback=='function'?callback:function(){};
	if(from<to)
		this.add(from,to,time,callback);
	else
		this.minus(from,to,time,callback);
};
