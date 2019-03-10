var jry_wb_word_special_fact  =  new function()
{
	this.switch = true;
	this.word=[];
	jry_wb_add_onclick((event)=>{this.run(event)});
	this.run = function(event)
	{
		if(!this.switch)
			return ;
		if (!event)
			var event  =  window.event;
		if(typeof event.path[0].onclick=='function' ||typeof event.path[0].onmouseup=='function' ||typeof event.path[0].onmousedown=='function' || event.path[0].tagName=='BUTTON' || event.path[0].tagName=='INPUT')
			return;
		if(event.touches)
			event  =  event.touches[0];
		else
			event  =  event;
		var word = document.createElement("span");document.body.appendChild(word);
		var scrollTop = document.body.scrollTop==0?document.documentElement.scrollTop:document.body.scrollTop;
		var scrollLeft = document.body.scrollLeft==0?document.documentElement.scrollLeft:document.body.scrollLeft;
		word.innerHTML = this.word[parseInt(Math.random()*this.word.length%this.word.length)];
		word.style="position:absolute;"; 
		word.style.top = event.clientY-35+scrollTop;word.style.left = Math.min(event.clientX+scrollLeft,document.body.clientWidth+scrollLeft-word.offsetWidth-35);
		word.style.fontSize = 30;
		word.classList.add('jry_wb_word_special_fact');
		function chenge()
		{
			var scrollTop = document.body.scrollTop==0?document.documentElement.scrollTop:document.body.scrollTop;		
			if(parseInt(word.style.top)>=scrollTop)
			{
				setTimeout(chenge,15);
				word.style.top = parseInt(word.style.top)-6;
				if(parseFloat(word.style.fontSize)>=16)
					word.style.fontSize=(parseFloat(word.style.fontSize)-0.4)+'px';
			}
			else
				document.body.removeChild(word);
		}
		setTimeout(chenge,15);
	};
};