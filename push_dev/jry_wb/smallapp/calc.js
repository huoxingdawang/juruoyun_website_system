function calc_init(area)
{
	jry_wb_include_once_css('calc.css');
	calc_run(area);
}
function calc_add(word)
{
//	console.log(word);
	screen.innerHTML+=word;
}
function calc_solve ()
{
	var data=screen.innerHTML.replace(/arcsin/g,"as_").replace(/arccos/g,"ac_").replace(/arctan/g,"at_").replace(/sin/g,"Math.sin").replace(/tan/g,"Math.tan").replace(/cos/g,"Math.cos").replace(/pi/g,"Math.PI").replace(/e/g,"Math.E").replace(/ln/g,"Math.log").replace(/lg2/g,"Math.log2")
	.replace(/lg/g,"Math.log10").replace(/rand\(\)/g,"Math.random()").replace(/根号/g,"Math.sqrt").replace(/立方根/g,"Math.cbrt").replace(/方/g,"Math.pow").replace(/as_/g,"Math.asin").replace(/ac_/g,"Math.acos").replace(/at_/g,"Math.atan");
	screen.innerHTML+='='+eval(data);
	
}
function calc_clean(all)
{
	if(all)
	{
		screen.innerHTML='';
		document.getElementById('fang').innerHTML='方';
	}
	else
		screen.innerHTML=screen.innerHTML.substring(0,screen.innerHTML.length-1)
}
function calc_run(area)
{
	var body=document.createElement('div');area.appendChild(body);
	body.classList.add('jry_wb_calc');
	screen=document.createElement('div');body.appendChild(screen);
	screen.classList.add('jry_wb_calc_screen');
	
	var keyboard=document.createElement('div');body.appendChild(keyboard);
	keyboard.classList.add('jry_wb_calc_keyboard');
	//line 0
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_pro','jry_wb_calc_button');		key.innerHTML="pi";		key.style.top=10;		key.style.left=10;	key.onclick=function(){calc_add(this.innerHTML);return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_pro','jry_wb_calc_button');		key.innerHTML="e";		key.style.top=10;		key.style.left=130;	key.onclick=function(){calc_add(this.innerHTML);return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_pro','jry_wb_calc_button');		key.innerHTML="rand()";	key.style.top=10;		key.style.left=250;	key.onclick=function(){calc_add(this.innerHTML);return false;};	
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_num','jry_wb_calc_button');		key.innerHTML="C";		key.style.top=10;		key.style.left=380;	key.onclick=function(){calc_clean(true);return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_num','jry_wb_calc_button');		key.innerHTML="退格";		key.style.top=10;		key.style.left=510;	key.onclick=function(){calc_clean(false);return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_num','jry_wb_calc_button');		key.innerHTML="( )";	key.style.top=10;		key.style.left=640;	key.onclick=function(){calc_add('(');return false;};key.oncontextmenu=function(){calc_add(')');return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_simple','jry_wb_calc_button');	key.innerHTML="+";		key.style.top=10;		key.style.left=770;	key.onclick=function(){calc_add(this.innerHTML);return false;};
	//line 1
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_pro','jry_wb_calc_button');		key.innerHTML="sin";	key.style.top=90;		key.style.left=10;	key.onclick=function(){calc_add(this.innerHTML+'(');return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_pro','jry_wb_calc_button');		key.innerHTML="cos";	key.style.top=90;		key.style.left=130;	key.onclick=function(){calc_add(this.innerHTML+'(');return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_pro','jry_wb_calc_button');		key.innerHTML="tan";	key.style.top=90;		key.style.left=250;	key.onclick=function(){calc_add(this.innerHTML+'(');return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_num','jry_wb_calc_button');		key.innerHTML="7";		key.style.top=90;		key.style.left=380;	key.onclick=function(){calc_add(this.innerHTML);return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_num','jry_wb_calc_button');		key.innerHTML="8";		key.style.top=90;		key.style.left=510;	key.onclick=function(){calc_add(this.innerHTML);return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_num','jry_wb_calc_button');		key.innerHTML="9";		key.style.top=90;		key.style.left=640;	key.onclick=function(){calc_add(this.innerHTML);return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_simple','jry_wb_calc_button');	key.innerHTML="-";		key.style.top=90;		key.style.left=770;	key.onclick=function(){calc_add(this.innerHTML);return false;};
	//line 2
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_pro','jry_wb_calc_button');		key.innerHTML="lg";		key.style.top=170;		key.style.left=10;	key.onclick=function(){calc_add(this.innerHTML+'(');return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_pro','jry_wb_calc_button');		key.innerHTML="ln";		key.style.top=170;		key.style.left=130;	key.onclick=function(){calc_add(this.innerHTML+'(');return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_pro','jry_wb_calc_button');		key.innerHTML="lg2";	key.style.top=170;		key.style.left=250;	key.onclick=function(){calc_add(this.innerHTML+'(');return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_num','jry_wb_calc_button');		key.innerHTML="4";		key.style.top=170;		key.style.left=380;	key.onclick=function(){calc_add(this.innerHTML);return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_num','jry_wb_calc_button');		key.innerHTML="5";		key.style.top=170;		key.style.left=510;	key.onclick=function(){calc_add(this.innerHTML);return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_num','jry_wb_calc_button');		key.innerHTML="6";		key.style.top=170;		key.style.left=640;	key.onclick=function(){calc_add(this.innerHTML);return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_simple','jry_wb_calc_button');	key.innerHTML="*";		key.style.top=170;		key.style.left=770;	key.onclick=function(){calc_add(this.innerHTML);return false;};

	//line 3
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_pro','jry_wb_calc_button');		key.innerHTML="根号";	key.style.top=250;		key.style.left=10;	key.onclick=function(){calc_add(this.innerHTML+'(');return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_pro','jry_wb_calc_button');		key.innerHTML="立方根";	key.style.top=250;		key.style.left=130;	key.onclick=function(){calc_add(this.innerHTML+'(');return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_pro','jry_wb_calc_button');		key.innerHTML="方";		key.style.top=250;		key.style.left=250;	key.onclick=function(){calc_add(this.innerHTML);if(this.innerHTML=='方'){this.innerHTML=',';calc_add('(');}else this.innerHTML='方';return false;};key.id='fang';
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_num','jry_wb_calc_button');		key.innerHTML="1";		key.style.top=250;		key.style.left=380;	key.onclick=function(){calc_add(this.innerHTML);return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_num','jry_wb_calc_button');		key.innerHTML="2";		key.style.top=250;		key.style.left=510;	key.onclick=function(){calc_add(this.innerHTML);return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_num','jry_wb_calc_button');		key.innerHTML="3";		key.style.top=250;		key.style.left=640;	key.onclick=function(){calc_add(this.innerHTML);return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_simple','jry_wb_calc_button');	key.innerHTML="/";		key.style.top=250;		key.style.left=770;	key.onclick=function(){calc_add(this.innerHTML);return false;};

	//line 4
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_pro','jry_wb_calc_button');		key.innerHTML="arctan";	key.style.top=330;		key.style.left=10;	key.onclick=function(){calc_add(this.innerHTML+'(');return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_pro','jry_wb_calc_button');		key.innerHTML="arccos";	key.style.top=330;		key.style.left=130;	key.onclick=function(){calc_add(this.innerHTML+'(');return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_pro','jry_wb_calc_button');		key.innerHTML="arcsin";	key.style.top=330;		key.style.left=250;	key.onclick=function(){calc_add(this.innerHTML+'(');return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_num','jry_wb_calc_button');		key.innerHTML="0";		key.style.top=330;		key.style.left=380;	key.style.width=230;key.onclick=function(){calc_add(this.innerHTML);return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_simple','jry_wb_calc_button');	key.innerHTML=".";		key.style.top=330;		key.style.left=640;	key.onclick=function(){calc_add(this.innerHTML);return false;};
	var key=document.createElement('div');keyboard.appendChild(key);key.classList.add('jry_wb_calc_button_get','jry_wb_calc_button');		key.innerHTML="=";		key.style.top=330;		key.style.left=770;	key.onclick=function(){calc_solve();return false;};
	
	keyboard.style.height=410;
	screen.style.width=880;
	keyboard.style.width=900;
	body.style.height=area.style.height=screen.clientHeight+keyboard.clientHeight+60;
}