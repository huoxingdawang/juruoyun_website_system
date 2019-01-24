var autoclick=100;
function autoclick_()
{
	if(autoclick)
		setTimeout(autoclick_,autoclick);
	clientWidth=document.body.clientWidth-4;
	clientHeight=document.body.clientHeight-4;
	jry_wb_add_onresize(function(){clientWidth=document.body.clientWidth-4;clientHeight=document.body.clientHeight-4;});	
	var scrollTop=document.body.scrollTop==0?document.documentElement.scrollTop:document.body.scrollTop;
	var scrollLeft=document.body.scrollLeft==0?document.documentElement.scrollLeft:document.body.scrollLeft;
	var x=Math.round(Math.random()*clientWidth)+screenLeft;
	var y=Math.round(Math.random()*clientHeight)+scrollTop;
	window.onmousemove({clientX:x,clientY:y});
	window.onclick({clientX:x,clientY:y});
}
autoclick_();