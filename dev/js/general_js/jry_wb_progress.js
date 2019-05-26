function jry_wb_progress_bar(area,width,progress,word,mouthclick,mouthmove,addclass,type,active,shuzhi,buttom_type)
{
	this.shuzhi=shuzhi==null?false:shuzhi;
	this.progress_body=document.createElement("div");area.appendChild(this.progress_body);
	this.progress_body.classList.add('jry_wb_progress','tooltip');
	if(addclass!='')
		this.progress_body.classList.add(addclass);
	this.progress_body.onmouseover=function()
	{
		buff=window.onmousewheel;
		if(document.removeEventListener)
			document.removeEventListener('DOMMouseScroll',window.onmousewheel);
		window.onmousewheel=function(){};
	};
	this.progress_body.onmouseout=function()
	{
		window.onmousewheel=buff;if(document.addEventListener)document.addEventListener('DOMMouseScroll',window.onmousewheel,false);
	};
	if(this.shuzhi)
		this.progress_body.style.height=width;
	else	
		this.progress_body.style.width=width;
	if(buttom_type==undefined)
		this.progress_bar_buttom=null;
	else
	{		
		this.progress_bar_buttom=document.createElement("div");this.progress_body.appendChild(this.progress_bar_buttom);
		this.progress_bar_buttom.classList.add('jry_wb_progress_bar');
		if(this.shuzhi)
			this.progress_bar_buttom.style.height=0;
		else	
			this.progress_bar_buttom.style.width=0;	
		if(buttom_type=='error')
			this.progress_bar_buttom.classList.add('jry_wb_color_error','jry_wb_color_normal_prevent');
		else if(buttom_type=='ok')
			this.progress_bar_buttom.classList.add('jry_wb_color_ok','jry_wb_color_ok_prevent');
		else if(buttom_type=='warn')
			this.progress_bar_buttom.classList.add('jry_wb_color_warn','jry_wb_warn_normal_prevent');
		else
			this.progress_bar_buttom.classList.add('jry_wb_color_normal','jry_wb_color_normal_prevent');		
	}
	this.progress_bar=document.createElement("div");this.progress_body.appendChild(this.progress_bar);
	this.progress_bar.classList.add('jry_wb_progress_bar');
	if(active)
		this.progress_bar.classList.add('jry_wb_progress_bar_active','jry_wb_progress_bar_striped');
	if(type=='error')
		this.progress_bar.classList.add('jry_wb_color_error','jry_wb_color_normal_prevent');
	else if(type=='ok')
		this.progress_bar.classList.add('jry_wb_color_ok','jry_wb_color_ok_prevent');
	else if(type=='warn')
		this.progress_bar.classList.add('jry_wb_color_warn','jry_wb_warn_normal_prevent');
	else
		this.progress_bar.classList.add('jry_wb_color_normal','jry_wb_color_normal_prevent');
	this.text = document.createElement("span");this.progress_bar.appendChild(this.text);
	this.text.oncontextmenu = function(){ return false;}; 
	this.text.onselectstart = function(){ return false;};	
	this.text.name="text";
	this.text.style.cursor='default';
	this.text.classList.add('jry_wb_progress_bar_progress_text');
	this.text.style.fontSize='16px';
	this.text.style.wordBreak='initial';
	this.span = document.createElement("span");this.progress_bar.appendChild(this.span);
	if(mouthmove!=null)
		this.span.classList.add("jry_wb_progress_mouth_move");
	this.span.oncontextmenu = function(){ return false;}; 
	this.span.onselectstart = function(){ return false;};
	this.span.style.cursor='default';
	if(isNaN(progress))
		progress = 0;
	this.update(progress,word);
	var buff=function(){};
	this.progress_body.onclick=(e)=>
	{
		if(mouthclick==null)
			return;		
		e=e||window.event;
		layerX=(e.clientX-this.progress_body.getBoundingClientRect().left);
		layerY=(e.clientY-this.progress_body.getBoundingClientRect().top);
		if(this.shuzhi)
			var progress=layerY/this.progress_body.scrollHeight;
		else
			var progress=layerX/this.progress_body.scrollWidth;
		if( typeof mouthclick=="function")
			mouthclick(progress);	
	};
	this.progress_body.onmousemove=(e)=>
	{
		if(mouthmove==null)
			return;
		e=e||window.event;
		layerX=(e.clientX-this.progress_body.getBoundingClientRect().left);
		layerY=(e.clientY-this.progress_body.getBoundingClientRect().top);
		if(this.shuzhi)
			var progress=layerY/this.progress_body.scrollHeight;
		else
			var progress=layerX/this.progress_body.scrollWidth;
		if(this.shuzhi)
		{
			var y = Math.max(0,Math.min(layerY-(this.span.scrollHeight/2),this.progress_body.scrollHeight-(this.span.scrollHeight)));
			this.span.style.top = y+"px";
			this.span.style.left="30px";
			this.span.style.height="25px";
		}		
		else
		{
			var x = Math.max(0,Math.min(layerX-(this.span.scrollWidth/2),this.progress_body.scrollWidth-(this.span.scrollWidth)));
			this.span.style.left = x+"px";	
		}
		if(typeof mouthmove=="function")
			mouthmove(progress);
	};
}
jry_wb_progress_bar.prototype.set_mouse_wheel=function(callback)
{
	this.progress_body.onmousewheel=function(e)
	{
		e=e||window.event;
		callback((e.deltaY/100)||(e.detail/3));
		return false;
	};
	if(this.progress_body.addEventListener)
		this.progress_body.addEventListener('DOMMouseScroll',this.progress_body.onmousewheel,false);
};
jry_wb_progress_bar.prototype.update = function(progress,word)
{
	if(this.shuzhi)
	{
		this.progress_bar.style.height=""+parseFloat(progress)*100+"%";
		word = word.replace(/(\s)/g,'').replace(/(\d{1})/g,'$1<br>').replace(/\s*$/,'');
	}
	else
		this.progress_bar.style.width=""+parseFloat(progress)*100+"%";
	this.text.innerHTML = word;		
};
jry_wb_progress_bar.prototype.update_buttom = function(progress)
{
	if(this.progress_bar_buttom==null)
		return;
	if(this.shuzhi)
		this.progress_bar_buttom.style.height=""+parseFloat(progress)*100+"%";
	else
		this.progress_bar_buttom.style.width=""+parseFloat(progress)*100+"%";
};