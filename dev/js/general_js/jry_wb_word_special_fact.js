var jry_wb_word_special_fact  =  new function()
{
	this.switch = true;
	this.word=[];
	jry_wb_add_onclick((event)=>{this.run(event)});
	this.run = function(event)
	{
		if(!this.switch)
			return ;
		if(typeof event.path!='undefined'&&typeof event.path[0]!='undefined'&&(typeof event.path[0].onclick=='function' ||typeof event.path[0].onmouseup=='function' ||typeof event.path[0].onmousedown=='function' || event.path[0].tagName=='BUTTON' || event.path[0].tagName=='INPUT'))return;
		if(typeof event.target!='undefined'&&(typeof event.target.onclick=='function' ||typeof event.target.onmouseup=='function' ||typeof event.target.onmousedown=='function' || event.target.tagName=='BUTTON' || event.target.tagName=='INPUT'))return ;
		if(typeof event.srcElement!='undefined'&&(typeof event.srcElement.onclick=='function' ||typeof event.srcElement.onmouseup=='function' ||typeof event.srcElement.onmousedown=='function' || event.srcElement.tagName=='BUTTON' || event.srcElement.tagName=='INPUT'))return ;
		if(typeof event.toElement!='undefined'&&(typeof event.toElement.onclick=='function' ||typeof event.toElement.onmouseup=='function' ||typeof event.toElement.onmousedown=='function' || event.toElement.tagName=='BUTTON' || event.toElement.tagName=='INPUT'))return ;
		var word = document.createElement("span");document.body.appendChild(word);
		var scrollLeft = document.body.scrollLeft==0?document.documentElement.scrollLeft:document.body.scrollLeft;
		word.innerHTML = this.word[parseInt(Math.random()*this.word.length%this.word.length)];
		word.classList.add('jry_wb_word_special_fact');
		word.style.top=event.clientY-35;
		word.style.left=Math.min(event.clientX+scrollLeft,document.body.clientWidth+scrollLeft-word.offsetWidth-35);
		word.style.opacity=1;
		word.style.fontSize='30px';
		word.style.position='fixed';
		setTimeout(function()
		{
			word.style.transitionDuration=(event.clientY-35+16)/200+'s';
			word.style.top=-16;
			word.style.opacity=0;
			word.style.fontSize='0px';
		},5);
		setTimeout(function()
		{
			document.body.removeChild(word);
		},1000*(event.clientY-35+16)/200);
	};
};